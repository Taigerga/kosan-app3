<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request, $id_kos)
    {
        $penghuni = Auth::guard('penghuni')->user();
        $kos = Kos::findOrFail($id_kos);

        $favorite = Favorite::where('id_penghuni', $penghuni->id_penghuni)
                           ->where('id_kos', $id_kos)
                           ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Kos dihapus dari favorit'
            ]);
        } else {
            Favorite::create([
                'id_penghuni' => $penghuni->id_penghuni,
                'id_kos' => $id_kos
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Kos ditambahkan ke favorit'
            ]);
        }
    }

    public function index()
    {
        $penghuni = Auth::guard('penghuni')->user();
        $favorites = Favorite::where('id_penghuni', $penghuni->id_penghuni)
                            ->with('kos')
                            ->get();

        return view('penghuni.favorites.index', compact('favorites'));
    }
}
