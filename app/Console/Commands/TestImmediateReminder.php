<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\KontrakSewa;
use Carbon\Carbon;

class TestImmediateReminder extends Command
{
    protected $signature = 'reminder:immediate {kontrakId}';
    protected $description = 'Send immediate reminder for testing';

    public function handle()
    {
        $kontrakId = $this->argument('kontrakId');
        $notificationService = app(NotificationService::class);
        $today = Carbon::now();
        
        $kontrak = KontrakSewa::with(['penghuni', 'kos', 'kos.pemilik', 'kamar'])->find($kontrakId);
        
        if (!$kontrak) {
            $this->error("Kontrak ID {$kontrakId} tidak ditemukan!");
            return;
        }
        
        $sisaHari = $today->diffInDays($kontrak->tanggal_selesai, false);
        $isPast = $sisaHari < 0;
        
        $this->info("📋 KONTRAK DETAIL:");
        $this->info("ID: {$kontrak->id_kontrak}");
        $this->info("Penghuni: {$kontrak->penghuni->nama} ({$kontrak->penghuni->no_hp})");
        $this->info("Kos: {$kontrak->kos->nama_kos}");
        $this->info("Pemilik: {$kontrak->kos->pemilik->nama} ({$kontrak->kos->pemilik->no_hp})");
        $this->info("Tanggal selesai: {$kontrak->tanggal_selesai->format('Y-m-d')}");
        $this->info("Sisa hari: {$sisaHari}");
        $this->info("Status: " . ($isPast ? 'TERLAMBAT' : 'AKTIF'));
        
        $this->newLine();
        $this->info("🚀 MENGIRIM NOTIFIKASI...");
        
        if ($isPast) {
            // Sudah lewat
            $hariTerlambat = abs($sisaHari);
            $this->info("📨 Mengirim notifikasi terlambat ({$hariTerlambat} hari)...");
            
            $success1 = $notificationService->sendNotifikasiTerlambat($kontrakId, $hariTerlambat);
            $success2 = $notificationService->sendNotifikasiTerlambatPemilik($kontrakId, $hariTerlambat);
            
            if ($success1 && $success2) {
                $kontrak->update(['notif_terlambat_dikirim' => now()]);
                $this->info("✅ Notifikasi terlambat berhasil dikirim!");
            } else {
                $this->error("❌ Gagal mengirim notifikasi terlambat!");
            }
            
        } elseif ($sisaHari <= 2 && $sisaHari >= 0) {
            // H-0 sampai H-2
            if ($sisaHari == 0) {
                $this->info("📨 Mengirim pengingat HARI INI...");
                $success1 = $notificationService->sendPengingatHariIni($kontrakId);
                $success2 = $notificationService->sendPengingatHariIniPemilik($kontrakId);
                $flag = 'notif_hari_ini_dikirim';
            } elseif ($sisaHari == 1) {
                $this->info("📨 Mengirim pengingat BESOK (H-1)...");
                $success1 = $notificationService->sendPengingatH1($kontrakId);
                $success2 = $notificationService->sendPengingatH1Pemilik($kontrakId);
                $flag = 'notif_h1_dikirim';
            } else {
                $this->info("📨 Mengirim pengingat 2 HARI LAGI...");
                $success1 = $notificationService->sendPengingat3Hari($kontrakId);
                $success2 = $notificationService->sendPengingat3HariPemilik($kontrakId);
                $flag = 'notif_3hari_dikirim';
            }
            
            if ($success1 && $success2) {
                $kontrak->update([$flag => now()]);
                $this->info("✅ Notifikasi berhasil dikirim!");
            } else {
                $this->error("❌ Gagal mengirim notifikasi!");
            }
            
        } elseif ($sisaHari <= 8 && $sisaHari >= 6) {
            // 6-8 hari lagi
            $this->info("📨 Mengirim pengingat 7 HARI...");
            
            $success1 = $notificationService->sendPengingat7Hari($kontrakId);
            $success2 = $notificationService->sendPengingat7HariPemilik($kontrakId);
            
            if ($success1 && $success2) {
                $kontrak->update(['notif_7hari_dikirim' => now()]);
                $this->info("✅ Notifikasi 7 hari berhasil dikirim!");
            } else {
                $this->error("❌ Gagal mengirim notifikasi 7 hari!");
            }
            
        } else {
            $this->warn("⚠️  Kontrak tidak dalam range notifikasi (6-8 hari, 2-4 hari, atau 0-2 hari).");
            $this->info("Sisa hari: {$sisaHari}");
        }
        
        $this->newLine();
        $this->info("📊 CEK QUEUE STATUS:");
        $whatsapp = app(\App\Services\WhatsAppService::class);
        $status = $whatsapp->getStatus();
        $this->info("Queue count: " . $status['queue_count']);
        $this->info("Pending messages: " . $status['pending_messages']);
        
        $this->newLine();
        $this->info("🔧 CEK LOG & QUEUE:");
        $this->info("Log file: storage/logs/laravel.log");
        $this->info("Queue file: storage/app/whatsapp_messages.json");
        
        // Tampilkan isi queue
        $queue = $whatsapp->getQueue();
        if (!empty($queue)) {
            $this->newLine();
            $this->info("📨 MESSAGES IN QUEUE:");
            foreach ($queue as $index => $msg) {
                $this->info(($index + 1) . ". To: {$msg['phone']}");
                $this->info("   Status: {$msg['status']}");
                $this->info("   Message: " . substr($msg['message'], 0, 50) . "...");
                $this->info("   ---");
            }
        }
    }
}