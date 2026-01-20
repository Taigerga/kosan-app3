<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{LoginController, RegisterController};
use App\Http\Controllers\Public\{HomeController, KosController, PageController};
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Penghuni\{
    DashboardController as PenghuniDashboard,
    KontrakController as PenghuniKontrak,
    PembayaranController as PenghuniPembayaran,
    ReviewController as PenghuniReview,
    AnalisisController as PenghuniAnalisis
};
use App\Http\Controllers\Pemilik\{
    DashboardController as PemilikDashboard,
    KontrakController as PemilikKontrak,
    PembayaranController as PemilikPembayaran,
    KosController as PemilikKos,
    KamarController as PemilikKamar,
    ReviewController as PemilikReview,
    AnalisisController as PemilikAnalisis
};

/* --------------------------------------------------------------------------
 *  PUBLIC ROUTES
 * -------------------------------------------------------------------------- */
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/kos', [KosController::class, 'index'])->name('public.kos.index');
Route::get('/kos/{id}', [KosController::class, 'show'])->name('public.kos.show');
Route::get('/peta', [KosController::class, 'peta'])->name('public.kos.peta');

// Static pages
Route::prefix('pages')->as('public.')->group(function () {
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/how-to', [PageController::class, 'howto'])->name('howto');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
});

/* --------------------------------------------------------------------------
 *  AUTH ROUTES
 * -------------------------------------------------------------------------- */
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/* --------------------------------------------------------------------------
 *  FILE ROUTES (public storage)
 * -------------------------------------------------------------------------- */
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $allowed = ['kos', 'kamar', 'ktp', 'bukti', 'pembayaran', 'profiles', 'reviews', 'kontrak', 'foto_profil', 'bukti_pembayaran'];
    abort_unless(in_array($folder, $allowed), 403, 'Folder tidak diizinkan');

    $path = storage_path("app/public/{$folder}/{$filename}");
    abort_unless(file_exists($path), 404, 'File tidak ditemukan');

    return response()->file($path);
})->name('storage.file');

// Aliases for backward compatibility
Route::get('/files/{folder}/{filename}', function ($folder, $filename) {
    return redirect()->route('storage.file', ['folder' => $folder, 'filename' => $filename]);
})->where(['folder' => 'pembayaran|bukti|reviews|kos']);

/* --------------------------------------------------------------------------
 *  TEST / DEBUG ROUTES
 * -------------------------------------------------------------------------- */
Route::prefix('test')->group(function () {
    Route::get('/auth', function () {
        $penghuni = auth('penghuni')->check();
        $pemilik  = auth('pemilik')->check();

        return [
            'penghuni' => $penghuni ? 'Logged in' : 'Not logged in',
            'pemilik'  => $pemilik  ? 'Logged in' : 'Not logged in',
            'user'     => $penghuni
                ? auth('penghuni')->user()->only(['nama', 'email'])
                : ($pemilik ? auth('pemilik')->user()->only(['nama', 'email']) : null)
        ];
    });

    Route::get('/email/kontrak-diterima/{id}', fn($id) => testMail(\App\Mail\Penghuni\KontrakDiterimaMail::class, $id));
    Route::get('/email/kontrak-ditolak/{id}', fn($id) => testMail(\App\Mail\Penghuni\KontrakDitolakMail::class, $id));
});

Route::get('/redirect', function () {
    return auth('penghuni')->check() ? redirect()->route('penghuni.dashboard')
         : (auth('pemilik')->check()  ? redirect()->route('pemilik.dashboard')
         : redirect('/'));
})->name('redirect');

/* --------------------------------------------------------------------------
 *  PENGHUNI ROUTES
 * -------------------------------------------------------------------------- */
Route::prefix('penghuni')->as('penghuni.')->group(function () {
    /* --- open routes (development) --- */
    Route::get('/dashboard', [PenghuniDashboard::class, 'index'])->name('dashboard');

    // Kontrak
    Route::get('/kontrak/create/{kosId}', [PenghuniKontrak::class, 'create'])->name('kontrak.create');
    Route::post('/kontrak', [PenghuniKontrak::class, 'store'])->name('kontrak.store');
    Route::get('/kontrak/{id}', [PenghuniKontrak::class, 'show'])->name('kontrak.show');
    Route::post('/kontrak/{id}/extend', [PenghuniKontrak::class, 'extend'])->name('kontrak.extend');
    Route::get('/kontrak', [PenghuniKontrak::class, 'index'])->name('kontrak.index');

    // Pembayaran
    Route::get('/pembayaran', [PenghuniPembayaran::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/create', [PenghuniPembayaran::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PenghuniPembayaran::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{id}', [PenghuniPembayaran::class, 'show'])->name('pembayaran.show');

    // Reviews
    Route::get('/reviews/create/{kos}', [PenghuniReview::class, 'create'])->name('reviews.create');
    Route::post('/reviews/store', [PenghuniReview::class, 'store'])->name('reviews.store');
    Route::get('/reviews/history', [PenghuniReview::class, 'history'])->name('reviews.history');

    /* --- protected routes --- */
    Route::middleware('auth:penghuni')->group(function () {
        Route::get('/reviews/{review}/edit', [PenghuniReview::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{review}', [PenghuniReview::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [PenghuniReview::class, 'destroy'])->name('reviews.destroy');

        // Profile
        Route::get('/profile', [ProfileController::class, 'showPenghuni'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'editPenghuni'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'updatePenghuni'])->name('profile.update');
        Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhotoPenghuni'])->name('profile.upload-photo');

        // Analisis
        Route::get('/analisis', [PenghuniAnalisis::class, 'index'])->name('analisis.index');
        Route::get('/analisis/spending', [PenghuniAnalisis::class, 'getSpendingAnalysis'])->name('analisis.spending');
    });
});

/* --------------------------------------------------------------------------
 *  PEMILIK ROUTES
 * -------------------------------------------------------------------------- */
Route::prefix('pemilik')->as('pemilik.')->group(function () {
    /* --- open routes (development) --- */
    Route::get('/dashboard', [PemilikDashboard::class, 'index'])->name('dashboard');

    // Kontrak
    Route::get('/kontrak', [PemilikKontrak::class, 'index'])->name('kontrak.index');
    Route::get('/kontrak/{id}', [PemilikKontrak::class, 'show'])->name('kontrak.show');
    Route::post('/kontrak/{id}/approve', [PemilikKontrak::class, 'approve'])->name('kontrak.approve');
    Route::post('/kontrak/{id}/reject', [PemilikKontrak::class, 'reject'])->name('kontrak.reject');
    Route::post('/kontrak/{id}/selesai', [PemilikKontrak::class, 'selesai'])->name('kontrak.selesai');
    Route::delete('/kontrak/{id}', [PemilikKontrak::class, 'destroy'])->name('kontrak.destroy');

    // Pembayaran
    Route::get('/pembayaran', [PemilikPembayaran::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{id}/approve', [PemilikPembayaran::class, 'approve'])->name('pembayaran.approve');
    Route::post('/pembayaran/{id}/reject', [PemilikPembayaran::class, 'reject'])->name('pembayaran.reject');

    // Kos
    Route::get('/kos', [PemilikKos::class, 'index'])->name('kos.index');
    Route::get('/kos/create', [PemilikKos::class, 'create'])->name('kos.create');
    Route::post('/kos', [PemilikKos::class, 'store'])->name('kos.store');
    Route::get('/kos/{id}/edit', [PemilikKos::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{id}', [PemilikKos::class, 'update'])->name('kos.update');
    Route::delete('/kos/{id}', [PemilikKos::class, 'destroy'])->name('kos.destroy');
    Route::get('/kos/{id}/show', [PemilikKos::class, 'show'])->name('kos.show');

    // Kamar
    Route::get('/kamar', [PemilikKamar::class, 'index'])->name('kamar.index');
    Route::get('/kamar/create', [PemilikKamar::class, 'create'])->name('kamar.create');
    Route::post('/kamar', [PemilikKamar::class, 'store'])->name('kamar.store');
    Route::get('/kamar/{id}/edit', [PemilikKamar::class, 'edit'])->name('kamar.edit');
    Route::put('/kamar/{id}', [PemilikKamar::class, 'update'])->name('kamar.update');
    Route::delete('/kamar/{id}', [PemilikKamar::class, 'destroy'])->name('kamar.destroy');

    // Review
    Route::get('/reviews', [PemilikReview::class, 'index'])->name('reviews.index');

    /* --- protected routes --- */
    Route::middleware('auth:pemilik')->group(function () {
        // Profile
        Route::get('/profile', [ProfileController::class, 'showPemilik'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'editPemilik'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'updatePemilik'])->name('profile.update');
        Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhotoPemilik'])->name('profile.upload-photo');

        // Analisis
        Route::get('/analisis', [PemilikAnalisis::class, 'index'])->name('analisis.index');
    });
});

/* --------------------------------------------------------------------------
 *  HELPERS
 * -------------------------------------------------------------------------- */
function testMail(string $mailable, int $id): string
{
    $kontrak = \App\Models\KontrakSewa::find($id);
    if (!$kontrak) return 'Kontrak tidak ditemukan';

    \Mail::to('test@example.com')->send(new $mailable($kontrak));
    $short = class_basename($mailable);
    return "Email {$short} berhasil dikirim!";
}

