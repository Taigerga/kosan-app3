<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Models\KontrakSewa;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function sendMenungguPersetujuan($kontrakId)
    {
        try {
            $success = $this->notificationService->sendMenungguPersetujuan($kontrakId);
            
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Notifikasi menunggu persetujuan berhasil dikirim' : 'Gagal mengirim notifikasi'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendPersetujuanDiterima($kontrakId)
    {
        try {
            $success = $this->notificationService->sendPersetujuanDiterima($kontrakId);
            
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Notifikasi persetujuan berhasil dikirim' : 'Gagal mengirim notifikasi'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendPersetujuanDitolak($kontrakId)
    {
        try {
            $success = $this->notificationService->sendPersetujuanDitolak($kontrakId);
            
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Notifikasi penolakan berhasil dikirim' : 'Gagal mengirim notifikasi'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendPengajuanBaru($kontrakId)
    {
        try {
            $success = $this->notificationService->sendPengajuanBaru($kontrakId);
            
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Notifikasi pengajuan baru berhasil dikirim' : 'Gagal mengirim notifikasi'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}