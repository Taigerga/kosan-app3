<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\Review;
use App\Models\KontrakSewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Menampilkan form untuk membuat review baru
     */
    public function create(Kos $kos)
    {
        // Cek apakah penghuni pernah menyewa kos ini
        $penghuni = Auth::guard('penghuni')->user();
        
        $kontrakAktif = KontrakSewa::where('id_penghuni', $penghuni->id_penghuni)
            ->where('id_kos', $kos->id_kos)
            ->where('status_kontrak', 'aktif')
            ->first();
            
        $kontrakSelesai = KontrakSewa::where('id_penghuni', $penghuni->id_penghuni)
            ->where('id_kos', $kos->id_kos)
            ->where('status_kontrak', 'selesai')
            ->first();

        // Cek apakah sudah ada review
        $existingReview = Review::where('id_penghuni', $penghuni->id_penghuni)
            ->where('id_kos', $kos->id_kos)
            ->first();

        if ($existingReview) {
            return redirect()->route('public.kos.show', $kos->id_kos)
                ->with('warning', 'Anda sudah memberikan review untuk kos ini.');
        }

        // Hanya bisa review jika pernah menyewa
        if (!$kontrakAktif && !$kontrakSelesai) {
            return redirect()->route('public.kos.show', $kos->id_kos)
                ->with('error', 'Hanya penghuni yang pernah menyewa di kos ini yang bisa memberikan review.');
        }

        return view('penghuni.reviews.create', compact('kos'));
    }

    /**
     * Menyimpan review baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kos' => 'required|exists:kos,id_kos',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:10|max:1000',
            'foto_review' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $penghuni = Auth::guard('penghuni')->user();
        
        // Cek apakah penghuni pernah menyewa kos ini
        $kontrak = KontrakSewa::where('id_penghuni', $penghuni->id_penghuni)
            ->where('id_kos', $request->id_kos)
            ->whereIn('status_kontrak', ['aktif', 'selesai'])
            ->first();

        if (!$kontrak) {
            return back()->with('error', 'Hanya penghuni yang pernah menyewa di kos ini yang bisa memberikan review.');
        }

        // Cek apakah sudah ada review
        $existingReview = Review::where('id_penghuni', $penghuni->id_penghuni)
            ->where('id_kos', $request->id_kos)
            ->first();

        if ($existingReview) {
            return back()->with('warning', 'Anda sudah memberikan review untuk kos ini.');
        }

        // Handle file upload
        $fotoPath = null;
        if ($request->hasFile('foto_review')) {
            $file = $request->file('foto_review');
            $filename = 'review_' . time() . '.' . $file->getClientOriginalExtension();
            $fotoPath = $file->storeAs('reviews', $filename, 'public');
        }

        // Create review
        $review = Review::create([
            'id_kos' => $request->id_kos,
            'id_penghuni' => $penghuni->id_penghuni,
            'id_kontrak' => $kontrak->id_kontrak,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'foto_review' => $fotoPath,
        ]);

        // OPTION 1: Redirect ke history dengan success message
        return redirect()->route('penghuni.reviews.history')
            ->with('success', 'Review berhasil ditambahkan! Review Anda sekarang ditampilkan di halaman kos.');

        // OPTION 2: Redirect kembali ke halaman kos
        // return redirect()->route('public.kos.show', $request->id_kos)
        //     ->with('success', 'Review berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk edit review
     */
    public function edit(Review $review)
    {
        // Authorization: hanya pemilik review yang bisa edit
        $penghuni = Auth::guard('penghuni')->user();
        
        if ($review->id_penghuni != $penghuni->id_penghuni) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit review ini.');
        }

        return view('penghuni.reviews.edit', compact('review'));
    }

    /**
     * Update review yang sudah ada
     */
    public function update(Request $request, Review $review)
    {
        // Authorization: hanya pemilik review yang bisa update
        $penghuni = Auth::guard('penghuni')->user();
        
        if ($review->id_penghuni != $penghuni->id_penghuni) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit review ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:10|max:1000',
            'foto_review' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hapus_foto' => 'nullable|boolean',
        ]);

        // Handle foto
        $fotoPath = $review->foto_review;
        
        if ($request->has('hapus_foto') && $request->hapus_foto) {
            // Hapus foto lama jika ada
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = null;
        } 
        elseif ($request->hasFile('foto_review')) {
            // Hapus foto lama jika ada
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            
            // Upload foto baru
            $file = $request->file('foto_review');
            $filename = 'review_' . time() . '_' . $review->id_review . '.' . $file->getClientOriginalExtension();
            $fotoPath = $file->storeAs('reviews', $filename, 'public');
        }

        // Update review - HAPUS status_review
        $review->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'foto_review' => $fotoPath,
            'updated_at' => now(),
        ]);

        return redirect()->route('penghuni.reviews.history')
            ->with('success', 'Review berhasil diperbarui!');
    }

    /**
     * Hapus review
     */
    public function destroy(Review $review)
    {
        // Authorization: hanya pemilik review yang bisa hapus
        $penghuni = Auth::guard('penghuni')->user();
        
        if ($review->id_penghuni != $penghuni->id_penghuni) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus review ini.');
        }

        // Hapus foto jika ada
        if ($review->foto_review && Storage::disk('public')->exists($review->foto_review)) {
            Storage::disk('public')->delete($review->foto_review);
        }

        $review->delete();

        return redirect()->route('penghuni.reviews.history')
            ->with('success', 'Review berhasil dihapus.');
    }

    /**
     * Menampilkan history review penghuni
     */
    public function history()
    {
        $penghuni = Auth::guard('penghuni')->user();
        $reviews = Review::with('kos')
            ->where('id_penghuni', $penghuni->id_penghuni)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('penghuni.reviews.history', compact('reviews'));
    }
}