@extends('layouts.app')

@section('title', 'Cara Memesan - AyoKos')
@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Cara Memesan Kos di 
                <span class="bg-gradient-to-r from-primary-400 to-indigo-400 bg-clip-text text-transparent">
                    AyoKos
                </span>
            </h1>
            <p class="text-lg text-dark-muted max-w-2xl mx-auto">
                Ikuti langkah-langkah mudah ini untuk menemukan dan memesan kos impian Anda.
            </p>
        </div>

        <!-- Steps -->
        <div class="space-y-8">
            <!-- Step 1 -->
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-full flex items-center justify-center border border-blue-500/30">
                        <span class="text-2xl font-bold text-blue-400">1</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <h3 class="text-xl md:text-2xl font-bold text-white">Cari Kos yang Tepat</h3>
                        <div class="ml-4 hidden md:block">
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="bg-dark-card border border-dark-border rounded-xl p-6">
                        <ul class="space-y-3 text-dark-muted">
                            <li class="flex items-start">
                                <i class="fas fa-search text-blue-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Gunakan filter pencarian berdasarkan lokasi, harga, dan fasilitas</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-info-circle text-blue-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Lihat detail kos termasuk foto, fasilitas, dan peraturan</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-star text-blue-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Baca ulasan dari penghuni sebelumnya</span>
                            </li>
                        </ul>
                        <div class="mt-6">
                            <a href="{{ route('public.kos.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                                <i class="fas fa-search mr-2"></i>
                                Cari Kos Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500/20 to-emerald-600/20 rounded-full flex items-center justify-center border border-green-500/30">
                        <span class="text-2xl font-bold text-green-400">2</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <h3 class="text-xl md:text-2xl font-bold text-white">Daftar / Login Akun</h3>
                        <div class="ml-4 hidden md:block">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="bg-dark-card border border-dark-border rounded-xl p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Register -->
                            <div>
                                <h4 class="font-semibold text-white mb-3 flex items-center">
                                    <i class="fas fa-user-plus text-blue-400 mr-2"></i>
                                    Belum Punya Akun?
                                </h4>
                                <ul class="space-y-2 text-dark-muted">
                                    <li class="flex items-start">
                                        <i class="fas fa-mouse-pointer text-blue-400 mr-2 mt-1"></i>
                                        <span>Klik tombol "Daftar" di pojok kanan atas</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-user-edit text-blue-400 mr-2 mt-1"></i>
                                        <span>Isi data diri lengkap dengan benar</span>
                                    </li>

                                </ul>
                            </div>
                            
                            <!-- Login -->
                            <div>
                                <h4 class="font-semibold text-white mb-3 flex items-center">
                                    <i class="fas fa-sign-in-alt text-green-400 mr-2"></i>
                                    Sudah Punya Akun?
                                </h4>
                                <ul class="space-y-2 text-dark-muted">
                                    <li class="flex items-start">
                                        <i class="fas fa-key text-green-400 mr-2 mt-1"></i>
                                        <span>Login dengan username dan password</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-id-card text-green-400 mr-2 mt-1"></i>
                                        <span>Pastikan data profil sudah lengkap</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-full flex items-center justify-center border border-purple-500/30">
                        <span class="text-2xl font-bold text-purple-400">3</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <h3 class="text-xl md:text-2xl font-bold text-white">Ajukan Kontrak Sewa</h3>
                        <div class="ml-4 hidden md:block">
                            <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="bg-dark-card border border-dark-border rounded-xl p-6">
                        <ul class="space-y-3 text-dark-muted mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-door-open text-purple-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Pilih kamar yang tersedia pada kos yang diinginkan</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-file-alt text-purple-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Isi formulir pengajuan kontrak dengan data lengkap</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-id-card text-purple-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Upload foto KTP yang jelas dan valid</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-calendar-alt text-purple-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Tentukan durasi sewa yang diinginkan</span>
                            </li>
                        </ul>
                        <div class="bg-purple-900/30 border border-purple-800/30 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-purple-400 mr-3 mt-1"></i>
                                <div>
                                    <p class="text-sm text-purple-300 font-medium">Proses Verifikasi</p>
                                    <p class="text-xs text-purple-200/70 mt-1">
                                        Pengajuan kontrak akan diverifikasi oleh pemilik kos dalam waktu 1-3 hari kerja.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500/20 to-yellow-600/20 rounded-full flex items-center justify-center border border-yellow-500/30">
                        <span class="text-2xl font-bold text-yellow-400">4</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <h3 class="text-xl md:text-2xl font-bold text-white">Bayar Uang Muka</h3>
                        <div class="ml-4 hidden md:block">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="bg-dark-card border border-dark-border rounded-xl p-6">
                        <div class="mb-6">
                            <h4 class="font-semibold text-white mb-3 flex items-center">
                                <i class="fas fa-credit-card text-yellow-400 mr-2"></i>
                                Metode Pembayaran
                            </h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-dark-bg/50 border border-dark-border p-3 rounded-lg text-center hover:border-yellow-500/50 transition">
                                    <div class="text-yellow-400 text-lg mb-1">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <p class="text-xs font-medium text-white">Transfer Bank</p>
                                </div>
                                <div class="bg-dark-bg/50 border border-dark-border p-3 rounded-lg text-center hover:border-yellow-500/50 transition">
                                    <div class="text-yellow-400 text-lg mb-1">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <p class="text-xs font-medium text-white">QRIS</p>
                                </div>

                            </div>
                        </div>
                        <ul class="space-y-3 text-dark-muted">
                            <li class="flex items-start">
                                <i class="fas fa-clock text-yellow-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Lakukan pembayaran uang muka setelah kontrak disetujui</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-upload text-yellow-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Upload bukti pembayaran melalui dashboard</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-hourglass-half text-yellow-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Tunggu konfirmasi dari sistem (maksimal 24 jam)</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500/20 to-red-600/20 rounded-full flex items-center justify-center border border-red-500/30">
                        <span class="text-2xl font-bold text-red-400">5</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <h3 class="text-xl md:text-2xl font-bold text-white">Mulai Tinggal</h3>
                        <div class="ml-4 hidden md:block">
                            <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="bg-dark-card border border-dark-border rounded-xl p-6">
                        <div class="mb-6">
                            <h4 class="font-semibold text-white mb-3">Setelah Pembayaran Dikonfirmasi:</h4>
                            <div class="bg-green-900/30 border border-green-800/30 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                                    <div>
                                        <p class="text-green-300 font-medium">Kontrak Anda telah aktif!</p>
                                        <p class="text-xs text-green-200/70 mt-1">Sekarang Anda bisa mulai koordinasi check-in</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="space-y-3 text-dark-muted mb-6">

                            <li class="flex items-start">
                                <i class="fas fa-search text-red-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Lakukan pengecekan kondisi kamar bersama pemilik</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-home text-red-400 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Anda sudah bisa menempati kamar yang dipesan</span>
                            </li>
                        </ul>
                        <div class="bg-blue-900/30 border border-blue-800/30 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-lightbulb text-blue-400 mr-3 mt-1"></i>
                                <div>
                                    <p class="text-sm text-blue-300 font-medium">Tips Penting</p>
                                    <p class="text-xs text-blue-200/70 mt-1">
                                        Jangan lupa untuk membayar tagihan bulanan tepat waktu melalui dashboard Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-16 bg-dark-card border border-dark-border rounded-2xl p-6 md:p-8">
            <h3 class="text-2xl font-bold text-white mb-8 text-center">
                <i class="fas fa-question-circle text-primary-400 mr-2"></i>
                Pertanyaan Umum
            </h3>
            <div class="space-y-6">
                <!-- FAQ 1 -->
                <div class="border-b border-dark-border pb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-8 h-8 bg-primary-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-primary-400 text-sm"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white mb-2">Berapa lama proses verifikasi kontrak?</h4>
                            <p class="text-dark-muted">
                                Proses verifikasi biasanya memakan waktu 1-3 hari kerja. Pemilik kos akan mengecek kelengkapan data dan dokumen yang Anda submit.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="border-b border-dark-border pb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-8 h-8 bg-red-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-400 text-sm"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white mb-2">Apa yang terjadi jika kontrak ditolak?</h4>
                            <p class="text-dark-muted">
                                Jika kontrak ditolak, Anda akan mendapatkan notifikasi beserta alasannya. Anda dapat mengajukan ulang dengan melengkapi data yang diminta.
                            </p>
                        </div>
                    </div>
                </div>
                

                
                <!-- FAQ 4 -->
                <div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-8 h-8 bg-green-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-headset text-green-400 text-sm"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white mb-2">Bagaimana jika ada masalah selama tinggal?</h4>
                            <p class="text-dark-muted">
                                Anda dapat melaporkan masalah melalui dashboard atau menghubungi pemilik kos langsung. AyoKos juga menyediakan fitur pelaporan jika diperlukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-12">
            <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-dark-border rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-white mb-4">
                    Siap Mencari Kos Impian Anda?
                </h3>
                <p class="text-dark-muted mb-6 max-w-2xl mx-auto">
                    Bergabunglah dengan ribuan penghuni yang telah menemukan rumah kedua mereka melalui AyoKos.
                </p>
                <a href="{{ route('public.kos.index') }}" 
                   class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-search mr-2"></i>
                    Mulai Pencarian
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Success Notification Modal untuk Logout -->
@if(session('success'))
<div class="modal fade custom-modal" id="logoutSuccessModal" tabindex="-1" 
     aria-labelledby="logoutSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-6">
                <div class="w-16 h-16 bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-400 text-2xl"></i>
                </div>
                <h5 class="modal-title text-lg font-semibold text-white mb-2" id="logoutSuccessModalLabel">{{ session('success') }}</h5>
                <p class="text-dark-muted text-sm">Anda telah berhasil logout</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var logoutSuccessModal = new bootstrap.Modal(document.getElementById('logoutSuccessModal'));
        logoutSuccessModal.show();
        
        setTimeout(function() {
            logoutSuccessModal.hide();
        }, 3000);
    });
</script>
@endif
@endpush