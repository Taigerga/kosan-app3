<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\KontrakSewa;
use App\Models\Pembayaran; // TAMBAHKAN INI
use App\Services\NotificationService;
use App\Services\NotificationEmailService;
use Illuminate\Http\Request;


class KontrakController extends Controller
{
    protected $notificationService;
    protected $emailService; // TAMBAHKAN INI

    public function __construct(
        NotificationService $notificationService,
        NotificationEmailService $emailService // TAMBAHKAN INI
    ) {
        $this->notificationService = $notificationService;
        $this->emailService = $emailService; // TAMBAHKAN INI
    }

    /**
     * Tampilkan semua kontrak (dengan tabs)
     */
    public function index()
    {
        $idPemilik = auth('pemilik')->id();

        // Ambil semua kos milik pemilik ini
        $kosIds = \App\Models\Kos::where('id_pemilik', $idPemilik)
            ->pluck('id_kos')
            ->toArray();

        // Hitung total keseluruhan untuk statistik
        $kontrakPendingCount = KontrakSewa::whereIn('id_kos', $kosIds)->where('status_kontrak', 'pending')->count();
        $kontrakAktifCount = KontrakSewa::whereIn('id_kos', $kosIds)->where('status_kontrak', 'aktif')->count();
        $kontrakSelesaiCount = KontrakSewa::whereIn('id_kos', $kosIds)->where('status_kontrak', 'selesai')->count();
        $kontrakDitolakCount = KontrakSewa::whereIn('id_kos', $kosIds)->where('status_kontrak', 'ditolak')->count();

        // Kontrak Pending
        $kontrakPending = KontrakSewa::with(['penghuni', 'kos', 'kamar'])
            ->whereIn('id_kos', $kosIds)
            ->where('status_kontrak', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Kontrak Aktif
        $kontrakAktif = KontrakSewa::with(['penghuni', 'kos', 'kamar'])
            ->whereIn('id_kos', $kosIds)
            ->where('status_kontrak', 'aktif')
            ->orderBy('tanggal_selesai', 'asc')
            ->paginate(10);

        // Kontrak Selesai/Ditolak
        $kontrakSelesai = KontrakSewa::whereIn('id_kos', $kosIds)
            ->where('status_kontrak', 'selesai')
            ->with(['penghuni', 'kos', 'kamar'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        // HANYA status 'ditolak' saja
        $kontrakDitolak = KontrakSewa::whereIn('id_kos', $kosIds)
            ->where('status_kontrak', 'ditolak')
            ->with(['penghuni', 'kos', 'kamar'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pemilik.kontrak.index', compact(
            'kontrakPending',
            'kontrakAktif',
            'kontrakSelesai',
            'kontrakDitolak',
            'kontrakPendingCount',
            'kontrakAktifCount',
            'kontrakSelesaiCount',
            'kontrakDitolakCount'
        ));


    }

    /**
     * Menyetujui kontrak dan kirim notifikasi
     */
    public function approve($idKontrak)
    {
        try {
            $kontrak = KontrakSewa::with(['penghuni', 'kos'])->findOrFail($idKontrak);

            // Verifikasi pemilik
            if ($kontrak->kos->id_pemilik != auth('pemilik')->id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
            }

            // Update status kontrak menjadi aktif tapi tanggal masih kosong
            $kontrak->update([
                'status_kontrak' => 'aktif',
                // Tanggal mulai dan selesai akan diisi saat pembayaran pertama kali dibuat
            ]);

            // Update status penghuni menjadi aktif
            $kontrak->penghuni->update(['status_penghuni' => 'aktif']);

            // Tidak membuat atau mengubah data pembayaran apapun di sini.

            // Update status kamar jadi terisi
            $kontrak->kamar->update(['status_kamar' => 'terisi']);

            // Kirim notifikasi WhatsApp
            $this->notificationService->sendPersetujuanDiterima($idKontrak);
            $this->notificationService->sendPersetujuanDiberikan($idKontrak);

            // Kirim EMAIL notifikasi ke penghuni
            $this->emailService->sendKontrakDiterima($kontrak);

            // Kirim EMAIL ke pemilik sendiri (opsional)
            $this->emailService->sendTenggatWaktuToPemilik($kontrak, 'aktif_baru');

            return redirect()->route('pemilik.kontrak.index')
                ->with('success', 'Kontrak disetujui. Notifikasi WhatsApp dan Email dikirim ke penghuni.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Menolak kontrak dan kirim notifikasi
     */

    public function reject(Request $request, $idKontrak)
    {
        try {
            $kontrak = KontrakSewa::with(['penghuni', 'kos'])->findOrFail($idKontrak);

            // Verifikasi pemilik
            if ($kontrak->kos->id_pemilik != auth('pemilik')->id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
            }

            $kontrak->update([
                'status_kontrak' => 'ditolak',
                'alasan_ditolak' => $request->alasan_ditolak
            ]);

            // ===== HAPUS RECORD PEMBAYARAN DEPOSIT =====
            $pembayaranDeposit = Pembayaran::where('id_kontrak', $kontrak->id_kontrak)
                ->where('status_pembayaran', 'pending')
                ->where(function ($query) {
                    $query->where('keterangan', 'like', '%Deposit%')
                        ->orWhere('keterangan', 'like', '%Uang Muka%');
                })
                ->first();

            if ($pembayaranDeposit) {
                $pembayaranDeposit->delete();
            }
            // =========================================

            // Kembalikan status kamar jadi tersedia
            $kontrak->kamar->update(['status_kamar' => 'tersedia']);

            // Kirim notifikasi penolakan WhatsApp
            $this->notificationService->sendPersetujuanDitolak($idKontrak);

            // Kirim EMAIL notifikasi ke penghuni
            $this->emailService->sendKontrakDitolak($kontrak);

            return redirect()->route('pemilik.kontrak.index')
                ->with('success', 'Kontrak ditolak. Notifikasi WhatsApp dan Email dikirim ke calon penghuni.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail kontrak
     */
    public function show($idKontrak)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kos', 'kamar'])->findOrFail($idKontrak);

        // Verifikasi pemilik
        if ($kontrak->kos->id_pemilik != auth('pemilik')->id()) {
            abort(403, 'Anda tidak memiliki akses!');
        }

        return view('pemilik.kontrak.show', compact('kontrak'));
    }

    /**
     * Menandai kontrak sebagai selesai
     */
    public function selesai($idKontrak)
    {
        try {
            $kontrak = KontrakSewa::with(['penghuni', 'kos'])->findOrFail($idKontrak);

            // Verifikasi pemilik
            if ($kontrak->kos->id_pemilik != auth('pemilik')->id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
            }

            $kontrak->update([
                'status_kontrak' => 'selesai'
            ]);

            // Cek apakah masih ada kontrak aktif lainnya
            $masihAdaKontrakAktif = KontrakSewa::where('id_penghuni', $kontrak->id_penghuni)
                ->where('status_kontrak', 'aktif')
                ->where('id_kontrak', '!=', $idKontrak)
                ->exists();

            if (!$masihAdaKontrakAktif) {
                $kontrak->penghuni->update(['status_penghuni' => 'nonaktif']);
            }

            // Kembalikan status kamar jadi tersedia
            $kontrak->kamar->update(['status_kamar' => 'tersedia']);

            // TAMBAHKAN: Kirim EMAIL notifikasi ke penghuni tentang kontrak selesai
            $this->emailService->sendTenggatWaktuToPenghuni($kontrak, 'selesai');
            $this->emailService->sendTenggatWaktuToPemilik($kontrak, 'selesai');

            return redirect()->route('pemilik.kontrak.index')
                ->with('success', 'Kontrak telah ditandai sebagai selesai. Kamar kini tersedia. Email notifikasi telah dikirim.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menandai kontrak selesai: ' . $e->getMessage());
        }
    }

    /**
     * TAMBAHKAN: Metode untuk testing email
     */
    public function testEmail($idKontrak, $type)
    {
        try {
            $kontrak = KontrakSewa::with(['penghuni', 'kos'])->findOrFail($idKontrak);

            if ($kontrak->kos->id_pemilik != auth('pemilik')->id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
            }

            switch ($type) {
                case 'diterima':
                    $this->emailService->sendKontrakDiterima($kontrak);
                    $message = 'Email kontrak diterima berhasil dikirim ke ' . $kontrak->penghuni->email;
                    break;
                case 'ditolak':
                    $this->emailService->sendKontrakDitolak($kontrak);
                    $message = 'Email kontrak ditolak berhasil dikirim ke ' . $kontrak->penghuni->email;
                    break;
                case 'tenggat_7hari':
                    $this->emailService->sendTenggatWaktuToPenghuni($kontrak, '7_hari');
                    $this->emailService->sendTenggatWaktuToPemilik($kontrak, '7_hari');
                    $message = 'Email notifikasi 7 hari berhasil dikirim ke penghuni dan pemilik';
                    break;
                case 'tenggat_hariini':
                    $this->emailService->sendTenggatWaktuToPenghuni($kontrak, 'tenggat');
                    $this->emailService->sendTenggatWaktuToPemilik($kontrak, 'tenggat');
                    $message = 'Email notifikasi hari ini berhasil dikirim ke penghuni dan pemilik';
                    break;
                default:
                    return redirect()->back()->with('error', 'Tipe email tidak valid');
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $kontrak = KontrakSewa::findOrFail($id);
        $pemilikId = auth()->guard('pemilik')->user()->id_pemilik;

        // Pastikan kontrak milik pemilik yang login
        if ($kontrak->kos->id_pemilik != $pemilikId) {
            return redirect()->route('pemilik.kontrak.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus kontrak ini.');
        }

        // Hanya boleh menghapus kontrak yang sudah selesai atau ditolak
        if (!in_array($kontrak->status_kontrak, ['selesai', 'ditolak'])) {
            return redirect()->route('pemilik.kontrak.index')
                ->with('error', 'Kontrak aktif tidak dapat dihapus.');
        }

        $namaPenghuni = $kontrak->penghuni->nama ?? 'Kontrak';
        $kontrak->delete();

        return redirect()->route('pemilik.kontrak.index')
            ->with('success', "Riwayat kontrak $namaPenghuni berhasil dihapus.");
    }

}