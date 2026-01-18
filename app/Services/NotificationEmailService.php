<?php

namespace App\Services;

use App\Mail\Penghuni\KontrakDiterimaMail;
use App\Mail\Penghuni\KontrakDitolakMail;
use App\Mail\Penghuni\NotifikasiTenggatWaktuMail;
use App\Mail\Pemilik\PengajuanBaruMail;
use App\Mail\Pemilik\NotifikasiTenggatWaktuPemilikMail;
use App\Models\KontrakSewa;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NotificationEmailService
{
    /**
     * Kirim email notifikasi kontrak diterima ke penghuni
     */
    public function sendKontrakDiterima(KontrakSewa $kontrak)
    {
        try {
            Mail::to($kontrak->penghuni->email)
                ->send(new KontrakDiterimaMail($kontrak));
            
            \Log::info("Email kontrak diterima dikirim ke: " . $kontrak->penghuni->email);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email kontrak diterima: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email notifikasi kontrak ditolak ke penghuni
     */
    public function sendKontrakDitolak(KontrakSewa $kontrak)
    {
        try {
            Mail::to($kontrak->penghuni->email)
                ->send(new KontrakDitolakMail($kontrak));
            
            \Log::info("Email kontrak ditolak dikirim ke: " . $kontrak->penghuni->email);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email kontrak ditolak: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email notifikasi pengajuan baru ke pemilik
     */
    public function sendPengajuanBaruToPemilik(KontrakSewa $kontrak)
    {
        try {
            Mail::to($kontrak->kos->pemilik->email)
                ->send(new PengajuanBaruMail($kontrak));
            
            \Log::info("Email pengajuan baru dikirim ke pemilik: " . $kontrak->kos->pemilik->email);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email pengajuan baru: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email notifikasi tenggat waktu ke PENGHUNI
     */
    public function sendTenggatWaktuToPenghuni(KontrakSewa $kontrak, $tipeNotifikasi)
    {
        try {
            $hariSisa = Carbon::parse($kontrak->tanggal_selesai)->diffInDays(now());
            
            Mail::to($kontrak->penghuni->email)
                ->send(new NotifikasiTenggatWaktuMail($kontrak, $hariSisa, $tipeNotifikasi));
            
            \Log::info("Email {$tipeNotifikasi} dikirim ke penghuni: " . $kontrak->penghuni->email);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email ke penghuni: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email notifikasi tenggat waktu ke PEMILIK
     */
    public function sendTenggatWaktuToPemilik(KontrakSewa $kontrak, $tipeNotifikasi)
    {
        try {
            $hariSisa = Carbon::parse($kontrak->tanggal_selesai)->diffInDays(now());
            
            Mail::to($kontrak->kos->pemilik->email)
                ->send(new NotifikasiTenggatWaktuPemilikMail($kontrak, $hariSisa, $tipeNotifikasi));
            
            \Log::info("Email {$tipeNotifikasi} dikirim ke pemilik: " . $kontrak->kos->pemilik->email);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email ke pemilik: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim notifikasi untuk semua kontrak yang mendekati tenggat waktu
     * Dipanggil oleh scheduled command
     */
    public function checkAndSendTenggatWaktuNotifications()
    {
        $kontraks = KontrakSewa::where('status_kontrak', 'aktif')
            ->whereNotNull('tanggal_selesai')
            ->get();

        foreach ($kontraks as $kontrak) {
            $this->checkSingleKontrak($kontrak);
        }
    }

    /**
     * Cek notifikasi untuk satu kontrak
     */
    private function checkSingleKontrak(KontrakSewa $kontrak)
    {
        $tanggalSelesai = Carbon::parse($kontrak->tanggal_selesai);
        $hariSisa = $tanggalSelesai->diffInDays(now());
        
        // 7 hari sebelum berakhir
        if ($hariSisa == 7 && !$kontrak->notif_7hari_dikirim) {
            $this->sendTenggatWaktuToPenghuni($kontrak, '7_hari');
            $this->sendTenggatWaktuToPemilik($kontrak, '7_hari');
            $kontrak->update(['notif_7hari_dikirim' => now()]);
        }
        
        // 3 hari sebelum berakhir
        if ($hariSisa == 3 && !$kontrak->notif_3hari_dikirim) {
            $this->sendTenggatWaktuToPenghuni($kontrak, '3_hari');
            $this->sendTenggatWaktuToPemilik($kontrak, '3_hari');
            $kontrak->update(['notif_3hari_dikirim' => now()]);
        }
        
        // 1 hari sebelum berakhir
        if ($hariSisa == 1 && !$kontrak->notif_h1_dikirim) {
            $this->sendTenggatWaktuToPenghuni($kontrak, '1_hari');
            $this->sendTenggatWaktuToPemilik($kontrak, '1_hari');
            $kontrak->update(['notif_h1_dikirim' => now()]);
        }
        
        // Hari berakhir
        if ($hariSisa == 0 && !$kontrak->notif_hari_ini_dikirim) {
            $this->sendTenggatWaktuToPenghuni($kontrak, 'tenggat');
            $this->sendTenggatWaktuToPemilik($kontrak, 'tenggat');
            $kontrak->update(['notif_hari_ini_dikirim' => now()]);
        }
        
        // Sudah melewati tenggat waktu (1 hari setelah berakhir)
        if ($hariSisa < 0 && abs($hariSisa) == 1 && !$kontrak->notif_terlambat_dikirim) {
            $this->sendTenggatWaktuToPenghuni($kontrak, 'terlambat');
            $this->sendTenggatWaktuToPemilik($kontrak, 'terlambat');
            $kontrak->update(['notif_terlambat_dikirim' => now()]);
        }
    }
}