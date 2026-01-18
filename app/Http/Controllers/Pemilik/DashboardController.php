<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kos;
use App\Models\Kamar;
use App\Models\KontrakSewa;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('pemilik')->user();
        
        // Data untuk stats
        $statistics = [
            'total_kos' => Kos::where('id_pemilik', $user->id_pemilik)->count(),
            'total_kamar' => Kamar::whereHas('kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })->count(),
            'kamar_tersedia' => Kamar::whereHas('kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })->where('status_kamar', 'tersedia')->count(),
            'total_penghuni' => KontrakSewa::whereHas('kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })->where('status_kontrak', 'aktif')->count(),
        ];

        // Data untuk tampilan
        $kos = Kos::withCount(['kamar' => function($query) {
                $query->where('status_kamar', 'tersedia');
            }])
            ->where('id_pemilik', $user->id_pemilik)
            ->orderBy('created_at', 'desc')
            ->get();

        $kamar = Kamar::with('kos')
            ->whereHas('kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Data kontrak pending
        $kontrakPending = KontrakSewa::with(['penghuni', 'kos', 'kamar'])
            ->whereHas('kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })
            ->where('status_kontrak', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Data pembayaran terbaru
        $pembayaranTerbaru = Pembayaran::with(['penghuni', 'kontrak.kos'])
            ->whereHas('kontrak.kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // PERBAIKAN: Pendapatan bulan ini termasuk deposit kontrak baru
        $pendapatanBulanIni = $this->hitungPendapatanBulanIni($user->id_pemilik);

        return view('pemilik.dashboard', compact(
            'user', 
            'statistics', 
            'kos', 
            'kamar', 
            'kontrakPending', 
            'pembayaranTerbaru', 
            'pendapatanBulanIni'
        ));
    }

    /**
     * Hitung pendapatan bulan ini termasuk deposit dari kontrak baru
     */
    private function hitungPendapatanBulanIni($pemilikId)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Hanya menghitung pembayaran yang sudah lunas di bulan ini
        $pendapatanPembayaran = Pembayaran::whereHas('kontrak.kos', function($query) use ($pemilikId) {
                $query->where('id_pemilik', $pemilikId);
            })
            ->where('status_pembayaran', 'lunas')
            ->where('tanggal_bayar', '>=', $startOfMonth)
            ->where('tanggal_bayar', '<=', $endOfMonth)
            ->sum('jumlah');

        return $pendapatanPembayaran;
    }
}