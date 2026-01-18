<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Kos;

class HomeController extends Controller
{
    // Di Controller (HomeController atau PublicController)
    public function index()
    {
        // Query untuk mendapatkan kos yang memiliki kamar tersedia
        $rekomendasiKos = Kos::where('status_kos', 'aktif')
            ->withWhereHas('kamar', function($query) {
                $query->where('status_kamar', 'tersedia')
                    ->where('harga', '>', 0); // Pastikan harga lebih dari 0
            })
            ->with(['kamar' => function($query) {
                $query->where('status_kamar', 'tersedia')
                    ->where('harga', '>', 0);
            }])
            ->with(['fasilitas', 'reviews'])
            ->limit(6)
            ->get();

        return view('public.home', compact('rekomendasiKos'));
}
}