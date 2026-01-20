@extends('layouts.app')

@section('title', 'Profil Saya - Pemilik Kos')

@section('content')
    <div class="p-4 md:p-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center">
                <i class="fas fa-user-circle text-primary-400 mr-3"></i>
                Profil Pemilik Kos
            </h1>
            <p class="text-dark-muted mt-2">Kelola informasi profil dan akun Anda sebagai pemilik kos</p>
        </div>

        <!-- Profile Card -->
        <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden shadow-2xl">
            <!-- Cover Photo -->
            <div class="h-40 bg-gradient-to-r from-primary-600 to-indigo-700 relative">
                <!-- Cover Pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-4 right-4 w-32 h-32 bg-white rounded-full blur-2xl"></div>
                    <div class="absolute bottom-4 left-4 w-24 h-24 bg-primary-400 rounded-full blur-2xl"></div>
                </div>

                <!-- Profile Photo -->
                <div class="absolute -bottom-16 left-6 md:left-8">
                    <div class="relative">
                        @if($pemilik->foto_profil)
                            <img src="{{ Storage::url($pemilik->foto_profil) }}" alt="Foto Profil"
                                class="w-32 h-32 md:w-40 md:h-40 rounded-2xl border-4 border-dark-card shadow-2xl object-cover">
                        @else
                            <div
                                class="w-32 h-32 md:w-40 md:h-40 rounded-2xl border-4 border-dark-card bg-gradient-to-br from-primary-500/20 to-indigo-500/20 shadow-2xl flex items-center justify-center">
                                <span
                                    class="text-4xl md:text-5xl text-primary-300 font-bold">{{ substr($pemilik->nama, 0, 1) }}</span>
                            </div>
                        @endif

                        <!-- Upload Button -->
                        <button onclick="openUploadModal()"
                            class="absolute -bottom-2 -right-2 bg-gradient-to-r from-primary-500 to-indigo-500 text-white p-2 md:p-3 rounded-full hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-xl hover:scale-110">
                            <i class="fas fa-camera text-sm md:text-base"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="pt-20 md:pt-24 px-4 md:px-8 pb-6 md:pb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex-1">
                        <h2 class="text-xl md:text-2xl font-bold text-white">{{ $pemilik->nama }}</h2>
                        <p class="text-dark-muted mt-1 flex items-center">
                            <i class="fas fa-envelope mr-2 text-primary-400"></i>
                            {{ $pemilik->email }}
                        </p>
                        <div class="flex flex-wrap items-center gap-4 mt-2">
                            <span class="text-dark-muted flex items-center">
                                <i class="fas fa-phone mr-2 text-green-400"></i>
                                {{ $pemilik->no_hp }}
                            </span>
                            <span class="text-dark-muted flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-yellow-400"></i>
                                Bergabung {{ $pemilik->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('pemilik.profile.edit') }}"
                            class="px-4 py-2 md:px-5 md:py-2.5 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 flex items-center shadow-lg hover:shadow-xl hover:-translate-y-1">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profil
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                @php
                    use App\Models\Kos;
                    use App\Models\Kamar;
                    use App\Models\KontrakSewa;

                    $totalKos = Kos::where('id_pemilik', $pemilik->id_pemilik)->count();
                    $totalKamar = Kamar::whereHas('kos', function ($q) use ($pemilik) {
                        $q->where('id_pemilik', $pemilik->id_pemilik);
                    })->count();

                    $kamarTerisi = Kamar::whereHas('kos', function ($q) use ($pemilik) {
                        $q->where('id_pemilik', $pemilik->id_pemilik);
                    })->where('status_kamar', 'terisi')->count();

                    $totalKontrak = KontrakSewa::whereHas('kos', function ($q) use ($pemilik) {
                        $q->where('id_pemilik', $pemilik->id_pemilik);
                    })->where('status_kontrak', 'aktif')->count();

                    $ratingKos = Kos::where('id_pemilik', $pemilik->id_pemilik)
                        ->withAvg('reviews', 'rating')
                        ->get()
                        ->avg('reviews_avg_rating');
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-6">
                    <!-- Total Kos -->
                    <div
                        class="card-hover bg-gradient-to-br from-primary-900/30 to-primary-800/20 p-4 md:p-5 rounded-xl border border-primary-800/30">
                        <div class="flex items-center">
                            <div class="p-2 md:p-3 bg-primary-900/50 rounded-lg mr-3 md:mr-4">
                                <i class="fas fa-home text-primary-400 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-primary-300">Total Kos</p>
                                <p class="text-xl md:text-2xl font-bold text-white">{{ $totalKos }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Kamar -->
                    <div
                        class="card-hover bg-gradient-to-br from-green-900/30 to-green-800/20 p-4 md:p-5 rounded-xl border border-green-800/30">
                        <div class="flex items-center">
                            <div class="p-2 md:p-3 bg-green-900/50 rounded-lg mr-3 md:mr-4">
                                <i class="fas fa-bed text-green-400 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-green-300">Total Kamar</p>
                                <p class="text-xl md:text-2xl font-bold text-white">{{ $totalKamar }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kamar Terisi -->
                    <div
                        class="card-hover bg-gradient-to-br from-purple-900/30 to-purple-800/20 p-4 md:p-5 rounded-xl border border-purple-800/30">
                        <div class="flex items-center">
                            <div class="p-2 md:p-3 bg-purple-900/50 rounded-lg mr-3 md:mr-4">
                                <i class="fas fa-users text-purple-400 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-purple-300">Kamar Terisi</p>
                                <p class="text-xl md:text-2xl font-bold text-white">{{ $kamarTerisi }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Rata-rata -->
                    <div
                        class="card-hover bg-gradient-to-br from-yellow-900/30 to-yellow-800/20 p-4 md:p-5 rounded-xl border border-yellow-800/30">
                        <div class="flex items-center">
                            <div class="p-2 md:p-3 bg-yellow-900/50 rounded-lg mr-3 md:mr-4">
                                <i class="fas fa-star text-yellow-400 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-yellow-300">Rating Rata-rata</p>
                                <p class="text-xl md:text-2xl font-bold text-white">
                                    {{ $ratingKos ? number_format($ratingKos, 1) : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-6 md:mt-8">
                    <!-- Personal Information -->
                    <div class="bg-dark-bg/50 p-5 md:p-6 rounded-xl border border-dark-border">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-user-circle text-primary-400 mr-3"></i>
                            Informasi Pribadi
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-dark-muted">Username</p>
                                <p class="font-medium text-white">{{ $pemilik->username }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-dark-muted">Jenis Kelamin</p>
                                <p class="font-medium text-white">
                                    @if($pemilik->jenis_kelamin == 'L')
                                        Laki-laki
                                    @elseif($pemilik->jenis_kelamin == 'P')
                                        Perempuan
                                    @else
                                        <span class="text-dark-muted">Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-dark-muted">Tanggal Lahir</p>
                                <p class="font-medium text-white">
                                    {{ $pemilik->tanggal_lahir ? \Carbon\Carbon::parse($pemilik->tanggal_lahir)->format('d M Y') : '<span class="text-dark-muted">Belum diisi</span>' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-dark-bg/50 p-5 md:p-6 rounded-xl border border-dark-border">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-address-book text-green-400 mr-3"></i>
                            Informasi Kontak
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-dark-muted">Nomor HP</p>
                                <p class="font-medium text-white flex items-center">
                                    <i class="fas fa-phone text-green-400 mr-2"></i>
                                    {{ $pemilik->no_hp }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted">Email</p>
                                <p class="font-medium text-white flex items-center">
                                    <i class="fas fa-envelope text-primary-400 mr-2"></i>
                                    {{ $pemilik->email }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted">Alamat</p>
                                <p class="font-medium text-white">
                                    {{ $pemilik->alamat ?: '<span class="text-dark-muted">Belum diisi</span>' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Information -->
                    <div class="bg-dark-bg/50 p-5 md:p-6 rounded-xl border border-dark-border lg:col-span-2">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-university text-primary-400 mr-3"></i>
                            Data Rekening Bank
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-dark-muted">Nama Bank</p>
                                <p class="font-medium text-white flex items-center">
                                    <i class="fas fa-money-check mr-2 text-primary-400"></i>
                                    {{ $pemilik->nama_bank ?: 'Belum diisi' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted">Nomor Rekening</p>
                                <p class="font-medium text-white flex items-center">
                                    <i class="fas fa-credit-card mr-2 text-green-400"></i>
                                    {{ $pemilik->nomor_rekening ?: 'Belum diisi' }}
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
                                                class="px-3 py-1 rounded-full text-sm font-medium bg-primary-900/30 text-primary-300">
                                                {{ ucfirst($pemilik->role) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-dark-muted">Status Akun</p>
                                        <p class="font-medium text-white">
                                            <span
                                                class="px-3 py-1 rounded-full text-sm font-medium 
                                                {{ $pemilik->status_pemilik == 'aktif' ? 'bg-green-900/30 text-green-300' :
        ($pemilik->status_pemilik == 'pending' ? 'bg-yellow-900/30 text-yellow-300' : 'bg-red-900/30 text-red-300') }}">
                                                {{ ucfirst($pemilik->status_pemilik) }}
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
                                            {{ $pemilik->updated_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-dark-muted">Member Sejak</p>
                                        <p class="font-medium text-white">
                                            <i class="fas fa-calendar-check text-dark-muted mr-2"></i>
                                            {{ $pemilik->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Kos List -->
                @php
                    $recentKos = \App\Models\Kos::where('id_pemilik', $pemilik->id_pemilik)
                        ->withCount([
                            'kamar as kamar_tersedia' => function ($q) {
                                $q->where('status_kamar', 'tersedia');
                            }
                        ])
                        ->latest()
                        ->take(3)
                        ->get();
                @endphp

                @if($recentKos->count() > 0)
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-building text-primary-400 mr-3"></i>
                                Kos Terbaru
                            </h3>
                            <a href="{{ route('pemilik.kos.index') }}"
                                class="text-sm text-primary-400 hover:text-primary-300 flex items-center">
                                Lihat semua
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($recentKos as $kos)
                                        <a href="{{ route('pemilik.kos.show', $kos->id_kos) }}"
                                            class="card-hover bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-primary-500/50 transition-all duration-300">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="font-semibold text-white truncate">{{ $kos->nama_kos }}</h4>
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full 
                                                    {{ $kos->status_kos == 'aktif' ? 'bg-green-900/30 text-green-300' :
                                ($kos->status_kos == 'pending' ? 'bg-yellow-900/30 text-yellow-300' : 'bg-red-900/30 text-red-300') }}">
                                                    {{ $kos->status_kos }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-dark-muted mb-3 truncate flex items-center">
                                                <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>
                                                {{ $kos->alamat }}
                                            </p>
                                            <div class="flex items-center justify-between mt-3">
                                                <div class="text-sm text-dark-muted flex items-center">
                                                    <i class="fas fa-users mr-2"></i>
                                                    {{ $kos->jenis_kos }}
                                                </div>
                                                <div class="text-sm font-medium text-white">
                                                    {{ $kos->kamar_tersedia }} kamar tersedia
                                                </div>
                                            </div>
                                        </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
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
                            class="block w-full px-4 py-3 border-2 border-dashed border-dark-border rounded-xl text-center cursor-pointer hover:border-primary-500 transition">
                            <i class="fas fa-cloud-upload-alt text-primary-400 text-xl mb-2"></i>
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
                        class="px-4 py-2.5 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 flex items-center">
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

                fetch('{{ route("pemilik.profile.upload-photo") }}', {
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
                            submitBtn.classList.remove('from-primary-500', 'to-indigo-500', 'hover:from-primary-600', 'hover:to-indigo-600');
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