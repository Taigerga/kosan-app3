<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\Fasilitas;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KosController extends Controller
{
    public function index(Request $request)
    {
        $query = Kos::with(['fasilitas', 'reviews'])
            ->where('status_kos', 'aktif');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kos', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%')
                    ->orWhere('kecamatan', 'like', '%' . $search . '%')
                    ->orWhere('kota', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('jenis_kos')) {
            $query->where('jenis_kos', $request->jenis_kos);
        }

        if ($request->filled('kota')) {
            $query->where('kota', 'like', '%' . $request->kota . '%');
        }

        // Filter Tipe Sewa - TAMBAHKAN INI
        if ($request->filled('tipe_sewa')) {
            $query->where('tipe_sewa', $request->tipe_sewa);
        }

        // Filter ketersediaan
        if ($request->filled('ketersediaan')) {
            if ($request->ketersediaan == 'tersedia') {
                $query->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('kamar')
                        ->whereColumn('kamar.id_kos', 'kos.id_kos')
                        ->where('status_kamar', 'tersedia');
                });
            } elseif ($request->ketersediaan == 'penuh') {
                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('kamar')
                        ->whereColumn('kamar.id_kos', 'kos.id_kos')
                        ->where('status_kamar', 'tersedia');
                });
            }
        }

        // Subquery untuk mendapatkan harga kamar tersedia terendah
        $query->addSelect([
            'harga_terendah_tersedia' => DB::table('kamar')
                ->select(DB::raw('MIN(harga)'))
                ->whereColumn('kamar.id_kos', 'kos.id_kos')
                ->where('status_kamar', 'tersedia')
        ]);

        // Subquery untuk jumlah kamar tersedia - PERBAIKI INI
        $query->addSelect([
            'kamar_tersedia_count' => DB::table('kamar')
                ->select(DB::raw('COUNT(*)'))
                ->whereColumn('kamar.id_kos', 'kos.id_kos')
                ->where('status_kamar', 'tersedia')
        ]);

        // Subquery untuk total kamar (termasuk yang tidak tersedia)
        $query->addSelect([
            'total_kamar_count' => DB::table('kamar')
                ->select(DB::raw('COUNT(*)'))
                ->whereColumn('kamar.id_kos', 'kos.id_kos')
        ]);

        // Subquery untuk harga terendah (SEMUA kamar, termasuk yang terisi) - untuk sorting
        $query->addSelect([
            'harga_terendah_all' => DB::table('kamar')
                ->select(DB::raw('MIN(harga)'))
                ->whereColumn('kamar.id_kos', 'kos.id_kos')
        ]);

        // Filter harga - hanya filter kamar yang tersedia
        if ($request->filled('min_harga')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                    ->from('kamar')
                    ->whereColumn('kamar.id_kos', 'kos.id_kos')
                    ->where('status_kamar', 'tersedia')
                    ->where('harga', '>=', $request->min_harga);
            });
        }

        if ($request->filled('max_harga')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                    ->from('kamar')
                    ->whereColumn('kamar.id_kos', 'kos.id_kos')
                    ->where('status_kamar', 'tersedia')
                    ->where('harga', '<=', $request->max_harga);
            });
        }

        // Filter rating
        if ($request->filled('min_rating')) {
            $query->whereHas('reviews', function ($q) use ($request) {
                $q->select(DB::raw('AVG(rating) as avg_rating, id_kos'))
                    ->groupBy('id_kos')
                    ->having('avg_rating', '>=', $request->min_rating);
            }, '>=', 1);
        }

        // Filter fasilitas
        if ($request->filled('fasilitas')) {
            $facilityIds = (array) $request->fasilitas;
            $query->whereHas('fasilitas', function ($q) use ($facilityIds) {
                $q->whereIn('fasilitas.id_fasilitas', $facilityIds);
            }, '=', count($facilityIds));
        }

        // Sorting - jika sorting by harga, gunakan harga terendah (ALL) untuk consistency
        switch ($request->sort) {
            case 'harga_asc':
                $query->orderByRaw('COALESCE(harga_terendah_all, 9999999999) ASC');
                break;

            case 'harga_desc':
                $query->orderByRaw('COALESCE(harga_terendah_all, 0) DESC');
                break;

            case 'rating_desc':
                $query->leftJoin('reviews', 'kos.id_kos', '=', 'reviews.id_kos')
                    ->select(
                        'kos.*',
                        DB::raw('COALESCE(AVG(reviews.rating), 0) as avg_rating'),
                        DB::raw('(SELECT MIN(harga) FROM kamar WHERE kamar.id_kos = kos.id_kos AND status_kamar = "tersedia") as harga_terendah_tersedia'),
                        DB::raw('(SELECT MIN(harga) FROM kamar WHERE kamar.id_kos = kos.id_kos) as harga_terendah_all'),
                        DB::raw('(SELECT COUNT(*) FROM kamar WHERE kamar.id_kos = kos.id_kos AND status_kamar = "tersedia") as kamar_tersedia_count'),
                        DB::raw('(SELECT COUNT(*) FROM kamar WHERE kamar.id_kos = kos.id_kos) as total_kamar_count')
                    )
                    ->groupBy('kos.id_kos')
                    ->orderByDesc('avg_rating');
                break;

            case 'nama_asc':
                $query->orderBy('nama_kos', 'asc');
                break;

            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $kos = $query->paginate(12)->withQueryString();

        // Get facilities list for filter dropdown
        $fasilitasList = Fasilitas::orderBy('nama_fasilitas')->get();

        return view('public.kos.index', compact('kos', 'fasilitasList'));
    }

    public function show($id)
    {
        $kos = Kos::with([
            'fasilitas',
            'kamar' => function ($query) {
                $query->where('status_kamar', 'tersedia');
            },
            'reviews' => function ($query) {
                $query->with('penghuni')
                    ->orderBy('created_at', 'desc');
            }
        ])->where('status_kos', 'aktif')->findOrFail($id);

        // Hitung total kamar (semua status)
        $kos->total_kamar_count = $kos->kamar()->count();
        $kos->kamar_tersedia_count = $kos->kamar()->where('status_kamar', 'tersedia')->count();

        // Calculate rating statistics
        $totalReviews = $kos->reviews->count();
        $averageRating = $kos->reviews->avg('rating');

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $kos->reviews->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => round($percentage, 1)
            ];
        }

        // Get similar kos
        $similarKos = collect();

        // 1. Kos dari pemilik yang sama (kecuali kos yang sedang dilihat)
        $ownerKos = Kos::where('id_pemilik', $kos->id_pemilik)
            ->where('id_kos', '!=', $kos->id_kos)
            ->where('status_kos', 'aktif')
            ->with(['kamar' => function ($query) {
                $query->where('status_kamar', 'tersedia');
            }])
            ->inRandomOrder()
            ->limit(2)
            ->get();

        $similarKos = $similarKos->concat($ownerKos);

        // 2. Jika kurang dari 2 kos dari pemilik yang sama, tambahkan kos serupa berdasarkan jenis kelamin
        if ($similarKos->count() < 2) {
            $remainingSlots = 2 - $similarKos->count();
            
            // Prioritaskan jenis kos yang sama, fallback ke kos campuran
            $similarByType = Kos::where('id_kos', '!=', $kos->id_kos)
                ->where('status_kos', 'aktif')
                ->where(function($query) use ($kos) {
                    $query->where('jenis_kos', $kos->jenis_kos)
                          ->orWhere('jenis_kos', 'campuran');
                })
                ->whereNotIn('id_kos', $similarKos->pluck('id_kos'))
                ->with(['kamar' => function ($query) {
                    $query->where('status_kamar', 'tersedia');
                }])
                ->inRandomOrder()
                ->limit($remainingSlots)
                ->get();

            $similarKos = $similarKos->concat($similarByType);
        }

        // 3. Jika masih kurang, tambahkan kos lain dari kota yang sama secara random
        if ($similarKos->count() < 2) {
            $remainingSlots = 2 - $similarKos->count();
            
            $similarByCity = Kos::where('id_kos', '!=', $kos->id_kos)
                ->where('status_kos', 'aktif')
                ->where('kota', $kos->kota)
                ->whereNotIn('id_kos', $similarKos->pluck('id_kos'))
                ->with(['kamar' => function ($query) {
                    $query->where('status_kamar', 'tersedia');
                }])
                ->inRandomOrder()
                ->limit($remainingSlots)
                ->get();

            $similarKos = $similarKos->concat($similarByCity);
        }

        // 4. Terakhir, jika masih kurang, ambil random dari semua kos aktif
        if ($similarKos->count() < 2) {
            $remainingSlots = 2 - $similarKos->count();
            
            $randomKos = Kos::where('id_kos', '!=', $kos->id_kos)
                ->where('status_kos', 'aktif')
                ->whereNotIn('id_kos', $similarKos->pluck('id_kos'))
                ->with(['kamar' => function ($query) {
                    $query->where('status_kamar', 'tersedia');
                }])
                ->inRandomOrder()
                ->limit($remainingSlots)
                ->get();

            $similarKos = $similarKos->concat($randomKos);
        }

        return view('public.kos.show', compact('kos', 'totalReviews', 'averageRating', 'ratingDistribution', 'similarKos'));
    }

    public function peta()
    {
        $kos = Kos::withCount([
            'kamar' => function ($query) {
                $query->where('status_kamar', 'tersedia');
            }
        ])->with([
                    'kamar' => function ($query) {
                        $query->where('status_kamar', 'tersedia')
                            ->select('id_kos', 'harga', 'status_kamar')
                            ->orderBy('harga', 'asc');
                    }
                ])->where('status_kos', 'aktif')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($k) {
                // Tambahkan properti untuk harga minimum
                $k->min_harga = $k->kamar->min('harga') ?? 0;
                $k->kamar_count = $k->kamar_count ?? 0;
                return $k;
            });

        return view('public.kos.peta', compact('kos'));
    }
}