<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Services\ALLNotificationService;
use Carbon\Carbon;

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
            ->whereHas('kontrak.kos', function ($query) use ($user) {
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

        $pembayaran = Pembayaran::with(['penghuni', 'kontrak.kos', 'kontrak.kamar'])
            ->whereHas('kontrak.kos', function ($query) use ($user) {
                $query->where('id_pemilik', $user->id_pemilik);
            })
            ->where('status_pembayaran', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $pembayaran->update([
                'status_pembayaran' => 'lunas',
                'tanggal_bayar' => now(),
            ]);

            $kontrak = $pembayaran->kontrak;
            $tipeSewa = strtolower($kontrak->kos->tipe_sewa);

            if (!$kontrak->tanggal_mulai) {
                // FIRST PAYMENT SIGN-IN: Start from NOW (Approval date)
                $startDate = now();

                // Calculate duration from the gaps in original payment record
                $origStart = Carbon::parse($pembayaran->tanggal_mulai_sewa);
                $origEnd = Carbon::parse($pembayaran->tanggal_akhir_sewa);

                // End date = Start date + (Original Duration)
                // Using days approach for exactness
                $diffInDays = $origStart->diffInDays($origEnd) + 1;
                $endDate = $startDate->copy()->addDays($diffInDays - 1);

                $kontrak->update([
                    'tanggal_mulai' => $startDate,
                    'tanggal_selesai' => $endDate
                ]);

                // Synchronize payment period to match official contract start
                $pembayaran->update([
                    'tanggal_mulai_sewa' => $startDate,
                    'tanggal_akhir_sewa' => $endDate,
                    'bulan_tahun' => $startDate->format('Y-m'),
                ]);

                Log::info('Contract initialized on approval', ['id' => $kontrak->id_kontrak, 'start' => $startDate->toDateString()]);
            } else {
                // EXTENSION: Just move the end date forward
                // The start date of this payment period was already calculated relative to previous end
                $kontrak->update([
                    'tanggal_selesai' => $pembayaran->tanggal_akhir_sewa
                ]);

                Log::info('Contract extended on approval', ['id' => $kontrak->id_kontrak, 'new_end' => $pembayaran->tanggal_akhir_sewa->toDateString()]);
            }

            DB::commit();

            // Send notifications
            $this->sendApprovalNotifications($pembayaran, 'approved');

            return redirect()->route('pemilik.pembayaran.index')
                ->with('success', 'Pembayaran berhasil dikonfirmasi! Durasi kontrak telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve payment: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menyetujui pembayaran.');
        }
    }

    public function reject($id)
    {
        $user = Auth::guard('pemilik')->user();

        $pembayaran = Pembayaran::with(['penghuni', 'kontrak.kos', 'kontrak.kamar'])
            ->whereHas('kontrak.kos', function ($query) use ($user) {
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
                'paymentDate' => $pembayaran->created_at ? $pembayaran->created_at->format('d/m/Y') : now()->format('d/m/Y'),
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