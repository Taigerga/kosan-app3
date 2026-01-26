<?php

namespace App\Services;

use App\Models\KontrakSewa;
use App\Models\Penghuni;
use App\Models\Pemilik;
use App\Models\Kos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;
use App\Mail\Penghuni\MenungguPersetujuanMail;
use App\Mail\Penghuni\KontrakDiterimaMail;
use App\Mail\Penghuni\KontrakDitolakMail as PenghuniKontrakDitolakMail;
use App\Mail\Pemilik\PengajuanBaruMail;
use App\Mail\Pemilik\KontrakDisetujuiMail;
use App\Mail\Pemilik\KontrakDitolakMail as PemilikKontrakDitolakMail;

class NotificationService
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Helper untuk format tanggal yang aman (handle null)
     */
    private function formatTanggalSafely($tanggal, $fallbackText = 'Belum ditentukan')
    {
        return $tanggal ? $tanggal->format('d F Y') : $fallbackText;
    }

    // ==================== NOTIFIKASI AWAL PENGAJUAN ====================

    public function sendMenungguPersetujuan($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak || $kontrak->notif_menunggu_dikirim) {
            return false;
        }

        // Send Email
        try {
            Mail::to($kontrak->penghuni->email)->send(new MenungguPersetujuanMail($kontrak));
        } catch (\Exception $e) {
            Log::error("Failed to send email MenungguPersetujuan: " . $e->getMessage());
        }

        $message = "⏳ *STATUS PENGAJUAN: MENUNGGU PERSETUJUAN*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "Permohonan Anda untuk ngekos di *{$kontrak->kos->nama_kos}* sedang menunggu persetujuan dari pemilik.\n\n"
            . "📅 Tanggal Daftar: " . $kontrak->created_at->format('d F Y') . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n\n"
            . "Mohon kesediaannya menunggu konfirmasi selanjutnya. Terima kasih.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        $success = $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);

        if ($success) {
            $kontrak->update(['notif_menunggu_dikirim' => now()]);
        }

        return $success;
    }

    public function sendPersetujuanDiterima($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || $kontrak->notif_disetujui_dikirim) {
            return false;
        }

        // Send Email
        try {
            Mail::to($kontrak->penghuni->email)->send(new KontrakDiterimaMail($kontrak));
        } catch (\Exception $e) {
            Log::error("Failed to send email KontrakDiterima: " . $e->getMessage());
        }

        // Handle null tanggal_mulai (baru disetujui tapi belum ada pembayaran)
        $tanggalMulaiText = $kontrak->tanggal_mulai ? $kontrak->tanggal_mulai->format('d F Y') : 'Menunggu pembayaran pertama';
        
        $message = "✅ *SELAMAT! PERMOHONAN DISETUJUI*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "Kabar gembira! Permohonan ngekos Anda di *{$kontrak->kos->nama_kos}* telah **DISETUJUI** oleh pemilik.\n\n"
            . "📅 Mulai Sewa: {$tanggalMulaiText}\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "💰 Biaya: Rp " . number_format($kontrak->harga_sewa, 0, ',', '.') . "\n\n"
            . ($kontrak->tanggal_mulai ? "Silakan cek email Anda untuk detail kontrak sewa.\n\n" : "Silakan lakukan pembayaran pertama untuk mengaktifkan masa sewa Anda.\n\n")
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        $success = $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);

        if ($success) {
            $kontrak->update(['notif_disetujui_dikirim' => now()]);
        }

        return $success;
    }

    public function sendPersetujuanDitolak($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'ditolak' || $kontrak->notif_tolak_dikirim) {
            return false;
        }

        // Send Email
        try {
            Mail::to($kontrak->penghuni->email)->send(new PenghuniKontrakDitolakMail($kontrak));
        } catch (\Exception $e) {
            Log::error("Failed to send email KontrakDitolak: " . $e->getMessage());
        }

        $message = "❌ *STATUS PENGAJUAN: DITOLAK*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "Mohon maaf, permohonan ngekos Anda di *{$kontrak->kos->nama_kos}* belum disetujui oleh pemilik saat ini.\n\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n\n"
            . "Jangan berkecil hati, Anda bisa mencari pilihan kamar kos lainnya di aplikasi kami.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        $success = $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);

        if ($success) {
            $kontrak->update(['notif_tolak_dikirim' => now()]);
        }

        return $success;
    }

    public function sendPengajuanBaru($kontrakId)
    {
        // Start bot MANUAL hanya saat pertama kali
        if (!$this->whatsapp->isBotStarted()) {
            $this->whatsapp->startBot();
            sleep(3); // Tunggu bot initialize
        }

        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || !$kontrak->kos->pemilik) {
            return false;
        }

        // Send Email
        try {
            Mail::to($kontrak->kos->pemilik->email)->send(new PengajuanBaruMail($kontrak));
        } catch (\Exception $e) {
            Log::error("Failed to send email PengajuanBaru: " . $e->getMessage());
        }

        $message = "🆕 *PENGAJUAN SEWA BARU*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "Ada calon penghuni baru yang ingin ngekos di properti Anda.\n\n"
            . "👤 Nama: {$kontrak->penghuni->nama}\n"
            . "🏠 Kos: {$kontrak->kos->nama_kos}\n"
            . "🛏 Kamar: {$kontrak->kamar->nomor_kamar}\n\n"
            . "Mohon segera login ke aplikasi untuk melihat detail dan melakukan konfirmasi.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    public function sendPersetujuanDiberikan($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || !$kontrak->kos->pemilik) {
            return false;
        }

        // Send Email
        try {
            Mail::to($kontrak->kos->pemilik->email)->send(new KontrakDisetujuiMail($kontrak));
        } catch (\Exception $e) {
            Log::error("Failed to send email KontrakDisetujui: " . $e->getMessage());
        }

        $message = "✅ *KONFIRMASI: PERSETUJUAN DIBERIKAN*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "Anda telah berhasil **MENYETUJUI** permohonan sewa dari:\n\n"
            . "👤 Penghuni: {$kontrak->penghuni->nama}\n"
            . "🏠 Properti: {$kontrak->kos->nama_kos}\n\n"
            . "Sistem telah mengirimkan notifikasi kepada penghuni perihal persetujuan ini.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    public function sendPersetujuanDitolakPemilik($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || !$kontrak->kos->pemilik) {
            return false;
        }

        // Send Email
        try {
            Mail::to($kontrak->kos->pemilik->email)->send(new PemilikKontrakDitolakMail($kontrak));
        } catch (\Exception $e) {
            Log::error("Failed to send email KontrakDitolakPemilik: " . $e->getMessage());
        }

        $message = "❌ *KONFIRMASI: PERSETUJUAN DITOLAK*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "Anda telah **MENOLAK** permohonan sewa dari:\n\n"
            . "👤 Penghuni: {$kontrak->penghuni->nama}\n"
            . "🏠 Properti: {$kontrak->kos->nama_kos}\n\n"
            . "Sistem telah mengirimkan notifikasi kepada penghuni perihal penolakan ini.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    // ==================== PENGINGAT MASA KONTRAK ====================

    /**
     * Pengingat 7 hari sebelum kontrak habis (update dari 5 hari)
     */
    public function sendPengingat7Hari($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || $kontrak->notif_7hari_dikirim) {
            return false;
        }

        // Skip jika tanggal_selesai null
        if (!$kontrak->tanggal_selesai) {
            return false;
        }

        $sisaHari = (int) ceil(Carbon::now()->diffInDays($kontrak->tanggal_selesai));
        $message = "⏰ *Pengingat Kontrak Kos*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "Masa kontrak Anda di *{$kontrak->kos->nama_kos}* akan berakhir dalam *{$sisaHari} hari*.\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n\n"
            . "Silakan hubungi pemilik kos untuk perpanjangan atau persiapan lainnya.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        $success = $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);

        if ($success) {
            $kontrak->update(['notif_7hari_dikirim' => now()]);
        }

        return $success;
    }

    /**
     * Pengingat 3 hari sebelum kontrak habis
     */
    public function sendPengingat3Hari($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || $kontrak->notif_3hari_dikirim) {
            return false;
        }

        // Skip jika tanggal_selesai null
        if (!$kontrak->tanggal_selesai) {
            return false;
        }

        $sisaHari = (int) ceil(Carbon::now()->diffInDays($kontrak->tanggal_selesai));
        $message = "⚠️ *Pengingat Penting Kontrak Kos*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "Masa kontrak Anda di *{$kontrak->kos->nama_kos}* akan berakhir dalam *{$sisaHari} hari*.\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "💰 Biaya sewa: Rp " . number_format($kontrak->harga_sewa, 0, ',', '.') . "\n\n"
            . "*Segera lakukan perpanjangan atau persiapan pindah!*\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        $success = $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);

        if ($success) {
            $kontrak->update(['notif_3hari_dikirim' => now()]);
        }

        return $success;
    }

    /**
     * Pengingat H-1 (besok habis) - update dari hari terakhir
     */
    public function sendPengingatH1($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai || $kontrak->notif_h1_dikirim) {
            return false;
        }

        $message = "🚨 *PENGINGAT TERAKHIR KONTRAK KOS*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "*BESOK* masa kontrak Anda di *{$kontrak->kos->nama_kos}* akan BERAKHIR!\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "💰 Biaya sewa: Rp " . number_format($kontrak->harga_sewa, 0, ',', '.') . "\n\n"
            . "*HUBUNGI PEMILIK KOS SEGERA!*\n"
            . "Untuk perpanjangan atau koordinasi pengembalian kamar.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        $success = $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);

        if ($success) {
            $kontrak->update(['notif_h1_dikirim' => now()]);
        }

        return $success;
    }

    /**
     * Pengingat hari ini habis
     */
    public function sendPengingatHariIni($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai || $kontrak->notif_hari_ini_dikirim) {
            return false;
        }

        $message = "⏳ *HARI INI KONTRAK BERAKHIR*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "Hari ini adalah *HARI TERAKHIR* kontrak Anda di *{$kontrak->kos->nama_kos}*.\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n\n"
            . "*TINDAKAN YANG HARUS DILAKUKAN:*\n"
            . "1. Lakukan pembayaran terakhir jika belum\n"
            . "2. Koordinasi dengan pemilik untuk check-out\n"
            . "3. Kosongkan kamar sebelum tengah malam\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        $success = $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);

        if ($success) {
            $kontrak->update(['notif_hari_ini_dikirim' => now()]);
        }

        return $success;
    }

    /**
     * Notifikasi keterlambatan (sudah lewat)
     */
    public function sendNotifikasiTerlambat($kontrakId, $hariTerlambat)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai || $kontrak->notif_terlambat_dikirim) {
            return false;
        }

        $message = "❌ *KONTRAK SUDAH BERAKHIR*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "Masa kontrak Anda di *{$kontrak->kos->nama_kos}* sudah BERAKHIR sejak *{$hariTerlambat} hari* yang lalu.\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n\n"
            . "*STATUS SAAT INI: KONTRAK TIDAK AKTIF*\n\n"
            . "Silakan hubungi pemilik kos untuk:\n"
            . "1. Penyelesaian administrasi\n"
            . "2. Pengembalian deposit\n"
            . "3. Pengambilan barang jika masih ada\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        $success = $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);

        if ($success) {
            $kontrak->update(['notif_terlambat_dikirim' => now()]);
        }

        return $success;
    }

    // ==================== PENGINGAT UNTUK PEMILIK ====================

    /**
     * Pengingat 7 hari untuk pemilik
     */
    public function sendPengingat7HariPemilik($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai || !$kontrak->kos->pemilik) {
            return false;
        }

        $sisaHari = (int) ceil(Carbon::now()->diffInDays($kontrak->tanggal_selesai));
        $message = "📋 *Pengingat Kontrak Penghuni*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "Kontrak penghuni *{$kontrak->penghuni->nama}* di *{$kontrak->kos->nama_kos}* akan berakhir dalam *{$sisaHari} hari*.\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "👤 Penghuni: {$kontrak->penghuni->nama} ({$kontrak->penghuni->no_hp})\n\n"
            . "Silakan koordinasi dengan penghuni untuk perpanjangan atau persiapan check-out.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    /**
     * Pengingat 3 hari untuk pemilik
     */
    public function sendPengingat3HariPemilik($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai || !$kontrak->kos->pemilik) {
            return false;
        }

        $sisaHari = (int) ceil(Carbon::now()->diffInDays($kontrak->tanggal_selesai));
        $message = "⚠️ *Pengingat Penting Kontrak Penghuni*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "Kontrak penghuni *{$kontrak->penghuni->nama}* di *{$kontrak->kos->nama_kos}* akan berakhir dalam *{$sisaHari} hari*.\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "👤 Penghuni: {$kontrak->penghuni->nama} ({$kontrak->penghuni->no_hp})\n"
            . "💰 Biaya sewa: Rp " . number_format($kontrak->harga_sewa, 0, ',', '.') . "\n\n"
            . "*Segera konfirmasi dengan penghuni!*\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    /**
     * Pengingat H-1 untuk pemilik
     */
    public function sendPengingatH1Pemilik($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai || !$kontrak->kos->pemilik) {
            return false;
        }

        $message = "🚨 *PENGINGAT TERAKHIR KONTRAK PENGHUNI*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "*BESOK* kontrak penghuni *{$kontrak->penghuni->nama}* di *{$kontrak->kos->nama_kos}* akan BERAKHIR!\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "👤 Penghuni: {$kontrak->penghuni->nama} ({$kontrak->penghuni->no_hp})\n"
            . "💰 Biaya sewa: Rp " . number_format($kontrak->harga_sewa, 0, ',', '.') . "\n\n"
            . "*HUBUNGI PENGHUNI SEGERA!*\n"
            . "Untuk konfirmasi perpanjangan atau persiapan check-out.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    /**
     * Pengingat hari ini untuk pemilik
     */
    public function sendPengingatHariIniPemilik($kontrakId)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai || !$kontrak->kos->pemilik) {
            return false;
        }

        $message = "⏳ *HARI INI KONTRAK PENGHUNI BERAKHIR*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "Hari ini adalah *HARI TERAKHIR* kontrak penghuni *{$kontrak->penghuni->nama}* di *{$kontrak->kos->nama_kos}*.\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "👤 Penghuni: {$kontrak->penghuni->nama} ({$kontrak->penghuni->no_hp})\n\n"
            . "*TINDAKAN YANG HARUS DILAKUKAN:*\n"
            . "1. Verifikasi pembayaran terakhir\n"
            . "2. Persiapan check-out dan inspeksi kamar\n"
            . "3. Pengembalian deposit (jika ada)\n"
            . "4. Update status kamar menjadi 'tersedia'\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    /**
     * Notifikasi keterlambatan untuk pemilik
     */
    public function sendNotifikasiTerlambatPemilik($kontrakId, $hariTerlambat)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai || !$kontrak->kos->pemilik) {
            return false;
        }

        $message = "❌ *KONTRAK PENGHUNI SUDAH BERAKHIR*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "Kontrak penghuni *{$kontrak->penghuni->nama}* di *{$kontrak->kos->nama_kos}* sudah BERAKHIR sejak *{$hariTerlambat} hari* yang lalu.\n\n"
            . "📅 Tanggal berakhir: " . $this->formatTanggalSafely($kontrak->tanggal_selesai) . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "👤 Penghuni: {$kontrak->penghuni->nama} ({$kontrak->penghuni->no_hp})\n\n"
            . "*STATUS SAAT INI: KONTRAK TIDAK AKTIF*\n\n"
            . "Tindakan yang diperlukan:\n"
            . "1. Update status kontrak menjadi 'selesai'\n"
            . "2. Update status kamar menjadi 'tersedia'\n"
            . "3. Penyelesaian administrasi dengan penghuni\n"
            . "4. Pengembalian deposit (jika ada)\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    // ==================== NOTIFIKASI PERPANJANGAN ====================

    /**
     * Notifikasi permintaan perpanjangan dari penghuni
     */
    public function sendNotifikasiPermintaanPerpanjangan($kontrakId, $durasiTambahan)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos.pemilik'])->find($kontrakId);

        if (!$kontrak || !$kontrak->kos->pemilik) {
            return false;
        }

        $message = "🔄 *PERMINTAAN PERPANJANGAN KONTRAK*\n\n"
            . "Halo {$kontrak->kos->pemilik->nama},\n"
            . "Penghuni *{$kontrak->penghuni->nama}* mengajukan perpanjangan kontrak di *{$kontrak->kos->nama_kos}*.\n\n"
            . "📅 Durasi tambahan: {$durasiTambahan} bulan\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "👤 Penghuni: {$kontrak->penghuni->nama} ({$kontrak->penghuni->no_hp})\n\n"
            . "Silakan login ke sistem untuk meninjau dan menyetujui permintaan ini.\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->kos->pemilik->no_hp, $message);
    }

    /**
     * Notifikasi perpanjangan disetujui untuk penghuni
     */
    public function sendNotifikasiPerpanjanganDisetujui($kontrakId, $tanggalBaruSelesai)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])->find($kontrakId);

        if (!$kontrak) {
            return false;
        }

        $message = "✅ *PERPANJANGAN KONTRAK DISETUJUI*\n\n"
            . "Halo {$kontrak->penghuni->nama},\n"
            . "Permintaan perpanjangan kontrak Anda di *{$kontrak->kos->nama_kos}* telah *DISETUJUI* oleh pemilik.\n\n"
            . "📅 Tanggal berakhir baru: " . $tanggalBaruSelesai->format('d F Y') . "\n"
            . "🏠 Kamar: {$kontrak->kamar->nomor_kamar}\n"
            . "💰 Biaya sewa: Rp " . number_format($kontrak->harga_sewa, 0, ',', '.') . "\n\n"
            . "Silakan lanjutkan pembayaran sesuai ketentuan. Terima kasih!\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem AyoKos_";

        return $this->whatsapp->sendMessage($kontrak->penghuni->no_hp, $message);
    }

    // ==================== TRIGGER METHODS ====================

    /**
     * Auto trigger ketika kontrak dibuat
     */
    public function triggerKontrakCreated($kontrakId)
    {
        $this->sendMenungguPersetujuan($kontrakId);
        $this->sendPengajuanBaru($kontrakId);
    }

    /**
     * Auto trigger ketika kontrak disetujui
     */
    public function triggerKontrakApproved($kontrakId)
    {
        $this->sendPersetujuanDiterima($kontrakId);
        $this->sendPersetujuanDiberikan($kontrakId);
    }

    /**
     * Auto trigger ketika kontrak ditolak
     */
    public function triggerKontrakRejected($kontrakId)
    {
        $this->sendPersetujuanDitolak($kontrakId);
        $this->sendPersetujuanDitolakPemilik($kontrakId);
    }

    /**
     * Auto trigger semua pengingat berdasarkan sisa hari
     */
    public function triggerAllReminders($kontrakId)
    {
        $kontrak = KontrakSewa::find($kontrakId);

        if (!$kontrak || $kontrak->status_kontrak !== 'aktif' || !$kontrak->tanggal_selesai) {
            return;
        }

        $sisaHari = (int) Carbon::now()->diffInDays($kontrak->tanggal_selesai);

        if ($sisaHari == 7) {
            $this->sendPengingat7Hari($kontrakId);
            $this->sendPengingat7HariPemilik($kontrakId);
        } elseif ($sisaHari == 3) {
            $this->sendPengingat3Hari($kontrakId);
            $this->sendPengingat3HariPemilik($kontrakId);
        } elseif ($sisaHari == 1) {
            $this->sendPengingatH1($kontrakId);
            $this->sendPengingatH1Pemilik($kontrakId);
        } elseif ($sisaHari == 0) {
            $this->sendPengingatHariIni($kontrakId);
            $this->sendPengingatHariIniPemilik($kontrakId);
        } elseif ($sisaHari < 0) {
            $hariTerlambat = abs($sisaHari);
            $this->sendNotifikasiTerlambat($kontrakId, $hariTerlambat);
            $this->sendNotifikasiTerlambatPemilik($kontrakId, $hariTerlambat);
        }
    }

    // ==================== COMPATIBILITY METHODS (untuk existing code) ====================

    /**
     * Untuk kompatibilitas dengan kode lama (5 hari)
     */
    public function sendPengingat5Hari($kontrakId)
    {
        // Panggil pengingat 7 hari sebagai ganti 5 hari
        return $this->sendPengingat7Hari($kontrakId);
    }

    public function sendPengingat5HariPemilik($kontrakId)
    {
        // Panggil pengingat 7 hari pemilik sebagai ganti 5 hari
        return $this->sendPengingat7HariPemilik($kontrakId);
    }

    /**
     * Untuk kompatibilitas dengan kode lama (hari terakhir)
     */
    public function sendPengingatHariTerakhir($kontrakId)
    {
        // Panggil pengingat H-1 sebagai ganti hari terakhir
        return $this->sendPengingatH1($kontrakId);
    }

    public function sendPengingatHariTerakhirPemilik($kontrakId)
    {
        // Panggil pengingat H-1 pemilik sebagai ganti hari terakhir
        return $this->sendPengingatH1Pemilik($kontrakId);
    }
}