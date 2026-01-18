<?php

use App\Http\Controllers\Pemilik\DashboardController;
use App\Http\Controllers\Pemilik\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:pemilik'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pemilik.dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('pemilik.dashboard.chart');
    
    // Payment management routes
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pemilik.pembayaran.index');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pemilik.pembayaran.show');
    Route::post('/pembayaran/{id}/verify', [PembayaranController::class, 'verifyPayment'])->name('pemilik.pembayaran.verify');
    Route::post('/pembayaran/{id}/mark-late', [PembayaranController::class, 'markAsLate'])->name('pemilik.pembayaran.mark-late');
    Route::get('/pembayaran/report', [PembayaranController::class, 'report'])->name('pemilik.pembayaran.report');
});