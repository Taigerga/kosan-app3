@extends('layouts.app')

@section('title', 'Kebijakan Privasi - AyoKos')
@section('content')
<div class="dashboard-container">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Kebijakan <span class="text-primary-400">Privasi</span>
            </h1>
            <p class="text-lg text-dark-muted max-w-2xl mx-auto">
                Kami menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi yang Anda berikan.
            </p>
            <div class="mt-4 text-sm text-primary-300 bg-primary-900/30 px-4 py-2 rounded-full inline-block">
                <i class="fas fa-calendar-alt mr-2"></i>
                Terakhir diperbarui: {{ date('d F Y') }}
            </div>
        </div>

        <!-- Privacy Content -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6 md:p-8 shadow-xl">
            <!-- Introduction -->
            <div class="mb-8">
                <div class="flex items-start mb-6">
                    <div class="bg-primary-900/30 p-3 rounded-xl mr-4">
                        <i class="fas fa-shield-alt text-xl text-primary-400"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-3">Pengantar</h2>
                        <div class="space-y-3 text-dark-muted">
                            <p>
                                Kebijakan Privasi ini menjelaskan bagaimana AyoKos mengumpulkan, menggunakan, menyimpan, 
                                dan melindungi informasi pribadi Anda ketika Anda menggunakan platform kami.
                            </p>
                            <p>
                                Dengan menggunakan AyoKos, Anda menyetujui pengumpulan dan penggunaan informasi sesuai dengan 
                                kebijakan ini. Jika Anda tidak setuju, mohon untuk tidak menggunakan platform kami.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information We Collect -->
            <section class="mb-10">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full w-9 h-9 flex items-center justify-center mr-3">
                        <span class="font-bold">1</span>
                    </div>
                    Informasi yang Kami Kumpulkan
                </h2>
                
                <div class="ml-12 space-y-8">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Informasi Pribadi</h3>
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <div class="flex items-center mb-3">
                                        <div class="bg-primary-900/30 p-2 rounded-lg mr-3">
                                            <i class="fas fa-user text-primary-400"></i>
                                        </div>
                                        <h4 class="font-medium text-white">Saat Pendaftaran</h4>
                                    </div>
                                    <ul class="space-y-2 text-dark-muted">
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Nama lengkap</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Alamat email</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Nomor telepon</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Username dan password</span>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="flex items-center mb-3">
                                        <div class="bg-green-900/30 p-2 rounded-lg mr-3">
                                            <i class="fas fa-file-contract text-green-400"></i>
                                        </div>
                                        <h4 class="font-medium text-white">Saat Transaksi</h4>
                                    </div>
                                    <ul class="space-y-2 text-dark-muted">
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-green-400 mr-2 mt-1.5"></i>
                                            <span>Foto KTP (untuk verifikasi)</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-green-400 mr-2 mt-1.5"></i>
                                            <span>Alamat domisili</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-green-400 mr-2 mt-1.5"></i>
                                            <span>Data pembayaran</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-green-400 mr-2 mt-1.5"></i>
                                            <span>Bukti pembayaran</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Information -->
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Informasi Penggunaan</h3>
                        <div class="bg-primary-900/10 border border-primary-800/30 rounded-xl p-5">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-server text-primary-400 mr-3"></i>
                                        <h4 class="font-medium text-white">Data Teknis</h4>
                                    </div>
                                    <ul class="space-y-2 text-dark-muted">
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Alamat IP</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Jenis browser dan versi</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Sistem operasi</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Data cookies</span>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-chart-line text-primary-400 mr-3"></i>
                                        <h4 class="font-medium text-white">Data Aktivitas</h4>
                                    </div>
                                    <ul class="space-y-2 text-dark-muted">
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Halaman yang dikunjungi</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Waktu akses</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Interaksi dengan platform</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                            <span>Pencarian yang dilakukan</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How We Use Information -->
            <section class="mb-10">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full w-9 h-9 flex items-center justify-center mr-3">
                        <span class="font-bold">2</span>
                    </div>
                    Bagaimana Kami Menggunakan Informasi Anda
                </h2>
                
                <div class="ml-12">
                    <div class="grid md:grid-cols-2 gap-5">
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 hover:border-primary-500/50 transition-colors">
                            <div class="text-primary-400 text-2xl mb-3">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h4 class="font-semibold text-white mb-2">Menyediakan Layanan</h4>
                            <p class="text-dark-muted text-sm">
                                Untuk memproses pendaftaran, mengelola akun, dan menyediakan layanan yang Anda minta.
                            </p>
                        </div>
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 hover:border-green-500/50 transition-colors">
                            <div class="text-green-400 text-2xl mb-3">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="font-semibold text-white mb-2">Verifikasi & Keamanan</h4>
                            <p class="text-dark-muted text-sm">
                                Untuk memverifikasi identitas, mencegah penipuan, dan melindungi keamanan platform.
                            </p>
                        </div>
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 hover:border-purple-500/50 transition-colors">
                            <div class="text-purple-400 text-2xl mb-3">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4 class="font-semibold text-white mb-2">Analisis & Pengembangan</h4>
                            <p class="text-dark-muted text-sm">
                                Untuk menganalisis penggunaan platform dan mengembangkan fitur-fitur baru.
                            </p>
                        </div>
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 hover:border-yellow-500/50 transition-colors">
                            <div class="text-yellow-400 text-2xl mb-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h4 class="font-semibold text-white mb-2">Komunikasi</h4>
                            <p class="text-dark-muted text-sm">
                                Untuk mengirim notifikasi, pembaruan, dan informasi penting terkait layanan.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Data Sharing -->
            <section class="mb-10">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full w-9 h-9 flex items-center justify-center mr-3">
                        <span class="font-bold">3</span>
                    </div>
                    Berbagi Data dengan Pihak Ketiga
                </h2>
                
                <div class="ml-12 space-y-6">
                    <div class="bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                            <p class="text-yellow-300">
                                <strong>Prinsip Kami:</strong> Kami tidak menjual data pribadi Anda kepada pihak ketiga.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4 text-dark-muted">
                        <p><strong class="text-white">Kami dapat membagikan data Anda dengan:</strong></p>
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="bg-dark-bg/50 p-5 rounded-xl">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-hands-helping text-primary-400 mr-3"></i>
                                    <h4 class="font-medium text-white">Penyedia Layanan</h4>
                                </div>
                                <ul class="space-y-2 text-sm text-dark-muted">
                                    <li class="flex items-start">
                                        <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                        <span>Penyedia hosting dan server</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                        <span>Layanan pembayaran</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                        <span>Layanan analitik</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                        <span>Layanan email marketing</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-dark-bg/50 p-5 rounded-xl">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-balance-scale text-primary-400 mr-3"></i>
                                    <h4 class="font-medium text-white">Situasi Khusus</h4>
                                </div>
                                <ul class="space-y-2 text-sm text-dark-muted">
                                    <li class="flex items-start">
                                        <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                        <span>Kepatuhan hukum</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                        <span>Perlindungan hak dan properti</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                        <span>Keamanan publik</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-circle text-xs text-primary-400 mr-2 mt-1.5"></i>
                                        <span>Merger atau akuisisi</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Data Security -->
            <section class="mb-10">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full w-9 h-9 flex items-center justify-center mr-3">
                        <span class="font-bold">4</span>
                    </div>
                    Keamanan Data
                </h2>
                
                <div class="ml-12">
                    <div class="bg-green-900/10 border border-green-800/30 rounded-xl p-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-green-900/30 p-3 rounded-lg mr-4">
                                <i class="fas fa-shield-alt text-xl text-green-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">Kami Melindungi Data Anda dengan</h3>
                        </div>
                        <div class="grid md:grid-cols-3 gap-5">
                            <div class="text-center bg-dark-bg/50 p-4 rounded-xl">
                                <div class="text-2xl text-green-400 mb-2">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <p class="font-medium text-white mb-1">Enkripsi SSL</p>
                                <p class="text-xs text-dark-muted">Data ditransmisikan secara aman</p>
                            </div>
                            <div class="text-center bg-dark-bg/50 p-4 rounded-xl">
                                <div class="text-2xl text-green-400 mb-2">
                                    <i class="fas fa-shield-virus"></i>
                                </div>
                                <p class="font-medium text-white mb-1">Firewall</p>
                                <p class="text-xs text-dark-muted">Perlindungan dari akses tidak sah</p>
                            </div>
                            <div class="text-center bg-dark-bg/50 p-4 rounded-xl">
                                <div class="text-2xl text-green-400 mb-2">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <p class="font-medium text-white mb-1">Monitoring 24/7</p>
                                <p class="text-xs text-dark-muted">Pemantauan keamanan terus menerus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Your Rights -->
            <section class="mb-10">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full w-9 h-9 flex items-center justify-center mr-3">
                        <span class="font-bold">5</span>
                    </div>
                    Hak-Hak Anda
                </h2>
                
                <div class="ml-12">
                    <div class="bg-primary-900/10 border border-primary-800/30 rounded-xl p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <div class="flex items-center mb-4">
                                    <div class="bg-primary-900/30 p-2 rounded-lg mr-3">
                                        <i class="fas fa-user-check text-primary-400"></i>
                                    </div>
                                    <h4 class="font-semibold text-white">Akses dan Koreksi</h4>
                                </div>
                                <ul class="space-y-3 text-dark-muted">
                                    <li class="flex items-start">
                                        <i class="fas fa-eye text-primary-400 mr-3 mt-1"></i>
                                        <span>Hak untuk mengakses data pribadi Anda</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-edit text-primary-400 mr-3 mt-1"></i>
                                        <span>Hak untuk memperbaiki data yang tidak akurat</span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <div class="flex items-center mb-4">
                                    <div class="bg-red-900/30 p-2 rounded-lg mr-3">
                                        <i class="fas fa-user-slash text-red-400"></i>
                                    </div>
                                    <h4 class="font-semibold text-white">Penghapusan dan Pembatasan</h4>
                                </div>
                                <ul class="space-y-3 text-dark-muted">
                                    <li class="flex items-start">
                                        <i class="fas fa-trash-alt text-red-400 mr-3 mt-1"></i>
                                        <span>Hak untuk meminta penghapusan data</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-ban text-yellow-400 mr-3 mt-1"></i>
                                        <span>Hak untuk membatasi pemrosesan data</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-6 p-4 bg-dark-bg rounded-lg border border-dark-border">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-primary-400 mr-3"></i>
                                <p class="text-sm text-dark-muted">
                                    Untuk menggunakan hak-hak Anda, silakan hubungi kami melalui email di 
                                    <a href="mailto:valorant270306@gmail.com" class="text-primary-400 hover:text-primary-300 hover:underline">valorant270306@gmail.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact -->
            <section class="mt-12 pt-8 border-t border-dark-border">
                <h2 class="text-2xl font-bold text-white mb-6">Hubungi Kami</h2>
                <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-6">
                    <p class="text-dark-muted mb-6">
                        Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, jangan ragu untuk menghubungi kami:
                    </p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="bg-primary-900/30 p-2 rounded-lg mr-3">
                                    <i class="fas fa-user-tie text-primary-400"></i>
                                </div>
                                <h4 class="font-semibold text-white">Petugas Privasi Data</h4>
                            </div>
                            <div class="space-y-2 text-dark-muted ml-11">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-3 text-primary-400"></i>
                                    <span>valorant270306@gmail.com</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone mr-3 text-primary-400"></i>
                                    <span>+6282121730722</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="bg-primary-900/30 p-2 rounded-lg mr-3">
                                    <i class="fas fa-map-marker-alt text-primary-400"></i>
                                </div>
                                <h4 class="font-semibold text-white">Alamat</h4>
                            </div>
                            <div class="text-dark-muted ml-11">
                                <p>Jl. Kebijakan Privasi No. 123</p>
                                <p>Jakarta Selatan, DKI Jakarta</p>
                                <p>Indonesia 12560</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


    </div>
</div>
@endsection