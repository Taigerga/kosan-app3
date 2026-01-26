@extends('layouts.app')

@section('title', 'Cari Kos - AyoKos')

@section('content')
    <div class="space-y-6">
        <!-- Search Header -->
        <div class="mb-8 bg-dark-card border border-dark-border rounded-2xl p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                        <i class="fas fa-search mr-3"></i>
                        Temukan Kos <span class="text-blue-400">Terbaik</span> <span class="text-white">dan</span> <span class="text-green-400">Nyaman</span>
                    </h1>
                    <p class="text-slate-400">Cari kos impian Anda dengan filter lengkap</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex items-center gap-4 mt-4 md:mt-0">
                    <div class="text-center">
                        <div class="text-xl font-bold text-white">{{ $kos->total() }}</div>
                        <div class="text-xs text-slate-400">Total</div>
                    </div>
                    <div class="h-8 w-px bg-slate-700"></div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-green-400">
                            {{ $kos->filter(function($k) { return $k->kamar_tersedia_count > 0; })->count() }}
                        </div>
                        <div class="text-xs text-slate-400">Tersedia</div>
                    </div>
                </div>
            </div>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('public.kos.index') }}" 
                  class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <!-- Search Input -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2 flex items-center">
                            <i class="fas fa-search mr-2 text-slate-400"></i>
                            Kata Kunci
                        </label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nama kos atau lokasi..." 
                                   class="w-full pl-10 pr-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                    
                    <!-- Jenis Kos -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2 flex items-center">
                            <i class="fas fa-users mr-2 text-blue-400"></i>
                            Jenis Kos
                        </label>
                        <select name="jenis_kos" 
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors appearance-none">
                            <option value="">Semua Jenis</option>
                            <option value="putra" {{ request('jenis_kos') == 'putra' ? 'selected' : '' }}>Putra</option>
                            <option value="putri" {{ request('jenis_kos') == 'putri' ? 'selected' : '' }}>Putri</option>
                            <option value="campuran" {{ request('jenis_kos') == 'campuran' ? 'selected' : '' }}>Campuran</option>
                        </select>
                    </div>
                    
                    <!-- Kota -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-green-400"></i>
                            Kota
                        </label>
                        <div class="relative">
                            <input type="text" name="kota" value="{{ request('kota') }}" 
                                   placeholder="Nama kota..." 
                                   class="w-full pl-10 pr-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors">
                            <i class="fas fa-city absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                    
                    <!-- Ketersediaan -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2 flex items-center">
                            <i class="fas fa-door-open mr-2 text-amber-800"></i>
                            Ketersediaan
                        </label>
                        <select name="ketersediaan" 
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors appearance-none">
                            <option value="">Semua Status</option>
                            <option value="tersedia" {{ request('ketersediaan') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="penuh" {{ request('ketersediaan') == 'penuh' ? 'selected' : '' }}>Penuh</option>
                        </select>
                    </div>
                    
                    <!-- Search Button -->
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-3 px-6 rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-colors duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-search mr-2"></i>
                            Cari Kos
                        </button>
                    </div>
                </div>
                
                <!-- Advanced Filters -->
                <div class="border-t border-slate-700 pt-4 mt-4">
                    <button type="button" onclick="toggleAdvancedFilters()" 
                            class="flex items-center text-blue-400 hover:text-blue-300 mb-4 transition-colors">
                        <i class="fas fa-sliders-h mr-2"></i>
                        Filter Lanjutan
                        <i id="filter-arrow" class="fas fa-chevron-down ml-2 transition-transform"></i>
                    </button>
                    
                    <div id="advancedFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4 hidden">
                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-money-bill-wave mr-2 text-green-400"></i>
                                Harga per Bulan
                            </label>
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <input type="number" name="min_harga" value="{{ request('min_harga') }}" 
                                           placeholder="Min" 
                                           class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors">
                                </div>
                                <div class="flex-1">
                                    <input type="number" name="max_harga" value="{{ request('max_harga') }}" 
                                           placeholder="Max" 
                                           class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rating Filter -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-star mr-2 text-yellow-400"></i>
                                Rating Minimal
                            </label>
                            <select name="min_rating" 
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors">
                                <option value="">Semua Rating</option>
                                <option value="5" {{ request('min_rating') == '5' ? 'selected' : '' }}>⭐ 5.0</option>
                                <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>⭐ 4.5+</option>
                                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>⭐ 4.0+</option>
                                <option value="3.5" {{ request('min_rating') == '3.5' ? 'selected' : '' }}>⭐ 3.5+</option>
                                <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>⭐ 3.0+</option>
                            </select>
                        </div>
                        
                        <!-- Facilities Filter -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-list mr-2 text-purple-400"></i>
                                Fasilitas
                            </label>
                            <select name="fasilitas[]" multiple 
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors h-32 custom-scrollbar">
                                @php
                                    $selectedFacilities = request('fasilitas', []);
                                @endphp
                                @foreach($fasilitasList as $fasilitas)
                                    <option value="{{ $fasilitas->id_fasilitas }}" 
                                            {{ in_array($fasilitas->id_fasilitas, $selectedFacilities) ? 'selected' : '' }}>
                                        @php
                                            $iconMap = [
                                                'wifi' => 'wifi',
                                                'laundry' => 'shirt',
                                                'kitchen' => 'utensils',
                                                'bath' => 'bath',
                                                'hot-water' => 'temperature-high',
                                                'motorcycle' => 'motorcycle',
                                                'car' => 'car',
                                                'cctv' => 'video',
                                                'security' => 'shield-alt'
                                            ];
                                            $displayIcon = $iconMap[$fasilitas->icon] ?? 'check';
                                        @endphp
                                        <i class="fas fa-{{ $displayIcon }} mr-2"></i>
                                        {{ $fasilitas->nama_fasilitas }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Ctrl+klik untuk pilih banyak
                            </p>
                        </div>
                        
                        <!-- Sort Options -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-sort mr-2 text-cyan-400"></i>
                                Urutkan Berdasarkan
                            </label>
                            <select name="sort" 
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors">
                                <option value="">Default</option>
                                <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Rating Tertinggi</option>
                                <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Active Filters -->
                    @php
                        $hasActiveFilters = request()->hasAny(['search', 'jenis_kos', 'kota', 'ketersediaan', 'min_harga', 'max_harga', 'min_rating', 'fasilitas', 'sort']);
                    @endphp
                    
                    @if($hasActiveFilters)
                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <div class="flex flex-wrap gap-2 items-center">
                            <span class="text-sm font-medium text-white">
                                <i class="fas fa-filter mr-1"></i>
                                Filter Aktif:
                            </span>
                            
                            @if(request('search'))
                            <span class="inline-flex items-center bg-blue-900/30 text-blue-300 px-3 py-1.5 rounded-full text-sm">
                                <i class="fas fa-search mr-1"></i>
                                "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-2 hover:text-white">
                                    &times;
                                </a>
                            </span>
                            @endif
                            
                            @if(request('jenis_kos'))
                            <span class="inline-flex items-center bg-blue-900/30 text-blue-300 px-3 py-1.5 rounded-full text-sm">
                                <i class="fas fa-users mr-1"></i>
                                {{ ucfirst(request('jenis_kos')) }}
                                <a href="{{ request()->fullUrlWithQuery(['jenis_kos' => null]) }}" class="ml-2 hover:text-white">
                                    &times;
                                </a>
                            </span>
                            @endif
                            
                            @if(request('kota'))
                            <span class="inline-flex items-center bg-green-900/30 text-green-300 px-3 py-1.5 rounded-full text-sm">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ request('kota') }}
                                <a href="{{ request()->fullUrlWithQuery(['kota' => null]) }}" class="ml-2 hover:text-white">
                                    &times;
                                </a>
                            </span>
                            @endif
                            
                            @if(request('ketersediaan'))
                            <span class="inline-flex items-center bg-yellow-900/30 text-yellow-300 px-3 py-1.5 rounded-full text-sm">
                                <i class="fas fa-door-open mr-1"></i>
                                {{ request('ketersediaan') == 'tersedia' ? 'Tersedia' : 'Penuh' }}
                                <a href="{{ request()->fullUrlWithQuery(['ketersediaan' => null]) }}" class="ml-2 hover:text-white">
                                    &times;
                                </a>
                            </span>
                            @endif
                            
                            @if(request('min_harga') || request('max_harga'))
                            <span class="inline-flex items-center bg-yellow-900/30 text-yellow-300 px-3 py-1.5 rounded-full text-sm">
                                <i class="fas fa-money-bill-wave mr-1"></i>
                                @if(request('min_harga') && request('max_harga'))
                                    Rp {{ number_format(request('min_harga'), 0, ',', '.') }} - Rp {{ number_format(request('max_harga'), 0, ',', '.') }}
                                @elseif(request('min_harga'))
                                    Min. Rp {{ number_format(request('min_harga'), 0, ',', '.') }}
                                @elseif(request('max_harga'))
                                    Max. Rp {{ number_format(request('max_harga'), 0, ',', '.') }}
                                @endif
                                <a href="{{ request()->fullUrlWithQuery(['min_harga' => null, 'max_harga' => null]) }}" class="ml-2 hover:text-white">
                                    &times;
                                </a>
                            </span>
                            @endif
                            
                            @if(request('min_rating'))
                            <span class="inline-flex items-center bg-yellow-900/30 text-yellow-300 px-3 py-1.5 rounded-full text-sm">
                                <i class="fas fa-star mr-1"></i>
                                {{ request('min_rating') }}+
                                <a href="{{ request()->fullUrlWithQuery(['min_rating' => null]) }}" class="ml-2 hover:text-white">
                                    &times;
                                </a>
                            </span>
                            @endif
                            
                            @if(request('fasilitas'))
                            @php
                                $selectedFacilityIds = request('fasilitas', []);
                                $selectedFacilities = $fasilitasList->whereIn('id_fasilitas', $selectedFacilityIds);
                            @endphp
                            @foreach($selectedFacilities as $fasilitas)
                            <span class="inline-flex items-center bg-purple-900/30 text-purple-300 px-3 py-1.5 rounded-full text-sm">
                                @php
                                    $iconMap = [
                                        'wifi' => 'wifi',
                                        'laundry' => 'shirt',
                                        'kitchen' => 'utensils',
                                        'bath' => 'bath',
                                        'hot-water' => 'temperature-high',
                                        'motorcycle' => 'motorcycle',
                                        'car' => 'car',
                                        'cctv' => 'video',
                                        'security' => 'shield-alt'
                                    ];
                                    $displayIcon = $iconMap[$fasilitas->icon] ?? 'check';
                                @endphp
                                <i class="fas fa-{{ $displayIcon }} mr-1"></i>
                                {{ $fasilitas->nama_fasilitas }}
                                <a href="{{ request()->fullUrlWithQuery(['fasilitas' => array_diff($selectedFacilityIds, [$fasilitas->id_fasilitas])]) }}" class="ml-2 hover:text-white">
                                    &times;
                                </a>
                            </span>
                            @endforeach
                            @endif
                            
                            @if(request('sort'))
                            <span class="inline-flex items-center bg-slate-900/30 text-slate-300 px-3 py-1.5 rounded-full text-sm">
                                <i class="fas fa-sort mr-1"></i>
                                {{ ucwords(str_replace('_', ' ', request('sort'))) }}
                                <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="ml-2 hover:text-white">
                                    &times;
                                </a>
                            </span>
                            @endif
                            
                            <a href="{{ route('public.kos.index') }}" 
                               class="text-sm text-blue-400 hover:text-blue-300 ml-auto transition-colors flex items-center">
                                <i class="fas fa-times mr-1"></i>
                                Hapus Semua Filter
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Results -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ">
            @forelse($kos as $k)
            <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">
                <!-- Kos Image -->
                <div class="relative h-56 overflow-hidden">
                    @if($k->foto_utama)
                        <img src="{{ asset('storage/' . $k->foto_utama) }}" 
                             alt="{{ $k->nama_kos }}" 
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=No+Image';">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                            <i class="fas fa-home text-4xl text-slate-400"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badges -->
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-900/80 backdrop-blur-sm text-blue-300">
                            {{ ucfirst($k->jenis_kos) }}
                        </span>
                        @if($k->kamar_tersedia_count > 0)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-900/80 backdrop-blur-sm text-green-300">
                            {{ $k->kamar_tersedia_count }} Kamar Tersedia
                        </span>
                        @endif
                    </div>
                    
                    <!-- Rating -->
                    @php
                        $averageRating = $k->reviews->avg('rating');
                        $reviewCount = $k->reviews->count();
                    @endphp
                    @if($averageRating)
                    <div class="absolute top-4 right-4">
                        <div class="flex items-center bg-yellow-900/80 backdrop-blur-sm px-3 py-1.5 rounded-full">
                            <span class="text-yellow-400 font-bold mr-1">{{ number_format($averageRating, 1) }}</span>
                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                            <span class="text-yellow-300 text-xs ml-1">({{ $reviewCount }})</span>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Kos Content -->
                <div class="p-5 bg-slate-900">
                    <h3 class="text-lg font-semibold text-white mb-2 truncate">{{ $k->nama_kos }}</h3>
                    
                    <div class="flex items-center text-slate-400 text-sm mb-3">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i>
                        <span class="truncate">{{ $k->alamat }}, {{ $k->kota }}</span>
                    </div>
                    
                    <!-- Facilities Preview -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($k->fasilitas->take(3) as $fasilitas)
                        <span class="px-2 py-1 text-xs rounded-lg bg-slate-700/50 text-slate-400">
                            @php
                                $iconMap = [
                                    'wifi' => 'wifi',
                                    'laundry' => 'shirt',
                                    'kitchen' => 'utensils',
                                    'bath' => 'bath',
                                    'hot-water' => 'temperature-high',
                                    'motorcycle' => 'motorcycle',
                                    'car' => 'car',
                                    'cctv' => 'video',
                                    'security' => 'shield-alt'
                                ];
                                $displayIcon = $iconMap[$fasilitas->icon] ?? 'check';
                            @endphp
                            <i class="fas fa-{{ $displayIcon }} mr-1"></i>
                            {{ $fasilitas->nama_fasilitas }}
                        </span>
                        @endforeach
                        @if($k->fasilitas->count() > 3)
                        <span class="px-2 py-1 text-xs rounded-lg bg-slate-700/50 text-slate-400">
                            +{{ $k->fasilitas->count() - 3 }} lagi
                        </span>
                        @endif
                    </div>
                    
                    <!-- Price and Action -->
                    <div class="flex justify-between items-center">
                        <div>
                            @if($k->kamar_tersedia_count > 0)
                                <span class="text-xl font-bold text-green-400">
                                    Rp {{ number_format($k->harga_terendah_tersedia, 0, ',', '.') }}
                                </span>
                                <p class="text-xs text-slate-400">mulai dari / 
                                    @if($k->tipe_sewa == 'harian')
                                        hari
                                    @elseif($k->tipe_sewa == 'mingguan')
                                        minggu
                                    @elseif($k->tipe_sewa == 'bulanan')
                                        bulan
                                    @elseif($k->tipe_sewa == 'tahunan')
                                        tahun
                                    @else
                                        bulan
                                    @endif
                                </p>
                            @else
                                <span class="text-lg font-bold text-red-400 bg-red-900/30 px-3 py-1.5 rounded-lg inline-block">
                                    Penuh
                                </span>
                            @endif
                        </div>
                        
                        <a href="{{ route('public.kos.show', $k->id_kos) }}"
                           class="px-4 py-2.5 rounded-xl font-medium transition-colors duration-300 bg-gradient-to-r from-blue-500 to-indigo-500 text-white hover:from-blue-600 hover:to-indigo-600 hover:shadow-lg">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Tidak ada kos yang ditemukan</h3>
                <p class="text-slate-400 mb-6">Coba ubah filter pencarian Anda</p>
                <a href="{{ route('public.kos.index') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Reset pencarian
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-slate-400">
                    Menampilkan {{ $kos->firstItem() }} - {{ $kos->lastItem() }} dari {{ $kos->total() }} kos
                </div>
                <div class="flex gap-2">
                    {{ $kos->links('vendor.pagination.custom-dark') }}
                </div>
            </div>
        </div>                    
        
        <!-- Stats Summary -->
        @if($kos->isNotEmpty())
        <div class="mt-8 bg-slate-800 border border-slate-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">
                <i class="fas fa-chart-bar mr-2 text-blue-400"></i>
                Statistik Pencarian
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4">
                    <p class="text-sm text-slate-400">Total Ditemukan</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $kos->total() }}</p>
                </div>
                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4">
                    <p class="text-sm text-slate-400">Harga Rata-rata <span class="text-xs text-slate-400/70">(tersedia)</span></p>
                    <p class="text-2xl font-bold text-green-400">
                        @php
                            $kosDenganKamarTersedia = $kos->filter(function($k) {
                                return $k->kamar_tersedia_count > 0;
                            });
                            $avgPrice = $kosDenganKamarTersedia->avg('harga_terendah_tersedia');
                        @endphp
                        @if($avgPrice)
                            Rp {{ number_format($avgPrice, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </p>
                </div>

                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4">
                    <p class="text-sm text-slate-400">Kos Penuh</p>
                    <p class="text-2xl font-bold text-red-400">
                        {{ $kos->filter(function($k) { return $k->kamar_tersedia_count == 0; })->count() }}
                    </p>
                </div>
                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4">
                    <p class="text-sm text-slate-400">Rating Tertinggi</p>
                    <p class="text-2xl font-bold text-yellow-400">
                        {{ number_format($kos->max(function($k) { return $k->reviews->avg('rating'); }) ?? 0, 1) }} ⭐
                    </p>
                </div>
                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4">
                    <p class="text-sm text-slate-400">Kamar Tersedia</p>
                    <p class="text-2xl font-bold text-purple-400">
                        {{ $kos->sum(function($k) { return $k->kamar->where('status_kamar', 'tersedia')->count(); }) }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        // Toggle advanced filters
        function toggleAdvancedFilters() {
            const filters = document.getElementById('advancedFilters');
            const arrow = document.getElementById('filter-arrow');
            
            if (filters.classList.contains('hidden')) {
                filters.classList.remove('hidden');
                filters.style.display = 'grid';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                filters.classList.add('hidden');
                filters.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Show advanced filters if any advanced filter is active
        document.addEventListener('DOMContentLoaded', function() {
            const hasAdvancedFilters = <?php echo request()->hasAny(['min_harga', 'max_harga', 'min_rating', 'fasilitas', 'sort']) ? 'true' : 'false'; ?>;
            
            if (hasAdvancedFilters) {
                const filters = document.getElementById('advancedFilters');
                const arrow = document.getElementById('filter-arrow');
                
                filters.classList.remove('hidden');
                filters.style.display = 'grid';
                arrow.style.transform = 'rotate(180deg)';
            }
            
            // Auto submit form when sort changes
            document.querySelector('select[name="sort"]')?.addEventListener('change', function() {
                this.form.submit();
            });
            
            // Handle multiple select display
            document.querySelectorAll('select[multiple]').forEach(select => {
                select.addEventListener('change', function() {
                    const selectedCount = Array.from(this.selectedOptions).length;
                    if (selectedCount > 0) {
                        this.style.backgroundColor = '#0f172a';
                    } else {
                        this.style.backgroundColor = '';
                    }
                });
                
                // Trigger change on load
                select.dispatchEvent(new Event('change'));
            });
        });
    </script>

    <style>
        /* Custom styles for multiple select */
        select[multiple] option:checked {
            background-color: #3b82f6;
            color: white;
        }
        
        select[multiple] option:hover {
            background-color: #475569;
        }
        
        /* Smooth transitions */
        #advancedFilters {
            transition: all 0.3s ease;
        }
        
        /* Custom scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #475569 #0f172a;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0f172a;
            border-radius: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #475569;
            border-radius: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #64748b;
        }
    </style>
@endsection