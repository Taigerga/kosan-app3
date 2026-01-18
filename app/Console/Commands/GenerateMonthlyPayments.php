<?php

namespace App\Console\Commands;

use App\Services\PaymentService;
use Illuminate\Console\Command;

class GenerateMonthlyPayments extends Command
{
    protected $signature = 'payments:generate-monthly';
    protected $description = 'Generate monthly payments for active contracts';

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
    }

    public function handle()
    {
        $count = $this->paymentService->generateMonthlyPayments();
        
        $this->info("Generated {$count} monthly payments.");
        
        // Juga cek pembayaran terlambat
        $overdueCount = $this->paymentService->checkOverduePayments();
        $this->info("Marked {$overdueCount} payments as overdue.");
        
        \Log::info("Generated {$count} monthly payments and marked {$overdueCount} as overdue at " . now());
    }
}