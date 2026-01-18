@extends('layouts.app')

@section('title', 'Kosan App - Tempat Cari Kos Terbaik')

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
</style>


    <!-- Hero Section -->
    <section class="relative py-20 md:py-24 overflow-hidden bg-animate">
        <div class="absolute inset-0 bg-black/20 z-0"></div>

        <div class="absolute inset-0 opacity-10 z-0">
            <div class="absolute top-0 left-0 w-72 h-72 bg-white rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-400 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>
        </div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-3xl mx-auto">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                    <i class="fas fa-home text-white text-3xl"></i>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-white">
                    Temukan Kos <span class="text-blue-200">Impian</span> Anda
                </h1>
                
                <p class="text-xl text-blue-50 mb-8 max-w-2xl mx-auto opacity-90">
                    Ribuan pilihan kos premium dengan fasilitas terbaik di seluruh Indonesia
                </p>
                
                <form action="{{ route('public.kos.index') }}" method="GET" class="max-w-3xl mx-auto">
                    <div class="bg-dark-card/50 backdrop-blur-md border border-white/10 rounded-2xl p-2 shadow-2xl">
                        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
                            <div class="flex-1 relative">
                                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                <input type="text" 
                                    name="search" 
                                    placeholder="Cari nama kos atau lokasi..." 
                                    class="w-full pl-12 pr-4 py-3 bg-dark-card border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 transition">
                            </div>
                            
                            <div class="relative">
                                <i class="fas fa-users absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                <select name="jenis_kos" 
                                        class="pl-12 pr-10 py-3 bg-dark-card border border-dark-border text-white rounded-xl focus:outline-none appearance-none transition">
                                    <option value="">Semua Jenis</option>
                                    <option value="putra">Putra</option>
                                    <option value="putri">Putri</option>
                                    <option value="campuran">Campuran</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                            </div>
                            
                            <button type="submit" 
                                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:-translate-y-1">
                                <i class="fas fa-search mr-2"></i>
                                Cari Kos
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $totalKos ?? '100+' }}</div>
                        <div class="text-sm text-primary-200">Kos Tersedia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $totalKamar ?? '500+' }}</div>
                        <div class="text-sm text-primary-200">Kamar Kosong</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $kotaTerdaftar ?? '20+' }}</div>
                        <div class="text-sm text-primary-200">Kota</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $penghuniAktif ?? '1000+' }}</div>
                        <div class="text-sm text-primary-200">Penghuni Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rekomendasi Kos -->
    <section class="py-16 bg-dark-bg">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    <span class="bg-gradient-to-r from-primary-400 to-indigo-400 bg-clip-text text-transparent">
                        Rekomendasi Kos
                    </span>
                    <span class="block text-lg text-dark-muted mt-2">Pilihan terbaik untuk kenyamanan Anda</span>
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($rekomendasiKos as $kos)
                <div class="card-hover bg-dark-card border border-dark-border rounded-2xl overflow-hidden transition-all duration-300">
                    <!-- Kos Image -->
                    <div class="relative h-56 overflow-hidden">
                        @if($kos->foto_utama)
                            <?php
                            $filePath = storage_path('app/public/' . $kos->foto_utama);
                            $fileExists = file_exists($filePath);
                            ?>
                            
                            @if($fileExists)
                                <img src="{{ url('storage/' . $kos->foto_utama) }}" 
                                    alt="{{ $kos->nama_kos }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-dark-border to-dark-bg flex items-center justify-center">
                                    <i class="fas fa-home text-4xl text-dark-muted"></i>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-dark-border to-dark-bg flex items-center justify-center">
                                <i class="fas fa-home text-4xl text-dark-muted"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-primary-900/80 backdrop-blur-sm text-primary-300">
                                {{ ucfirst($kos->jenis_kos) }}
                            </span>
                        </div>
                        
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-900/80 backdrop-blur-sm text-green-300">
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
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-semibold text-white truncate">{{ $kos->nama_kos }}</h3>
                            <div class="flex items-center text-yellow-400 text-sm">
                                @php
                                    // Hitung rating rata-rata dari reviews
                                    $ratingKos = $kos->reviews->avg('rating');
                                @endphp
                                @if($ratingKos)
                                    <i class="fas fa-star mr-1"></i>
                                    <span>{{ number_format($ratingKos, 1) }}</span>
                                @endif
                            </div>
                        </div>
                                                
                        <div class="flex items-center text-dark-muted text-sm mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>
                            <span class="truncate">{{ $kos->alamat }}</span>
                        </div>
                        
                        <!-- Fasilitas -->
                        <div class="flex flex-wrap gap-2 mb-5">
                            @php
                                $fasilitas = $kos->fasilitas->take(3);
                            @endphp
                            @foreach($fasilitas as $fasilitasItem)
                            <span class="px-2 py-1 text-xs rounded-lg bg-dark-border/50 text-dark-muted">
                                <i class="fas fa-{{ $fasilitasItem->icon ?? 'check' }} mr-1"></i>
                                {{ $fasilitasItem->nama_fasilitas }}
                            </span>
                            @endforeach
                            @if($kos->fasilitas->count() > 3)
                            <span class="px-2 py-1 text-xs rounded-lg bg-dark-border/50 text-dark-muted">
                                +{{ $kos->fasilitas->count() - 3 }} lagi
                            </span>
                            @endif
                        </div>
                        
                        <!-- Action Button -->
                        <a href="{{ route('public.kos.show', $kos->id_kos) }}" 
                           class="block w-full bg-gradient-to-r from-primary-500 to-indigo-500 hover:from-primary-600 hover:to-indigo-600 text-white text-center py-3 rounded-xl font-medium transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="text-center mt-10">
                <a href="{{ route('public.kos.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-dark-border text-white rounded-xl hover:border-primary-500 hover:text-primary-300 transition-all duration-300 group">
                    <span>Lihat Semua Kos</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-dark-card">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Mengapa Memilih <span class="text-primary-400">KosanApp</span>?
                </h2>
                <p class="text-lg text-dark-muted max-w-2xl mx-auto">
                    Platform pencarian kos terbaik dengan pengalaman pengguna yang luar biasa
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center p-6 bg-dark-bg/50 border border-dark-border rounded-2xl hover:border-primary-500/50 transition-all duration-300 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500/20 to-primary-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-layer-group text-2xl text-primary-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Pilihan Terlengkap</h3>
                    <p class="text-dark-muted">
                        Ribuan kos dengan berbagai tipe, fasilitas, dan harga untuk setiap kebutuhan
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="text-center p-6 bg-dark-bg/50 border border-dark-border rounded-2xl hover:border-green-500/50 transition-all duration-300 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500/20 to-emerald-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-2xl text-green-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">100% Terverifikasi</h3>
                    <p class="text-dark-muted">
                        Semua kos telah diverifikasi untuk memastikan kenyamanan dan keamanan penghuni
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="text-center p-6 bg-dark-bg/50 border border-dark-border rounded-2xl hover:border-purple-500/50 transition-all duration-300 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500/20 to-indigo-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-2xl text-purple-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Harga Kompetitif</h3>
                    <p class="text-dark-muted">
                        Dapatkan harga terbaik dengan fasilitas lengkap dan transparan
                    </p>
                </div>
            </div>
            
            <!-- Additional Features -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                <div class="text-center p-4">
                    <div class="text-primary-400 text-xl mb-2">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="text-sm text-dark-muted">Mobile Friendly</div>
                </div>
                <div class="text-center p-4">
                    <div class="text-green-400 text-xl mb-2">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="text-sm text-dark-muted">24/7 Support</div>
                </div>
                <div class="text-center p-4">
                    <div class="text-yellow-400 text-xl mb-2">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="text-sm text-dark-muted">Peta Interaktif</div>
                </div>
                <div class="text-center p-4">
                    <div class="text-purple-400 text-xl mb-2">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="text-sm text-dark-muted">Kontrak Digital</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-primary-900/30 to-indigo-900/30">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Siap Temukan Kos Impian Anda?
                </h2>
                <p class="text-lg text-primary-200 mb-8">
                    Bergabunglah dengan ribuan penghuni yang telah menemukan tempat tinggal sempurna melalui KosanApp
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.kos.index') }}" 
                       class="px-8 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i>
                        Cari Kos Sekarang
                    </a>
                    
                    @guest
                    <a href="{{ route('register') }}" 
                       class="px-8 py-3 bg-dark-card border border-dark-border text-white font-semibold rounded-xl hover:border-primary-500 hover:text-primary-300 transition-all duration-300">
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

@endpush