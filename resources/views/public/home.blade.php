@extends('layouts.app')

@section('title', 'AyoKos - Tempat Cari Kos Terbaik')

@section('content')

<style>
    .bg-animate {
        background: linear-gradient(-45deg, #1e3a8a, #1e40af, #3b82f6, #1d4ed8);
        background-size: 400% 400%;
        animation: gradientAnimation 10s ease infinite;
    }

    @keyframes gradientAnimation {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    /* Mobile Optimizations */
    .mobile-optimized {
        /* Base mobile optimizations */
    }
    
    @media (max-width: 640px) {
        /* Hero Section Mobile */
        .hero-title {
            font-size: 2rem !important;
            line-height: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.125rem !important;
        }
        
        .hero-stats {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1rem !important;
        }
        
        .hero-stats > div {
            padding: 0.75rem;
        }
        
        /* Search Form Mobile */
        .search-form-input {
            padding-left: 3rem !important;
            padding-right: 1rem !important;
            font-size: 0.875rem;
        }
        
        .search-form-icon {
            left: 1rem !important;
        }
        
        .search-form-select {
            padding-left: 3rem !important;
            padding-right: 2.5rem !important;
            font-size: 0.875rem;
        }
        
        .search-form-button {
            width: 100%;
            padding: 0.75rem !important;
        }
        
        /* Cards Mobile */
        .kos-card {
            margin-bottom: 1rem;
        }
        
        .kos-image {
            height: 200px !important;
        }
        
        .kos-title {
            font-size: 1.125rem !important;
            line-height: 1.5rem;
        }
        
        .kos-facilities {
            flex-wrap: nowrap !important;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 0.5rem;
        }
        
        .kos-facilities::-webkit-scrollbar {
            height: 3px;
        }
        
        .facility-badge {
            flex-shrink: 0;
            white-space: nowrap;
        }
        
        /* Features Mobile */
        .feature-card {
            padding: 1.5rem !important;
            margin-bottom: 1rem;
        }
        
        /* Button Mobile */
        .mobile-full-width {
            width: 100% !important;
        }
        
        .mobile-text-center {
            text-align: center !important;
        }
        
        /* Touch-friendly elements */
        .touch-target {
            min-height: 44px;
            min-width: 44px;
        }
        
        .touch-padding {
            padding: 12px 16px;
        }
        
        /* Hide decorative elements on mobile */
        .mobile-hide {
            display: none;
        }
        
        /* Adjust spacing for mobile */
        .mobile-py {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }
        
        .mobile-mt {
            margin-top: 2rem !important;
        }
        
        .mobile-mb {
            margin-bottom: 1.5rem !important;
        }
    }
    
    @media (max-width: 768px) {
        .section-padding {
            padding-top: 4rem !important;
            padding-bottom: 4rem !important;
        }
        
        .container-padding {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        .grid-mobile {
            grid-template-columns: 1fr !important;
        }
        
        .text-mobile-lg {
            font-size: 1.5rem !important;
        }
        
        .text-mobile-xl {
            font-size: 2rem !important;
        }
    }
    
    /* Prevent horizontal scroll */
    html, body {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Improve touch scrolling */
    * {
        -webkit-tap-highlight-color: transparent;
    }
    
    /* Better button feedback on mobile */
    button, a {
        transition: transform 0.1s ease, opacity 0.1s ease;
    }
    
    button:active, a:active {
        transform: scale(0.98);
        opacity: 0.9;
    }
</style>

    <!-- Hero Section -->
    <section class="relative py-16 md:py-24 overflow-hidden bg-animate section-padding">
        <div class="absolute inset-0 bg-black/20 z-0"></div>

        <div class="absolute inset-0 opacity-10 z-0 mobile-hide">
            <div class="absolute top-0 left-0 w-72 h-72 bg-white rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-400 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>
        </div>
        
        <div class="container mx-auto px-4 text-center relative z-10 container-padding">
            <div class="max-w-3xl mx-auto">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl touch-target">
                    <i class="fas fa-home text-white text-2xl md:text-3xl"></i>
                </div>
                
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6 text-white hero-title">
                    Temukan Kos <span class="text-blue-200">Impian</span> Anda
                </h1>
                
                <p class="text-lg md:text-xl text-blue-50 mb-6 md:mb-8 max-w-2xl mx-auto opacity-90 hero-subtitle">
                    Ribuan pilihan kos premium dengan fasilitas terbaik di seluruh Indonesia
                </p>
                
                <form action="{{ route('public.kos.index') }}" method="GET" class="max-w-3xl mx-auto">
                    <div class="bg-dark-card/50 backdrop-blur-md border border-white/10 rounded-2xl p-2 md:p-4 shadow-2xl">
                        <div class="flex flex-col space-y-3 md:space-y-0 md:space-x-3">
                            <div class="flex-1 relative">
                                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted search-form-icon"></i>
                                <input type="text" 
                                    name="search" 
                                    placeholder="Cari nama kos atau lokasi..." 
                                    class="w-full pl-12 pr-4 py-3 md:py-4 bg-dark-card border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 transition text-sm md:text-base search-form-input touch-padding">
                            </div>
                            
                            <div class="relative">
                                <i class="fas fa-users absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted search-form-icon"></i>
                                <select name="jenis_kos" 
                                        class="w-full pl-12 pr-10 py-3 md:py-4 bg-dark-card border border-dark-border text-white rounded-xl focus:outline-none appearance-none transition text-sm md:text-base search-form-select touch-padding">
                                    <option value="">Semua Jenis</option>
                                    <option value="putra">Putra</option>
                                    <option value="putri">Putri</option>
                                    <option value="campuran">Campuran</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                            </div>
                            
                            <button type="submit" 
                                    class="px-6 md:px-8 py-3 md:py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:-translate-y-1 touch-target search-form-button">
                                <i class="fas fa-search mr-2"></i>
                                <span class="text-sm md:text-base">Cari Kos</span>
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-8 md:mt-12 max-w-2xl mx-auto hero-stats">
                    <div class="text-center p-3 md:p-4 bg-white/10 backdrop-blur-sm rounded-xl">
                        <div class="text-xl md:text-3xl font-bold text-white mb-1">{{ $totalKos ?? '100+' }}</div>
                        <div class="text-xs md:text-sm text-primary-200">Kos Tersedia</div>
                    </div>
                    <div class="text-center p-3 md:p-4 bg-white/10 backdrop-blur-sm rounded-xl">
                        <div class="text-xl md:text-3xl font-bold text-white mb-1">{{ $totalKamar ?? '500+' }}</div>
                        <div class="text-xs md:text-sm text-primary-200">Kamar Kosong</div>
                    </div>
                    <div class="text-center p-3 md:p-4 bg-white/10 backdrop-blur-sm rounded-xl">
                        <div class="text-xl md:text-3xl font-bold text-white mb-1">{{ $kotaTerdaftar ?? '20+' }}</div>
                        <div class="text-xs md:text-sm text-primary-200">Kota</div>
                    </div>
                    <div class="text-center p-3 md:p-4 bg-white/10 backdrop-blur-sm rounded-xl">
                        <div class="text-xl md:text-3xl font-bold text-white mb-1">{{ $penghuniAktif ?? '1000+' }}</div>
                        <div class="text-xs md:text-sm text-primary-200">Penghuni Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rekomendasi Kos -->
    <section class="py-12 md:py-16 bg-dark-bg section-padding">
        <div class="container mx-auto px-4 container-padding">
            <div class="text-center mb-8 md:mb-12 mobile-mb">
                <h2 class="text-2xl md:text-4xl font-bold text-white mb-3 md:mb-4 text-mobile-xl">
                    <span class="bg-gradient-to-r from-primary-400 to-indigo-400 bg-clip-text text-transparent">
                        Rekomendasi Kos
                    </span>
                </h2>
                <p class="text-base md:text-lg text-dark-muted mt-1 md:mt-2">Pilihan terbaik untuk kenyamanan Anda</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 grid-mobile">
                @foreach($rekomendasiKos as $kos)
                <div class="card-hover bg-dark-card border border-dark-border rounded-2xl overflow-hidden transition-all duration-300 kos-card">
                    <!-- Kos Image -->
                    <div class="relative h-48 md:h-56 overflow-hidden kos-image">
                        @if($kos->foto_utama)
                            <?php
                            $filePath = storage_path('app/public/' . $kos->foto_utama);
                            $fileExists = file_exists($filePath);
                            ?>
                            
                            @if($fileExists)
                                <img src="{{ url('storage/' . $kos->foto_utama) }}" 
                                    alt="{{ $kos->nama_kos }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                    loading="lazy">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-dark-border to-dark-bg flex items-center justify-center">
                                    <i class="fas fa-home text-3xl md:text-4xl text-dark-muted"></i>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-dark-border to-dark-bg flex items-center justify-center">
                                <i class="fas fa-home text-3xl md:text-4xl text-dark-muted"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3 md:top-4 md:left-4">
                            <span class="px-2 py-1 md:px-3 md:py-1 rounded-full text-xs font-medium bg-primary-900/80 backdrop-blur-sm text-primary-300">
                                {{ ucfirst($kos->jenis_kos) }}
                            </span>
                        </div>
                        
                        <!-- Price Badge -->
                        <div class="absolute top-3 right-3 md:top-4 md:right-4">
                            <span class="px-2 py-1 md:px-3 md:py-1 rounded-full text-xs font-medium bg-green-900/80 backdrop-blur-sm text-green-300">
                                @php
                                    $minHarga = $kos->kamar->min('harga') ?? 0;
                                    if ($minHarga > 1000000) {
                                        echo 'Rp ' . number_format($minHarga/1000000, 1) . ' Jt';
                                    } else {
                                        echo 'Rp ' . number_format($minHarga, 0, ',', '.');
                                    }
                                @endphp
                            </span>
                        </div>
                    </div>
                    
                    <!-- Kos Content -->
                    <div class="p-4 md:p-5">
                        <div class="flex items-start justify-between mb-2 md:mb-3">
                            <h3 class="text-base md:text-lg font-semibold text-white truncate kos-title">{{ $kos->nama_kos }}</h3>
                            <div class="flex items-center text-yellow-400 text-sm">
                                @php
                                    $ratingKos = $kos->reviews->avg('rating');
                                @endphp
                                @if($ratingKos)
                                    <i class="fas fa-star mr-1 text-xs md:text-sm"></i>
                                    <span class="text-xs md:text-sm">{{ number_format($ratingKos, 1) }}</span>
                                @endif
                            </div>
                        </div>
                                                
                        <div class="flex items-center text-dark-muted text-xs md:text-sm mb-3 md:mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-primary-400 text-xs"></i>
                            <span class="truncate">{{ Str::limit($kos->alamat, 40) }}</span>
                        </div>
                        
                        <!-- Fasilitas -->
                        <div class="flex gap-2 mb-4 md:mb-5 overflow-x-auto pb-2 kos-facilities">
                            @php
                                $fasilitas = $kos->fasilitas->take(3);
                            @endphp
                            @foreach($fasilitas as $fasilitasItem)
                            <span class="px-2 py-1 text-xs rounded-lg bg-dark-border/50 text-dark-muted whitespace-nowrap facility-badge">
                                <i class="fas fa-{{ $fasilitasItem->icon ?? 'check' }} mr-1"></i>
                                {{ Str::limit($fasilitasItem->nama_fasilitas, 15) }}
                            </span>
                            @endforeach
                            @if($kos->fasilitas->count() > 3)
                            <span class="px-2 py-1 text-xs rounded-lg bg-dark-border/50 text-dark-muted whitespace-nowrap facility-badge">
                                +{{ $kos->fasilitas->count() - 3 }} lagi
                            </span>
                            @endif
                        </div>
                        
                        <!-- Action Button -->
                        <a href="{{ route('public.kos.show', $kos->id_kos) }}" 
                           class="block w-full bg-gradient-to-r from-primary-500 to-indigo-500 hover:from-primary-600 hover:to-indigo-600 text-white text-center py-2 md:py-3 rounded-xl font-medium transition-all duration-300 hover:shadow-lg hover:-translate-y-1 touch-target touch-padding text-sm md:text-base">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="text-center mt-8 md:mt-10 mobile-mt">
                <a href="{{ route('public.kos.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-dark-border text-white rounded-xl hover:border-primary-500 hover:text-primary-300 transition-all duration-300 group touch-target mobile-full-width">
                    <span class="text-sm md:text-base">Lihat Semua Kos</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 md:py-16 bg-dark-card section-padding">
        <div class="container mx-auto px-4 container-padding">
            <div class="text-center mb-8 md:mb-12 mobile-mb">
                <h2 class="text-2xl md:text-4xl font-bold text-white mb-3 md:mb-4 text-mobile-xl">
                    Mengapa Memilih <span class="text-primary-400">AyoKos</span>?
                </h2>
                <p class="text-base md:text-lg text-dark-muted max-w-2xl mx-auto">
                    Platform pencarian kos terbaik dengan pengalaman pengguna yang luar biasa
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 grid-mobile">
                <!-- Feature 1 -->
                <div class="text-center p-5 md:p-6 bg-dark-bg/50 border border-dark-border rounded-2xl hover:border-primary-500/50 transition-all duration-300 card-hover feature-card">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-primary-500/20 to-primary-600/20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <i class="fas fa-layer-group text-xl md:text-2xl text-primary-400"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-white mb-2 md:mb-3">Pilihan Terlengkap</h3>
                    <p class="text-dark-muted text-sm md:text-base">
                        Ribuan kos dengan berbagai tipe, fasilitas, dan harga untuk setiap kebutuhan
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="text-center p-5 md:p-6 bg-dark-bg/50 border border-dark-border rounded-2xl hover:border-green-500/50 transition-all duration-300 card-hover feature-card">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-green-500/20 to-emerald-600/20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <i class="fas fa-shield-alt text-xl md:text-2xl text-green-400"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-white mb-2 md:mb-3">100% Terverifikasi</h3>
                    <p class="text-dark-muted text-sm md:text-base">
                        Semua kos telah diverifikasi untuk memastikan kenyamanan dan keamanan penghuni
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="text-center p-5 md:p-6 bg-dark-bg/50 border border-dark-border rounded-2xl hover:border-purple-500/50 transition-all duration-300 card-hover feature-card">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-purple-500/20 to-indigo-600/20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <i class="fas fa-chart-line text-xl md:text-2xl text-purple-400"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-white mb-2 md:mb-3">Harga Kompetitif</h3>
                    <p class="text-dark-muted text-sm md:text-base">
                        Dapatkan harga terbaik dengan fasilitas lengkap dan transparan
                    </p>
                </div>
            </div>
            
            <!-- Additional Features -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-6 md:mt-8">
                <div class="text-center p-3 md:p-4">
                    <div class="text-primary-400 text-lg md:text-xl mb-2">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="text-xs md:text-sm text-dark-muted">Mobile Friendly</div>
                </div>
                <div class="text-center p-3 md:p-4">
                    <div class="text-green-400 text-lg md:text-xl mb-2">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="text-xs md:text-sm text-dark-muted">24/7 Support</div>
                </div>
                <div class="text-center p-3 md:p-4">
                    <div class="text-yellow-400 text-lg md:text-xl mb-2">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="text-xs md:text-sm text-dark-muted">Peta Interaktif</div>
                </div>
                <div class="text-center p-3 md:p-4">
                    <div class="text-purple-400 text-lg md:text-xl mb-2">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="text-xs md:text-sm text-dark-muted">Kontrak Digital</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 md:py-16 bg-gradient-to-r from-primary-900/30 to-indigo-900/30 section-padding">
        <div class="container mx-auto px-4 text-center container-padding">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-2xl md:text-4xl font-bold text-white mb-4 md:mb-6 text-mobile-xl">
                    Siap Temukan Kos Impian Anda?
                </h2>
                <p class="text-base md:text-lg text-primary-200 mb-6 md:mb-8">
                    Bergabunglah dengan ribuan penghuni yang telah menemukan tempat tinggal sempurna melalui AyoKos
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center mobile-text-center">
                    <a href="{{ route('public.kos.index') }}" 
                       class="px-6 md:px-8 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl touch-target mobile-full-width text-sm md:text-base">
                        <i class="fas fa-search mr-2"></i>
                        Cari Kos Sekarang
                    </a>
                    
                    @guest
                    <a href="{{ route('register') }}" 
                       class="px-6 md:px-8 py-3 bg-dark-card border border-dark-border text-white font-semibold rounded-xl hover:border-primary-500 hover:text-primary-300 transition-all duration-300 touch-target mobile-full-width text-sm md:text-base">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Gratis
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Mobile optimization scripts
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent zoom on double-tap for iOS
        document.addEventListener('touchstart', function(event) {
            if (event.touches.length > 1) {
                event.preventDefault();
            }
        }, { passive: false });

        // Improve touch scrolling for facility badges
        const facilityContainers = document.querySelectorAll('.kos-facilities');
        facilityContainers.forEach(container => {
            let isScrolling;
            container.addEventListener('touchmove', () => {
                clearTimeout(isScrolling);
                isScrolling = setTimeout(() => {
                    // Smooth scroll behavior
                }, 66);
            }, false);
        });

        // Adjust viewport for mobile devices
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            document.body.classList.add('mobile-device');
            
            // Add touch feedback for all buttons and links
            const interactiveElements = document.querySelectorAll('button, a, input[type="submit"], .touch-target');
            interactiveElements.forEach(el => {
                el.addEventListener('touchstart', function() {
                    this.style.opacity = '0.9';
                });
                el.addEventListener('touchend', function() {
                    this.style.opacity = '1';
                });
            });
        }
    });
</script>
@endpush