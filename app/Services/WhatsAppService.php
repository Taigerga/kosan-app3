<?php

namespace App\Services;

use App\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $messageQueueFile;
    private $isBotRunning = false;
    private $botStarted = false;
    private $lastMessageTime = 0;
    private $minDelayBetweenMessages = 3000; // minimal 3 detik antar pesan
    private $useHybridMode;

    public function __construct()
    {
        $this->messageQueueFile = storage_path('app/whatsapp_messages.json');
        // Mode hybrid: true = pakai database (PC rumah), false = pakai file local (VPS lokal)
        $this->useHybridMode = config('services.whatsapp.hybrid_mode', false);
    }

    /**
     * Check if using hybrid mode (PC rumah as bridge)
     */
    public function isHybridMode()
    {
        return $this->useHybridMode;
    }

    /**
     * Send message - akan disimpan ke queue untuk diproses
     */
    public function sendMessage($phone, $message, $type = 'general', $metadata = null)
    {
        try {
            // Rate limiting - cek delay antar pesan
            $now = microtime(true) * 1000;
            $timeSinceLastMessage = $now - $this->lastMessageTime;
            
            if ($timeSinceLastMessage < $this->minDelayBetweenMessages) {
                $sleepTime = ($this->minDelayBetweenMessages - $timeSinceLastMessage) / 1000;
                Log::info("Rate limiting: sleeping for {$sleepTime} seconds");
                sleep(ceil($sleepTime));
            }
            
            $formattedPhone = $this->formatPhoneNumber($phone);
            
            if ($this->useHybridMode) {
                // Mode Hybrid: Simpan ke database untuk diambil PC rumah
                return $this->saveToDatabase($formattedPhone, $message, $type, $metadata);
            } else {
                // Mode Local: Simpan ke file untuk bot local
                return $this->saveToFile($formattedPhone, $message);
            }
            
        } catch (\Exception $e) {
            Log::error('Error queueing WhatsApp message: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Save message to database (Hybrid Mode)
     */
    private function saveToDatabase($phone, $message, $type = 'general', $metadata = null)
    {
        try {
            $whatsappMessage = WhatsAppMessage::create([
                'phone' => $phone,
                'message' => $message,
                'status' => 'pending',
                'attempts' => 0,
                'type' => $type,
                'metadata' => $metadata
            ]);

            Log::info("WhatsApp message queued to database for: {$phone}", [
                'phone' => $phone,
                'message_id' => $whatsappMessage->id,
                'type' => $type,
                'mode' => 'hybrid'
            ]);
            
            $this->lastMessageTime = microtime(true) * 1000;
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error saving to database: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Save message to file (Local Mode - untuk backward compatibility)
     */
    private function saveToFile($phone, $message)
    {
        try {
            $messageData = [
                'id' => uniqid(),
                'type' => 'send_message',
                'phone' => $phone,
                'message' => $message,
                'timestamp' => now()->toISOString(),
                'status' => 'pending'
            ];

            $queue = [];
            if (file_exists($this->messageQueueFile)) {
                $existingData = file_get_contents($this->messageQueueFile);
                if ($existingData) {
                    $queue = json_decode($existingData, true) ?? [];
                }
            }

            $queue[] = $messageData;
            file_put_contents($this->messageQueueFile, json_encode($queue, JSON_PRETTY_PRINT));

            Log::info("WhatsApp message queued to file for: {$phone}", [
                'phone' => $phone,
                'message_id' => $messageData['id'],
                'mode' => 'local'
            ]);
            
            $this->lastMessageTime = microtime(true) * 1000;
            
            // Start bot local jika belum running
            if (!$this->botStarted) {
                $this->startBot();
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error saving to file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Start bot manually (hanya untuk mode local)
     */
    public function startBot()
    {
        if ($this->botStarted || $this->useHybridMode) {
            return true;
        }

        try {
            $nodeScriptPath = base_path('app/Services/WhatsAppBot/whatsapp-bot.js');
            
            if (!file_exists($nodeScriptPath)) {
                Log::error('WhatsApp bot script not found: ' . $nodeScriptPath);
                return false;
            }

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = 'start "WhatsApp Bot" /B cmd /c "node ' . escapeshellarg($nodeScriptPath) . '"';
                pclose(popen($command, 'r'));
            } else {
                $command = 'nohup node ' . escapeshellarg($nodeScriptPath) . ' > /dev/null 2>&1 &';
                exec($command);
            }

            Log::info('WhatsApp bot started in background (local mode)');
            $this->botStarted = true;
            $this->isBotRunning = true;
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to start WhatsApp bot: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get bot status
     */
    public function getStatus()
    {
        if ($this->useHybridMode) {
            // Hybrid mode: ambil status dari cache (diupdate oleh bridge)
            $botStatus = Cache::get('whatsapp_bot_status', [
                'status' => 'unknown',
                'updated_at' => null
            ]);
            
            $isOnline = $botStatus['updated_at'] && 
                        now()->diffInMinutes($botStatus['updated_at']) < 2;

            return [
                'mode' => 'hybrid',
                'bot_online' => $isOnline,
                'bot_status' => $botStatus['status'],
                'bot_last_seen' => $botStatus['updated_at'],
                'pending_count' => WhatsAppMessage::where('status', 'pending')->count(),
                'sent_today' => WhatsAppMessage::where('status', 'sent')
                    ->whereDate('sent_at', today())->count(),
            ];
        } else {
            // Local mode: status dari file
            $queueCount = 0;
            $pendingCount = 0;
            
            if (file_exists($this->messageQueueFile)) {
                $queueData = file_get_contents($this->messageQueueFile);
                $queue = json_decode($queueData, true) ?? [];
                $queueCount = count($queue);
                $pendingCount = count(array_filter($queue, fn($msg) => $msg['status'] === 'pending'));
            }

            return [
                'mode' => 'local',
                'bot_started' => $this->botStarted,
                'queue_count' => $queueCount,
                'pending_messages' => $pendingCount,
            ];
        }
    }

    /**
     * Get queue (local mode only)
     */
    public function getQueue()
    {
        if ($this->useHybridMode) {
            return WhatsAppMessage::whereIn('status', ['pending', 'processing'])
                ->orderBy('created_at', 'asc')
                ->get();
        }

        try {
            if (file_exists($this->messageQueueFile)) {
                $queueData = file_get_contents($this->messageQueueFile);
                return json_decode($queueData, true) ?? [];
            }
            return [];
        } catch (\Exception $e) {
            Log::error('Error reading queue: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clear queue
     */
    public function clearQueue()
    {
        try {
            if ($this->useHybridMode) {
                WhatsAppMessage::whereIn('status', ['pending', 'processing'])->delete();
                Log::info('WhatsApp message queue cleared (hybrid mode)');
                return true;
            } else {
                if (file_exists($this->messageQueueFile)) {
                    unlink($this->messageQueueFile);
                    Log::info('WhatsApp message queue cleared (local mode)');
                    return true;
                }
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error clearing queue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Stop bot (local mode only)
     */
    public function stopBot()
    {
        if ($this->useHybridMode) {
            Log::info('Cannot stop bot in hybrid mode (bot runs on remote PC)');
            return false;
        }

        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('taskkill /F /IM node.exe 2>nul 1>nul');
            } else {
                exec("pkill -f 'whatsapp-bot.js' 2>/dev/null");
            }
            
            $this->botStarted = false;
            $this->isBotRunning = false;
            Log::info('WhatsApp bot stopped');
            return true;
        } catch (\Exception $e) {
            Log::error('Error stopping bot: ' . $e->getMessage());
            return false;
        }
    }

    public function isBotStarted()
    {
        return $this->botStarted;
    }

    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        if (substr($phone, 0, 3) === '+62') {
            $phone = '62' . substr($phone, 3);
        }
        
        if (strlen($phone) < 10) {
            throw new \Exception("Invalid phone number length: " . $phone);
        }
        
        return $phone;
    }
}
