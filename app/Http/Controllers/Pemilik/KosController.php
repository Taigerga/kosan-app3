<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Kos;
use App\Models\Fasilitas;

class KosController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('pemilik')->user();
        
        $query = Kos::withCount('kamar')
            ->where('id_pemilik', $user->id_pemilik);
            
        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kos', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $search . '%')
                  ->orWhere('kota', 'like', '%' . $search . '%');
            });
        }
        
        $kos = $query->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pemilik.kos.index', compact('kos'));
    }

    public function create()
    {
        $fasilitas = Fasilitas::all();
        return view('pemilik.kos.create', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('pemilik')->user();

        $validated = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
            'peraturan' => 'nullable|string',
            'jenis_kos' => 'required|in:putra,putri,campuran',
            'tipe_sewa' => 'required|in:harian,mingguan,bulanan,tahunan',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',      // TAMBAHKAN INI
            'longitude' => 'nullable|numeric',     // TAMBAHKAN INI
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id_fasilitas'
        ]);

        // Handle file upload
        if ($request->hasFile('foto_utama')) {
            $file = $request->file('foto_utama');
            $filename = 'kos_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('kos', $filename, 'public');
            $validated['foto_utama'] = $path;
        }

        $validated['id_pemilik'] = $user->id_pemilik;
        $validated['status_kos'] = 'aktif';

        $kos = Kos::create($validated);

        // Simpan fasilitas
        if ($request->has('fasilitas')) {
            $kos->fasilitas()->attach($request->fasilitas);
        }

        return redirect()->route('pemilik.kos.index')
            ->with('success', 'Kos berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = Auth::guard('pemilik')->user();
        $kos = Kos::with('fasilitas')
            ->where('id_pemilik', $user->id_pemilik)
            ->findOrFail($id);
        $fasilitas = Fasilitas::all();

        return view('pemilik.kos.edit', compact('kos', 'fasilitas'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('pemilik')->user();
        $kos = Kos::where('id_pemilik', $user->id_pemilik)
            ->findOrFail($id);

        $validated = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
            'peraturan' => 'nullable|string',
            'jenis_kos' => 'required|in:putra,putri,campuran',
            'tipe_sewa' => 'required|in:harian,mingguan,bulanan,tahunan',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_kos' => 'required|in:aktif,nonaktif,pending',
            'latitude' => 'nullable|numeric',      // TAMBAHKAN INI
            'longitude' => 'nullable|numeric',     // TAMBAHKAN INI
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id_fasilitas'
        ]);

        // Handle file upload
        if ($request->hasFile('foto_utama')) {
            // Hapus foto lama jika ada
            if ($kos->foto_utama) {
                \Storage::disk('public')->delete($kos->foto_utama);
            }

            $file = $request->file('foto_utama');
            $filename = 'kos_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('kos', $filename, 'public');
            $validated['foto_utama'] = $path;
        }

        // Debug: lihat data yang akan diupdate
        \Log::info('Updating kos with coordinates:', [
            'latitude' => $validated['latitude'] ?? 'null',
            'longitude' => $validated['longitude'] ?? 'null'
        ]);

        $kos->update($validated);

        // Update fasilitas
        if ($request->has('fasilitas')) {
            $kos->fasilitas()->sync($request->fasilitas);
        } else {
            $kos->fasilitas()->detach();
        }

        return redirect()->route('pemilik.kos.index')
            ->with('success', 'Kos berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::guard('pemilik')->user();
        $kos = Kos::where('id_pemilik', $user->id_pemilik)
            ->findOrFail($id);

        // Hapus foto jika ada
        if ($kos->foto_utama) {
            \Storage::disk('public')->delete($kos->foto_utama);
        }

        $kos->delete();

        return redirect()->route('pemilik.kos.index')
            ->with('success', 'Kos berhasil dihapus!');
    }
    public function show($id)
    {
        $user = Auth::guard('pemilik')->user();
        $kos = Kos::with(['fasilitas', 'kamar'])
            ->where('id_pemilik', $user->id_pemilik)
            ->findOrFail($id);

        return view('pemilik.kos.show', compact('kos'));
    }

    public function peta()
    {
        $kos = Kos::with(['fasilitas', 'pemilik'])
            ->where('status_kos', 'aktif')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('public.kos.peta', compact('kos'));
    }


}