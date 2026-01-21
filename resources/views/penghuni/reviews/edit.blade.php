@extends('layouts.app')

@section('title', 'Edit Review - AyoKos')

@section('content')
<div class="p-4 md:p-6">
    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="bg-dark-card/50 border border-dark-border rounded-xl p-4 mb-6">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('public.home') }}" 
                       class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-primary-300 transition">
                        <i class="fas fa-gauge mr-2"></i>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-dark-border mx-2 text-xs"></i>
                        <a href="{{ route('penghuni.reviews.history') }}" 
                           class="ml-1 text-sm font-medium text-dark-muted hover:text-primary-300 transition">
                           <i class="fas fa-star mr-2"></i>
                            Review Saya
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-dark-border mx-2 text-xs"></i>
                        <span class="ml-1 text-sm font-medium text-dark-text">
                            <i class="fas fa-pencil mr-2"></i>
                            Edit Review
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Card -->
        <div class="bg-dark-card border border-dark-border rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Edit Review</h1>
                        <p class="text-dark-muted">Perbarui review Anda untuk {{ $review->kos->nama_kos }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Kos Info -->
                <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4 mb-6">
                    <div class="flex items-center space-x-4">
                        @if($review->kos->foto_utama)
                            @php
                                $filePath = storage_path('app/public/' . $review->kos->foto_utama);
                                $fileExists = file_exists($filePath);
                            @endphp
                            @if($fileExists)
                                <img src="{{ url('storage/' . $review->kos->foto_utama) }}" 
                                     alt="{{ $review->kos->nama_kos }}" 
                                     class="w-16 h-16 object-cover rounded-xl border border-dark-border">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-dark-border to-dark-bg rounded-xl border border-dark-border flex items-center justify-center">
                                    <i class="fas fa-home text-dark-muted text-xl"></i>
                                </div>
                            @endif
                        @else
                            <div class="w-16 h-16 bg-gradient-to-br from-dark-border to-dark-bg rounded-xl border border-dark-border flex items-center justify-center">
                                <i class="fas fa-home text-dark-muted text-xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-white">{{ $review->kos->nama_kos }}</h3>
                                <span class="text-xs px-2 py-1 rounded-full bg-green-900/30 text-green-300">
                                    {{ $review->kontrak->kamar->nomor_kamar ?? 'Kamar' }}
                                </span>
                            </div>
                            <p class="text-sm text-dark-muted mt-1">{{ $review->kos->alamat }}, {{ $review->kos->kota }}</p>
                            <p class="text-xs text-dark-muted/70 mt-1">
                                <i class="far fa-calendar-alt mr-1"></i>
                                Review dibuat: {{ $review->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('penghuni.reviews.update', $review->id_review) }}" method="POST" enctype="multipart/form-data" id="reviewForm">
                    @csrf
                    @method('PUT')

                    <!-- Rating -->
                    <div class="mb-6">
                        <label class="block text-white font-medium mb-3 flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            Rating Anda
                        </label>
                        <div class="flex items-center space-x-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    onclick="setRating({{ $i }})" 
                                    class="text-3xl rating-star focus:outline-none transition-all duration-200 hover:scale-110 hover:rotate-12"
                                    data-rating="{{ $i }}"
                                    aria-label="Rating {{ $i }} bintang">
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @else
                                    <i class="far fa-star text-dark-muted"></i>
                                @endif
                            </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="{{ $review->rating }}" required>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-dark-muted">
                                <span id="rating-text">
                                    @switch($review->rating)
                                        @case(1) Sangat Buruk @break
                                        @case(2) Buruk @break
                                        @case(3) Cukup @break
                                        @case(4) Baik @break
                                        @case(5) Sangat Baik @break
                                    @endswitch
                                </span>
                            </div>
                            <div class="text-sm text-dark-muted">
                                {{ $review->rating }} dari 5 bintang
                            </div>
                        </div>
                        @error('rating')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Komentar -->
                    <div class="mb-6">
                        <label for="komentar" class="block text-white font-medium mb-3 flex items-center">
                            <i class="fas fa-edit text-primary-400 mr-2"></i>
                            Komentar
                        </label>
                        <textarea name="komentar" id="komentar" 
                                  rows="6"
                                  class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                  placeholder="Bagaimana pengalaman Anda selama tinggal di kos ini?"
                                  required>{{ old('komentar', $review->komentar) }}</textarea>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-xs text-dark-muted flex items-center">
                                <i class="fas fa-lightbulb mr-1 text-yellow-400"></i>
                                Bagikan tentang fasilitas, kebersihan, lingkungan, dan layanan
                            </div>
                            <div class="text-xs text-dark-muted">
                                <span id="char-count">0</span>/500 karakter
                            </div>
                        </div>
                        @error('komentar')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Foto Review -->
                    <div class="mb-8">
                        <label class="block text-white font-medium mb-3 flex items-center">
                            <i class="fas fa-camera text-purple-400 mr-2"></i>
                            Foto Review
                        </label>
                        
                        <!-- Foto saat ini -->
                        @if($review->foto_review)
                        <div class="mb-4">
                            <p class="text-sm text-dark-muted mb-2">Foto saat ini:</p>
                            <div class="flex items-center space-x-4 p-4 bg-dark-bg/50 border border-dark-border rounded-xl">
                                @php
                                    $reviewFilePath = storage_path('app/public/' . $review->foto_review);
                                    $reviewFileExists = file_exists($reviewFilePath);
                                @endphp
                                @if($reviewFileExists)
                                    <img src="{{ url('storage/' . $review->foto_review) }}" 
                                         alt="Foto review" 
                                         class="w-24 h-24 object-cover rounded-lg border border-dark-border">
                                @else
                                    <div class="w-24 h-24 bg-gradient-to-br from-dark-border to-dark-bg rounded-lg border border-dark-border flex items-center justify-center">
                                        <i class="fas fa-image text-dark-muted text-2xl"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <label class="flex items-center space-x-2 cursor-pointer group">
                                            <div class="relative">
                                                <input type="checkbox" name="hapus_foto" value="1" class="sr-only peer">
                                                <div class="w-6 h-6 bg-dark-bg border-2 border-dark-border rounded-md peer-checked:bg-red-600 peer-checked:border-red-600 transition-all duration-200 group-hover:border-red-500"></div>
                                                <i class="fas fa-check absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                            <span class="text-sm text-red-400 group-hover:text-red-300 transition">Hapus foto ini</span>
                                        </label>
                                    </div>
                                    <p class="text-xs text-dark-muted mt-2">Centang untuk menghapus foto saat ini</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Upload foto baru -->
                        <p class="text-sm text-dark-muted mb-3">Upload foto baru (opsional):</p>
                        <div class="flex items-center justify-center w-full">
                            <label for="foto_review" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-dark-border rounded-xl cursor-pointer bg-dark-bg/30 hover:bg-dark-bg/50 hover:border-primary-500/50 transition-all duration-300 group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500/20 to-purple-500/20 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-cloud-upload-alt text-primary-400 text-xl"></i>
                                    </div>
                                    <p class="mb-2 text-sm text-dark-muted group-hover:text-white transition">
                                        <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                    </p>
                                    <p class="text-xs text-dark-muted">PNG, JPG, GIF (Max 2MB)</p>
                                </div>
                                <input id="foto_review" name="foto_review" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>
                        <div id="image-preview" class="mt-4 hidden">
                            <p class="text-sm text-dark-muted mb-2">Pratinjau foto baru:</p>
                            <div class="relative inline-block">
                                <img id="preview-image" class="w-32 h-32 object-cover rounded-xl border border-dark-border" />
                                <button type="button" onclick="removePreview()" 
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                        @error('foto_review')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col-reverse sm:flex-row justify-between items-center space-y-4 sm:space-y-0 space-y-reverse pt-6 border-t border-dark-border">
                        <!-- Delete Button -->
                        <button type="button" 
                                onclick="showDeleteModal()"
                                class="px-6 py-3 bg-gradient-to-r from-red-900/30 to-red-800/30 border border-red-800/50 text-red-400 rounded-xl hover:from-red-800/40 hover:to-red-700/40 hover:border-red-700 hover:text-red-300 transition-all duration-300 group flex items-center">
                            <i class="fas fa-trash-alt mr-2 group-hover:rotate-12 transition-transform"></i>
                            Hapus Review
                        </button>

                        <!-- Save/Cancel Buttons -->
                        <div class="flex space-x-3">
                            <a href="{{ route('penghuni.reviews.history') }}" 
                               class="px-6 py-3 bg-dark-border text-dark-text rounded-xl hover:bg-dark-border/80 hover:text-white transition-all duration-300 flex items-center">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" 
                                    id="submit-btn"
                                    class="px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border border-dark-border w-96 shadow-2xl rounded-2xl bg-dark-card">
        <div class="mt-3">
            <div class="w-16 h-16 bg-gradient-to-br from-red-500/20 to-red-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-white text-center mb-3">Hapus Review</h3>
            <p class="text-sm text-dark-muted text-center mb-6">
                Apakah Anda yakin ingin menghapus review ini? 
                <span class="block text-red-400 mt-1">Tindakan ini tidak dapat dibatalkan.</span>
            </p>
            
            <div class="flex justify-center space-x-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="px-5 py-2.5 bg-dark-border text-white rounded-xl hover:bg-dark-border/80 transition">
                    Batal
                </button>
                <form id="delete-form" action="{{ route('penghuni.reviews.destroy', $review->id_review) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Rating system
    let currentRating = {{ $review->rating }};
    const stars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');

    const ratingDescriptions = {
        1: 'Sangat Buruk',
        2: 'Buruk',
        3: 'Cukup',
        4: 'Baik',
        5: 'Sangat Baik'
    };

    function setRating(rating) {
        currentRating = rating;
        ratingInput.value = rating;
        ratingText.textContent = ratingDescriptions[rating];
        
        stars.forEach((star, index) => {
            const starIcon = star.querySelector('i');
            if (index < rating) {
                starIcon.className = 'fas fa-star text-yellow-400';
                starIcon.parentElement.classList.add('animate-pulse');
                setTimeout(() => {
                    starIcon.parentElement.classList.remove('animate-pulse');
                }, 300);
            } else {
                starIcon.className = 'far fa-star text-dark-muted';
            }
        });
    }

    // Character counter
    const komentarTextarea = document.getElementById('komentar');
    const charCount = document.getElementById('char-count');

    komentarTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
        if (this.value.length > 500) {
            charCount.classList.add('text-red-400');
        } else {
            charCount.classList.remove('text-red-400');
        }
    });

    // Initialize character count
    charCount.textContent = komentarTextarea.value.length;
    if (komentarTextarea.value.length > 500) {
        charCount.classList.add('text-red-400');
    }

    // Image preview
    const fileInput = document.getElementById('foto_review');
    const previewContainer = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            // Check file size (max 2MB)
            if (this.files[0].size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });

    function removePreview() {
        fileInput.value = '';
        previewContainer.classList.add('hidden');
        previewImage.src = '';
    }

    // Delete modal
    function showDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    }

    // Form validation
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        const komentar = document.getElementById('komentar').value.trim();
        const rating = document.getElementById('rating-input').value;
        
        if (komentar.length < 10) {
            e.preventDefault();
            showNotification('Komentar harus minimal 10 karakter', 'error');
            return;
        }
        
        if (komentar.length > 500) {
            e.preventDefault();
            showNotification('Komentar maksimal 500 karakter', 'error');
            return;
        }
        
        if (!rating || rating < 1 || rating > 5) {
            e.preventDefault();
            showNotification('Harap berikan rating', 'error');
            return;
        }
    });

    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-xl z-50 animate-slideIn ${
            type === 'error' ? 'bg-red-900/90 border border-red-700 text-red-100' : 
            'bg-green-900/90 border border-green-700 text-green-100'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} mr-3"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>

<style>
    .rating-star {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .rating-star:hover {
        transform: scale(1.15);
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .animate-slideIn {
        animation: slideIn 0.3s ease-out;
    }
    
    /* Custom file input styling */
    input[type="file"]::file-selector-button {
        display: none;
    }
    
    /* Custom checkbox */
    input[type="checkbox"]:checked + div {
        background-color: #dc2626;
        border-color: #dc2626;
    }
</style>
@endsection