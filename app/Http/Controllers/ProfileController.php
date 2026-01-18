<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Penghuni;
use App\Models\Pemilik;
use App\Models\Kos;
use App\Models\Kamar;
use App\Models\KontrakSewa;
use App\Models\Review;

class ProfileController extends Controller
{
    // ==================== PENGHUNI ====================

    /**
     * Tampilkan profil penghuni
     */
    public function showPenghuni()
    {
        $penghuni = Auth::guard('penghuni')->user();

        // Load relations for stats
        $penghuni->load([
            'kontrakSewa' => function ($query) {
                $query->where('status_kontrak', 'aktif');
            },
            'reviews',
            'pembayaran' => function ($query) {
                $query->where('status_pembayaran', 'lunas');
            }
        ]);

        return view('penghuni.profile.show', compact('penghuni'));
    }

    /**
     * Tampilkan form edit profil penghuni
     */
    public function editPenghuni()
    {
        $penghuni = Auth::guard('penghuni')->user();
        return view('penghuni.profile.edit', compact('penghuni'));
    }

    /**
     * Update profil penghuni
     */
    public function updatePenghuni(Request $request)
    {
        $penghuni = Auth::guard('penghuni')->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:100|unique:penghuni,email,' . $penghuni->id_penghuni . ',id_penghuni',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'username' => 'required|string|max:50|unique:penghuni,username,' . $penghuni->id_penghuni . ',id_penghuni',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Jika password diisi, hash password baru
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $penghuni->update($validated);

        return redirect()->route('penghuni.profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Upload foto profil penghuni
     */
    public function uploadPhotoPenghuni(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $penghuni = Auth::guard('penghuni')->user();

        // Hapus foto lama jika ada
        if ($penghuni->foto_profil && Storage::exists($penghuni->foto_profil)) {
            Storage::delete($penghuni->foto_profil);
        }

        // Upload foto baru
        $path = $request->file('foto_profil')->store('profiles', 'public');
        $penghuni->update(['foto_profil' => $path]);

        return response()->json([
            'success' => true,
            'url' => Storage::url($path),
            'message' => 'Foto profil berhasil diupload'
        ]);
    }

    // ==================== PEMILIK ====================

    /**
     * Tampilkan profil pemilik
     */
    public function showPemilik()
    {
        $pemilik = Auth::guard('pemilik')->user();

        // Hitung stats
        $totalKos = Kos::where('id_pemilik', $pemilik->id_pemilik)->count();
        $totalKamar = Kamar::whereHas('kos', function ($q) use ($pemilik) {
            $q->where('id_pemilik', $pemilik->id_pemilik);
        })->count();

        $kamarTerisi = Kamar::whereHas('kos', function ($q) use ($pemilik) {
            $q->where('id_pemilik', $pemilik->id_pemilik);
        })->where('status_kamar', 'terisi')->count();

        $totalKontrak = KontrakSewa::whereHas('kos', function ($q) use ($pemilik) {
            $q->where('id_pemilik', $pemilik->id_pemilik);
        })->where('status_kontrak', 'aktif')->count();

        $ratingKos = Kos::where('id_pemilik', $pemilik->id_pemilik)
            ->withAvg('reviews', 'rating')
            ->get()
            ->avg('reviews_avg_rating');

        $recentKos = Kos::where('id_pemilik', $pemilik->id_pemilik)
            ->withCount([
                'kamar as kamar_tersedia' => function ($q) {
                    $q->where('status_kamar', 'tersedia');
                }
            ])
            ->latest()
            ->take(3)
            ->get();

        return view('pemilik.profile.show', compact(
            'pemilik',
            'totalKos',
            'totalKamar',
            'kamarTerisi',
            'totalKontrak',
            'ratingKos',
            'recentKos'
        ));
    }

    /**
     * Tampilkan form edit profil pemilik
     */
    public function editPemilik()
    {
        $pemilik = Auth::guard('pemilik')->user();
        return view('pemilik.profile.edit', compact('pemilik'));
    }

    /**
     * Update profil pemilik
     */
    public function updatePemilik(Request $request)
    {
        $pemilik = Auth::guard('pemilik')->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:100|unique:pemilik,email,' . $pemilik->id_pemilik . ',id_pemilik',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'username' => 'required|string|max:50|unique:pemilik,username,' . $pemilik->id_pemilik . ',id_pemilik',
            'password' => 'nullable|string|min:8|confirmed',
            'nama_bank' => 'nullable|string|max:50',
            'nomor_rekening' => 'nullable|string|max:50',
        ]);

        // Jika password diisi, hash password baru
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pemilik->update($validated);

        return redirect()->route('pemilik.profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Upload foto profil pemilik
     */
    public function uploadPhotoPemilik(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pemilik = Auth::guard('pemilik')->user();

        // Hapus foto lama jika ada
        if ($pemilik->foto_profil && Storage::exists($pemilik->foto_profil)) {
            Storage::delete($pemilik->foto_profil);
        }

        // Upload foto baru
        $path = $request->file('foto_profil')->store('profiles', 'public');
        $pemilik->update(['foto_profil' => $path]);

        return response()->json([
            'success' => true,
            'url' => Storage::url($path),
            'message' => 'Foto profil berhasil diupload'
        ]);
    }
}