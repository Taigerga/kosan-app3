<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;

class TestWhatsAppCommand extends Command
{
    protected $signature = 'whatsapp:test {phone} {message}';
    protected $description = 'Test WhatsApp message sending';

    public function handle()
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        // Format nomor
        if (substr($phone, 0, 2) === '08') {
            $phone = '62' . substr($phone, 1);
        }

        $this->info("📱 Mengirim ke: {$phone}");
        $this->info("💬 Pesan: {$message}");

        $whatsapp = app(WhatsAppService::class);

        try {
            // Cek status dengan error handling
            $status = $whatsapp->getStatus();
            
            // PERBAIKAN: Handle jika key tidak ada
            $isRunning = $status['is_running'] ?? false;
            $isConnected = $status['connected'] ?? false;
            
            $this->info('🤖 Bot Status: ' . ($isRunning ? '✅ Running' : '❌ Stopped'));
            $this->info('🔗 Connection: ' . ($isConnected ? '✅ Connected' : '❌ Disconnected'));
            
            if (isset($status['message'])) {
                $this->info('📝 Message: ' . $status['message']);
            }
            
            // Debug: Tampilkan semua status
            $this->info("\n📊 Full Status:");
            foreach ($status as $key => $value) {
                $this->info("  {$key}: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value));
            }

            if (!$isRunning || !$isConnected) {
                $this->error('⚠️ WhatsApp bot is not running/connected!');
                $this->line('ℹ️  Coba salah satu dari:');
                $this->line('   1. php artisan whatsapp:start');
                $this->line('   2. Buka: http://localhost:8000/whatsapp/qr');
                $this->line('   3. Cek folder: whatsapp/auth_info/');
                return 1;
            }

            // Kirim pesan
            $this->info("\n🚀 Mengirim pesan...");
            $result = $whatsapp->sendMessage($phone, $message);
            
            if (isset($result['success']) && $result['success']) {
                $this->info('✅ Message queued successfully!');
                $this->info('📤 ID: ' . ($result['message_id'] ?? 'N/A'));
            } else {
                $this->error('❌ Failed to queue message');
                if (isset($result['error'])) {
                    $this->error('   Error: ' . $result['error']);
                }
            }

        } catch (\Exception $e) {
            $this->error('💥 Exception: ' . $e->getMessage());
            $this->error('📝 Trace: ' . $e->getTraceAsString());
        }

        return 0;
    }
}