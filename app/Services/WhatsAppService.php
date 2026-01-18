<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WhatsAppService
{
    private $messageQueueFile;
    private $isBotRunning = false;
    private $botStarted = false;

    public function __construct()
    {
        $this->messageQueueFile = storage_path('app/whatsapp_messages.json');
        // JANGAN start bot di constructor!
        // $this->initNodeProcess(); // HAPUS BARIS INI
    }

    /**
     * Start bot manually (bukan otomatis)
     */
    public function startBot()
    {
        if ($this->botStarted) {
            return true;
        }

        try {
            $nodeScriptPath = base_path('app/Services/WhatsAppBot/whatsapp-bot.js');
            
            if (!file_exists($nodeScriptPath)) {
                Log::error('WhatsApp bot script not found: ' . $nodeScriptPath);
                return false;
            }

            // Jalankan di background (detached)
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows: start /B (background) di console terpisah
                $command = 'start "WhatsApp Bot" /B cmd /c "node ' . escapeshellarg($nodeScriptPath) . '"';
                pclose(popen($command, 'r'));
            } else {
                // Linux/Mac: nohup &
                $command = 'nohup node ' . escapeshellarg($nodeScriptPath) . ' > /dev/null 2>&1 &';
                exec($command);
            }

            Log::info('WhatsApp bot started in background');
            $this->botStarted = true;
            $this->isBotRunning = true;
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to start WhatsApp bot: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send message - bot akan start otomatis hanya saat pertama kali send
     */
    public function sendMessage($phone, $message)
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phone);
            
            $messageData = [
                'id' => uniqid(),
                'type' => 'send_message',
                'phone' => $formattedPhone,
                'message' => $message,
                'timestamp' => now()->toISOString(),
                'status' => 'pending'
            ];

            // Baca queue yang ada
            $queue = [];
            if (file_exists($this->messageQueueFile)) {
                $existingData = file_get_contents($this->messageQueueFile);
                if ($existingData) {
                    $queue = json_decode($existingData, true) ?? [];
                }
            }

            // Tambahkan message baru
            $queue[] = $messageData;

            // Simpan ke file
            file_put_contents($this->messageQueueFile, json_encode($queue, JSON_PRETTY_PRINT));

            Log::info("WhatsApp message queued for: {$formattedPhone}", [
                'phone' => $formattedPhone,
                'message_id' => $messageData['id']
            ]);
            
            // Start bot jika belum running (hanya untuk message pertama)
            if (!$this->botStarted) {
                $this->startBot();
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error queueing WhatsApp message: ' . $e->getMessage());
            return false;
        }
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

    /**
     * Check bot status
     */
    public function getStatus()
    {
        $queueCount = 0;
        $pendingCount = 0;
        
        if (file_exists($this->messageQueueFile)) {
            $queueData = file_get_contents($this->messageQueueFile);
            $queue = json_decode($queueData, true) ?? [];
            $queueCount = count($queue);
            $pendingCount = count(array_filter($queue, fn($msg) => $msg['status'] === 'pending'));
        }

        return [
            'bot_started' => $this->botStarted,
            'queue_count' => $queueCount,
            'pending_messages' => $pendingCount,
            'queue_file_exists' => file_exists($this->messageQueueFile)
        ];
    }

    public function getQueue()
    {
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

    public function clearQueue()
    {
        try {
            if (file_exists($this->messageQueueFile)) {
                unlink($this->messageQueueFile);
                Log::info('WhatsApp message queue cleared');
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error clearing queue: ' . $e->getMessage());
            return false;
        }
    }

    public function stopBot()
    {
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
}