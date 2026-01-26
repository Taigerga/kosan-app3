@extends('layouts.app')

@section('title', 'Ulasan Kos Saya - AyoKos')

@section('content')
<div class="space-y-6">
    <!-- Alerts -->
    @if(session('success'))
    <div class="bg-gradient-to-r from-green-900/20 to-emerald-900/20 border border-green-800/30 rounded-2xl p-4 mb-6" role="alert">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-green-400"></i>
            </div>
            <div class="flex-1">
                <strong class="font-semibold text-white">Berhasil!</strong>
                <p class="text-green-300 text-sm mt-1">{{ session('success') }}</p>
            </div>
            <button type="button" class="text-green-400 hover:text-green-300" onclick="this.parentElement.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-gradient-to-r from-red-900/20 to-pink-900/20 border border-red-800/30 rounded-2xl p-4 mb-6" role="alert">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-red-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation text-red-400"></i>
            </div>
            <div class="flex-1">
                <strong class="font-semibold text-white">Error!</strong>
                <p class="text-red-300 text-sm mt-1">{{ session('error') }}</p>
            </div>
            <button type="button" class="text-red-400 hover:text-red-300" onclick="this.parentElement.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Breadcrumb -->
    <div class="bg-dark-card/50 border border-dark-border rounded-xl p-4 mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('pemilik.dashboard') }}" class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                        <a href="{{ route('pemilik.reviews.index') }}" class="inline-flex items-center text-sm font-medium text-white">
                            <i class="fas fa-star mr-2"></i>
                            Kelola Reviews
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>   
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>

                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2 flex items-center">
                    <i class="fas fa-star mr-3"></i>
                    Ulasan untuk Kos Saya
                </h1>
                <p class="text-dark-muted">Semua ulasan yang diberikan penghuni untuk kos yang Anda miliki</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-primary-900/30">
                    <i class="fas fa-comment-alt text-primary-400 text-xl"></i>
                </div>
                <span class="text-sm font-medium px-2 py-1 rounded-full bg-primary-900/20 text-primary-300">
                    Total
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-1">{{ $reviews->total() }}</h3>
            <p class="text-sm text-dark-muted">Total Ulasan</p>
        </div>

        <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-yellow-900/30">
                    <i class="fas fa-star text-yellow-400 text-xl"></i>
                </div>
                <span class="text-sm font-medium px-2 py-1 rounded-full bg-yellow-900/20 text-yellow-300">
                    Rata-rata
                </span>
            </div>
             <h3 class="text-2xl font-bold text-white mb-1">{{ number_format($overall_avg_rating ?? 0, 1) }}</h3>
            <p class="text-sm text-dark-muted">Rating Rata-rata</p>
        </div>

        <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-green-900/30">
                    <i class="fas fa-calendar-alt text-green-400 text-xl"></i>
                </div>
                <span class="text-sm font-medium px-2 py-1 rounded-full bg-green-900/20 text-green-300">
                    Terbaru
                </span>
            </div>
             <h3 class="text-2xl font-bold text-white mb-1">
                 {{ $latest_review ? $latest_review->created_at->format('d M Y') : '-' }}
             </h3>
            <p class="text-sm text-dark-muted">Terakhir Diterima</p>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
        @if($reviews->count() > 0)
            <div class="divide-y divide-dark-border">
                @foreach($reviews as $review)
                <div class="p-6 hover:bg-dark-bg/30 transition-all duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                        <!-- Left Content -->
                        <div class="flex-1">
                            <div class="flex items-start space-x-4">
                                <!-- Kos Image -->
                                <div class="flex-shrink-0">
                                    @if($review->kos && $review->kos->foto_utama)
                                        <?php
                                        $filePath = storage_path('app/public/' . $review->kos->foto_utama);
                                        $fileExists = file_exists($filePath);
                                        ?>
                                        @if($fileExists)
                                            <img src="{{ url('storage/' . $review->kos->foto_utama) }}" 
                                                 alt="{{ $review->kos->nama_kos }}" 
                                                 class="w-20 h-20 object-cover rounded-xl border border-dark-border">
                                        @else
                                            <div class="w-20 h-20 bg-gradient-to-br from-dark-border to-dark-bg rounded-xl border border-dark-border flex items-center justify-center">
                                                <i class="fas fa-home text-2xl text-dark-muted"></i>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-dark-border to-dark-bg rounded-xl border border-dark-border flex items-center justify-center">
                                            <i class="fas fa-home text-2xl text-dark-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Review Content -->
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                                        <div>
                                            <h3 class="font-semibold text-white text-lg hover:text-primary-300 transition cursor-pointer">
                                                {{ optional($review->kos)->nama_kos ?? '—' }}
                                            </h3>
                                            <div class="flex items-center text-sm text-dark-muted mt-1">
                                                <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>
                                                {{ optional($review->kos)->alamat ?? '-' }}, {{ optional($review->kos)->kota ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rating -->
                                    <div class="flex items-center flex-wrap gap-3 mb-3">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-sm"></i>
                                                @else
                                                    <i class="far fa-star text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm font-medium text-white">{{ $review->rating }}/5</span>
                                        <span class="text-dark-muted">•</span>
                                        <span class="text-sm text-dark-muted">{{ $review->created_at->format('d M Y H:i') }}</span>
                                    </div>

                                    <!-- Comment -->
                                    <p class="text-dark-text mt-3 bg-dark-bg/50 p-4 rounded-xl border border-dark-border">
                                        <i class="fas fa-quote-left text-primary-400/50 mr-2"></i>
                                        {{ $review->komentar }}
                                    </p>

                                    <!-- Review Image -->
                                    @if($review->foto_review)
                                    <div class="mt-4">
                                        <img src="{{ asset('storage/' . $review->foto_review) }}" 
                                             alt="Foto review" 
                                             class="w-24 h-24 object-cover rounded-xl border-2 border-dark-border hover:border-primary-500 cursor-pointer transition-all duration-300 hover:scale-105"
                                             onclick="openImage('{{ asset('storage/' . $review->foto_review) }}')">
                                        <p class="text-xs text-dark-muted mt-1">Klik untuk memperbesar</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Actions -->
                        <div class="lg:w-48 flex flex-col space-y-3">
                            <a href="{{ route('pemilik.kos.show', optional($review->kos)->id_kos) }}" 
                               class="flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg">
                                <i class="fas fa-eye"></i>
                                <span>Lihat Kos</span>
                            </a>
                            
                            <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-3">
                                <div class="flex items-center space-x-2">
                                    @if($review->penghuni && $review->penghuni->foto_profil)
                                        <?php
                                        $filePath = storage_path('app/public/' . $review->penghuni->foto_profil);
                                        $fileExists = file_exists($filePath);
                                        ?>
                                        @if($fileExists)
                                            <img src="{{ url('storage/' . $review->penghuni->foto_profil) }}" 
                                                 alt="{{ $review->penghuni->nama }}" 
                                                 class="w-8 h-8 rounded-full object-cover border-2 border-green-400">
                                        @else
                                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium text-xs">{{ strtoupper(substr($review->penghuni->nama, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-xs text-dark-muted">Penghuni</p>
                                        <p class="text-sm font-medium text-white">{{ optional($review->penghuni)->nama ?? 'Penghuni' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Table Footer -->
            @if($reviews->hasPages())
                <div class="px-6 py-4 border-t border-dark-border">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-dark-muted">
                            Menampilkan {{ $reviews->firstItem() }} - {{ $reviews->lastItem() }} dari {{ $reviews->total() }} ulasan
                        </div>
                        <div class="flex space-x-2">
                            {{ $reviews->links('vendor.pagination.custom-dark') }}
                        </div>
                    </div>
                </div>
            @endif
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gradient-to-br from-dark-border to-dark-bg rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-comment-slash text-4xl text-dark-muted"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Belum Ada Ulasan</h3>
            <p class="text-dark-muted max-w-md mx-auto mb-6">
                Belum ada ulasan untuk kos Anda. Ulasan akan muncul di sini setelah penghuni memberikan rating.
            </p>
            <a href="{{ route('pemilik.kos.index') }}" 
               class="inline-flex items-center space-x-2 px-6 py-3 bg-dark-bg border border-dark-border text-white rounded-xl hover:border-primary-500 hover:text-primary-300 transition-all duration-300">
                <i class="fas fa-home"></i>
                <span>Kelola Kos Anda</span>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImage()" 
                class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300 transition">
            <i class="fas fa-times"></i>
        </button>
        <img id="modal-image" class="max-w-full max-h-[80vh] rounded-2xl shadow-2xl">
        <div class="text-center text-white text-sm mt-4 opacity-75">
            Klik di luar gambar untuk menutup
        </div>
    </div>
</div>

<script>
    function openImage(src) {
        document.getElementById('modal-image').src = src;
        document.getElementById('image-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImage() {
        document.getElementById('image-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside
    document.getElementById('image-modal').addEventListener('click', function(e) {
        if (e.target.id === 'image-modal') closeImage();
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) { 
        if (e.key === 'Escape') closeImage(); 
    });
</script>

<style>
    /* Custom pagination styling */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
    }
    
    .pagination li {
        margin: 0 2px;
    }
    
    .pagination li a,
    .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border: 1px solid #334155;
        border-radius: 10px;
        background: #1e293b;
        color: #e2e8f0;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .pagination li a:hover {
        background: #334155;
        border-color: #475569;
        color: #ffffff;
    }
    
    .pagination li.active span {
        background: linear-gradient(135deg, #0ea5e9, #6366f1);
        border-color: #0ea5e9;
        color: white;
    }
    
    .pagination li.disabled span {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection