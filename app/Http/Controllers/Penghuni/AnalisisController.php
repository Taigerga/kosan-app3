<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KontrakSewa;
use App\Models\Pembayaran;
use App\Models\Review;
use App\Models\Kos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalisisController extends Controller
{
    public function index()
    {
        $penghuniId = auth()->guard('penghuni')->user()->id_penghuni;
        
        // 1. Data Riwayat Kontrak
        $riwayatKontrak = KontrakSewa::with(['kos', 'kamar'])
            ->where('id_penghuni', $penghuniId)
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Data Pembayaran per Bulan (6 bulan terakhir)
        $pembayaranPerBulan = Pembayaran::selectRaw('
                DATE_FORMAT(tanggal_bayar, "%Y-%m") as bulan,
                SUM(jumlah) as total,
                COUNT(*) as jumlah_transaksi
            ')
            ->join('kontrak_sewa', 'pembayaran.id_kontrak', '=', 'kontrak_sewa.id_kontrak')
            ->where('kontrak_sewa.id_penghuni', $penghuniId)
            ->where('pembayaran.status_pembayaran', 'lunas')
            ->where('pembayaran.tanggal_bayar', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // 3. Data Status Pembayaran
        $statusPembayaran = Pembayaran::selectRaw('
                status_pembayaran,
                COUNT(*) as jumlah,
                SUM(jumlah) as total_nominal
            ')
            ->join('kontrak_sewa', 'pembayaran.id_kontrak', '=', 'kontrak_sewa.id_kontrak')
            ->where('kontrak_sewa.id_penghuni', $penghuniId)
            ->groupBy('status_pembayaran')
            ->get();

        // 4. Data Durasi Tinggal
        $durasiTinggal = KontrakSewa::selectRaw('
                CASE 
                    WHEN durasi_sewa <= 3 THEN "Jangka Pendek (1-3 bulan)"
                    WHEN durasi_sewa <= 6 THEN "Jangka Menengah (4-6 bulan)"
                    WHEN durasi_sewa <= 12 THEN "Jangka Panjang (7-12 bulan)"
                    ELSE "Lebih dari 1 tahun"
                END as kategori_durasi,
                COUNT(*) as jumlah_kontrak,
                AVG(durasi_sewa) as rata_rata_durasi
            ')
            ->where('id_penghuni', $penghuniId)
            ->groupBy('kategori_durasi')
            ->orderByRaw('MIN(durasi_sewa)')
            ->get();

        // 5. Data Jenis Kos yang Disewa
        $jenisKosDisewa = Kos::selectRaw('
                kos.jenis_kos,
                COUNT(kontrak_sewa.id_kontrak) as jumlah_sewa,
                AVG(kontrak_sewa.harga_sewa) as rata_rata_harga
            ')
            ->join('kontrak_sewa', 'kos.id_kos', '=', 'kontrak_sewa.id_kos')
            ->where('kontrak_sewa.id_penghuni', $penghuniId)
            ->groupBy('kos.jenis_kos')
            ->get();

        // 6. Data Review yang Diberikan
        $reviewStats = Review::selectRaw('
                FLOOR(rating) as rating_bulat,
                COUNT(*) as jumlah
            ')
            ->where('id_penghuni', $penghuniId)
            ->groupBy('rating_bulat')
            ->orderBy('rating_bulat')
            ->get();

        // 7. Data Tipe Kamar yang Disewa
        $tipeKamarDisewa = DB::table('kontrak_sewa')
            ->selectRaw('
                kamar.tipe_kamar,
                COUNT(kontrak_sewa.id_kontrak) as jumlah_sewa,
                AVG(kontrak_sewa.harga_sewa) as rata_rata_harga
            ')
            ->join('kamar', 'kontrak_sewa.id_kamar', '=', 'kamar.id_kamar')
            ->where('kontrak_sewa.id_penghuni', $penghuniId)
            ->groupBy('kamar.tipe_kamar')
            ->get();

        // 8. Statistik Ringkasan
        $statistikRingkasan = [
            'total_kontrak' => $riwayatKontrak->count(),
            'kontrak_aktif' => $riwayatKontrak->where('status_kontrak', 'aktif')->count(),
            'total_pembayaran' => Pembayaran::join('kontrak_sewa', 'pembayaran.id_kontrak', '=', 'kontrak_sewa.id_kontrak')
                ->where('kontrak_sewa.id_penghuni', $penghuniId)
                ->where('pembayaran.status_pembayaran', 'lunas')
                ->sum('pembayaran.jumlah'),
            'jumlah_review' => Review::where('id_penghuni', $penghuniId)->count(),
            'rata_rata_rating' => Review::where('id_penghuni', $penghuniId)->avg('rating') ?? 0,
        ];

        $penghuni = auth()->guard('penghuni')->user();

        return view('penghuni.analisis.index', compact(
            'riwayatKontrak',
            'pembayaranPerBulan',
            'statusPembayaran',
            'durasiTinggal',
            'jenisKosDisewa',
            'reviewStats',
            'tipeKamarDisewa',
            'statistikRingkasan',
            'penghuni'
        ));
    }

    public function getSpendingAnalysis()
    {
        $penghuniId = auth()->guard('penghuni')->user()->id_penghuni;
        
        // Analisis pengeluaran per kategori
        $spendingByMonth = Pembayaran::selectRaw('
                YEAR(tanggal_bayar) as tahun,
                MONTH(tanggal_bayar) as bulan,
                SUM(jumlah) as total_pengeluaran
            ')
            ->join('kontrak_sewa', 'pembayaran.id_kontrak', '=', 'kontrak_sewa.id_kontrak')
            ->where('kontrak_sewa.id_penghuni', $penghuniId)
            ->where('pembayaran.status_pembayaran', 'lunas')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(12)
            ->get()
            ->reverse();

        // Kenaikan harga rata-rata
        $priceTrend = DB::table('kontrak_sewa')
            ->selectRaw('
                YEAR(tanggal_mulai) as tahun,
                AVG(harga_sewa) as rata_harga,
                COUNT(*) as jumlah_kontrak
            ')
            ->where('id_penghuni', $penghuniId)
            ->whereNotNull('tanggal_mulai')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        return response()->json([
            'spending_by_month' => $spendingByMonth,
            'price_trend' => $priceTrend,
        ]);
    }
}