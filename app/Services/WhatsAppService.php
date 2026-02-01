<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $messageQueueFile;

    public function __construct()
    {
        $this->messageQueueFile = storage_path('app/whatsapp_messages.json');

        // Pastikan file queue ada
        if (!file_exists($this->messageQueueFile)) {
            file_put_contents($this->messageQueueFile, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    /**
     * QUEUE ONLY
     * Tidak pernah start / stop bot dari PHP
     */
    public function sendMessage(string $phone, string $message): bool
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phone);

            $payload = [
                'id'        => uniqid('wa_', true),
                'phone'     => $formattedPhone,
                'message'   => $message,
                'status'    => 'pending',
                'created_at'=> now()->toISOString()
            ];

            $queue = json_decode(file_get_contents($this->messageQueueFile), true) ?? [];
            $queue[] = $payload;

            file_put_contents(
                $this->messageQueueFile,
                json_encode($queue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            Log::info('WA queued', [
                'phone' => $formattedPhone,
                'id'    => $payload['id']
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error('WA queue failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Read queue (monitoring)
     */
    public function getQueue(): array
    {
        try {
            return json_decode(file_get_contents($this->messageQueueFile), true) ?? [];
        } catch (\Throwable $e) {
            Log::error('Read queue failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Optional: clear queue (admin only)
     */
    public function clearQueue(): bool
    {
        try {
            file_put_contents($this->messageQueueFile, json_encode([], JSON_PRETTY_PRINT));
            Log::warning('WA queue cleared manually');
            return true;
        } catch (\Throwable $e) {
            Log::error('Clear queue failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Format nomor ke 62xxxxxxxxx
     */
    private function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '62') === false) {
            throw new \Exception('Invalid phone format');
        }

        if (strlen($phone) < 10) {
            throw new \Exception('Phone number too short');
        }

        return $phone;
    }
}
