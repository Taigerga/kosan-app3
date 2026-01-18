<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KontrakSewa;
use App\Models\Penghuni;
use App\Models\Pemilik;
use App\Services\ALLNotificationService;
use Carbon\Carbon;

class SendContractReminders extends Command
{
    protected $signature = 'contract:send-reminders
                            {--test : Test mode, don\'t actually send notifications}
                            {--contract= : Process specific contract ID}';

    protected $description = 'Send contract expiration reminders via email and WhatsApp';

    protected $allNotificationService;

    public function __construct(ALLNotificationService $allNotificationService)
    {
        parent::__construct();
        $this->allNotificationService = $allNotificationService;
    }

    public function handle()
    {
        $this->info('Starting contract reminder process...');
        $this->info('Time: ' . now()->format('Y-m-d H:i:s'));

        // Check for specific contract
        if ($this->option('contract')) {
            $contracts = KontrakSewa::where('id_kontrak', $this->option('contract'))
                ->where('status_kontrak', 'aktif')
                ->with(['penghuni', 'kos.pemilik', 'kamar'])
                ->get();
        } else {
            // Get all active contracts
            // Include contracts up to 30 days past expiration to catch any missed ones
            $contracts = KontrakSewa::where('status_kontrak', 'aktif')
                ->where('tanggal_selesai', '>=', now()->subDays(30))
                ->with(['penghuni', 'kos.pemilik', 'kamar'])
                ->get();
        }

        $this->info('Found ' . $contracts->count() . ' active contracts.');

        $remindersSent = 0;
        $completionsProcessed = 0;
        $errors = 0;

        foreach ($contracts as $contract) {
            try {
                $result = $this->processContract($contract);
                $remindersSent += $result['reminders'];

                // Check if contract should be marked as completed
                if ($this->shouldMarkAsCompleted($contract)) {
                    $this->markContractAsCompleted($contract);
                    $completionsProcessed++;
                }
            } catch (\Exception $e) {
                $errors++;
                $this->error("Error processing contract ID {$contract->id_kontrak}: " . $e->getMessage());
            }
        }

        $this->info("Process completed.");
        $this->info("- Reminders sent: {$remindersSent}");
        $this->info("- Contracts completed: {$completionsProcessed}");
        $this->info("- Errors: {$errors}");

        if ($this->option('test')) {
            $this->warn('⚠️ TEST MODE: No notifications were actually sent.');
        }
    }

    private function processContract(KontrakSewa $contract)
    {
        $remindersSent = 0;
        $endDate = Carbon::parse($contract->tanggal_selesai);
        $daysLeft = now()->diffInDays($endDate, false); // Negative if past due

        $penghuni = $contract->penghuni;
        $pemilik = $contract->kos->pemilik;

        if (!$penghuni) {
            $this->error("Missing penghuni for contract ID: {$contract->id_kontrak}");
            return ['reminders' => 0];
        }

        if (!$pemilik) {
            $this->error("Missing pemilik for contract ID: {$contract->id_kontrak}");
            return ['reminders' => 0];
        }

        // Contract information
        $kosName = $contract->kos->nama_kos;
        $roomNumber = $contract->kamar->nomor_kamar ?? 'N/A';
        $formattedEndDate = $endDate->format('d-m-Y');

        // Notifications for different days before expiration
        $reminderDays = [7, 5, 3, 1];

        foreach ($reminderDays as $days) {
            if ($daysLeft == $days && !$this->isNotificationSent($contract, "notif_{$days}hari_dikirim")) {
                $this->sendDualReminder(
                    $contract,
                    $penghuni,
                    $pemilik,
                    $kosName,
                    $roomNumber,
                    $days,
                    $formattedEndDate,
                    'before'
                );
                $this->markNotificationSent($contract, "notif_{$days}hari_dikirim");
                $remindersSent++;
            }
        }

        // Notification on expiration day
        if ($daysLeft == 0 && !$this->isNotificationSent($contract, "notif_hari_ini_dikirim")) {
            $this->sendDualReminder(
                $contract,
                $penghuni,
                $pemilik,
                $kosName,
                $roomNumber,
                0,
                $formattedEndDate,
                'today'
            );
            $this->markNotificationSent($contract, "notif_hari_ini_dikirim");
            $remindersSent++;
        }

        // Notification for overdue contracts (1-7 days overdue)
        if ($daysLeft < 0 && $daysLeft >= -7 && !$this->isNotificationSent($contract, "notif_terlambat_dikirim")) {
            $this->sendDualReminder(
                $contract,
                $penghuni,
                $pemilik,
                $kosName,
                $roomNumber,
                abs($daysLeft),
                $formattedEndDate,
                'overdue'
            );
            $this->markNotificationSent($contract, "notif_terlambat_dikirim");
            $remindersSent++;
        }

        return ['reminders' => $remindersSent];
    }

    private function sendDualReminder($contract, $penghuni, $pemilik, $kosName, $roomNumber, $days, $endDate, $type)
    {
        if (!$this->option('test')) {
            // Send to penghuni
            $this->info("Sending {$type} reminder to penghuni: {$penghuni->nama} (Contract: {$contract->id_kontrak})");

            $this->allNotificationService->sendDualContractReminder(
                $penghuni,
                $kosName,
                $roomNumber,
                $days,
                $endDate,
                $type,
                false // isPemilik
            );

            // Send to pemilik (with modified message)
            $this->info("Sending {$type} reminder to pemilik: {$pemilik->nama}");

            $this->allNotificationService->sendDualContractReminder(
                $pemilik,
                $kosName,
                $roomNumber,
                $days,
                $endDate,
                $type,
                true // isPemilik
            );
        } else {
            $this->line("TEST: Would send {$type} reminder for contract {$contract->id_kontrak}");
            $this->line("      Penghuni: {$penghuni->nama}, Pemilik: {$pemilik->nama}");
        }
    }

    private function shouldMarkAsCompleted(KontrakSewa $contract)
    {
        // Mark as completed if contract ended yesterday or earlier
        // and status is still active
        $endDate = Carbon::parse($contract->tanggal_selesai);
        return $contract->status_kontrak === 'aktif' && $endDate->isPast() && !$endDate->isToday();
    }

    private function markContractAsCompleted(KontrakSewa $contract)
    {
        if (!$this->option('test')) {
            // Update contract status
            $contract->status_kontrak = 'selesai';
            $contract->save();

            // Also update room status
            if ($contract->kamar) {
                $contract->kamar->status_kamar = 'tersedia';
                $contract->kamar->save();
            }

            // Send completion notification
            $this->sendCompletionNotification($contract);

            $this->info("✓ Contract ID {$contract->id_kontrak} marked as completed");
        } else {
            $this->line("TEST: Would mark contract {$contract->id_kontrak} as completed");
        }
    }

    private function sendCompletionNotification(KontrakSewa $contract)
    {
        if ($this->option('test')) {
            $this->line("TEST: Would send completion notification for contract {$contract->id_kontrak}");
            return;
        }

        $penghuni = $contract->penghuni;
        $pemilik = $contract->kos->pemilik;
        $kosName = $contract->kos->nama_kos;
        $roomNumber = $contract->kamar->nomor_kamar ?? 'N/A';
        $endDate = Carbon::parse($contract->tanggal_selesai)->format('d-m-Y');

        $this->info("Sending completion notification for contract {$contract->id_kontrak}");

        // Send to penghuni
        $this->allNotificationService->sendDualContractReminder(
            $penghuni,
            $kosName,
            $roomNumber,
            0,
            $endDate,
            'completion',
            false
        );

        // Send to pemilik
        $this->allNotificationService->sendDualContractReminder(
            $pemilik,
            $kosName,
            $roomNumber,
            0,
            $endDate,
            'completion',
            true
        );
    }

    private function isNotificationSent(KontrakSewa $contract, $field)
    {
        return !empty($contract->$field);
    }

    private function markNotificationSent(KontrakSewa $contract, $field)
    {
        if (!$this->option('test')) {
            $contract->$field = now();
            $contract->save();
        }
    }
}