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
    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden shadow-2xl">
        <!-- Cover Photo -->
        <div class="h-40 bg-gradient-to-r from-green-600 to-emerald-700 relative">
            <!-- Cover Pattern -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-4 right-4 w-32 h-32 bg-white rounded-full blur-2xl"></div>
                <div class="absolute bottom-4 left-4 w-24 h-24 bg-green-400 rounded-full blur-2xl"></div>
            </div>

            <!-- Profile Photo -->
            <div class="absolute -bottom-16 left-6 md:left-8">
                <div class="relative">
                    @if($penghuni->foto_profil)
                        <img src="{{ Storage::url($penghuni->foto_profil) }}" alt="Foto Profil"
                            class="w-32 h-32 md:w-40 md:h-40 rounded-2xl border-4 border-dark-card shadow-2xl object-cover">
                    @else
                        <div
                            class="w-32 h-32 md:w-40 md:h-40 rounded-2xl border-4 border-dark-card bg-gradient-to-br from-green-500/20 to-emerald-500/20 shadow-2xl flex items-center justify-center">
                            <span
                                class="text-4xl md:text-5xl text-green-300 font-bold">{{ substr($penghuni->nama, 0, 1) }}</span>
                        </div>
                    @endif

                    <!-- Upload Button -->
                    <button onclick="openUploadModal()"
                        class="absolute -bottom-2 -right-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white p-2 md:p-3 rounded-full hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-xl hover:scale-110">
                        <i class="fas fa-camera text-sm md:text-base"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="pt-20 md:pt-24 px-4 md:px-8 pb-6 md:pb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1">
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
                    <p class="text-dark-muted mt-1 flex items-center">
                        <i class="fas fa-envelope mr-2 text-green-400"></i>
                        {{ $penghuni->email }}
                    </p>
                    <div class="flex flex-wrap items-center gap-4 mt-2">
                        <span class="text-dark-muted flex items-center">
                            <i class="fas fa-phone mr-2 text-green-400"></i>
                            {{ $penghuni->no_hp }}
                        </span>
                        <span class="text-dark-muted flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-yellow-400"></i>
                            Bergabung {{ $penghuni->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('penghuni.profile.edit') }}"
                        class="px-4 py-2 md:px-5 md:py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 flex items-center shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Profil
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            @php
                $kontrakAktif = $penghuni->kontrakSewa()->where('status_kontrak', 'aktif')->count();
                $totalReview = $penghuni->reviews()->count();
                $totalPembayaran = $penghuni->pembayaran()->where('status_pembayaran', 'lunas')->count();
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-6">
                <!-- Kontrak Aktif -->
                <div
                    class="card-hover bg-gradient-to-br from-green-900/30 to-green-800/20 p-4 md:p-5 rounded-xl border border-green-800/30">
                    <div class="flex items-center">
                        <div class="p-2 md:p-3 bg-green-900/50 rounded-lg mr-3 md:mr-4">
                            <i class="fas fa-file-contract text-green-400 text-lg md:text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm text-green-300">Kontrak Aktif</p>
                            <p class="text-xl md:text-2xl font-bold text-white">{{ $kontrakAktif }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Review -->
                <div
                    class="card-hover bg-gradient-to-br from-yellow-900/30 to-yellow-800/20 p-4 md:p-5 rounded-xl border border-yellow-800/30">
                    <div class="flex items-center">
                        <div class="p-2 md:p-3 bg-yellow-900/50 rounded-lg mr-3 md:mr-4">
                            <i class="fas fa-star text-yellow-400 text-lg md:text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm text-yellow-300">Total Review</p>
                            <p class="text-xl md:text-2xl font-bold text-white">{{ $totalReview }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran Lunas -->
                <div
                    class="card-hover bg-gradient-to-br from-blue-900/30 to-blue-800/20 p-4 md:p-5 rounded-xl border border-blue-800/30">
                    <div class="flex items-center">
                        <div class="p-2 md:p-3 bg-blue-900/50 rounded-lg mr-3 md:mr-4">
                            <i class="fas fa-credit-card text-blue-400 text-lg md:text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm text-blue-300">Pembayaran Lunas</p>
                            <p class="text-xl md:text-2xl font-bold text-white">{{ $totalPembayaran }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div
                    class="card-hover bg-gradient-to-br from-purple-900/30 to-purple-800/20 p-4 md:p-5 rounded-xl border border-purple-800/30">
                    <div class="flex items-center">
                        <div class="p-2 md:p-3 bg-purple-900/50 rounded-lg mr-3 md:mr-4">
                            <i class="fas fa-user-tag text-purple-400 text-lg md:text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm text-purple-300">Status</p>
                            <p class="text-xl md:text-2xl font-bold text-white capitalize">{{ $penghuni->status_penghuni }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-6 md:mt-8">
                <!-- Personal Information -->
                <div class="bg-dark-bg/50 p-5 md:p-6 rounded-xl border border-dark-border">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-user-circle text-green-400 mr-3"></i>
                        Informasi Pribadi
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-dark-muted">Username</p>
                            <p class="font-medium text-white">{{ $penghuni->username }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-dark-muted">Jenis Kelamin</p>
                            <p class="font-medium text-white">
                                @if($penghuni->jenis_kelamin == 'L')
                                    <i class="fas fa-mars text-blue-400 mr-1"></i>Laki-laki
                                @elseif($penghuni->jenis_kelamin == 'P')
                                    <i class="fas fa-venus text-pink-400 mr-1"></i>Perempuan
                                @else
                                    <span class="text-dark-muted">Belum diisi</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-dark-muted">Tanggal Lahir</p>
                            <p class="font-medium text-white">
                                {{ $penghuni->tanggal_lahir ? \Carbon\Carbon::parse($penghuni->tanggal_lahir)->format('d M Y') : '<span class="text-dark-muted">Belum diisi</span>' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-dark-bg/50 p-5 md:p-6 rounded-xl border border-dark-border">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-address-book text-blue-400 mr-3"></i>
                        Informasi Kontak
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-dark-muted">Nomor HP</p>
                            <p class="font-medium text-white flex items-center">
                                <i class="fas fa-phone text-green-400 mr-2"></i>
                                {{ $penghuni->no_hp }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-dark-muted">Email</p>
                            <p class="font-medium text-white flex items-center">
                                <i class="fas fa-envelope text-green-400 mr-2"></i>
                                {{ $penghuni->email }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-dark-muted">Alamat</p>
                            <p class="font-medium text-white">
                                {{ $penghuni->alamat ?: '<span class="text-dark-muted">Belum diisi</span>' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-dark-bg/50 p-5 md:p-6 rounded-xl border border-dark-border lg:col-span-2">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-key text-yellow-400 mr-3"></i>
                        Informasi Akun
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-dark-muted">Role</p>
                                    <p class="font-medium text-white">
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-medium bg-green-900/30 text-green-300">
                                            {{ ucfirst($penghuni->role) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-dark-muted">Status Akun</p>
                                    <p class="font-medium text-white">
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-medium 
                                            {{ $penghuni->status_penghuni == 'aktif' ? 'bg-green-900/30 text-green-300' :
                        ($penghuni->status_penghuni == 'calon' ? 'bg-yellow-900/30 text-yellow-300' : 'bg-red-900/30 text-red-300') }}">
                                            {{ ucfirst($penghuni->status_penghuni) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-dark-muted">Terakhir Login</p>
                                    <p class="font-medium text-white">
                                        <i class="fas fa-clock text-dark-muted mr-2"></i>
                                        {{ $penghuni->updated_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-dark-muted">Member Sejak</p>
                                    <p class="font-medium text-white">
                                        <i class="fas fa-calendar-check text-dark-muted mr-2"></i>
                                        {{ $penghuni->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
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
    <div id="uploadModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Upload Foto Profil</h3>
                <button onclick="closeUploadModal()" class="text-dark-muted hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <!-- Upload Preview -->
                    <div class="mb-4 text-center">
                        <div id="imagePreview"
                            class="w-32 h-32 mx-auto rounded-xl border-2 border-dashed border-dark-border bg-dark-bg/50 flex items-center justify-center mb-4">
                            <i class="fas fa-user-circle text-4xl text-dark-muted"></i>
                        </div>
                        <p class="text-sm text-dark-muted">Pratinjau foto profil</p>
                    </div>

                    <!-- File Input -->
                    <div class="relative">
                        <input type="file" name="foto_profil" id="photoInput" accept="image/*" class="hidden" required>
                        <label for="photoInput"
                            class="block w-full px-4 py-3 border-2 border-dashed border-dark-border rounded-xl text-center cursor-pointer hover:border-green-500 transition">
                            <i class="fas fa-cloud-upload-alt text-green-400 text-xl mb-2"></i>
                            <p class="text-white font-medium">Pilih Foto</p>
                            <p class="text-xs text-dark-muted mt-1">Format: JPG, PNG, GIF. Max: 2MB</p>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeUploadModal()"
                        class="px-4 py-2.5 border border-dark-border text-dark-muted rounded-xl hover:text-white hover:border-dark-border/80 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 flex items-center">
                        <i class="fas fa-upload mr-2"></i>
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Image preview
            const photoInput = document.getElementById('photoInput');
            const imagePreview = document.getElementById('imagePreview');

            photoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-xl">`;
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Modal functions
            function openUploadModal() {
                document.getElementById('uploadModal').classList.remove('hidden');
                document.getElementById('uploadModal').classList.add('flex');
                document.body.classList.add('overflow-hidden');
            }

            function closeUploadModal() {
                document.getElementById('uploadModal').classList.add('hidden');
                document.getElementById('uploadModal').classList.remove('flex');
                document.getElementById('photoInput').value = '';
                imagePreview.innerHTML = '<i class="fas fa-user-circle text-4xl text-dark-muted"></i>';
                document.body.classList.remove('overflow-hidden');
            }

            // Form submission
            document.getElementById('uploadForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Uploading...';
                submitBtn.disabled = true;

                const formData = new FormData(this);

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
                            // Show success message
                            submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Success!';
                            submitBtn.classList.remove('from-green-500', 'to-emerald-500', 'hover:from-green-600', 'hover:to-emerald-600');
                            submitBtn.classList.add('from-green-500', 'to-green-600');

                            // Reload page after 1 second
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            alert('Upload gagal: ' + (data.message || 'Terjadi kesalahan'));
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat upload');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });

            // Close modal on ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeUploadModal();
                }
            });

            // Close modal when clicking outside
            document.getElementById('uploadModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    closeUploadModal();
                }
            });
        </script>
    @endpush

@endsection