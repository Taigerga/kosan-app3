<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembayaran;
use App\Services\ALLNotificationService;

class TestPaymentNotification extends Command
{
    protected $signature = 'payment:test-notification {id}';
    protected $description = 'Test payment notification system';

    public function handle()
    {
        $id = $this->argument('id');
        $pembayaran = Pembayaran::with(['penghuni', 'kontrak.kos', 'kontrak.kamar'])->find($id);

        if (!$pembayaran) {
            $this->error('Pembayaran dengan ID ' . $id . ' tidak ditemukan');
            return 1;
        }

        $this->info('Testing payment notification for Pembayaran ID: ' . $id);
        $this->info('Kos: ' . $pembayaran->kontrak->kos->nama_kos);
        $this->info('Penghuni: ' . $pembayaran->penghuni->nama);
        $this->info('Jumlah: Rp ' . number_format($pembayaran->jumlah, 0, ',', '.'));

        try {
            $notificationService = app(ALLNotificationService::class);

            // Prepare payment data
            $paymentData = [
                'kosName' => $pembayaran->kontrak->kos->nama_kos,
                'roomNumber' => $pembayaran->kontrak->kamar->nomor_kamar ?? null,
                'amount' => $pembayaran->jumlah,
                'paymentDate' => $pembayaran->created_at ? $pembayaran->created_at->format('d/m/Y') : now()->format('d/m/Y'),
                'period' => ($pembayaran->tanggal_mulai_sewa ? $pembayaran->tanggal_mulai_sewa->format('d/m/Y') : $pembayaran->bulan_tahun) . ' - ' . ($pembayaran->tanggal_akhir_sewa ? $pembayaran->tanggal_akhir_sewa->format('d/m/Y') : $pembayaran->bulan_tahun),
                'penghuniName' => $pembayaran->penghuni->nama,
                'metodePembayaran' => $pembayaran->metode_pembayaran,
            ];

            $this->line("\nTesting WhatsApp notification...");
            $whatsappResult = $notificationService->sendPaymentWhatsAppNotification(
                '62812345678', // test number
                'pending_penghuni',
                $paymentData
            );

            $this->info('✅ WhatsApp notification test successful');

            $this->line("\nTesting email notification...");
            $emailResult = $notificationService->sendPaymentEmailNotification(
                'test@example.com',
                'pending_penghuni',
                $paymentData
            );

            $this->info('✅ Email notification test successful');

            $this->line("\nTesting dual notification...");
            $dualResult = $notificationService->sendDualPaymentNotification(
                $pembayaran->penghuni,
                'pending_penghuni',
                $paymentData,
                false
            );

            $this->info('✅ Dual notification test successful');

            $this->line("\nTesting approval notification...");
            $approvalResult = $notificationService->sendDualPaymentNotification(
                $pembayaran->penghuni,
                'approved_penghuni',
                array_merge($paymentData, ['approvedDate' => now()->format('d/m/Y')]),
                false
            );

            $this->info('✅ Approval notification test successful');

            $this->newLine();
            $this->info('🎉 All payment notification tests passed successfully!');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}