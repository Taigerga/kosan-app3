@extends('layouts.app')

@section('title', 'History Review - AyoKos')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="bg-dark-card/50 border border-dark-border rounded-xl p-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('penghuni.dashboard') }}" class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-primary-300">
                        <i class="fas fa-gauge mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                        <a href="{{ route('penghuni.reviews.history') }}" class="inline-flex items-center text-sm font-medium text-white">
                            <i class="fas fa-star mr-2"></i>
                            Riwayat Review
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                    <i class="fas fa-star mr-3"></i>
                    History Review
                </h1>
                <p class="text-dark-muted">Review yang telah Anda berikan untuk kos-kos</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('public.kos.index') }}" 
                   class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Cari Kos
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-900/30 border border-green-800/50 text-green-300 px-4 py-3 rounded-xl flex items-center justify-between" role="alert">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-green-900/50 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-check text-green-400"></i>
            </div>
            <div>
                <span class="font-medium">Berhasil!</span>
                <span class="block text-sm text-green-300/80">{{ session('success') }}</span>
            </div>
        </div>
        <button type="button" class="text-green-400 hover:text-green-300" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-900/30 border border-red-800/50 text-red-300 px-4 py-3 rounded-xl flex items-center justify-between" role="alert">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-red-900/50 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-times text-red-400"></i>
            </div>
            <div>
                <span class="font-medium">Error!</span>
                <span class="block text-sm text-red-300/80">{{ session('error') }}</span>
            </div>
        </div>
        <button type="button" class="text-red-400 hover:text-red-300" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-yellow-900/30 border border-yellow-800/50 text-yellow-300 px-4 py-3 rounded-xl flex items-center justify-between" role="alert">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-yellow-900/50 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div>
                <span class="font-medium">Perhatian!</span>
                <span class="block text-sm text-yellow-300/80">{{ session('warning') }}</span>
            </div>
        </div>
        <button type="button" class="text-yellow-400 hover:text-yellow-300" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Reviews -->
        <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-blue-900/30">
                    <i class="fas fa-star text-blue-400 text-xl"></i>
                </div>
                <span class="text-sm font-medium px-2 py-1 rounded-full bg-blue-900/20 text-blue-300">
                    {{ $reviews->total() }}
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-1">{{ $reviews->total() }}</h3>
            <p class="text-sm text-dark-muted">Total Review</p>
        </div>
        
        <!-- Average Rating -->
        <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-yellow-900/30">
                    <i class="fas fa-chart-line text-yellow-400 text-xl"></i>
                </div>
                <span class="text-sm font-medium px-2 py-1 rounded-full bg-yellow-900/20 text-yellow-300">
                    Rata-rata
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-1">
                {{ number_format($reviews->avg('rating') ?? 0, 1) }}
            </h3>
            <p class="text-sm text-dark-muted">Rating Rata-rata</p>
        </div>
        
        <!-- Last Updated -->
        <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-green-900/30">
                    <i class="fas fa-calendar-alt text-green-400 text-xl"></i>
                </div>
                <span class="text-sm font-medium px-2 py-1 rounded-full bg-green-900/20 text-green-300">
                    Terbaru
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-1">
                {{ $reviews->first() ? $reviews->first()->updated_at->format('d M') : '-' }}
            </h3>
            <p class="text-sm text-dark-muted">Terakhir Diupdate</p>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
        @if($reviews->count() > 0)
            <div class="divide-y divide-dark-border">
                @foreach($reviews as $review)
                <div class="p-6 hover:bg-dark-bg/30 transition-all duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                        <!-- Kos Image -->
                        <div class="w-full lg:w-48 flex-shrink-0">
                            <div class="relative h-48 lg:h-full rounded-xl overflow-hidden">
                                @if($review->kos && $review->kos->foto_utama)
                                    <img src="{{ asset('storage/' . $review->kos->foto_utama) }}" 
                                        alt="{{ $review->kos->nama_kos }}" 
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer"
                                        onclick="openImage('{{ asset('storage/' . $review->kos->foto_utama) }}')">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-dark-border to-dark-bg flex items-center justify-center">
                                        <i class="fas fa-home text-4xl text-dark-muted"></i>
                                    </div>
                                @endif
                                
                                <!-- Rating Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-lg bg-yellow-900/80 backdrop-blur-sm text-yellow-300">
                                        {{ $review->rating }}/5
                                    </span>
                                </div>
                                
                                <!-- Kos Type Badge -->
                                <div class="absolute bottom-3 left-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-lg bg-primary-900/80 backdrop-blur-sm text-primary-300">
                                        {{ $review->kos->jenis_kos }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Review Photo -->
                            @if($review->foto_review)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $review->foto_review) }}" 
                                    alt="Foto review" 
                                    class="w-full h-24 object-cover rounded-lg cursor-pointer hover:scale-105 transition-transform duration-300"
                                    onclick="openImage('{{ asset('storage/' . $review->foto_review) }}')">
                                <p class="text-xs text-dark-muted mt-1 text-center">Foto Review</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Review Content -->
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                                <div>
                                    <div class="flex items-center space-x-3 mb-2">
                                        @auth('penghuni')
                                            @if(Auth::guard('penghuni')->user()->foto_profil)
                                                <?php
                                                $filePath = storage_path('app/public/' . Auth::guard('penghuni')->user()->foto_profil);
                                                $fileExists = file_exists($filePath);
                                                ?>
                                                @if($fileExists)
                                                    <img src="{{ url('storage/' . Auth::guard('penghuni')->user()->foto_profil) }}" 
                                                         alt="{{ Auth::guard('penghuni')->user()->nama }}" 
                                                         class="w-10 h-10 rounded-full object-cover border-2 border-green-400">
                                                @else
                                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white font-medium text-sm">{{ strtoupper(substr(Auth::guard('penghuni')->user()->nama, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-white text-sm"></i>
                                                </div>
                                            @endif
                                        @endauth
                                        <div>
                                            <h3 class="text-xl font-semibold text-white mb-1">{{ $review->kos->nama_kos }}</h3>
                                            <p class="text-sm text-green-400">Review oleh {{ Auth::guard('penghuni')->user()->nama }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-dark-muted text-sm mb-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>
                                        <span>{{ $review->kos->alamat }}, {{ $review->kos->kota }}</span>
                                    </div>
                                </div>
                                
                                <!-- Date Info -->
                                <div class="flex flex-col text-sm text-dark-muted">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-green-400"></i>
                                        <span>Dibuat: {{ $review->created_at->format('d M Y') }}</span>
                                    </div>
                                    @if($review->updated_at != $review->created_at)
                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-edit mr-2 text-yellow-400"></i>
                                        <span>Diedit: {{ $review->updated_at->format('d M Y') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Stars Rating -->
                            <div class="flex items-center mb-4">
                                <div class="flex text-yellow-400 mr-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-white font-medium">{{ $review->rating }}.0</span>
                            </div>
                            
                            <!-- Comment -->
                            <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4 mb-4">
                                <div class="flex items-start">
                                    <i class="fas fa-comment text-primary-400 mr-3 mt-1"></i>
                                    <p class="text-dark-text">{{ $review->komentar }}</p>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('public.kos.show', $review->kos->id_kos) }}" 
                                   class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition flex items-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    Lihat Kos
                                </a>
                                
                                <a href="{{ route('penghuni.reviews.edit', $review->id_review) }}" 
                                   class="px-4 py-2 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-xl hover:from-yellow-700 hover:to-yellow-800 transition flex items-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Review
                                </a>
                                
                                <button type="button" 
                                        onclick="showDeleteModal({{ $review->id_review }})"
                                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition flex items-center">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus Review
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-dark-border">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-dark-muted">
                        Menampilkan {{ $reviews->firstItem() }} - {{ $reviews->lastItem() }} dari {{ $reviews->total() }} review
                    </div>
                    <div class="flex space-x-2">
                        {{ $reviews->links('vendor.pagination.custom-dark') }}
                    </div>
                </div>
            </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-20 h-20 bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-star text-yellow-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mt-4">Belum Ada Review</h3>
            <p class="text-dark-muted mt-2 max-w-md mx-auto">
                Anda belum memberikan review untuk kos manapun. Berikan pengalaman Anda untuk membantu penghuni lain.
            </p>
            <div class="mt-6">
                <a href="{{ route('public.kos.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Cari Kos untuk Direview
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImage()" 
                class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300 transition">
            <div class="w-10 h-10 bg-dark-card/80 rounded-full flex items-center justify-center">
                <i class="fas fa-times"></i>
            </div>
        </button>
        <img id="modal-image" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl">
    </div>
</div>

<script>
    // Image modal functions
    function openImage(src) {
        document.getElementById('modal-image').src = src;
        document.getElementById('image-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImage() {
        document.getElementById('image-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal on background click
    document.getElementById('image-modal').addEventListener('click', function(e) {
        if (e.target.id === 'image-modal') {
            closeImage();
        }
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImage();
        }
    });
    
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });
    });
</script>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-dark-card border border-dark-border rounded-2xl max-w-md w-full p-6 relative">
        <!-- Close Button -->
        <button onclick="closeDeleteModal()" 
                class="absolute top-4 right-4 text-dark-muted hover:text-white transition">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <!-- Modal Content -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Konfirmasi Hapus</h3>
            <p class="text-dark-muted">Apakah Anda yakin ingin menghapus review ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        
        <!-- Form -->
        <form id="delete-form" action="" method="POST">
            @csrf
            @method('DELETE')
            
            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 bg-dark-bg border border-dark-border text-white rounded-xl hover:bg-dark-bg/80 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Delete modal functions
    function showDeleteModal(reviewId) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        form.action = `/penghuni/reviews/${reviewId}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal on background click
    document.getElementById('delete-modal').addEventListener('click', function(e) {
        if (e.target.id === 'delete-modal') {
            closeDeleteModal();
        }
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeImage();
        }
    });
</script>

<style>
    /* Custom scrollbar for modal */
    #image-modal img {
        scrollbar-width: thin;
        scrollbar-color: #475569 transparent;
    }
    
    #image-modal img::-webkit-scrollbar {
        width: 8px;
    }
    
    #image-modal img::-webkit-scrollbar-track {
        background: transparent;
    }
    
    #image-modal img::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 4px;
    }
</style>
@endsection