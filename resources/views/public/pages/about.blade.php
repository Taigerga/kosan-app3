@extends('layouts.app')

@section('title', 'Tentang Kami - AyoKos')
@section('content')
<div class="space-y-12">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/20 via-dark-bg to-indigo-900/20"></div>
        
        <div class="container mx-auto px-4 py-16 md:py-24 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Animated Logo -->
                <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                    <i class="fas fa-info-circle text-white text-3xl"></i>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Tentang <span class="bg-gradient-to-r from-primary-400 to-indigo-400 bg-clip-text text-transparent">AyoKos</span>
                </h1>
                
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    Platform terpercaya yang menghubungkan pencari kos dengan pemilik kos terbaik di seluruh Indonesia.
                </p>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Mission -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-8 card-hover">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-500/20 to-indigo-500/20 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-bullseye text-2xl text-primary-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-4">Misi Kami</h3>
                <p class="text-dark-muted leading-relaxed">
                    Menyediakan platform yang aman, transparan, dan mudah digunakan untuk mempermudah 
                    pencarian dan pengelolaan kos, serta membangun komunitas penghuni dan pemilik kos 
                    yang saling mendukung.
                </p>
            </div>

            <!-- Vision -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-8 card-hover">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-eye text-2xl text-green-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-4">Visi Kami</h3>
                <p class="text-dark-muted leading-relaxed">
                    Menjadi platform nomor satu di Indonesia dalam bidang pencarian dan pengelolaan kos, 
                    dengan memberikan pengalaman terbaik bagi semua pengguna melalui teknologi inovatif 
                    dan layanan yang terpercaya.
                </p>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Nilai-Nilai <span class="text-primary-400">Kami</span>
            </h2>
            <p class="text-lg text-dark-muted max-w-2xl mx-auto">
                Prinsip yang kami pegang teguh dalam setiap layanan
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Innovation -->
            <div class="bg-dark-card/50 border border-dark-border rounded-2xl p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-xl bg-primary-900/30 mr-4">
                        <i class="fas fa-lightbulb text-xl text-primary-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Inovasi</h3>
                </div>
                <p class="text-dark-muted">
                    Terus berinovasi untuk memberikan solusi terbaik dalam pencarian dan pengelolaan kos.
                </p>
            </div>

            <!-- Reliability -->
            <div class="bg-dark-card/50 border border-dark-border rounded-2xl p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-xl bg-green-900/30 mr-4">
                        <i class="fas fa-shield-alt text-xl text-green-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Keandalan</h3>
                </div>
                <p class="text-dark-muted">
                    Menjamin keamanan dan keandalan dalam setiap transaksi dan informasi yang diberikan.
                </p>
            </div>

            <!-- Community -->
            <div class="bg-dark-card/50 border border-dark-border rounded-2xl p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-xl bg-purple-900/30 mr-4">
                        <i class="fas fa-users text-xl text-purple-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Komunitas</h3>
                </div>
                <p class="text-dark-muted">
                    Membangun komunitas yang saling mendukung antara penghuni dan pemilik kos.
                </p>
            </div>
        </div>

        <!-- Additional Values -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
            <div class="text-center p-4 bg-dark-bg/30 rounded-xl border border-dark-border">
                <i class="fas fa-handshake text-2xl text-yellow-400 mb-2"></i>
                <div class="text-sm font-medium text-white">Integritas</div>
            </div>
            <div class="text-center p-4 bg-dark-bg/30 rounded-xl border border-dark-border">
                <i class="fas fa-heart text-2xl text-red-400 mb-2"></i>
                <div class="text-sm font-medium text-white">Peduli</div>
            </div>
            <div class="text-center p-4 bg-dark-bg/30 rounded-xl border border-dark-border">
                <i class="fas fa-rocket text-2xl text-blue-400 mb-2"></i>
                <div class="text-sm font-medium text-white">Efisiensi</div>
            </div>
            <div class="text-center p-4 bg-dark-bg/30 rounded-xl border border-dark-border">
                <i class="fas fa-globe text-2xl text-green-400 mb-2"></i>
                <div class="text-sm font-medium text-white">Aksesibilitas</div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Tim <span class="text-primary-400">Kami</span>
            </h2>
            <p class="text-lg text-dark-muted max-w-2xl mx-auto">
                Orang-orang hebal di balik layanan AyoKos
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Founder -->
            <div class="text-center group">
                <div class="w-40 h-40 mx-auto mb-6 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-full overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/20 to-indigo-600/20 group-hover:opacity-0 transition-opacity"></div>
                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-white">
                        MR
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-white mb-1">Muhammad Rizki</h3>
                <p class="text-primary-300 font-medium mb-3">Founder & CEO</p>
                <p class="text-dark-muted text-sm">
                    Bertanggung jawab atas visi dan strategi perusahaan.
                </p>
                <div class="flex justify-center space-x-3 mt-4">
                    <a href="#" class="text-dark-muted hover:text-primary-400 transition">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-dark-muted hover:text-primary-400 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <!-- CTO -->
            <div class="text-center group">
                <div class="w-40 h-40 mx-auto mb-6 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-600/20 to-emerald-600/20 group-hover:opacity-0 transition-opacity"></div>
                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-white">
                        MR
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-white mb-1">Muhammad Rizki</h3>
                <p class="text-green-300 font-medium mb-3">CTO</p>
                <p class="text-dark-muted text-sm">
                    Mengelola pengembangan teknologi dan platform.
                </p>
                <div class="flex justify-center space-x-3 mt-4">
                    <a href="#" class="text-dark-muted hover:text-green-400 transition">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-dark-muted hover:text-green-400 transition">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>

            <!-- Head of Operations -->
            <div class="text-center group">
                <div class="w-40 h-40 mx-auto mb-6 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 to-pink-600/20 group-hover:opacity-0 transition-opacity"></div>
                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-white">
                        MR
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-white mb-1">Muhammad Rizki</h3>
                <p class="text-purple-300 font-medium mb-3">Head of Operations</p>
                <p class="text-dark-muted text-sm">
                    Mengawasi operasional dan layanan pelanggan.
                </p>
                <div class="flex justify-center space-x-3 mt-4">
                    <a href="#" class="text-dark-muted hover:text-purple-400 transition">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-dark-muted hover:text-purple-400 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="container mx-auto px-4">
        <div class="bg-gradient-to-r from-primary-900/40 via-indigo-900/40 to-purple-900/40 border border-dark-border rounded-2xl p-8">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-white mb-2">500+</div>
                    <div class="text-primary-200">Kosan Terdaftar</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-white mb-2">2,000+</div>
                    <div class="text-primary-200">Penghuni Aktif</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-white mb-2">50+</div>
                    <div class="text-primary-200">Kota Terjangkau</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-white mb-2">98%</div>
                    <div class="text-primary-200">Kepuasan Pengguna</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline/Milestones -->
    <section class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Perjalanan <span class="text-primary-400">Kami</span>
            </h2>
            <p class="text-lg text-dark-muted max-w-2xl mx-auto">
            </p>
        </div>

        <div class="relative">
            <!-- Timeline line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-0.5 bg-gradient-to-b from-primary-500/30 via-indigo-500/30 to-purple-500/30"></div>
            
            <!-- Milestone 1 -->
            <div class="flex flex-col md:flex-row items-center mb-12">
                <div class="md:w-1/2 md:pr-12 text-right mb-4 md:mb-0">
                    <div class="bg-dark-card border border-dark-border rounded-xl p-6 inline-block">
                        <div class="text-primary-400 text-sm font-semibold mb-1">Januari 2026</div>
                        <h4 class="text-lg font-bold text-white mb-2">Peluncuran AyoKos</h4>
                        <p class="text-dark-muted text-sm">
                            Platform pertama kami diluncurkan dengan fokus pada kota Bandung.
                        </p>
                    </div>
                </div>
                <div class="md:w-1/2 md:pl-12 flex justify-center md:justify-start">
                    <div class="w-4 h-4 rounded-full bg-primary-500 border-4 border-dark-card"></div>
                </div>
            </div>

            <!-- Milestone 2 -->
            <div class="flex flex-col md:flex-row items-center mb-12">
                <div class="md:w-1/2 md:pr-12 flex justify-center md:justify-end order-2 md:order-1">
                    <div class="w-4 h-4 rounded-full bg-indigo-500 border-4 border-dark-card"></div>
                </div>
                <div class="md:w-1/2 md:pl-12 mb-4 md:mb-0 order-1 md:order-2">
                    <div class="bg-dark-card border border-dark-border rounded-xl p-6 inline-block">
                        <div class="text-indigo-400 text-sm font-semibold mb-1">Febuari 2026</div>
                        <h4 class="text-lg font-bold text-white mb-2">Ekspansi ke 10 Kota</h4>
                        <p class="text-dark-muted text-sm">
                            Berhasil memperluas layanan ke 10 kota besar di Indonesia.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Milestone 3 -->
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 md:pr-12 text-right mb-4 md:mb-0">
                    <div class="bg-dark-card border border-dark-border rounded-xl p-6 inline-block">
                        <div class="text-purple-400 text-sm font-semibold mb-1">Maret 2026</div>
                        <h4 class="text-lg font-bold text-white mb-2">1000+ Pengguna Aktif</h4>
                        <p class="text-dark-muted text-sm">
                            Mencapai tonggak sejarah dengan 1000+ pengguna aktif di platform.
                        </p>
                    </div>
                </div>
                <div class="md:w-1/2 md:pl-12 flex justify-center md:justify-start">
                    <div class="w-4 h-4 rounded-full bg-purple-500 border-4 border-dark-card"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="container mx-auto px-4 pb-12">
        <div class="text-center bg-dark-card border border-dark-border rounded-2xl p-8 md:p-12">
            <div class="max-w-2xl mx-auto">
                <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">
                    Bergabunglah dengan Komunitas Kami
                </h3>
                <p class="text-dark-muted mb-6">
                    Baik Anda mencari kos atau memiliki kos untuk disewakan, AyoKos adalah platform yang tepat untuk Anda.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.kos.index') }}" 
                       class="px-8 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i>
                        Cari Kos Sekarang
                    </a>
                    @guest
                    <a href="{{ route('register') }}" 
                       class="px-8 py-3 bg-dark-bg border border-dark-border text-white font-semibold rounded-xl hover:border-primary-500 hover:text-primary-300 transition-all duration-300">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar sebagai Pemilik
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>
</div>
@endsection