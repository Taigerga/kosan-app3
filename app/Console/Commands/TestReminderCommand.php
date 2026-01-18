<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\KontrakSewa;
use Carbon\Carbon;

class TestReminderCommand extends Command
{
    protected $signature = 'reminder:test {kontrakId} {type}';
    protected $description = 'Test reminder notifications';

    public function handle()
    {
        $kontrakId = $this->argument('kontrakId');
        $type = $this->argument('type');
        
        $notificationService = app(NotificationService::class);
        $kontrak = KontrakSewa::find($kontrakId);
        
        if (!$kontrak) {
            $this->error("Kontrak ID {$kontrakId} tidak ditemukan!");
            return;
        }
        
        $sisaHari = Carbon::now()->diffInDays($kontrak->tanggal_selesai);
        $this->info("Kontrak ID: {$kontrakId}");
        $this->info("Penghuni: {$kontrak->penghuni->nama}");
        $this->info("Kos: {$kontrak->kos->nama_kos}");
        $this->info("Sisa hari: {$sisaHari}");
        $this->info("Tanggal selesai: {$kontrak->tanggal_selesai->format('d F Y')}");
        
        switch ($type) {
            case '7hari':
                $this->info("\n📨 Testing Pengingat 7 Hari:");
                $success = $notificationService->sendPengingat7Hari($kontrakId);
                $successPemilik = $notificationService->sendPengingat7HariPemilik($kontrakId);
                break;
                
            case '3hari':
                $this->info("\n📨 Testing Pengingat 3 Hari:");
                $success = $notificationService->sendPengingat3Hari($kontrakId);
                $successPemilik = $notificationService->sendPengingat3HariPemilik($kontrakId);
                break;
                
            case 'h1':
                $this->info("\n📨 Testing Pengingat H-1:");
                $success = $notificationService->sendPengingatH1($kontrakId);
                $successPemilik = $notificationService->sendPengingatH1Pemilik($kontrakId);
                break;
                
            case 'hariini':
                $this->info("\n📨 Testing Pengingat Hari Ini:");
                $success = $notificationService->sendPengingatHariIni($kontrakId);
                $successPemilik = $notificationService->sendPengingatHariIniPemilik($kontrakId);
                break;
                
            case 'terlambat':
                $hariTerlambat = 5; // Contoh 5 hari terlambat
                $this->info("\n📨 Testing Notifikasi Terlambat ({$hariTerlambat} hari):");
                $success = $notificationService->sendNotifikasiTerlambat($kontrakId, $hariTerlambat);
                $successPemilik = $notificationService->sendNotifikasiTerlambatPemilik($kontrakId, $hariTerlambat);
                break;
                
            default:
                $this->error("Type tidak valid. Pilihan: 7hari, 3hari, h1, hariini, terlambat");
                return;
        }
        
        if ($success) {
            $this->info("✅ Notifikasi untuk penghuni BERHASIL dikirim!");
        } else {
            $this->error("❌ Notifikasi untuk penghuni GAGAL dikirim!");
        }
        
        if ($successPemilik) {
            $this->info("✅ Notifikasi untuk pemilik BERHASIL dikirim!");
        } else {
            $this->error("❌ Notifikasi untuk pemilik GAGAL dikirim!");
        }
    }
}