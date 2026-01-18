<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KontrakSewa;
use Carbon\Carbon;

class ForceReminderTestCommand extends Command
{
    protected $signature = 'reminder:force-test {--kontrak=} {--days=7}';
    protected $description = 'Force test reminder notifications by modifying contract dates';

    public function handle()
    {
        $kontrakId = $this->option('kontrak');
        $daysOffset = (int) $this->option('days'); // Konversi ke integer
        
        if (!$kontrakId) {
            // Cari kontrak aktif pertama
            $kontrak = KontrakSewa::where('status_kontrak', 'aktif')
                ->with(['penghuni', 'kos'])
                ->first();
            
            if (!$kontrak) {
                $this->error('Tidak ada kontrak aktif ditemukan!');
                return;
            }
            
            $kontrakId = $kontrak->id_kontrak;
        }
        
        $kontrak = KontrakSewa::find($kontrakId);
        
        if (!$kontrak) {
            $this->error("Kontrak ID {$kontrakId} tidak ditemukan!");
            return;
        }
        
        $this->info('📋 KONTRAK SEBELUM:');
        $this->info('ID: ' . $kontrak->id_kontrak);
        $this->info('Penghuni: ' . $kontrak->penghuni->nama);
        $this->info('Kos: ' . $kontrak->kos->nama_kos);
        $this->info('Tanggal selesai: ' . $kontrak->tanggal_selesai->format('Y-m-d'));
        $this->info('Sisa hari dari sekarang: ' . Carbon::now()->diffInDays($kontrak->tanggal_selesai));
        
        // Hitung tanggal baru (hari ini + X hari)
        $newEndDate = Carbon::now()->addDays($daysOffset);
        $originalDate = $kontrak->tanggal_selesai;
        
        $this->info("\n🔄 MENGUPDATE KONTRAK...");
        $this->info('Tanggal selesai baru: ' . $newEndDate->format('Y-m-d'));
        $this->info('Sisa hari baru: ' . Carbon::now()->diffInDays($newEndDate) . ' hari');
        
        // Update tanggal selesai untuk testing
        $kontrak->update([
            'tanggal_selesai' => $newEndDate,
            'notif_7hari_dikirim' => null,
            'notif_3hari_dikirim' => null,
            'notif_h1_dikirim' => null,
            'notif_hari_ini_dikirim' => null,
            'notif_terlambat_dikirim' => null,
        ]);
        
        $this->info('✅ Kontrak berhasil diupdate!');
        $this->info('✅ Semua notifikasi flags direset');
        
        $this->info("\n🚀 MENJALANKAN SCHEDULER...");
        
        // Jalankan scheduler manual
        \Artisan::call('schedule:run');
        
        $this->info("\n📊 HASIL:");
        $this->info('✅ Scheduler berhasil dijalankan');
        $this->info('📄 Check log: storage/logs/laravel.log');
        $this->info('📁 Check queue: storage/app/whatsapp_messages.json');
        
        // Cek kontrak setelah update
        $updatedKontrak = KontrakSewa::find($kontrakId);
        $this->info("\n📅 KONTRAK SETELAH UPDATE:");
        $this->info('Tanggal selesai: ' . $updatedKontrak->tanggal_selesai->format('Y-m-d'));
        $this->info('Sisa hari: ' . Carbon::now()->diffInDays($updatedKontrak->tanggal_selesai) . ' hari');
        
        // Tampilkan status notifikasi
        $this->info("\n🚩 STATUS NOTIFIKASI:");
        $this->info('7 hari: ' . ($updatedKontrak->notif_7hari_dikirim ? '✅ Dikirim' : '⏳ Pending'));
        $this->info('3 hari: ' . ($updatedKontrak->notif_3hari_dikirim ? '✅ Dikirim' : '⏳ Pending'));
        $this->info('H-1: ' . ($updatedKontrak->notif_h1_dikirim ? '✅ Dikirim' : '⏳ Pending'));
        $this->info('Hari ini: ' . ($updatedKontrak->notif_hari_ini_dikirim ? '✅ Dikirim' : '⏳ Pending'));
        $this->info('Terlambat: ' . ($updatedKontrak->notif_terlambat_dikirim ? '✅ Dikirim' : '⏳ Pending'));
        
        // Restore original date jika diminta
        if ($this->confirm('Kembalikan tanggal ke asli?', false)) {
            $updatedKontrak->update(['tanggal_selesai' => $originalDate]);
            $this->info('✅ Tanggal dikembalikan ke: ' . $originalDate->format('Y-m-d'));
        }
        
        $this->info("\n🔧 TEST COMMANDS LAINNYA:");
        $this->info('Test 7 hari: php artisan reminder:test ' . $kontrakId . ' 7hari');
        $this->info('Test 3 hari: php artisan reminder:test ' . $kontrakId . ' 3hari');
        $this->info('Test H-1: php artisan reminder:test ' . $kontrakId . ' h1');
        $this->info('Test hari ini: php artisan reminder:test ' . $kontrakId . ' hariini');
        $this->info('Test terlambat: php artisan reminder:test ' . $kontrakId . ' terlambat');
        
        $this->info("\n📱 CHECK WHATSAPP QUEUE:");
        $this->info('php artisan whatsapp:queue status');
    }
}