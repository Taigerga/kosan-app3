<?php

namespace App\Console\Commands;

use App\Services\NotificationEmailService;
use Illuminate\Console\Command;

class SendEmailNotifications extends Command
{
    protected $signature = 'notifications:send-emails';
    protected $description = 'Kirim notifikasi email untuk kontrak dan tenggat waktu';

    protected $emailService;

    public function __construct(NotificationEmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    public function handle()
    {
        $this->info('Memulai pengiriman notifikasi email...');
        
        // Kirim notifikasi tenggat waktu
        $this->emailService->checkAndSendTenggatWaktuNotifications();
        
        $this->info('Notifikasi email telah dikirim.');
        
        return Command::SUCCESS;
    }
}