<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = Auth::guard('pemilik')->user();
        
        $pembayaran = Pembayaran::with(['penghuni', 'kontrak.kos'])
            ->whereHas('kontrak.kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statistics = [
            'total' => $pembayaran->count(),
            'lunas' => $pembayaran->where('status_pembayaran', 'lunas')->count(),
            'pending' => $pembayaran->where('status_pembayaran', 'pending')->count(),
            'belum' => $pembayaran->where('status_pembayaran', 'belum')->count(),
            'terlambat' => $pembayaran->where('status_pembayaran', 'terlambat')->count(),
        ];

        return view('pemilik.pembayaran.index', compact('pembayaran', 'statistics'));
    }

    public function approve($id)
    {
        $user = Auth::guard('pemilik')->user();
        
        $pembayaran = Pembayaran::with(['kontrak.kos'])
            ->whereHas('kontrak.kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })
            ->where('status_pembayaran', 'pending')
            ->findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => 'lunas',
            'tanggal_bayar' => now(),
        ]);

        return redirect()->route('pemilik.pembayaran.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function reject($id)
    {
        $user = Auth::guard('pemilik')->user();
        
        $pembayaran = Pembayaran::with(['kontrak.kos'])
            ->whereHas('kontrak.kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })
            ->where('status_pembayaran', 'pending')
            ->findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => 'belum',
            // 'bukti_pembayaran' dihapus
        ]);

        return redirect()->route('pemilik.pembayaran.index')
            ->with('success', 'Pembayaran ditolak. Penghuni harus mengupload bukti baru.');
    }
}