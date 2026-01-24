<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Services\ALLNotificationService;

class PembayaranController extends Controller
{
    protected $notificationService;

    public function __construct(ALLNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $user = Auth::guard('pemilik')->user();
        
        $query = Pembayaran::with(['penghuni', 'kontrak.kos'])
            ->whereHas('kontrak.kos', function($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            });

        $statistics = [
            'total' => (clone $query)->count(),
            'lunas' => (clone $query)->where('status_pembayaran', 'lunas')->count(),
            'pending' => (clone $query)->where('status_pembayaran', 'pending')->count(),
            'belum' => (clone $query)->where('status_pembayaran', 'belum')->count(),
            'terlambat' => (clone $query)->where('status_pembayaran', 'terlambat')->count(),
        ];

        $pembayaran = $query->orderBy('created_at', 'desc')->paginate(5);

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

        // Send notifications
        $this->sendApprovalNotifications($pembayaran, 'approved');

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

        // Send notifications
        $this->sendApprovalNotifications($pembayaran, 'rejected');

return redirect()->route('pemilik.pembayaran.index')
            ->with('success', 'Pembayaran ditolak. Penghuni harus mengupload bukti baru.');
    }

    /**
     * Send approval/rejection notifications to penghuni and pemilik
     */
    private function sendApprovalNotifications($pembayaran, $action)
    {
        try {
            // Get related data
            $penghuni = $pembayaran->penghuni;
            $kontrak = $pembayaran->kontrak;
            $pemilik = Auth::guard('pemilik')->user();
            
            // Prepare payment data
            $paymentData = [
                'kosName' => $kontrak->kos->nama_kos,
                'roomNumber' => $kontrak->kamar->nomor_kamar ?? null,
                'amount' => $pembayaran->jumlah,
                'paymentDate' => $pembayaran->tanggal_pembayaran ? $pembayaran->tanggal_pembayaran->format('d/m/Y') : $pembayaran->created_at->format('d/m/Y'),
                'period' => $this->formatPaymentPeriod($pembayaran),
                'penghuniName' => $penghuni->nama,
                'metodePembayaran' => $pembayaran->metode_pembayaran,
                'approvedDate' => now()->format('d/m/Y'),
            ];

            if ($action === 'approved') {
                // Send notification to penghuni (disetujui)
                $this->notificationService->sendDualPaymentNotification(
                    $penghuni,
                    'approved_penghuni',
                    $paymentData,
                    false
                );

                // Send notification to pemilik (telah disetujui)
                $this->notificationService->sendDualPaymentNotification(
                    $pemilik,
                    'approved_pemilik',
                    $paymentData,
                    true
                );
            } else {
                // Send notification to penghuni (ditolak)
                $this->notificationService->sendDualPaymentNotification(
                    $penghuni,
                    'rejected_penghuni',
                    $paymentData,
                    false
                );

                // Send notification to pemilik (telah ditolak)
                $this->notificationService->sendDualPaymentNotification(
                    $pemilik,
                    'rejected_pemilik',
                    $paymentData,
                    true
                );
            }

        } catch (\Exception $e) {
            // Log error but don't stop the process
            Log::error('Failed to send payment approval notifications: ' . $e->getMessage());
        }
    }

    /**
     * Format payment period for display
     */
    private function formatPaymentPeriod($pembayaran)
    {
        if ($pembayaran->tanggal_mulai_sewa && $pembayaran->tanggal_akhir_sewa) {
            $start = $pembayaran->tanggal_mulai_sewa->format('d/m/Y');
            $end = $pembayaran->tanggal_akhir_sewa->format('d/m/Y');
            return "{$start} - {$end}";
        }
        
        return $pembayaran->bulan_tahun ?? 'Periode tidak diketahui';
    }
}