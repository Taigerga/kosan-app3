@extends('layouts.app')

@section('title', 'Detail Kontrak - AyoKos')

@section('content')
<div class="p-4 md:p-6">
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
                        <a href="{{ route('pemilik.kontrak.index') }}" class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                            <i class="fas fa-file-contract mr-2"></i>
                            Kelola Kontrak
                        </a>
                    </div>
                </li>
                <li class="inline-flex items-center">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                        <span class="inline-flex items-center text-sm font-medium text-white">
                            <i class="fas fa-eye mr-2"></i>
                            Detail Kontrak
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('pemilik.kontrak.index') }}" 
           class="inline-flex items-center text-primary-400 hover:text-primary-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Kelola Kontrak
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-900/30 border border-green-800/50 rounded-xl">
        <div class="flex items-center text-green-400">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-900/30 border border-red-800/50 rounded-xl">
        <div class="flex items-center text-red-400">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Badge -->
            <div class="flex items-center justify-between">
                <div>
                    @if($kontrak->status_kontrak === 'pending')
                        <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold bg-yellow-900/30 text-yellow-300 border border-yellow-800/50">
                            <i class="fas fa-clock mr-2"></i>
                            Menunggu Persetujuan
                        </span>
                    @elseif($kontrak->status_kontrak === 'aktif')
                        <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold bg-green-900/30 text-green-300 border border-green-800/50">
                            <i class="fas fa-check-circle mr-2"></i>
                            Kontrak Aktif
                        </span>
                    @elseif($kontrak->status_kontrak === 'selesai')
                        <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold bg-blue-900/30 text-blue-300 border border-blue-800/50">
                            <i class="fas fa-flag-checkered mr-2"></i>
                            Kontrak Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold bg-red-900/30 text-red-300 border border-red-800/50">
                            <i class="fas fa-times-circle mr-2"></i>
                            Ditolak
                        </span>
                    @endif
                </div>
                
                <!-- Quick Actions -->
                @if($kontrak->status_kontrak === 'pending')
                <div class="flex space-x-3">
                    <form action="{{ route('pemilik.kontrak.approve', $kontrak->id_kontrak) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Setujui
                        </button>
                    </form>
                    <button onclick="openRejectModal()" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Tolak
                    </button>
                </div>
                @endif
            </div>

            <!-- Informasi Kos & Kamar -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-2">{{ $kontrak->kos->nama_kos }}</h2>
                        <div class="flex items-center text-dark-muted">
                            <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>
                            <span>{{ $kontrak->kos->alamat }}</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-primary-900/30 text-primary-300 border border-primary-800/50">
                        <i class="fas fa-door-open mr-1"></i>
                        Kamar {{ $kontrak->kamar->nomor_kamar }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-dark-muted mb-1">Tipe Kamar</p>
                        <p class="font-medium text-white">{{ $kontrak->kamar->tipe_kamar }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-dark-muted mb-1">Status Kamar</p>
                        <p class="font-medium capitalize 
                            {{ $kontrak->kamar->status_kamar == 'tersedia' ? 'text-green-400' : 
                               ($kontrak->kamar->status_kamar == 'terisi' ? 'text-blue-400' : 'text-yellow-400') }}">
                            {{ $kontrak->kamar->status_kamar }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Kontrak -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-file-contract text-primary-400 mr-3"></i>
                    Detail Kontrak
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-dark-bg/50 p-4 rounded-xl">
                        <p class="text-sm text-dark-muted mb-1">Tanggal Pendaftaran</p>
                        <p class="font-semibold text-white">{{ $kontrak->tanggal_daftar->format('d M Y') }}</p>
                    </div>
                    <div class="bg-dark-bg/50 p-4 rounded-xl">
                        <p class="text-sm text-dark-muted mb-1">Durasi Sewa</p>
                        <p class="font-semibold text-white">{{ $kontrak->durasi_sewa }} bulan</p>
                    </div>
                    
                    @if($kontrak->tanggal_mulai)
                    <div class="bg-dark-bg/50 p-4 rounded-xl">
                        <p class="text-sm text-dark-muted mb-1">Tanggal Mulai</p>
                        <p class="font-semibold text-white">{{ $kontrak->tanggal_mulai->format('d M Y') }}</p>
                    </div>
                    <div class="bg-dark-bg/50 p-4 rounded-xl">
                        <p class="text-sm text-dark-muted mb-1">Tanggal Selesai</p>
                        <p class="font-semibold text-white">{{ $kontrak->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Penghuni -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-user text-green-400 mr-3"></i>
                    Data Calon Penghuni
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user text-green-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted">Nama Lengkap</p>
                                <p class="font-semibold text-white">{{ $kontrak->penghuni->nama ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-id-card text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted">NIK</p>
                                <p class="font-semibold text-white">{{ $kontrak->penghuni->nik ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-phone text-purple-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted">No. Telepon</p>
                                <p class="font-semibold text-white">{{ $kontrak->penghuni->no_telepon ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500/20 to-yellow-500/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-envelope text-orange-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted">Email</p>
                                <p class="font-semibold text-white break-words">{{ $kontrak->penghuni->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Harga Sewa -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-wallet text-yellow-400 mr-3"></i>
                    Informasi Pembayaran
                </h3>
                
                <div class="bg-dark-bg/50 p-6 rounded-xl">
                    <div class="text-center mb-4">
                        <p class="text-sm text-dark-muted">Harga Sewa per Bulan</p>
                        <div class="text-3xl font-bold text-white mt-1">
                            Rp {{ number_format($kontrak->harga_sewa, 0, ',', '.') }}
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-sm text-dark-muted">
                            Total untuk {{ $kontrak->durasi_sewa }} bulan:
                            <span class="text-white font-semibold ml-2">
                                Rp {{ number_format($kontrak->harga_sewa * $kontrak->durasi_sewa, 0, ',', '.') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dokumen KTP -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-file-alt text-blue-400 mr-3"></i>
                    Dokumen
                </h3>
                
                <div>
                    <p class="text-sm text-dark-muted mb-3">Foto KTP Penghuni</p>
                    @if($kontrak->foto_ktp)
                        <div class="border-2 border-dark-border rounded-xl overflow-hidden">
                            <img src="{{ asset('storage/' . $kontrak->foto_ktp) }}" 
                                 alt="Foto KTP" 
                                 class="w-full h-auto max-h-80 object-contain bg-dark-bg">
                        </div>
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $kontrak->foto_ktp) }}" 
                               target="_blank" 
                               class="inline-flex items-center text-primary-400 hover:text-primary-300 transition">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Buka dokumen lengkap
                            </a>
                        </div>
                    @else
                        <div class="bg-dark-bg/50 p-8 rounded-xl text-center">
                            <i class="fas fa-file-image text-4xl text-dark-muted mb-3"></i>
                            <p class="text-dark-muted">Tidak ada dokumen tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Alasan Ditolak -->
            @if($kontrak->status_kontrak === 'ditolak' && $kontrak->alasan_ditolak)
            <div class="bg-red-900/20 border border-red-800/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-red-300 mb-3 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alasan Penolakan
                </h3>
                <div class="bg-red-900/30 p-4 rounded-xl border border-red-800/30">
                    <p class="text-red-200">{{ $kontrak->alasan_ditolak }}</p>
                </div>
            </div>
            @endif

            <!-- Action Button untuk Aktif -->
            @if($kontrak->status_kontrak === 'aktif')
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Aksi Kontrak</h3>
                <form action="{{ route('pemilik.kontrak.selesai', $kontrak->id_kontrak) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center"
                            onclick="return confirm('Yakin ingin menandai kontrak ini sebagai selesai?')">
                        <i class="fas fa-flag-checkered mr-2"></i>
                        Tandai Kontrak Selesai
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 sticky top-6">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-history text-purple-400 mr-3"></i>
                    Timeline Kontrak
                </h3>
                
                <div class="space-y-6">
                    <div class="relative pl-8 pb-6">
                        <div class="absolute left-0 top-0 w-3 h-3 bg-primary-500 rounded-full"></div>
                        <div class="absolute left-[5px] top-3 w-[2px] h-full bg-dark-border"></div>
                        <div>
                            <p class="text-sm text-dark-muted">Diajukan</p>
                            <p class="font-semibold text-white">{{ $kontrak->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($kontrak->tanggal_mulai)
                    <div class="relative pl-8 pb-6">
                        <div class="absolute left-0 top-0 w-3 h-3 bg-green-500 rounded-full"></div>
                        <div class="absolute left-[5px] top-3 w-[2px] h-full bg-dark-border"></div>
                        <div>
                            <p class="text-sm text-dark-muted">Mulai Kontrak</p>
                            <p class="font-semibold text-white">{{ $kontrak->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="relative pl-8 pb-6">
                        <div class="absolute left-0 top-0 w-3 h-3 bg-blue-500 rounded-full"></div>
                        <div class="absolute left-[5px] top-3 w-[2px] h-full bg-dark-border"></div>
                        <div>
                            <p class="text-sm text-dark-muted">Berakhir Kontrak</p>
                            <p class="font-semibold text-white">{{ $kontrak->tanggal_selesai->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-0 w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <div>
                            <p class="text-sm text-dark-muted">Update Terakhir</p>
                            <p class="font-semibold text-white">{{ $kontrak->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Info -->
                <div class="mt-8 pt-6 border-t border-dark-border">
                    <h4 class="text-sm font-semibold text-dark-muted mb-3">Informasi Cepat</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-muted">ID Kontrak</span>
                            <span class="text-white font-mono">#{{ $kontrak->id_kontrak }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-muted">Status Penghuni</span>
                            <span class="capitalize 
                                {{ $kontrak->penghuni->status_penghuni == 'aktif' ? 'text-green-400' : 
                                   ($kontrak->penghuni->status_penghuni == 'calon' ? 'text-yellow-400' : 'text-red-400') }}">
                                {{ $kontrak->penghuni->status_penghuni ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($kontrak->status_kontrak === 'pending')
<div id="rejectModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-dark-card border border-dark-border rounded-2xl p-8 max-w-md w-full shadow-2xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-red-900/30 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-times text-red-400 text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Tolak Kontrak</h2>
        </div>
        
        <form action="{{ route('pemilik.kontrak.reject', $kontrak->id_kontrak) }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-white font-semibold mb-3">Alasan Penolakan <span class="text-red-400">*</span></label>
                <textarea name="alasan_ditolak" 
                          required 
                          rows="4"
                          class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/30 transition"
                          placeholder="Berikan alasan penolakan yang jelas dan konstruktif..."></textarea>
                @error('alasan_ditolak')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition font-semibold">
                    <i class="fas fa-times mr-2"></i>
                    Tolak Kontrak
                </button>
                <button type="button" 
                        onclick="closeRejectModal()" 
                        class="flex-1 px-4 py-3 bg-dark-border hover:bg-dark-border/80 text-white rounded-xl transition font-semibold">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('rejectModal');
        if (event.target === modal) {
            closeRejectModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeRejectModal();
        }
    });
</script>
@endsection