<?php
// app/Console/Commands/SendReminderNotifications.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KontrakSewa;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendReminderNotifications extends Command
{
    protected $signature = 'notifications:send-reminders';
    protected $description = 'Send WhatsApp reminder notifications for contract deadlines';

    public function handle(NotificationService $notificationService)
    {
        $today = Carbon::today();
        
        // Cari kontrak yang akan berakhir dalam 5 hari
        $fiveDaysFromNow = $today->copy()->addDays(5);
        $contractsDueIn5Days = KontrakSewa::whereDate('tanggal_selesai', $fiveDaysFromNow)
            ->where('status_kontrak', 'aktif')
            ->get();

        foreach ($contractsDueIn5Days as $contract) {
            $notificationService->send5DaysReminder($contract->id_kontrak);
            $this->info("Sent 5-day reminder for contract: {$contract->id_kontrak}");
        }

        // Cari kontrak yang berakhir hari ini
        $contractsDueToday = KontrakSewa::whereDate('tanggal_selesai', $today)
            ->where('status_kontrak', 'aktif')
            ->get();

        foreach ($contractsDueToday as $contract) {
            $notificationService->sendLastDayReminder($contract->id_kontrak);
            $this->info("Sent last-day reminder for contract: {$contract->id_kontrak}");
        }

        $this->info('Reminder notifications sent successfully.');
    }
}