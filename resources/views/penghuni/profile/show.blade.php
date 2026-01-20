@extends('layouts.app')

@section('title', 'Profil Saya - Penghuni')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="mb-6 bg-dark-card/50 border border-dark-border rounded-xl p-4">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center">
            <i class="fas fa-user-circle text-green-400 mr-3"></i>
            Profil Saya
        </h1>
        <p class="text-dark-muted mt-2">Kelola informasi profil dan akun Anda</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
        <!-- Cover Photo -->
        <div class="h-48 bg-gradient-to-r from-green-900/50 to-emerald-900/50 relative">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-green-500 rounded-full -translate-y-1/3 translate-x-1/3 blur-3xl"></div>
            </div>
            
            <!-- Profile Photo -->
            <div class="absolute -bottom-16 left-6 md:left-8">
                <div class="relative">
                    @if($penghuni->foto_profil)
                        <img src="{{ Storage::url($penghuni->foto_profil) }}" 
                             alt="Foto Profil" 
                             class="w-28 h-28 md:w-32 md:h-32 rounded-2xl border-4 border-dark-card shadow-2xl object-cover">
                    @else
                        <div class="w-28 h-28 md:w-32 md:h-32 rounded-2xl border-4 border-dark-card bg-gradient-to-br from-green-500/20 to-emerald-500/20 shadow-2xl flex items-center justify-center">
                            <span class="text-3xl md:text-4xl font-bold text-green-300">{{ substr($penghuni->nama, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    <!-- Upload Button -->
                    <button onclick="openUploadModal()" 
                            class="absolute -bottom-2 -right-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white p-2 rounded-full hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="pt-20 md:pt-24 px-6 md:px-8 pb-6 md:pb-8">
            <div class="flex flex-col md:flex-row md:items-start justify-between">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-3 mb-2">
                        <h2 class="text-xl md:text-2xl font-bold text-white">{{ $penghuni->nama }}</h2>
                        @if($penghuni->status_penghuni == 'aktif')
                            <span class="px-2 py-1 bg-green-900/30 text-green-300 text-xs rounded-full font-medium">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            </span>
                        @elseif($penghuni->status_penghuni == 'calon')
                            <span class="px-2 py-1 bg-yellow-900/30 text-yellow-300 text-xs rounded-full font-medium">
                                <i class="fas fa-clock mr-1"></i>
                                Calon
                            </span>
                        @endif
                    </div>
                    <p class="text-dark-muted mb-3">
                        <i class="fas fa-envelope mr-2 text-green-400"></i>
                        {{ $penghuni->email }}
                    </p>
                    <div class="flex items-center text-sm text-dark-muted">
                        <i class="fas fa-calendar-alt mr-2 text-blue-400"></i>
                        Bergabung {{ $penghuni->created_at->format('d M Y') }}
                    </div>
                </div>
                <a href="{{ route('penghuni.profile.edit') }}" 
                   class="px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 flex items-center justify-center md:justify-start">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Profil
                </a>
            </div>

            <!-- Profile Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Personal Information -->
                <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 card-hover">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-user text-green-400 mr-3"></i>
                        Informasi Pribadi
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Username</span>
                            <span class="font-medium text-white">{{ $penghuni->username }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Jenis Kelamin</span>
                            <span class="font-medium text-white">
                                @if($penghuni->jenis_kelamin == 'L')
                                    <i class="fas fa-mars text-blue-400 mr-1"></i>Laki-laki
                                @elseif($penghuni->jenis_kelamin == 'P')
                                    <i class="fas fa-venus text-pink-400 mr-1"></i>Perempuan
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Tanggal Lahir</span>
                            <span class="font-medium text-white">
                                {{ $penghuni->tanggal_lahir ? \Carbon\Carbon::parse($penghuni->tanggal_lahir)->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 card-hover">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-phone-alt text-blue-400 mr-3"></i>
                        Kontak
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Nomor HP</span>
                            <span class="font-medium text-white">
                                <i class="fas fa-phone mr-2 text-green-400"></i>
                                {{ $penghuni->no_hp }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Email</span>
                            <span class="font-medium text-white truncate">
                                <i class="fas fa-envelope mr-2 text-green-400"></i>
                                {{ $penghuni->email }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm text-dark-muted block mb-1">Alamat</span>
                            <span class="font-medium text-white text-sm">
                                <i class="fas fa-map-marker-alt mr-2 text-red-400"></i>
                                {{ $penghuni->alamat ?: '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 card-hover">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-shield-alt text-yellow-400 mr-3"></i>
                        Informasi Akun
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Role</span>
                            <span class="font-medium text-white capitalize">
                                <i class="fas fa-user-tag mr-2 text-purple-400"></i>
                                {{ $penghuni->role }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Status</span>
                            <span class="font-medium capitalize
                                {{ $penghuni->status_penghuni == 'aktif' ? 'text-green-400' : 
                                   ($penghuni->status_penghuni == 'calon' ? 'text-yellow-400' : 'text-red-400') }}">
                                <i class="fas 
                                    {{ $penghuni->status_penghuni == 'aktif' ? 'fa-check-circle' : 
                                       ($penghuni->status_penghuni == 'calon' ? 'fa-clock' : 'fa-times-circle') }} mr-2"></i>
                                {{ $penghuni->status_penghuni }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Terakhir Diupdate</span>
                            <span class="font-medium text-white">
                                <i class="fas fa-sync-alt mr-2 text-primary-400"></i>
                                {{ $penghuni->updated_at->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 card-hover">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-purple-400 mr-3"></i>
                        Statistik
                    </h3>
                    <div class="space-y-3">
                        @php
                            $kontrakAktif = $penghuni->kontrakSewa()->where('status_kontrak', 'aktif')->count();
                            $totalReview = $penghuni->reviews()->count();
                            $totalPembayaran = $penghuni->pembayaran()->where('status_pembayaran', 'lunas')->count();
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Kontrak Aktif</span>
                            <span class="font-bold text-xl text-green-400">{{ $kontrakAktif }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Total Review</span>
                            <span class="font-bold text-xl text-yellow-400">{{ $totalReview }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-dark-muted">Pembayaran Lunas</span>
                            <span class="font-bold text-xl text-blue-400">{{ $totalPembayaran }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('penghuni.kontrak.index') }}" 
           class="bg-dark-card border border-dark-border rounded-xl p-4 hover:border-green-500/50 transition-all duration-300 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-contract text-green-400"></i>
                </div>
                <div>
                    <h4 class="font-medium text-white group-hover:text-green-300">Kontrak Saya</h4>
                    <p class="text-xs text-dark-muted">{{ $kontrakAktif ?? 0 }} kontrak aktif</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('penghuni.pembayaran.index') }}" 
           class="bg-dark-card border border-dark-border rounded-xl p-4 hover:border-blue-500/50 transition-all duration-300 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-credit-card text-blue-400"></i>
                </div>
                <div>
                    <h4 class="font-medium text-white group-hover:text-blue-300">Pembayaran</h4>
                    <p class="text-xs text-dark-muted">{{ $totalPembayaran ?? 0 }} pembayaran lunas</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('penghuni.reviews.history') }}" 
           class="bg-dark-card border border-dark-border rounded-xl p-4 hover:border-yellow-500/50 transition-all duration-300 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-900/30 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <div>
                    <h4 class="font-medium text-white group-hover:text-yellow-300">Review Saya</h4>
                    <p class="text-xs text-dark-muted">{{ $totalReview ?? 0 }} review ditulis</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Upload Photo Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-dark-card border border-dark-border rounded-2xl p-6 max-w-md w-full animate-fadeIn">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Upload Foto Profil</h3>
            <button onclick="closeUploadModal()" class="text-dark-muted hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-2">Pilih Foto</label>
                <div class="border-2 border-dashed border-dark-border rounded-xl p-6 text-center hover:border-green-500/50 transition">
                    <i class="fas fa-cloud-upload-alt text-3xl text-dark-muted mb-2"></i>
                    <p class="text-dark-muted text-sm mb-2">Drag & drop atau klik untuk upload</p>
                    <input type="file" 
                           name="foto_profil" 
                           id="photoInput" 
                           accept="image/*" 
                           class="hidden" 
                           required>
                    <label for="photoInput" 
                           class="inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg cursor-pointer transition">
                        <i class="fas fa-folder-open mr-2"></i>
                        Pilih File
                    </label>
                </div>
                <p class="text-xs text-dark-muted mt-2">Format: JPG, PNG, maksimal 2MB</p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeUploadModal()" 
                        class="px-4 py-2 border border-dark-border text-dark-text hover:text-white hover:border-dark-border/80 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 transition flex items-center">
                    <i class="fas fa-upload mr-2"></i>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-dark-card rounded-2xl p-6 max-w-lg w-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Preview Foto</h3>
            <button onclick="closePreviewModal()" class="text-dark-muted hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="imagePreview" class="rounded-xl overflow-hidden mb-4"></div>
        <div class="flex justify-end space-x-3">
            <button onclick="closePreviewModal()" class="px-4 py-2 border border-dark-border text-dark-text rounded-lg">
                Batal
            </button>
            <button onclick="submitUpload()" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                Konfirmasi
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    document.getElementById('uploadModal').classList.add('flex');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
    document.getElementById('photoInput').value = '';
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewModal').classList.remove('flex');
    document.getElementById('imagePreview').innerHTML = '';
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML = `
                <img src="${e.target.result}" 
                     class="w-full h-64 object-cover rounded-xl" 
                     alt="Preview">
            `;
            
            // Close upload modal and open preview
            closeUploadModal();
            document.getElementById('previewModal').classList.remove('hidden');
            document.getElementById('previewModal').classList.add('flex');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function submitUpload() {
    const formData = new FormData(document.getElementById('uploadForm'));
    
    fetch('{{ route("penghuni.profile.upload-photo") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Upload gagal: ' + (data.message || 'Terjadi kesalahan'));
            closePreviewModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat upload');
        closePreviewModal();
    });
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // File input change event
    document.getElementById('photoInput').addEventListener('change', function(e) {
        previewImage(this);
    });
    
    // Form submit event
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitUpload();
    });
});
</script>

<style>
.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@endsection