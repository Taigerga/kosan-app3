<?php

namespace App\Services;

use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $baseUrl;
    protected $apiKey;
    protected $secretKey;

    public function __construct()
    {
        // Konfigurasi payment gateway (contoh: Midtrans/Xendit)
        $this->baseUrl = config('services.payment.base_url');
        $this->apiKey = config('services.payment.api_key');
        $this->secretKey = config('services.payment.secret_key');
    }

    /**
     * Create payment transaction
     */
    public function createPayment(Pembayaran $pembayaran, $user)
    {
        try {
            // Untuk demo, kita buat simulasi payment
            $externalId = 'PYM-' . time() . '-' . $pembayaran->id_pembayaran;
            $paymentUrl = url("/payment/simulate/{$externalId}");

            // Update pembayaran dengan external_id (kita simpan di keterangan)
            $pembayaran->update([
                'keterangan' => "External ID: {$externalId}",
                'status_pembayaran' => 'pending'
            ]);

            return [
                'success' => true,
                'external_id' => $externalId,
                'payment_url' => $paymentUrl,
                'expires_at' => now()->addHours(24)
            ];

        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create payment'
            ];
        }
    }

    /**
     * Handle payment callback from payment gateway
     */
    public function handleCallback($payload)
    {
        try {
            $externalId = $payload['external_id'] ?? null;
            $status = $payload['status'] ?? null;

            if (!$externalId || !$status) {
                throw new \Exception('Invalid callback payload');
            }

            // Cari pembayaran berdasarkan external_id di keterangan
            $pembayaran = Pembayaran::where('keterangan', 'like', "%{$externalId}%")->first();

            if (!$pembayaran) {
                throw new \Exception('Payment not found: ' . $externalId);
            }

            switch ($status) {
                case 'success':
                    $pembayaran->markAsPaid();
                    break;
                case 'failed':
                    $pembayaran->update(['status_pembayaran' => 'belum']);
                    break;
                case 'expired':
                    $pembayaran->update(['status_pembayaran' => 'belum']);
                    break;
            }

            // Send notification
            event(new \App\Events\PaymentStatusUpdated($pembayaran));

            return [
                'success' => true,
                'message' => 'Payment status updated'
            ];

        } catch (\Exception $e) {
            Log::error('Payment callback failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($externalId)
    {
        try {
            $pembayaran = Pembayaran::where('keterangan', 'like', "%{$externalId}%")->first();

            if (!$pembayaran) {
                throw new \Exception('Payment not found');
            }

            return [
                'success' => true,
                'status' => $pembayaran->status_pembayaran,
                'pembayaran' => $pembayaran
            ];

        } catch (\Exception $e) {
            Log::error('Payment status check failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}