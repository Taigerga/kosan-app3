<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;

class WhatsAppQueueCommand extends Command
{
    protected $signature = 'whatsapp:queue {action? : monitor|clear|status}';
    protected $description = 'Monitor and manage WhatsApp message queue';

    public function handle()
    {
        $action = $this->argument('action') ?? 'monitor';
        $whatsapp = app(WhatsAppService::class);

        switch ($action) {
            case 'status':
                $this->showStatus($whatsapp);
                break;
            case 'clear':
                $this->clearQueue($whatsapp);
                break;
            case 'monitor':
            default:
                $this->monitorQueue($whatsapp);
                break;
        }
    }

    private function showStatus($whatsapp)
    {
        $status = $whatsapp->getStatus();
        $queue = $whatsapp->getQueue();

        $this->info('📊 WhatsApp Bot Status:');
        $this->line('Running: ' . ($status['is_running'] ? '✅ Yes' : '❌ No'));
        $this->line('PID: ' . ($status['pid'] ?? 'N/A'));
        $this->line('Queue Count: ' . $status['queue_count']);
        $this->line('Script Exists: ' . ($status['node_script_exists'] ? '✅ Yes' : '❌ No'));
        $this->line('Auth Folder: ' . ($status['auth_folder_exists'] ? '✅ Yes' : '❌ No'));

        if (!empty($queue)) {
            $this->info("\n📨 Message Queue:");
            foreach ($queue as $index => $message) {
                $this->line(($index + 1) . ". To: {$message['phone']}");
                $this->line("   Message: {$message['message']}");
                $this->line("   Status: {$message['status']}");
                $this->line("   Time: {$message['timestamp']}");
                $this->line("   ---");
            }
        }
    }

    private function clearQueue($whatsapp)
    {
        if ($this->confirm('Are you sure you want to clear the WhatsApp message queue?')) {
            $success = $whatsapp->clearQueue();
            if ($success) {
                $this->info('✅ Queue cleared successfully!');
            } else {
                $this->error('❌ Failed to clear queue');
            }
        }
    }

    private function monitorQueue($whatsapp)
    {
        try {
            $status = $whatsappService->getConnectionStatus();
            
            // PERBAIKAN: Cek apakah key 'is_running' ada
            if (!isset($status['is_running'])) {
                $this->error('WhatsApp service tidak merespon atau belum berjalan');
                $this->info('Status raw: ' . json_encode($status));
                
                // Coba start WhatsApp service
                $this->info('Mencoba memulai WhatsApp service...');
                $startResult = $whatsappService->start();
                $this->info('Start result: ' . json_encode($startResult));
                
                // Tunggu sebentar
                sleep(3);
                
                // Cek status lagi
                $status = $whatsappService->getConnectionStatus();
            }
            
            // Sekarang cek dengan aman
            if (isset($status['is_running']) && $status['is_running']) {
                $this->info('WhatsApp service berjalan');
                $this->info('Pesan dalam queue: ' . ($status['queue_count'] ?? 0));
                
                // Process queue jika ada
                if (isset($status['queue_count']) && $status['queue_count'] > 0) {
                    $processed = $whatsappService->processQueue();
                    $this->info('Diproses: ' . $processed . ' pesan');
                }
            } else {
                $this->error('WhatsApp service tidak berjalan');
                $this->info('QR Code: http://localhost:8000/whatsapp/qr');
            }
            
        } catch (\Exception $e) {
            $this->error('Error monitoring queue: ' . $e->getMessage());
        }
    }
}