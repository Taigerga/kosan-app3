<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\KontrakSewa;
use App\Models\Penghuni;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemilikDashboardController extends Controller
{
    public function index()
    {
        $pemilik = Auth::user();
        
        // Total kos
        $totalKos = Kos::where('id_pemilik', $pemilik->id_pemilik)->count();
        
        // Total kamar
        $totalKamar = Kamar::whereHas('kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })->count();
        
        // Kamar tersedia
        $kamarTersedia = Kamar::whereHas('kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })->where('status_kamar', 'tersedia')->count();
        
        // Total penghuni aktif
        $totalPenghuni = KontrakSewa::whereHas('kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })->where('status_kontrak', 'aktif')->count();
        
        // Pendapatan bulan ini
        $pendapatanBulanIni = Pembayaran::whereHas('kontrak.kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })
        ->where('status_pembayaran', 'lunas')
        ->where('bulan_tahun', date('Y-m'))
        ->sum('jumlah');
        
        // Pembayaran pending
        $pembayaranPending = Pembayaran::whereHas('kontrak.kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })
        ->where('status_pembayaran', 'pending')
        ->count();
        
        // Kontrak pending
        $kontrakPending = KontrakSewa::whereHas('kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })
        ->where('status_kontrak', 'pending')
        ->count();
        
        // Chart data - Pendapatan 6 bulan terakhir
        $pendapatanChart = Pembayaran::whereHas('kontrak.kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })
        ->where('status_pembayaran', 'lunas')
        ->where('bulan_tahun', '>=', now()->subMonths(5)->format('Y-m'))
        ->select('bulan_tahun', DB::raw('SUM(jumlah) as total'))
        ->groupBy('bulan_tahun')
        ->orderBy('bulan_tahun')
        ->get();
        
        // Kos dengan penghuni terbanyak
        $kosTerpopuler = Kos::where('id_pemilik', $pemilik->id_pemilik)
            ->withCount(['kontrak as penghuni_count' => function($query) {
                $query->where('status_kontrak', 'aktif');
            }])
            ->orderBy('penghuni_count', 'desc')
            ->limit(5)
            ->get();
        
        // Pembayaran terbaru yang pending
        $pembayaranTerbaru = Pembayaran::with(['penghuni', 'kontrak.kos'])
            ->whereHas('kontrak.kos', function($query) use ($pemilik) {
                $query->where('id_pemilik', $pemilik->id_pemilik);
            })
            ->where('status_pembayaran', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Notifikasi terbaru
        $notifikasi = Notification::where('id_user', $pemilik->id_pemilik)
            ->where('user_type', 'pemilik')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_kos' => $totalKos,
                    'total_kamar' => $totalKamar,
                    'kamar_tersedia' => $kamarTersedia,
                    'total_penghuni' => $totalPenghuni,
                    'pendapatan_bulan_ini' => (float) $pendapatanBulanIni,
                    'pembayaran_pending' => $pembayaranPending,
                    'kontrak_pending' => $kontrakPending,
                ],
                'chart_data' => $pendapatanChart,
                'kos_terpopuler' => $kosTerpopuler,
                'pembayaran_terbaru' => $pembayaranTerbaru,
                'notifikasi_terbaru' => $notifikasi
            ]
        ]);
    }

    public function getKosStats()
    {
        $pemilik = Auth::user();
        
        $stats = Kos::where('id_pemilik', $pemilik->id_pemilik)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status_kos = "aktif" THEN 1 ELSE 0 END) as aktif,
                SUM(CASE WHEN status_kos = "nonaktif" THEN 1 ELSE 0 END) as nonaktif,
                SUM(CASE WHEN status_kos = "pending" THEN 1 ELSE 0 END) as pending
            ')
            ->first();
            
        $kamarStats = Kamar::whereHas('kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })
        ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status_kamar = "tersedia" THEN 1 ELSE 0 END) as tersedia,
            SUM(CASE WHEN status_kamar = "terisi" THEN 1 ELSE 0 END) as terisi,
            SUM(CASE WHEN status_kamar = "maintenance" THEN 1 ELSE 0 END) as maintenance
        ')
        ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'kos' => $stats,
                'kamar' => $kamarStats
            ]
        ]);
    }

    public function getPendapatanTahunan($tahun = null)
    {
        $pemilik = Auth::user();
        $tahun = $tahun ?? date('Y');
        
        $pendapatan = Pembayaran::whereHas('kontrak.kos', function($query) use ($pemilik) {
            $query->where('id_pemilik', $pemilik->id_pemilik);
        })
        ->where('status_pembayaran', 'lunas')
        ->where('bulan_tahun', 'like', $tahun . '-%')
        ->selectRaw('MONTH(STR_TO_DATE(CONCAT(bulan_tahun, "-01"), "%Y-%m-%d")) as bulan, SUM(jumlah) as total')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();
        
        // Format data untuk chart
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $pendapatanData = array_fill(0, 12, 0);
        
        foreach ($pendapatan as $item) {
            $pendapatanData[$item->bulan - 1] = (float) $item->total;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $bulanLabels,
                'pendapatan' => $pendapatanData,
                'tahun' => $tahun,
                'total_tahunan' => array_sum($pendapatanData)
            ]
        ]);
    }

    public function getAktivitasTerbaru()
    {
        $pemilik = Auth::user();
        
        // Gabungkan berbagai aktivitas
        $pembayaran = Pembayaran::with(['penghuni', 'kontrak.kos'])
            ->whereHas('kontrak.kos', function($query) use ($pemilik) {
                $query->where('id_pemilik', $pemilik->id_pemilik);
            })
            ->select('id_pembayaran', 'id_penghuni', 'bulan_tahun', 'jumlah', 'status_pembayaran', 'created_at', DB::raw("'pembayaran' as tipe"))
            ->orderBy('created_at', 'desc')
            ->limit(10);
            
        $kontrak = KontrakSewa::with(['penghuni', 'kos'])
            ->whereHas('kos', function($query) use ($pemilik) {
                $query->where('id_pemilik', $pemilik->id_pemilik);
            })
            ->select('id_kontrak', 'id_penghuni', 'status_kontrak', 'created_at', DB::raw("'kontrak' as tipe"))
            ->orderBy('created_at', 'desc')
            ->limit(10);
            
        $aktivitas = $pembayaran->union($kontrak)
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $aktivitas
        ]);
    }
}