<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\KontrakSewa;
use App\Models\Pemilik;
use App\Services\ALLNotificationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
        $user = Auth::guard('penghuni')->user();

        $pembayaran = Pembayaran::with(['kontrak.kos'])
            ->where('id_penghuni', $user->id_penghuni)
            ->orderBy('bulan_tahun', 'desc')
            ->paginate(10, ['*'], 'pembayaran_page')
            ->withQueryString();

        $kontrakAktif = KontrakSewa::with(['kos'])
            ->where('id_penghuni', $user->id_penghuni)
            ->where('status_kontrak', 'aktif')
            ->paginate(5, ['*'], 'kontrak_page')
            ->withQueryString();

        return view('penghuni.pembayaran.index', compact('pembayaran', 'kontrakAktif', 'user'));
    }

    public function create(Request $request)
    {
        $user = Auth::guard('penghuni')->user();

        $kontrakAktif = KontrakSewa::with(['kos', 'kamar', 'kos.pemilik'])
            ->where('id_penghuni', $user->id_penghuni)
            ->where('status_kontrak', 'aktif')
            ->get();

        if ($kontrakAktif->isEmpty()) {
            return redirect()->route('penghuni.pembayaran.index')
                ->with('error', 'Anda tidak memiliki kontrak aktif.');
        }

        if ($request->has('id_kontrak')) {
            $selectedKontrak = $kontrakAktif->where('id_kontrak', $request->id_kontrak)->first() ?? $kontrakAktif->first();
        } else {
            $selectedKontrak = $kontrakAktif->first();
        }
        $tipeSewa = $selectedKontrak->kos->tipe_sewa; // harian, mingguan, bulanan

        // Config options based on tipe_sewa
        $paymentOptions = [];
        $unitLabel = '';
        $maxLimit = 0;

        switch ($tipeSewa) {
            case 'harian':
                $unitLabel = 'Hari';
                $maxLimit = 365;
                // Generate options for standard intervals, but allow input
                // For UI simplicity, maybe just show inputs or dropdown?
                // User requirement: "muncul hari (maks 365 hari)"
                // Let's generate a reasonable set for dropdown or just support loop in view
                // Actually the view expects an array. Generating 365 items is too much.
                // We'll generate a subset or let view handle it. 
                // Let's generate 1-30, then jumps? 
                // Or maybe the user allows text input? 
                // "Pembayaran jadi kan di form nya cuma bulan doang ... kalau yg hari yg ditampilkan hari"
                // The current view uses radio buttons. 365 radio buttons is bad.
                // I will maintain loop for small numbers, but maybe input for larger?
                // For now, let's generate 1-7, 10, 14, 30?
                // User said "maks 365 hari". 
                // Let's rely on JavaScript in view to handle range, but here we pass config.
                // But to allow the VIEW to iterate, I should pass min/max/step or just the options.
                // Let's provide standard options: 1, 3, 7, 14, 30 days.
                $ranges = [1, 2, 3, 4, 5, 6, 7, 14, 30];
                foreach ($ranges as $i) {
                    $paymentOptions[] = [
                        'value' => $i,
                        'label' => "$i Hari",
                        'total' => $selectedKontrak->harga_sewa * $i,
                        'max_date' => $this->calculateMaxDate($selectedKontrak, $i, 'harian')
                    ];
                }
                break;

            case 'mingguan':
                $unitLabel = 'Minggu';
                $maxLimit = 52;
                // Show 1-4, 8, 12?
                // User said "maks 52 minggu".
                // I'll generic 1-12 weeks for quick selection
                for ($i = 1; $i <= 12; $i++) {
                    $paymentOptions[] = [
                        'value' => $i,
                        'label' => "$i Minggu",
                        'total' => $selectedKontrak->harga_sewa * $i,
                        'max_date' => $this->calculateMaxDate($selectedKontrak, $i, 'mingguan')
                    ];
                }
                break;

            case 'tahunan':
                $unitLabel = 'Tahun';
                $maxLimit = 5;
                for ($i = 1; $i <= 5; $i++) {
                    $paymentOptions[] = [
                        'value' => $i,
                        'label' => "$i Tahun",
                        'total' => $selectedKontrak->harga_sewa * $i,
                        'max_date' => $this->calculateMaxDate($selectedKontrak, $i, 'tahunan')
                    ];
                }
                break;

            default: // bulanan => treat as bulanan
                $unitLabel = 'Bulan';
                $maxLimit = 12;
                for ($i = 1; $i <= 12; $i++) {
                    $paymentOptions[] = [
                        'value' => $i,
                        'label' => "$i Bulan",
                        'total' => $selectedKontrak->harga_sewa * $i,
                        'max_date' => $this->calculateMaxDate($selectedKontrak, $i, 'bulanan')
                    ];
                }
                break;
        }

        return view('penghuni.pembayaran.create', compact('kontrakAktif', 'selectedKontrak', 'paymentOptions', 'user', 'unitLabel', 'maxLimit', 'tipeSewa'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('PembayaranController::store - START', [
                'request_params' => $request->except(['bukti_pembayaran']),
                'has_file' => $request->hasFile('bukti_pembayaran'),
                'user_id' => Auth::guard('penghuni')->id()
            ]);

            $user = Auth::guard('penghuni')->user();

            Log::debug('Store validation started');
            $validated = $request->validate([
                'id_kontrak' => 'required|exists:kontrak_sewa,id_kontrak',
                'jumlah_waktu' => 'required|integer|min:1',
                'metode_pembayaran' => 'required|in:transfer,qris',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Increased to 5MB just in case
            ]);

            $kontrak = KontrakSewa::with(['kos.pemilik', 'kamar'])
                ->where('id_penghuni', $user->id_penghuni)
                ->where('id_kontrak', $request->id_kontrak)
                ->firstOrFail();

            $tipeSewa = strtolower($kontrak->kos->tipe_sewa);
            $jumlahWaktu = (int) $request->jumlah_waktu;

            // Validation max limits
            $maxLimit = match ($tipeSewa) {
                'harian' => 365,
                'mingguan' => 52,
                'tahunan' => 5,
                default => 12
            };

            if ($jumlahWaktu > $maxLimit) {
                return back()->with('error', "Maksimal pembayaran adalah $maxLimit " . ($tipeSewa == 'bulanan' ? 'bulan' : ($tipeSewa == 'mingguan' ? 'minggu' : ($tipeSewa == 'tahunan' ? 'tahun' : 'hari'))))->withInput();
            }

            // Tentukan tanggal mulai
            $tanggalMulai = $this->getTanggalMulaiOtomatis($kontrak);

            // Jika kontrak belum memiliki tanggal_mulai (baru disetujui), mulai dari hari ini
            if (!$kontrak->tanggal_mulai || !$tanggalMulai) {
                $tanggalMulai = Carbon::now();
            }

            // Calculate End Date based on type
            $tanggalAkhir = $tanggalMulai->copy();
            if ($tipeSewa == 'harian') {
                $tanggalAkhir = $tanggalAkhir->addDays($jumlahWaktu - 1);
            } elseif ($tipeSewa == 'mingguan') {
                $tanggalAkhir = $tanggalAkhir->addWeeks($jumlahWaktu)->subDay();
            } elseif ($tipeSewa == 'tahunan') {
                $tanggalAkhir = $tanggalAkhir->addYears($jumlahWaktu)->subDay();
            } else { // bulanan
                $tanggalAkhir = $tanggalAkhir->addMonths($jumlahWaktu)->subDay();
            }

            // Determine payment type
            $jenisPembayaran = 'rutin';
            $keterangan = 'Pembayaran rutin';

            if ($kontrak->tanggal_selesai) {
                $tanggalSelesaiKontrak = Carbon::parse($kontrak->tanggal_selesai);
                if ($tanggalMulai->greaterThan($tanggalSelesaiKontrak)) {
                    $jenisPembayaran = 'advance';
                    $keterangan = 'Pembayaran di muka (perpanjangan otomatis)';
                }
            } else {
                $tanggalSelesaiKontrak = null;
            }

            // Upload bukti pembayaran
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPembayaran = $request->file('bukti_pembayaran');
                $fileName = time() . '_' . $user->id_penghuni . '_' . uniqid() . '.' . $buktiPembayaran->getClientOriginalExtension();
                $buktiPembayaranPath = $buktiPembayaran->storeAs('bukti_pembayaran', $fileName, 'public');

                if (!$buktiPembayaranPath) {
                    throw new \Exception('Gagal menyimpan file bukti pembayaran.');
                }
            } else {
                return back()->with('error', 'File bukti pembayaran harus diupload')->withInput();
            }

            // wrap in transaction to ensure safety
            \DB::beginTransaction();
            try {
                $pembayaran = Pembayaran::create([
                    'id_kontrak' => $kontrak->id_kontrak,
                    'id_penghuni' => $user->id_penghuni,
                    'bulan_tahun' => $tanggalMulai->format('Y-m'),
                    'tanggal_mulai_sewa' => $tanggalMulai,
                    'tanggal_akhir_sewa' => $tanggalAkhir,
                    'tanggal_jatuh_tempo' => $tanggalMulai,
                    'jumlah' => $kontrak->harga_sewa * $jumlahWaktu,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'bukti_pembayaran' => $buktiPembayaranPath,
                    'status_pembayaran' => 'pending',
                    'jenis_pembayaran' => $jenisPembayaran,
                    'keterangan' => $keterangan . " (" . $jumlahWaktu . " " . $tipeSewa . ")",
                ]);

                \DB::commit();
                Log::info('Pembayaran record created (pending status)', ['id' => $pembayaran->id_pembayaran]);

                // Notifications in separate try-catch so it doesn't break the user experience
                try {
                    $this->sendPaymentNotifications($pembayaran, $kontrak, $user);
                } catch (\Exception $ne) {
                    Log::error('Notification error (ignoring): ' . $ne->getMessage());
                }

                return redirect()->route('penghuni.pembayaran.index')
                    ->with('success', 'Pembayaran berhasil dikirim! Menunggu konfirmasi pemilik.');

            } catch (\Exception $e) {
                \DB::rollBack();
                if (isset($buktiPembayaranPath)) {
                    Storage::disk('public')->delete($buktiPembayaranPath);
                }
                Log::error('Database transaction failed in store: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('PembayaranController::store - FAILED: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::guard('penghuni')->user();

        $pembayaran = Pembayaran::with(['kontrak.kos'])
            ->where('id_penghuni', $user->id_penghuni)
            ->findOrFail($id);

        return view('penghuni.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Get start date automatically
     */
    private function getTanggalMulaiOtomatis($kontrak)
    {
        // Find latest payment end date
        $lastPayment = Pembayaran::where('id_kontrak', $kontrak->id_kontrak)
            ->whereIn('status_pembayaran', ['lunas', 'pending'])
            ->orderBy('tanggal_akhir_sewa', 'desc')
            ->orderBy('bulan_tahun', 'desc') // Fallback to old field
            ->first();

        if ($lastPayment) {
            if ($lastPayment->tanggal_akhir_sewa) {
                return Carbon::parse($lastPayment->tanggal_akhir_sewa)->addDay();
            }
            // Fallback for monthly legacy data
            return Carbon::createFromFormat('Y-m', $lastPayment->bulan_tahun)->endOfMonth()->addDay();
        }

        // If no payment, return contract start date or Now?
        // If no contract dates (baru disetujui), start from today
        if ($kontrak->tanggal_mulai) {
            return Carbon::parse($kontrak->tanggal_mulai);
        }
        return Carbon::now();
    }

    /**
     * Calculate max date based on tipe sewa
     */
    private function calculateMaxDate($kontrak, $jumlah, $tipeSewa)
    {
        $startDate = $this->getTanggalMulaiOtomatis($kontrak);

        $endDate = $startDate->copy();

        if ($tipeSewa == 'harian') {
            $endDate->addDays((int) $jumlah - 1);
        } elseif ($tipeSewa == 'mingguan') {
            $totalHari = (int) $jumlah * 7;
            $endDate->addDays($totalHari - 1);
        } elseif ($tipeSewa == 'tahunan') {
            $endDate->addYears((int) $jumlah)->subDay();
        } else {
            $endDate->addMonths((int) $jumlah)->subDay();
        }

        return $endDate;
    }

    /**
     * Send payment notifications to penghuni and pemilik
     */
    private function sendPaymentNotifications($pembayaran, $kontrak, $penghuni)
    {
        try {
            // Get pemilik data
            Log::info('Getting pemilik data', [
                'kontrak_id' => $kontrak->id_kontrak,
                'kos_id' => $kontrak->id_kos
            ]);

            $pemilik = $kontrak->kos->pemilik;

            if (!$pemilik) {
                Log::error('Pemilik not found for kontrak', ['kontrak_id' => $kontrak->id_kontrak]);
                return ['error' => 'Pemilik not found'];
            }

            Log::info('Pemilik data found', [
                'pemilik_id' => $pemilik->id_pemilik,
                'pemilik_name' => $pemilik->nama,
                'pemilik_email' => $pemilik->email,
                'pemilik_phone' => $pemilik->no_hp
            ]);

            // Prepare payment data
            $paymentData = [
                'kosName' => $kontrak->kos->nama_kos,
                'roomNumber' => $kontrak->kamar->nomor_kamar ?? null,
                'amount' => $pembayaran->jumlah,
                'paymentDate' => $pembayaran->created_at ? $pembayaran->created_at->format('d/m/Y') : now()->format('d/m/Y'),
                'period' => $this->formatPaymentPeriod($pembayaran),
                'penghuniName' => $penghuni->nama,
                'metodePembayaran' => $pembayaran->metode_pembayaran,
            ];

            // Send notification to penghuni (menunggu verifikasi)
            $this->notificationService->sendDualPaymentNotification(
                $penghuni,
                'pending_penghuni',
                $paymentData,
                false
            );

            // Send notification to pemilik (pembayaran baru)
            if ($pemilik) {
                $this->notificationService->sendDualPaymentNotification(
                    $pemilik,
                    'pending_pemilik',
                    $paymentData,
                    true
                );
            }

        } catch (\Exception $e) {
            // Log error but don't stop the process
            Log::error('Failed to send payment notifications: ' . $e->getMessage());
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

        return $pembayaran->bulan_tahun;
    }
}