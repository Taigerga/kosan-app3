<?php

namespace App\Console\Commands;

use App\Services\PaymentService;
use Illuminate\Console\Command;

class ExpirePendingPayments extends Command
{
    protected $signature = 'payments:expire';
    protected $description = 'Expire pending payments that passed their expiry time';

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
    }

    public function handle()
    {
        $count = $this->paymentService->expirePendingPayments();
        
        $this->info("Expired {$count} pending payments.");
        
        // Log activity
        \Log::info("Expired {$count} pending payments at " . now());
    }
}