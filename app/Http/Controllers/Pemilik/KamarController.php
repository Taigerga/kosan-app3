<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use App\Models\Kos;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('pemilik')->user();

        $query = Kamar::with('kos')
            ->whereHas('kos', function ($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            });

        // Filter by kos jika ada
        if ($request->has('kos') && $request->kos != '') {
            $query->where('id_kos', $request->kos);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_kamar', $request->status);
        }

        // Filter by tipe
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe_kamar', $request->tipe);
        }

        // Hitung statistik keseluruhan
        $stats = [
            'total_kamar' => (clone $query)->count(),
            'tersedia' => (clone $query)->where('status_kamar', 'tersedia')->count(),
            'terisi' => (clone $query)->where('status_kamar', 'terisi')->count(),
            'maintenance' => (clone $query)->where('status_kamar', 'maintenance')->count(),
        ];

        $kamar = $query->orderBy('created_at', 'desc')->paginate(10);
        $kos = Kos::where('id_pemilik', $user->id_pemilik)->get();

        return view('pemilik.kamar.index', compact('kamar', 'kos', 'stats'));
    }

    public function create()
    {
        $user = Auth::guard('pemilik')->user();
        $kos = Kos::where('id_pemilik', $user->id_pemilik)
            ->where('status_kos', 'aktif')
            ->get();

        return view('pemilik.kamar.create', compact('kos'));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('pemilik')->user();

        $validated = $request->validate([
            'id_kos' => 'required|exists:kos,id_kos',
            'nomor_kamar' => 'required|string|max:10',
            'tipe_kamar' => 'required|in:Standar,Deluxe,VIP,Superior,Ekonomi',
            'harga' => 'required|numeric|min:0',
            'luas_kamar' => 'required|string|max:20',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas_kamar' => 'nullable|array', // Ubah menjadi array
            'foto_kamar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_kamar' => 'required|in:tersedia,terisi,maintenance'
        ]);

        // Validasi nomor kamar unik per kos
        $exists = Kamar::where('id_kos', $request->id_kos)
            ->where('nomor_kamar', $request->nomor_kamar)
            ->exists();

        if ($exists) {
            return back()->withErrors(['nomor_kamar' => 'Nomor kamar sudah ada di kos ini.'])->withInput();
        }

        // Handle file upload
        if ($request->hasFile('foto_kamar')) {
            $file = $request->file('foto_kamar');

            // Gunakan DIRECTORY_SEPARATOR untuk cross-platform compatibility
            $filename = 'kamar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Simpan dengan path yang konsisten
            $path = $file->storeAs('kamar', $filename, 'public');

            // Normalize path untuk Windows (ganti backslash dengan forward slash)
            $path = str_replace('\\', '/', $path);

            $validated['foto_kamar'] = $path;

            // Debug log
            \Log::info('Foto kamar disimpan:', [
                'original_path' => $path,
                'normalized' => $validated['foto_kamar'],
                'storage_exists' => \Storage::disk('public')->exists($validated['foto_kamar'])
            ]);
        }

        // Handle fasilitas kamar (array to JSON) - PERBAIKAN
        if ($request->has('fasilitas_kamar') && is_array($request->fasilitas_kamar)) {
            $validated['fasilitas_kamar'] = json_encode($request->fasilitas_kamar);
        } else {
            $validated['fasilitas_kamar'] = null; // atau json_encode([]) untuk array kosong
        }

        Kamar::create($validated);

        return redirect()->route('pemilik.kamar.index')
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = Auth::guard('pemilik')->user();
        $kamar = Kamar::with('kos')
            ->whereHas('kos', function ($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })
            ->findOrFail($id);

        $kos = Kos::where('id_pemilik', $user->id_pemilik)
            ->where('status_kos', 'aktif')
            ->get();

        return view('pemilik.kamar.edit', compact('kamar', 'kos'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('pemilik')->user();
        $kamar = Kamar::whereHas('kos', function ($query) use ($user) {
            $query->where('id_pemilik', $user->id_pemilik);
        })
            ->findOrFail($id);

        $validated = $request->validate([
            'id_kos' => 'required|exists:kos,id_kos',
            'nomor_kamar' => 'required|string|max:10',
            'tipe_kamar' => 'required|in:Standar,Deluxe,VIP,Superior,Ekonomi',
            'harga' => 'required|numeric|min:0',
            'luas_kamar' => 'required|string|max:20',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas_kamar' => 'nullable|array', // Ubah menjadi array
            'foto_kamar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_kamar' => 'required|in:tersedia,terisi,maintenance'
        ]);

        // Validasi nomor kamar unik per kos (kecuali untuk kamar ini)
        $exists = Kamar::where('id_kos', $request->id_kos)
            ->where('nomor_kamar', $request->nomor_kamar)
            ->where('id_kamar', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['nomor_kamar' => 'Nomor kamar sudah ada di kos ini.'])->withInput();
        }

        // Handle file upload
        if ($request->hasFile('foto_kamar')) {
            // Hapus foto lama jika ada
            if ($kamar->foto_kamar) {
                \Storage::disk('public')->delete($kamar->foto_kamar);
            }

            $file = $request->file('foto_kamar');
            $filename = 'kamar_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('kamar', $filename, 'public');
            // Normalize path separators (Windows compatibility)
            $path = str_replace('\\', '/', $path);
            $validated['foto_kamar'] = $path;
        }

        // Handle fasilitas kamar (array to JSON) - PERBAIKAN
        if ($request->has('fasilitas_kamar') && is_array($request->fasilitas_kamar)) {
            $validated['fasilitas_kamar'] = json_encode($request->fasilitas_kamar);
        } else {
            $validated['fasilitas_kamar'] = null; // atau json_encode([]) untuk array kosong
        }

        $kamar->update($validated);

        return redirect()->route('pemilik.kamar.index')
            ->with('success', 'Kamar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::guard('pemilik')->user();
        $kamar = Kamar::whereHas('kos', function ($query) use ($user) {
            $query->where('id_pemilik', $user->id_pemilik);
        })
            ->findOrFail($id);

        // Hapus foto jika ada
        if ($kamar->foto_kamar) {
            \Storage::disk('public')->delete($kamar->foto_kamar);
        }

        $kamar->delete();

        return redirect()->route('pemilik.kamar.index')
            ->with('success', 'Kamar berhasil dihapus!');
    }
}
