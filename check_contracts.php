<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KontrakSewa;
use Carbon\Carbon;

echo "=== CHECKING ACTIVE CONTRACTS ===\n\n";

$contracts = KontrakSewa::where('status_kontrak', 'aktif')
    ->with(['penghuni', 'kos', 'kamar'])
    ->get();

echo "Total active contracts: " . $contracts->count() . "\n\n";

foreach ($contracts as $contract) {
    $endDate = $contract->tanggal_selesai ? Carbon::parse($contract->tanggal_selesai) : null;
    $daysLeft = $endDate ? now()->diffInDays($endDate, false) : null;

    echo "Contract ID: {$contract->id_kontrak}\n";
    echo "  Penghuni: " . ($contract->penghuni ? $contract->penghuni->nama : 'N/A') . "\n";
    echo "  Kos: " . ($contract->kos ? $contract->kos->nama_kos : 'N/A') . "\n";
    echo "  Kamar: " . ($contract->kamar ? $contract->kamar->nomor_kamar : 'N/A') . "\n";
    echo "  Status Kamar: " . ($contract->kamar ? $contract->kamar->status_kamar : 'N/A') . "\n";
    echo "  Tanggal Mulai: " . ($contract->tanggal_mulai ? $contract->tanggal_mulai->format('Y-m-d') : 'NULL') . "\n";
    echo "  Tanggal Selesai: " . ($endDate ? $endDate->format('Y-m-d') : 'NULL') . "\n";
    echo "  Sisa Hari: " . ($daysLeft !== null ? $daysLeft . " hari" : 'N/A') . "\n";

    if ($endDate) {
        echo "  Is Past: " . ($endDate->isPast() ? 'YES' : 'NO') . "\n";
        echo "  Is Today: " . ($endDate->isToday() ? 'YES' : 'NO') . "\n";
        echo "  Should Complete: " . ($endDate->isPast() && !$endDate->isToday() ? 'YES ✓' : 'NO') . "\n";
    }

    echo "\n";
}

echo "=== DONE ===\n";
