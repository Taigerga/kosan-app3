<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KontrakSewa;
use App\Models\Pembayaran;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Manual check auth
        if (!Auth::guard('penghuni')->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai penghuni.');
        }

        $user = Auth::guard('penghuni')->user();

        $kontrakAktif = KontrakSewa::with(['kos', 'kamar'])
            ->where('id_penghuni', $user->id_penghuni)
            ->where('status_kontrak', 'aktif')
            ->get();

        // Tambahkan data sisa waktu untuk setiap kontrak
        $kontrakAktif->each(function ($kontrak) {
            $sekarang = Carbon::now();

            // Handle null tanggal_selesai dan tanggal_mulai
            if (!$kontrak->tanggal_selesai || !$kontrak->tanggal_mulai) {
                // Kontrak baru disetujui tapi belum ada pembayaran
                $kontrak->sisaHari = null;
                $kontrak->totalHari = null;
                $kontrak->persentaseAkhir = null;
                $kontrak->statusWarna = 'gray'; // Warna khusus untuk kontrak tanpa periode
                $kontrak->sudahBerakhir = false;
                $kontrak->statusText = 'Menunggu pembayaran pertama';
                return; // Skip ke next kontrak
            }

            $selesai = Carbon::parse($kontrak->tanggal_selesai);
            $mulai = Carbon::parse($kontrak->tanggal_mulai);

            // Hitung sisa hari (convert ke integer)
            $sisaHari = (int) floor($sekarang->diffInDays($selesai, false));
            $totalHari = (int) floor($mulai->diffInDays($selesai));

            // Hitung persentase waktu tersisa
            $persentaseAkhir = $totalHari > 0 ? ($sisaHari / $totalHari) * 100 : 0;

            // Tentukan status warna
            if ($persentaseAkhir > 50) {
                $statusWarna = 'green'; // Waktu masih banyak
            } elseif ($persentaseAkhir > 20) {
                $statusWarna = 'yellow'; // Waktu mulai berkurang
            } else {
                $statusWarna = 'red'; // Waktu hampir habis
            }

            $kontrak->sisaHari = max($sisaHari, 0);
            $kontrak->totalHari = $totalHari;
            $kontrak->persentaseAkhir = max($persentaseAkhir, 0);
            $kontrak->statusWarna = $statusWarna;
            $kontrak->sudahBerakhir = $sisaHari < 0;
            $kontrak->statusText = $sisaHari < 0 ? 'Kontrak telah berakhir' : 'Kontrak aktif';
        });

        $pembayaranTerakhir = Pembayaran::with(['kontrak.kos'])
            ->where('id_penghuni', $user->id_penghuni)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // PERBAIKAN: Hitung total pembayaran termasuk deposit kontrak
        $totalPembayaran = $this->hitungTotalPembayaran($user->id_penghuni);

        return view('penghuni.dashboard', compact(
            'user',
            'kontrakAktif',
            'pembayaranTerakhir',
            'totalPembayaran'
        ));
    }

    /**
     * Hitung total pembayaran termasuk deposit dari kontrak
     */
    private function hitungTotalPembayaran($penghuniId)
    {
        // Hanya hitung pembayaran yang sudah berstatus 'lunas'
        return Pembayaran::where('id_penghuni', $penghuniId)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah');
    }
}