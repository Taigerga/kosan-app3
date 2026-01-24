<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Tampilkan daftar review untuk kos yang dimiliki pemilik saat ini
     */
    public function index(Request $request)
    {
        $pemilik = Auth::guard('pemilik')->user();

        if (!$pemilik) {
            abort(403, 'Unauthorized');
        }

        // Ambil id kos yang dimiliki pemilik
        $kosIds = Kos::where('id_pemilik', $pemilik->id_pemilik)->pluck('id_kos');

        // Ambil review yang terkait dengan kos-kos tersebut
        $reviews = Review::with(['kos', 'penghuni'])
            ->whereIn('id_kos', $kosIds->toArray())
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        // Hitung statistik keseluruhan (bukan per halaman)
        $overall_avg_rating = Review::whereIn('id_kos', $kosIds->toArray())->avg('rating');
        $latest_review = Review::whereIn('id_kos', $kosIds->toArray())->orderBy('created_at', 'desc')->first();

        return view('pemilik.reviews.index', compact('reviews', 'overall_avg_rating', 'latest_review'));
    }
}
