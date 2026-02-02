<?php

use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\PemilikDashboardController;
use App\Http\Controllers\API\WhatsAppBridgeController;
use App\Http\Controllers\API\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Untuk pemilik
    Route::get('/pemilik/pembayaran/pending', [PaymentController::class, 'pendingPayments']);
    Route::post('/pemilik/pembayaran/{pembayaranId}/confirm', [PaymentController::class, 'confirmPayment']);
    Route::post('/pemilik/pembayaran/{pembayaranId}/reject', [PaymentController::class, 'rejectPayment']);
    
    // Untuk penghuni
    Route::get('/penghuni/pembayaran/riwayat', [PaymentController::class, 'paymentHistory']);
    Route::get('/penghuni/pembayaran/belum-bayar', [PaymentController::class, 'unpaidPayments']);
    Route::post('/penghuni/pembayaran/{pembayaranId}/upload-bukti', [PaymentController::class, 'uploadProof']);
});


Route::middleware('auth:sanctum')->group(function () {
    // Dashboard routes
    Route::get('/pemilik/dashboard', [PemilikDashboardController::class, 'index']);
    Route::get('/pemilik/dashboard/stats/kos', [PemilikDashboardController::class, 'getKosStats']);
    Route::get('/pemilik/dashboard/pendapatan/{tahun?}', [PemilikDashboardController::class, 'getPendapatanTahunan']);
    Route::get('/pemilik/dashboard/aktivitas', [PemilikDashboardController::class, 'getAktivitasTerbaru']);
});



use App\Http\Controllers\API\PaymentCallbackController;

// Payment callback routes
Route::post('/payment/callback', [PaymentCallbackController::class, 'handleCallback']);
Route::get('/payment/simulate/{externalId}', [PaymentCallbackController::class, 'simulatePayment'])->name('payment.simulate');

Route::prefix('notifications')->group(function () {
    Route::post('menunggu-persetujuan/{kontrakId}', [NotificationController::class, 'sendMenungguPersetujuan']);
    Route::post('persetujuan-diterima/{kontrakId}', [NotificationController::class, 'sendPersetujuanDiterima']);
    Route::post('persetujuan-ditolak/{kontrakId}', [NotificationController::class, 'sendPersetujuanDitolak']);
    Route::post('pengajuan-baru/{kontrakId}', [NotificationController::class, 'sendPengajuanBaru']);
});

// WhatsApp Bridge API (untuk komunikasi VPS → PC Rumah)
Route::prefix('whatsapp-bridge')->group(function () {
    // Endpoint untuk bot (PC Rumah)
    Route::get('/pending', [WhatsAppBridgeController::class, 'getPendingMessages']);
    Route::post('/mark-sent', [WhatsAppBridgeController::class, 'markAsSent']);
    Route::post('/mark-failed', [WhatsAppBridgeController::class, 'markAsFailed']);
    Route::post('/bot-status', [WhatsAppBridgeController::class, 'updateBotStatus']);
    
    // Endpoint untuk admin panel
    Route::get('/status', [WhatsAppBridgeController::class, 'getBotStatus']);
    Route::get('/stats', [WhatsAppBridgeController::class, 'getStats']);
});
