<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penghuni;
use App\Models\Pemilik;

class TestAuthCommand extends Command
{
    protected $signature = 'test:auth';
    protected $description = 'Test authentication models and relationships';

    public function handle()
    {
        $this->info('Testing Authentication Models...');

        // Test Penghuni
        $penghuni = Penghuni::first();
        if ($penghuni) {
            $this->info("Penghuni found: {$penghuni->nama} ({$penghuni->email})");
        } else {
            $this->error('No penghuni found');
        }

        // Test Pemilik
        $pemilik = Pemilik::first();
        if ($pemilik) {
            $this->info("Pemilik found: {$pemilik->nama} ({$pemilik->email})");
        } else {
            $this->error('No pemilik found');
        }

        $this->info('Auth test completed!');
        
        return Command::SUCCESS;
    }
}