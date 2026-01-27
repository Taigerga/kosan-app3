@extends('layouts.app')

@section('title', $kos->nama_kos . ' - AyoKos')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="bg-dark-card/50 border border-dark-border rounded-xl p-4">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('public.home') }}" class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-primary-300">
                    <i class="fas fa-gauge mr-2"></i>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right mx-2 text-dark-border text-xs"></i>
                    <a href="{{ route('public.kos.index') }}" class="text-sm font-medium text-dark-muted hover:text-primary-300">
                        <i class="fas fa-home mr-2"></i>
                        Kos
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right mx-2 text-dark-border text-xs"></i>
                    <span class="ml-1 text-sm font-medium text-white truncate max-w-xs">
                        <i class="fa-solid fa-tag mr-2"></i>
                        {{ $kos->nama_kos }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Gallery -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
                @if($kos->foto_utama)
                    <img src="{{ asset('storage/' . $kos->foto_utama) }}" 
                         alt="{{ $kos->nama_kos }}" 
                         class="w-full h-64 md:h-80 object-cover hover:scale-105 transition-transform duration-700">
                @else
                    <div class="w-full h-64 md:h-80 bg-gradient-to-br from-dark-border to-dark-bg flex items-center justify-center">
                        <i class="fas fa-home text-6xl text-dark-muted"></i>
                    </div>
                @endif
            </div>

            <!-- Basic Info -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ $kos->nama_kos }}</h1>
                        <div class="flex items-start text-dark-muted mb-4">
                            <i class="fas fa-map-marker-alt text-primary-400 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span class="leading-relaxed">{{ $kos->alamat }}, {{ $kos->kecamatan }}, {{ $kos->kota }}</span>
                        </div>
                        
                        <!-- Rating -->
                        @if($kos->reviews->count() > 0)
                        <div class="flex items-center">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($averageRating))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $averageRating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-lg font-semibold text-white mr-2">{{ number_format($averageRating, 1) }}</span>
                            </div>
                            <span class="text-dark-muted">({{ $totalReviews }} ulasan)</span>
                        </div>
                        @endif
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-primary-900/30 text-primary-300 border border-primary-700/30 capitalize whitespace-nowrap">
                                {{ $kos->jenis_kos }}
                            </span>
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-green-900/30 text-green-300 border border-green-700/30 whitespace-nowrap">
                                {{ $kos->kamar->count() }} Kamar
                            </span>
                        </div>
                        <button onclick="shareKos()" 
                                class="px-3 py-1.5 rounded-full text-sm font-medium bg-blue-900/30 text-blue-300 border border-blue-700/30 hover:bg-blue-800/40 transition-all duration-300 flex items-center whitespace-nowrap">
                            <i class="fas fa-share-alt mr-1"></i>
                            Bagikan
                        </button>
                    </div>
                </div>
                
                <!-- Pemilik Info Card -->
                @if($kos->pemilik)
                <div class="bg-gradient-to-r from-blue-900/20 to-indigo-900/20 border border-blue-800/30 rounded-xl p-4 mt-4">
                    <div class="flex items-center space-x-4">
                        @if($kos->pemilik->foto_profil)
                            <?php
                            $filePath = storage_path('app/public/' . $kos->pemilik->foto_profil);
                            $fileExists = file_exists($filePath);
                            ?>
                            @if($fileExists)
                                <img src="{{ url('storage/' . $kos->pemilik->foto_profil) }}" 
                                     alt="{{ $kos->pemilik->nama }}" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-blue-400">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">{{ strtoupper(substr($kos->pemilik->nama, 0, 1)) }}</span>
                                </div>
                            @endif
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-tie text-white text-lg"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-lg">Pemilik Kos</h3>
                            <p class="text-sm text-blue-300">{{ $kos->pemilik->nama }}</p>
                            <p class="text-xs text-dark-muted mt-1">Terverifikasi • {{ $kos->created_at->format('Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="text-xs text-green-400 font-medium">Aktif</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Description -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-file-alt text-primary-400 mr-3"></i>
                    Deskripsi Kos
                </h2>
                <div class="prose prose-invert max-w-none">
                    <p class="text-dark-text leading-relaxed whitespace-pre-line">{{ $kos->deskripsi }}</p>
                </div>
            </div>

            <!-- Facilities -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-th-large text-primary-400 mr-3"></i>
                    Fasilitas
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($kos->fasilitas->groupBy('kategori') as $kategori => $fasilitasList)
                    <div>
                        <h3 class="font-semibold text-primary-300 mb-4 capitalize text-lg">
                            {{ str_replace('_', ' ', $kategori) }}
                        </h3>
                        <div class="space-y-3">
                            @foreach($fasilitasList as $fasilitas)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-primary-900/20 flex items-center justify-center">
                                    @switch($fasilitas->kategori)
                                        @case('umum') <i class="fas fa-wifi text-primary-400"></i> @break
                                        @case('kamar_mandi') <i class="fas fa-shower text-blue-400"></i> @break
                                        @case('dapur') <i class="fas fa-utensils text-green-400"></i> @break
                                        @case('parkir') <i class="fas fa-parking text-yellow-400"></i> @break
                                        @case('keamanan') <i class="fas fa-shield-alt text-red-400"></i> @break
                                        @default <i class="fas fa-check text-green-400"></i>
                                    @endswitch
                                </div>
                                <span class="text-dark-text">{{ $fasilitas->nama_fasilitas }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Available Rooms -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-door-open text-primary-400 mr-3"></i>
                    Kamar Tersedia
                </h2>
                <div class="space-y-6">
                    @forelse($kos->kamar as $kamar)
                    <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-6 hover:border-primary-500/50 transition-all duration-300">
                        <div class="flex flex-col lg:flex-row gap-6">
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">Kamar {{ $kamar->nomor_kamar }}</h3>
                                        <div class="flex items-center space-x-3 mt-2">
                                            <span class="text-sm text-dark-muted bg-dark-border/50 px-3 py-1 rounded-lg">
                                                {{ $kamar->tipe_kamar }}
                                            </span>
                                            <span class="text-sm text-dark-muted bg-dark-border/50 px-3 py-1 rounded-lg">
                                                {{ $kamar->luas_kamar }}
                                            </span>
                                            <span class="text-sm text-dark-muted bg-dark-border/50 px-3 py-1 rounded-lg">
                                                Untuk {{ $kamar->kapasitas }} orang
                                            </span>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-green-900/30 text-green-300">
                                        Tersedia
                                    </span>
                                </div>
                                
                                @php
                                    $fasilitasKamar = $kamar->fasilitas_kamar;
                                    $maxAttempts = 3;
                                    $attempts = 0;
                                    
                                    while (is_string($fasilitasKamar) && $attempts < $maxAttempts) {
                                        $decoded = json_decode($fasilitasKamar, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                            $fasilitasKamar = $decoded;
                                        } else {
                                            break;
                                        }
                                        $attempts++;
                                    }
                                    
                                    if (is_string($fasilitasKamar)) {
                                        $fasilitasKamar = [$fasilitasKamar];
                                    }
                                    
                                    $fasilitasKamar = is_array($fasilitasKamar) ? $fasilitasKamar : [];
                                    $fasilitasKamar = array_filter($fasilitasKamar);
                                @endphp

                                @if(count($fasilitasKamar) > 0)
                                <div class="mb-4">
                                    <h4 class="font-medium text-primary-300 mb-3">Fasilitas Kamar:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($fasilitasKamar as $fasilitas)
                                            @if(is_string($fasilitas))
                                            <span class="px-3 py-1.5 rounded-lg text-sm bg-primary-900/20 text-primary-300 border border-primary-700/30">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                {{ $fasilitas }}
                                            </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="lg:text-right lg:border-l lg:border-dark-border lg:pl-6 lg:min-w-48">
                                <div class="mb-4">
                                    <p class="text-3xl font-bold text-green-400 mb-1">
                                        Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-dark-muted">per 
                                        @if($kos->tipe_sewa == 'harian')
                                            hari
                                        @elseif($kos->tipe_sewa == 'mingguan')
                                            minggu
                                        @elseif($kos->tipe_sewa == 'bulanan')
                                            bulan
                                        @elseif($kos->tipe_sewa == 'tahunan')
                                            tahun
                                        @else
                                            bulan
                                        @endif
                                    </p>
                                </div>
                                @auth('penghuni')
                                    @php
                                        $user = Auth::guard('penghuni')->user();
                                        $isAllowed = true;
                                        if ($kos->jenis_kos == 'putra' && $user->jenis_kelamin != 'L') $isAllowed = false;
                                        if ($kos->jenis_kos == 'putri' && $user->jenis_kelamin != 'P') $isAllowed = false;
                                    @endphp

                                    @if($isAllowed)
                                    <a href="{{ route('penghuni.kontrak.create', $kos->id_kos) }}" 
                                       class="w-full lg:w-auto px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 font-semibold inline-block transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                                        <i class="fas fa-check mr-2"></i>
                                        Pilih Kamar Ini
                                    </a>
                                    @else
                                    <button disabled 
                                            class="w-full lg:w-auto px-6 py-3 bg-red-900/20 text-red-400 border border-red-800/50 rounded-xl font-semibold inline-block cursor-not-allowed transition-all duration-300">
                                        <i class="fas fa-ban mr-2"></i>
                                        Khusus {{ ucfirst($kos->jenis_kos) }}
                                    </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="w-full lg:w-auto px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 font-semibold inline-block transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Login untuk Pesan
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-dark-border/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-door-closed text-3xl text-dark-muted"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mt-4">Tidak Ada Kamar Tersedia</h3>
                        <p class="text-dark-muted mt-2">Semua kamar sudah terisi untuk saat ini.</p>
                        <a href="{{ route('public.kos.index') }}" 
                           class="inline-block mt-6 px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300">
                            <i class="fas fa-search mr-2"></i>
                            Cari Kos Lainnya
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Rules -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-clipboard-list text-primary-400 mr-3"></i>
                    Peraturan Kos
                </h2>
                <div class="bg-dark-bg/50 rounded-xl p-5 border border-dark-border">
                    <pre class="whitespace-pre-wrap font-sans text-dark-text text-sm leading-relaxed">{{ $kos->peraturan }}</pre>
                </div>
            </div>

            <!-- Reviews -->
            @if($kos->reviews->count() > 0)
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-comments text-primary-400 mr-3"></i>
                    Ulasan Penghuni
                </h2>
                
                <!-- Rating Summary -->
                <div class="bg-dark-bg/50 rounded-xl p-6 mb-8 border border-dark-border">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="text-center md:text-left">
                            <div class="text-5xl font-bold text-white mb-2">{{ number_format($averageRating, 1) }}</div>
                            <div class="flex justify-center md:justify-start text-yellow-400 text-xl mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($averageRating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $averageRating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-dark-muted">Berdasarkan {{ $totalReviews }} ulasan</div>
                        </div>
                        <div class="w-full md:w-64">
                            @for($rating = 5; $rating >= 1; $rating--)
                            @php
                                $count = $kos->reviews->where('rating', $rating)->count();
                                $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                            @endphp
                            <div class="flex items-center mb-3">
                                <span class="text-sm text-dark-muted w-8">{{ $rating }} <i class="fas fa-star text-yellow-400"></i></span>
                                <div class="flex-1 bg-dark-border rounded-full h-2 mx-3">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm text-dark-muted w-8 text-right">{{ $count }}</span>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="space-y-6">
                    @foreach($kos->reviews as $review)
                    <div class="border-b border-dark-border pb-6 last:border-b-0">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                            <div class="flex items-center space-x-4">
                                @if($review->penghuni->foto_profil)
                                    <?php
                                    $filePath = storage_path('app/public/' . $review->penghuni->foto_profil);
                                    $fileExists = file_exists($filePath);
                                    ?>
                                    @if($fileExists)
                                        <img src="{{ url('storage/' . $review->penghuni->foto_profil) }}" 
                                             alt="{{ $review->penghuni->nama }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-primary-400">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500/20 to-indigo-500/20 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-lg">
                                                {{ strtoupper(substr($review->penghuni->nama, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500/20 to-indigo-500/20 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-lg">
                                            {{ strtoupper(substr($review->penghuni->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-semibold text-white">{{ $review->penghuni->nama }}</h4>
                                    <p class="text-sm text-dark-muted">
                                        {{ $review->created_at->format('d M Y') }}
                                        @if($review->updated_at->gt($review->created_at))
                                        <span class="text-xs text-dark-muted ml-1">(diedit)</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                
                                @auth('penghuni')
                                    @if(Auth::guard('penghuni')->id() == $review->id_penghuni)
                                    <div class="relative review-action-btn">
                                        <button type="button" 
                                                class="text-dark-muted hover:text-white focus:outline-none px-2 py-1 rounded-lg hover:bg-dark-border/50">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="absolute right-0 mt-2 w-40 bg-dark-card border border-dark-border rounded-xl shadow-xl hidden group-hover:block z-10">
                                            <a href="{{ route('penghuni.reviews.edit', $review->id_review) }}" 
                                               class="flex items-center px-4 py-3 text-sm text-dark-text hover:bg-dark-border/50 hover:text-white transition">
                                                <i class="fas fa-edit mr-3 text-primary-400"></i>
                                                Edit Review
                                            </a>
                                            <form action="{{ route('penghuni.reviews.destroy', $review->id_review) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirmDeleteReview()">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="flex items-center w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-red-900/20 transition">
                                                    <i class="fas fa-trash mr-3"></i>
                                                    Hapus Review
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                        <p class="text-dark-text leading-relaxed whitespace-pre-line">{{ $review->komentar }}</p>
                        
                        @if($review->foto_review)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $review->foto_review) }}" 
                                 alt="Foto review" 
                                 class="w-40 h-40 object-cover rounded-xl cursor-pointer hover:scale-105 transition-transform duration-300"
                                 onclick="openImageModal('{{ asset('storage/' . $review->foto_review) }}')">
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-8 text-center">
                <div class="w-20 h-20 bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-comment text-3xl text-yellow-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mt-4">Belum Ada Ulasan</h3>
                <p class="text-dark-muted mt-2">Jadilah yang pertama memberikan ulasan untuk kos ini.</p>
                
                @auth('penghuni')
                    @php
                        $penghuni = Auth::guard('penghuni')->user();
                        $canReview = false;
                        $hasReviewed = false;
                        
                        $kontrak = \App\Models\KontrakSewa::where('id_penghuni', $penghuni->id_penghuni)
                            ->where('id_kos', $kos->id_kos)
                            ->whereIn('status_kontrak', ['aktif', 'selesai'])
                            ->first();
                            
                        if ($kontrak) {
                            $canReview = true;
                            
                            $existingReview = \App\Models\Review::where('id_penghuni', $penghuni->id_penghuni)
                                ->where('id_kos', $kos->id_kos)
                                ->first();
                                
                            if ($existingReview) {
                                $hasReviewed = true;
                            }
                        }
                    @endphp
                    
                    @if($canReview && !$hasReviewed)
                    <div class="mt-6">
                        <a href="{{ route('penghuni.reviews.create', $kos->id_kos) }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 font-semibold transition-all duration-300 hover:shadow-lg">
                            <i class="fas fa-star mr-2"></i>
                            Beri Review Pertama
                        </a>
                    </div>
                    @elseif($hasReviewed)
                    <p class="text-green-400 mt-6">✅ Anda sudah memberikan review untuk kos ini.</p>
                    @endif
                @endauth
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Action Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-calendar-check text-primary-400 mr-3"></i>
                    Informasi Booking
                </h2>
                
                <!-- Price Range -->
                <div class="mb-8">
                    <h3 class="font-semibold text-primary-300 mb-3">Harga Mulai Dari:</h3>
                    @if($kos->kamar->min('harga') > 0)
                        <p class="text-4xl font-bold text-green-400">
                            Rp {{ number_format($kos->kamar->min('harga'), 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-4xl font-bold text-red-400">
                            Penuh
                        </p>
                    @endif
                    <p class="text-sm text-dark-muted mt-1">per 
                        @if($kos->tipe_sewa == 'harian')
                            hari
                        @elseif($kos->tipe_sewa == 'mingguan')
                            minggu
                        @elseif($kos->tipe_sewa == 'bulanan')
                            bulan
                        @elseif($kos->tipe_sewa == 'tahunan')
                            tahun
                        @else
                            bulan
                        @endif
                    </p>
                </div>

                <!-- Kos Info -->
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-dark-muted">Jenis Kos:</span>
                        <span class="font-semibold text-white capitalize">{{ $kos->jenis_kos }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-dark-muted">Tipe Sewa:</span>
                        <span class="font-semibold text-white capitalize">{{ $kos->tipe_sewa }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-dark-muted">Kamar Tersedia:</span>
                        <span class="font-semibold text-green-400">{{ $kos->kamar->count() }} kamar</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-dark-muted">Lokasi:</span>
                        <span class="font-semibold text-white text-right">{{ $kos->kota }}, {{ $kos->provinsi }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    @auth('penghuni')
                        @php
                            $user = Auth::guard('penghuni')->user();
                            $isAllowed = true;
                            if ($kos->jenis_kos == 'putra' && $user->jenis_kelamin != 'L') $isAllowed = false;
                            if ($kos->jenis_kos == 'putri' && $user->jenis_kelamin != 'P') $isAllowed = false;
                        @endphp

                        @if($kos->kamar->count() > 0)
                            @if($isAllowed)
                            <a href="{{ route('penghuni.kontrak.create', $kos->id_kos) }}" 
                               class="w-full px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white text-center rounded-xl hover:from-primary-600 hover:to-indigo-600 font-semibold block transition-all duration-300 hover:shadow-lg">
                                <i class="fas fa-home mr-2"></i>
                                Daftar Sekarang
                            </a>
                            @else
                            <button disabled 
                                    class="w-full px-6 py-3 bg-red-900/20 text-red-400 border border-red-800/50 text-center rounded-xl font-semibold block cursor-not-allowed transition-all duration-300">
                                <i class="fas fa-ban mr-2"></i>
                                Maaf, Kos ini Khusus {{ ucfirst($kos->jenis_kos) }}
                            </button>
                            @endif
                        @else
                        <button disabled class="w-full px-6 py-3 bg-gray-900/50 text-dark-muted text-center rounded-xl font-semibold block cursor-not-allowed border border-dark-border">
                            <i class="fas fa-times mr-2"></i>
                            Penuh
                        </button>
                        @endif
                    @else
                    <div class="text-center">
                        <p class="text-dark-muted mb-4">Login untuk mendaftar</p>
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" 
                               class="w-full px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 block transition-all duration-300">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </a>
                            <a href="{{ route('register') }}" 
                               class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 block transition-all duration-300">
                                <i class="fas fa-user-plus mr-2"></i>
                                Daftar Akun Baru
                            </a>
                        </div>
                    </div>
                    @endauth
                </div>

                <!-- Button Review -->
                @auth('penghuni')
                    @php
                        $penghuni = Auth::guard('penghuni')->user();
                        $canReview = false;
                        $hasReviewed = false;
                        
                        $kontrak = \App\Models\KontrakSewa::where('id_penghuni', $penghuni->id_penghuni)
                            ->where('id_kos', $kos->id_kos)
                            ->whereIn('status_kontrak', ['aktif', 'selesai'])
                            ->first();
                            
                        if ($kontrak) {
                            $canReview = true;
                            
                            $existingReview = \App\Models\Review::where('id_penghuni', $penghuni->id_penghuni)
                                ->where('id_kos', $kos->id_kos)
                                ->first();
                                
                            if ($existingReview) {
                                $hasReviewed = true;
                            }
                        }
                    @endphp
                    
                    @if($canReview && !$hasReviewed)
                    <div class="mt-8 pt-8 border-t border-dark-border">
                        <p class="text-sm text-dark-muted mb-4">Sudah pernah tinggal di kos ini?</p>
                        <a href="{{ route('penghuni.reviews.create', $kos->id_kos) }}" 
                           class="w-full px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-center rounded-xl hover:from-yellow-600 hover:to-orange-600 font-semibold block transition-all duration-300 hover:shadow-lg">
                            <i class="fas fa-star mr-2"></i>
                            Beri Review
                        </a>
                    </div>
                    @elseif($hasReviewed)
                    <div class="mt-8 pt-8 border-t border-dark-border">
                        <p class="text-sm text-dark-muted mb-4">Review Anda:</p>
                        <a href="{{ route('penghuni.reviews.edit', $existingReview->id_review) }}" 
                           class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-center rounded-xl hover:from-green-600 hover:to-emerald-600 font-semibold block transition-all duration-300 hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Review Anda
                        </a>
                        <p class="text-xs text-dark-muted mt-3 text-center">
                            Lihat semua review Anda di 
                            <a href="{{ route('penghuni.reviews.history') }}" class="text-primary-300 hover:text-primary-400 hover:underline">
                                History Review
                            </a>
                        </p>
                    </div>
                    @endif
                @endauth

                <!-- Contact Info -->
                <div class="mt-8 pt-8 border-t border-dark-border">
                    <h3 class="font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-headset text-primary-400 mr-2"></i>
                        Butuh Bantuan?
                    </h3>
                    <div class="space-y-3">
                        @if($kos->pemilik)
                            @php
                                $waNumber = $kos->pemilik->no_hp;
                                if (str_starts_with($waNumber, '0')) {
                                    $waNumber = '62' . substr($waNumber, 1);
                                } elseif (str_starts_with($waNumber, '+')) {
                                    $waNumber = substr($waNumber, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $waNumber }}?text=Halo%20{{ urlencode($kos->pemilik->nama) }},%20saya%20tertarik%20dengan%20kos%20{{ urlencode($kos->nama_kos) }}%20di%20{{ urlencode($kos->alamat) }}" 
                               target="_blank"
                               class="flex items-center justify-center space-x-3 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 group">
                                <i class="fab fa-whatsapp text-lg"></i>
                                <span>Hubungi via WhatsApp</span>
                            </a>
                            <button onclick="showContactModal()" 
                                    class="w-full px-4 py-3 bg-dark-bg/50 text-white rounded-xl hover:bg-dark-border/50 transition-all duration-300 border border-dark-border">
                                <i class="fas fa-phone mr-2"></i>
                                Telepon Pemilik
                            </button>
                        @else
                            <div class="bg-dark-bg/50 rounded-xl p-4 text-center border border-dark-border">
                                <p class="text-dark-muted text-sm">Informasi kontak tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Location Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-map-marker-alt text-primary-400 mr-3"></i>
                    Lokasi
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-lg bg-primary-900/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-location-dot text-primary-400"></i>
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ $kos->alamat }}</p>
                            <p class="text-dark-muted text-sm mt-1">{{ $kos->kecamatan }}, {{ $kos->kota }}</p>
                            <p class="text-dark-muted text-sm">{{ $kos->provinsi }} - {{ $kos->kode_pos }}</p>
                        </div>
                    </div>
                    
                    @if($kos->latitude && $kos->longitude)
                    <div class="mt-4">
                        <div id="map" class="h-72 rounded-xl z-0"></div>
                        <div class="flex justify-between mt-4">
                            <button id="locate-btn" 
                                    class="text-sm text-primary-300 hover:text-primary-400 transition flex items-center">
                                <i class="fas fa-location-crosshairs mr-2"></i>
                                Lokasi Saya
                            </button>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $kos->latitude }},{{ $kos->longitude }}" 
                               target="_blank"
                               class="text-sm text-green-400 hover:text-green-300 transition flex items-center">
                                <i class="fas fa-directions mr-2"></i>
                                Petunjuk Arah
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="bg-dark-bg/50 rounded-xl p-6 text-center border border-dark-border">
                        <i class="fas fa-map text-3xl text-dark-muted mb-3"></i>
                        <span class="text-dark-muted text-sm">📍 Peta lokasi tidak tersedia</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Similar Kos -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-building text-primary-400 mr-3"></i>
                    Kos Serupa
                </h2>
                <div class="space-y-4">
                    @forelse($similarKos as $similar)
                    <a href="{{ route('public.kos.show', $similar->id_kos) }}" 
                       class="block bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-primary-500/50 transition-all duration-300">
                        <div class="flex space-x-4">
                            @if($similar->foto_utama)
                                <img src="{{ asset('storage/' . $similar->foto_utama) }}" 
                                     alt="{{ $similar->nama_kos }}" 
                                     class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-dark-border to-dark-bg rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-home text-dark-muted"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-white text-sm truncate">{{ $similar->nama_kos }}</h4>
                                <p class="text-green-400 font-bold text-sm mt-1">
                                    @if($similar->kamar->count() > 0)
                                        Rp {{ number_format($similar->kamar->min('harga'), 0, ',', '.') }}
                                    @else
                                        Penuh
                                    @endif
                                </p>
                                <div class="flex items-center mt-2">
                                    <span class="text-xs px-2 py-1 rounded bg-dark-border/50 text-dark-muted capitalize">
                                        {{ $similar->jenis_kos }}
                                    </span>
                                    <span class="text-xs text-dark-muted ml-2">{{ $similar->kota }}</span>
                                    @if($similar->id_pemilik == $kos->id_pemilik)
                                        <span class="text-xs px-2 py-1 rounded bg-blue-900/30 text-blue-300 ml-2">
                                            <i class="fas fa-user-tie mr-1"></i>Pemilik Sama
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-dark-border/30 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-building text-xl text-dark-muted"></i>
                        </div>
                        <p class="text-sm text-dark-muted">Tidak ada kos serupa saat ini</p>
                    </div>
                    @endforelse
                </div>
                <a href="{{ route('public.kos.index') }}?jenis_kos={{ $kos->jenis_kos }}&kota={{ $kos->kota }}" 
                   class="block text-center mt-6 text-primary-300 hover:text-primary-400 text-sm font-medium transition">
                    <span>Lihat lebih banyak</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" 
                class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300 bg-black/50 rounded-full w-10 h-10 flex items-center justify-center transition">
            <i class="fas fa-times"></i>
        </button>
        <img id="modal-image" class="max-w-full max-h-screen rounded-xl shadow-2xl">
    </div>
</div>

<script>
    // Image modal functions
    function openImageModal(src) {
        document.getElementById('modal-image').src = src;
        document.getElementById('image-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        document.getElementById('image-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal on background click
    document.getElementById('image-modal').addEventListener('click', function(e) {
        if (e.target.id === 'image-modal') {
            closeImageModal();
        }
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    // Confirm delete review
    function confirmDeleteReview() {
        return confirm('Apakah Anda yakin ingin menghapus review ini? Tindakan ini tidak dapat dibatalkan.');
    }

    // Share function
    function shareKos() {
        const url = window.location.href;
        
        // Try to use Web Share API first (for mobile)
        if (navigator.share) {
            navigator.share({
                title: '{{ $kos->nama_kos }}',
                text: 'Lihat kos ini: {{ $kos->nama_kos }} - {{ $kos->alamat }}, {{ $kos->kota }}',
                url: url
            }).catch(err => {
                // If share fails, fallback to copy
                copyToClipboard(url);
            });
        } else {
            // Fallback to copy to clipboard
            copyToClipboard(url);
        }
    }

    // Copy to clipboard function
    function copyToClipboard(text) {
        // Create a temporary textarea element
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            document.execCommand('copy');
            showNotification('Link berhasil disalin!');
        } catch (err) {
            // Fallback for modern browsers
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    showNotification('Link berhasil disalin!');
                }).catch(() => {
                    showNotification('Gagal menyalin link', 'error');
                });
            } else {
                showNotification('Gagal menyalin link', 'error');
            }
        } finally {
            document.body.removeChild(textarea);
        }
    }

    // Show notification
    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-lg z-50 flex items-center space-x-2 transition-all duration-300 transform translate-x-full`;
        
        if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
            notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
        } else {
            notification.classList.add('bg-red-500', 'text-white');
            notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
        }
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Leaflet map initialization
    document.addEventListener('DOMContentLoaded', function() {
        @if($kos->latitude && $kos->longitude)
        const map = L.map('map').setView([{{ $kos->latitude }}, {{ $kos->longitude }}], 15);
        
        // Add OpenStreetMap tiles with dark theme
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
            className: 'dark-tiles'
        }).addTo(map);
        
        // Custom icon with gradient
        const houseIcon = L.divIcon({
            html: `
                <div class="relative">
                    <div class="absolute -inset-2 bg-primary-500/20 rounded-full animate-ping"></div>
                    <div class="relative bg-gradient-to-br from-primary-500 to-indigo-500 rounded-full w-12 h-12 flex items-center justify-center shadow-lg border-2 border-white">
                        <i class="fas fa-home text-white"></i>
                    </div>
                </div>
            `,
            iconSize: [48, 48],
            iconAnchor: [24, 48],
            popupAnchor: [0, -48],
            className: 'custom-house-icon'
        });
        
        // Add marker for kos location
        const marker = L.marker([{{ $kos->latitude }}, {{ $kos->longitude }}], {
            icon: houseIcon
        }).addTo(map);
        
        // Popup content
        marker.bindPopup(`
            <div class="p-3 bg-dark-card border border-dark-border rounded-xl shadow-xl">
                <h3 class="font-bold text-white text-base mb-2">{{ $kos->nama_kos }}</h3>
                <p class="text-dark-text text-sm mb-3">{{ $kos->alamat }}</p>
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $kos->latitude }},{{ $kos->longitude }}" 
                   target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 text-sm transition">
                    <i class="fas fa-directions mr-2"></i>
                    Arah ke sini
                </a>
            </div>
        `);
        
        // Add user location button
        const locateBtn = document.getElementById('locate-btn');
        let userMarker = null;
        
        locateBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                locateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...';
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        
                        // Remove previous user marker
                        if (userMarker) {
                            map.removeLayer(userMarker);
                        }
                        
                        // Add user marker
                        const userIcon = L.divIcon({
                            html: `
                                <div class="relative">
                                    <div class="absolute -inset-2 bg-blue-500/20 rounded-full animate-ping"></div>
                                    <div class="relative bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full w-10 h-10 flex items-center justify-center shadow-lg border-2 border-white">
                                        <i class="fas fa-location-dot text-white"></i>
                                    </div>
                                </div>
                            `,
                            iconSize: [40, 40],
                            iconAnchor: [20, 40],
                            className: 'custom-user-icon'
                        });
                        
                        userMarker = L.marker([userLat, userLng], {
                            icon: userIcon
                        }).addTo(map);
                        
                        userMarker.bindPopup(`
                            <div class="p-2 bg-dark-card border border-dark-border rounded-lg">
                                <p class="text-white text-sm font-medium">Lokasi Anda</p>
                            </div>
                        `).openPopup();
                        
                        // Fit bounds to show both markers
                        const bounds = L.latLngBounds([
                            [userLat, userLng],
                            [{{ $kos->latitude }}, {{ $kos->longitude }}]
                        ]);
                        map.fitBounds(bounds, { padding: [60, 60] });
                        
                        locateBtn.innerHTML = '<i class="fas fa-location-crosshairs mr-2"></i>Lokasi Saya';
                    },
                    function(error) {
                        alert('Tidak dapat mengakses lokasi Anda. Pastikan izin lokasi diaktifkan.');
                        console.error('Geolocation error:', error);
                        locateBtn.innerHTML = '<i class="fas fa-location-crosshairs mr-2"></i>Lokasi Saya';
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Browser tidak mendukung geolocation.');
            }
        });
        
        // Add scale control
        L.control.scale().addTo(map);
        
        // Add custom CSS for map
        const style = document.createElement('style');
        style.textContent = `
            .custom-house-icon {
                background: none !important;
                border: none !important;
            }
            .custom-user-icon {
                background: none !important;
                border: none !important;
            }
            .leaflet-popup-content {
                margin: 0 !important;
            }
            .leaflet-popup-content-wrapper {
                background: transparent !important;
                box-shadow: none !important;
                border: none !important;
            }
            .leaflet-control-attribution {
                background: rgba(0,0,0,0.5) !important;
                color: #94a3b8 !important;
                font-size: 10px !important;
            }
            .leaflet-control-scale-line {
                background: rgba(0,0,0,0.5) !important;
                color: #94a3b8 !important;
                border: 1px solid #334155 !important;
            }
        `;
        document.head.appendChild(style);
        @endif
        
        // Initialize Contact Modal
        @if($kos->pemilik)
        const contactModal = new Modal('contactModal');
        window.contactModal = contactModal;
        
        window.showContactModal = function() {
            contactModal.show();
        };
        @endif
        
        // Hover effect for review actions
        document.querySelectorAll('.review-action-btn button').forEach(button => {
            button.addEventListener('mouseenter', function() {
                const menu = this.nextElementSibling;
                if (menu && menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                }
            });
            
            button.addEventListener('mouseleave', function() {
                const menu = this.nextElementSibling;
                setTimeout(() => {
                    if (menu && !menu.matches(':hover')) {
                        menu.classList.add('hidden');
                    }
                }, 100);
            });
            
            const menu = button.nextElementSibling;
            if (menu) {
                menu.addEventListener('mouseenter', function() {
                    this.classList.remove('hidden');
                });
                
                menu.addEventListener('mouseleave', function() {
                    this.classList.add('hidden');
                });
            }
        });
    });
</script>

<style>
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3);
        border-color: var(--primary-500);
    }
    
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
    
    .transition-transform {
        transition: transform 0.3s ease;
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
    
    .animate-ping {
        animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    
    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    /* Custom scrollbar for modal */
    #image-modal img {
        max-height: 80vh;
    }
</style>

@if($kos->pemilik)
<!-- Contact Owner Modal -->
<div id="contactModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-modal-close></div>
    <div class="relative bg-dark-card border border-dark-border rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl">
        <div class="border-b border-dark-border p-5">
            <h5 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-headset text-primary-400 mr-2"></i>
                Hubungi Pemilik
            </h5>
        </div>
        <div class="p-6 text-center">
            <div class="mb-4 inline-block">
                @if($kos->pemilik->foto_profil)
                    <img src="{{ url('storage/' . $kos->pemilik->foto_profil) }}" 
                         alt="{{ $kos->pemilik->nama }}" 
                         class="w-20 h-20 rounded-full object-cover border-4 border-primary-500/20 mx-auto">
                @else
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-full flex items-center justify-center mx-auto shadow-lg">
                        <i class="fas fa-user-tie text-white text-3xl"></i>
                    </div>
                @endif
            </div>
            <h5 class="text-xl font-bold text-white mb-1">{{ $kos->pemilik->nama }}</h5>
            <p class="text-dark-muted text-sm mb-6">Pemilik {{ $kos->nama_kos }}</p>
            
            <div class="bg-dark-bg/50 rounded-2xl p-4 border border-dark-border mb-6">
                <p class="text-xs text-dark-muted uppercase tracking-wider font-semibold mb-2">Nomor Telepon</p>
                <p class="text-2xl font-bold text-primary-400 tracking-widest">{{ $kos->pemilik->no_hp }}</p>
            </div>
            
            <div class="grid grid-cols-1 gap-3">
                <a href="tel:{{ $kos->pemilik->no_hp }}" 
                   class="flex items-center justify-center space-x-3 px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg font-semibold group">
                    <i class="fas fa-phone-alt group-hover:rotate-12 transition-transform"></i>
                    <span>Telepon Sekarang</span>
                </a>
                
                @php
                    $waNumber = $kos->pemilik->no_hp;
                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    } elseif (str_starts_with($waNumber, '+')) {
                        $waNumber = substr($waNumber, 1);
                    }
                @endphp
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20{{ urlencode($kos->pemilik->nama) }},%20saya%20ingin%20bertanya%20tentang%20kos%20{{ urlencode($kos->nama_kos) }}" 
                   target="_blank"
                   class="flex items-center justify-center space-x-3 px-6 py-3 bg-green-900/20 text-green-400 border border-green-800/30 rounded-xl hover:bg-green-800/30 transition-all duration-300 font-semibold group">
                    <i class="fab fa-whatsapp text-lg group-hover:scale-110 transition-transform"></i>
                    <span>WhatsApp Pemilik</span>
                </a>
            </div>
        </div>
        <div class="p-4 bg-dark-bg/30 text-center border-t border-dark-border">
            <button type="button" class="modal-close-btn text-dark-muted hover:text-white transition-colors text-sm font-medium">
                Kembali
            </button>
        </div>
    </div>
</div>
@endif
@endsection