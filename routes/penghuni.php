<?php

use App\Http\Controllers\Penghuni\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:penghuni'])->group(function () {
    // Payment routes for tenant
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('penghuni.pembayaran.index');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('penghuni.pembayaran.show');
    Route::post('/pembayaran/create', [PembayaranController::class, 'createPayment'])->name('penghuni.pembayaran.create');
    Route::post('/pembayaran/{id}/upload-bukti', [PembayaranController::class, 'uploadBukti'])->name('penghuni.pembayaran.upload-bukti');
    Route::post('/pembayaran/{id}/check-status', [PembayaranController::class, 'checkStatus'])->name('penghuni.pembayaran.check-status');
});