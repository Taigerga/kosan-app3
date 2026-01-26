<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kos;
use App\Models\Kamar;
use App\Models\KontrakSewa;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use Illuminate\Support\Facades\DB;

class AnalisisController extends Controller
{
    public function index()
    {
        $pemilikId = auth()->guard('pemilik')->user()->id_pemilik;
        
        // 1. Data Pendapatan per Bulan (12 bulan terakhir)
        $pendapatanPerBulan = Pembayaran::selectRaw('
                DATE_FORMAT(tanggal_bayar, "%Y-%m") as bulan,
                SUM(jumlah) as total
            ')
            ->join('kontrak_sewa', 'pembayaran.id_kontrak', '=', 'kontrak_sewa.id_kontrak')
            ->join('kos', 'kontrak_sewa.id_kos', '=', 'kos.id_kos')
            ->where('kos.id_pemilik', $pemilikId)
            ->where('pembayaran.status_pembayaran', 'lunas')
            ->where('pembayaran.tanggal_bayar', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // 2. Data Status Kamar
        $statusKamar = Kamar::selectRaw('
                status_kamar,
                COUNT(*) as jumlah
            ')
            ->join('kos', 'kamar.id_kos', '=', 'kos.id_kos')
            ->where('kos.id_pemilik', $pemilikId)
            ->groupBy('status_kamar')
            ->get();

        // 3. Data Jenis Kos
        $jenisKos = Kos::selectRaw('
                jenis_kos,
                COUNT(*) as jumlah
            ')
            ->where('id_pemilik', $pemilikId)
            ->groupBy('jenis_kos')
            ->get();

        // 4. Data Kontrak per Status
        $statusKontrak = KontrakSewa::selectRaw('
                status_kontrak,
                COUNT(*) as jumlah
            ')
            ->join('kos', 'kontrak_sewa.id_kos', '=', 'kos.id_kos')
            ->where('kos.id_pemilik', $pemilikId)
            ->groupBy('status_kontrak')
            ->get();

        // 5. Data Penghuni Aktif per Kos
        $penghuniPerKos = Kos::selectRaw('
                kos.nama_kos,
                COUNT(kontrak_sewa.id_penghuni) as jumlah_penghuni
            ')
            ->leftJoin('kontrak_sewa', function($join) {
                $join->on('kos.id_kos', '=', 'kontrak_sewa.id_kos')
                     ->where('kontrak_sewa.status_kontrak', 'aktif');
            })
            ->where('kos.id_pemilik', $pemilikId)
            ->groupBy('kos.id_kos', 'kos.nama_kos')
            ->paginate(10);

        // Data lengkap untuk PDF
        $penghuniPerKosFull = Kos::selectRaw('
                kos.nama_kos,
                COUNT(kontrak_sewa.id_penghuni) as jumlah_penghuni
            ')
            ->leftJoin('kontrak_sewa', function($join) {
                $join->on('kos.id_kos', '=', 'kontrak_sewa.id_kos')
                     ->where('kontrak_sewa.status_kontrak', 'aktif');
            })
            ->where('kos.id_pemilik', $pemilikId)
            ->groupBy('kos.id_kos', 'kos.nama_kos')
            ->get();

        // 6. Data Tipe Kamar
        $tipeKamar = Kamar::selectRaw('
                tipe_kamar,
                COUNT(*) as jumlah
            ')
            ->join('kos', 'kamar.id_kos', '=', 'kos.id_kos')
            ->where('kos.id_pemilik', $pemilikId)
            ->groupBy('tipe_kamar')
            ->get();

        // 7. Data Review/Rating
        $reviewData = DB::table('reviews')
            ->selectRaw('
                rating,
                COUNT(*) as jumlah
            ')
            ->join('kos', 'reviews.id_kos', '=', 'kos.id_kos')
            ->where('kos.id_pemilik', $pemilikId)
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        // 8. Data Pendapatan per Kos
        $pendapatanPerKos = Kos::selectRaw('
                kos.nama_kos,
                COALESCE(SUM(pembayaran.jumlah), 0) as total_pendapatan
            ')
            ->leftJoin('kontrak_sewa', 'kos.id_kos', '=', 'kontrak_sewa.id_kos')
            ->leftJoin('pembayaran', function($join) {
                $join->on('kontrak_sewa.id_kontrak', '=', 'pembayaran.id_kontrak')
                     ->where('pembayaran.status_pembayaran', 'lunas')
                     ->whereYear('pembayaran.tanggal_bayar', date('Y'));
            })
            ->where('kos.id_pemilik', $pemilikId)
            ->groupBy('kos.id_kos', 'kos.nama_kos')
            ->orderBy('total_pendapatan', 'desc')
            ->paginate(10);

        // Data lengkap untuk PDF
        $pendapatanPerKosFull = Kos::selectRaw('
                kos.nama_kos,
                COALESCE(SUM(pembayaran.jumlah), 0) as total_pendapatan
            ')
            ->leftJoin('kontrak_sewa', 'kos.id_kos', '=', 'kontrak_sewa.id_kos')
            ->leftJoin('pembayaran', function($join) {
                $join->on('kontrak_sewa.id_kontrak', '=', 'pembayaran.id_kontrak')
                     ->where('pembayaran.status_pembayaran', 'lunas')
                     ->whereYear('pembayaran.tanggal_bayar', date('Y'));
            })
            ->where('kos.id_pemilik', $pemilikId)
            ->groupBy('kos.id_kos', 'kos.nama_kos')
            ->orderBy('total_pendapatan', 'desc')
            ->get();

        $pemilik = auth()->guard('pemilik')->user();
        return view('pemilik.analisis.index', compact(
            'pendapatanPerBulan',
            'statusKamar',
            'jenisKos',
            'statusKontrak',
            'penghuniPerKos',
            'penghuniPerKosFull',
            'tipeKamar',
            'reviewData',
            'pendapatanPerKos',
            'pendapatanPerKosFull',
            'pemilik'
        ));
    }
}