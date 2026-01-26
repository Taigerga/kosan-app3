@extends('layouts.app')

@section('title', 'Detail Kontrak - AyoKos')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="bg-dark-card/50 border border-dark-border rounded-xl p-4">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('penghuni.dashboard') }}" class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                    <i class="fas fa-gauge mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="inline-flex items-center">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                    <a href="{{ route('penghuni.kontrak.index') }}" class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                        <i class="fas fa-file-contract mr-2"></i>
                        Riwayat Kontrak
                    </a>
                </div>
            </li>
            <li class="inline-flex items-center">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                    <span class="inline-flex items-center text-sm font-medium text-white">
                        <i class="fas fa-pencil mr-2"></i>
                        Detail Kontrak
                    </span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Notifications -->
    @if(session('success'))
    <div class="bg-green-900/30 border border-green-800/50 text-green-300 px-4 py-3 rounded-xl mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-900/30 border border-red-800/50 text-red-300 px-4 py-3 rounded-xl mb-6">
        <div class="flex items-center">
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
                    <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold bg-yellow-900/30 text-yellow-300 border border-yellow-800/30">
                        <i class="fas fa-clock mr-2"></i>
                        Menunggu Persetujuan
                    </span>
                    @elseif($kontrak->status_kontrak === 'aktif')
                    <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold bg-green-900/30 text-green-300 border border-green-800/30">
                        <i class="fas fa-check-circle mr-2"></i>
                        Kontrak Aktif
                    </span>
                    @elseif($kontrak->status_kontrak === 'selesai')
                    <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold bg-blue-900/30 text-blue-300 border border-blue-800/30">
                        <i class="fas fa-check-double mr-2"></i>
                        Kontrak Selesai
                    </span>
                    @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold bg-red-900/30 text-red-300 border border-red-800/30">
                        <i class="fas fa-times-circle mr-2"></i>
                        Ditolak
                    </span>
                    @endif
                </div>
                
                <!-- ID Kontrak -->
                <div class="text-sm text-dark-muted">
                    ID: <span class="font-mono text-white">{{ $kontrak->id_kontrak }}</span>
                </div>
            </div>

            <!-- Informasi Kos -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-home text-primary-400 mr-3"></i>
                    {{ $kontrak->kos->nama_kos }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-dark-border/50 rounded-lg">
                                <i class="fas fa-map-marker-alt text-primary-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted mb-1">Alamat</p>
                                <p class="font-medium text-white">{{ $kontrak->kos->alamat }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-dark-border/50 rounded-lg">
                                <i class="fas fa-door-closed text-green-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted mb-1">Kamar</p>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-white">Kamar {{ $kontrak->kamar->nomor_kamar }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full bg-dark-border/50 text-dark-muted">
                                        {{ $kontrak->kamar->tipe_kamar }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Kontrak -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-file-contract text-blue-400 mr-3"></i>
                    Detail Kontrak
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-start space-x-3 mb-6">
                            <div class="p-2 bg-dark-border/50 rounded-lg">
                                <i class="fas fa-calendar-plus text-primary-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted mb-1">Tanggal Pendaftaran</p>
                                <p class="font-medium text-white">{{ $kontrak->tanggal_daftar->format('d M Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-dark-border/50 rounded-lg">
                                <i class="fas fa-calendar-alt text-green-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted mb-1">Durasi Sewa</p>
                                <p class="font-medium text-white">{{ $kontrak->durasi_sewa }} bulan</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($kontrak->tanggal_mulai)
                    <div>
                        <div class="flex items-start space-x-3 mb-6">
                            <div class="p-2 bg-dark-border/50 rounded-lg">
                                <i class="fas fa-calendar-day text-yellow-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted mb-1">Tanggal Mulai</p>
                                <p class="font-medium text-white">{{ $kontrak->tanggal_mulai ? $kontrak->tanggal_mulai->format('d M Y') : 'Menunggu pembayaran pertama' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-dark-border/50 rounded-lg">
                                <i class="fas fa-calendar-check text-red-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark-muted mb-1">Tanggal Selesai</p>
                                <p class="font-medium text-white">{{ $kontrak->tanggal_selesai ? $kontrak->tanggal_selesai->format('d M Y') : 'Menunggu pembayaran pertama' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Harga Sewa -->
            <div class="card-hover bg-gradient-to-br from-primary-900/20 to-indigo-900/20 border border-primary-800/30 rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-wallet text-yellow-400 mr-3"></i>
                    Harga Sewa
                </h3>
                <div class="text-4xl font-bold text-white mb-2">
                    Rp {{ number_format($kontrak->harga_sewa, 0, ',', '.') }}
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-primary-200 text-sm">
                        Per {{ $kontrak->durasi_sewa }} bulan
                    </p>
                    @if($kontrak->status_kontrak === 'aktif' && !$kontrak->sudahBerakhir)
                    <div class="text-sm text-dark-muted">
                        <i class="fas fa-clock mr-1"></i>
                        Berakhir dalam {{ $kontrak->sisaHari ?? '?' }} hari
                    </div>
                    @endif
                </div>
            </div>

            <!-- Dokumen -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-file-alt text-purple-400 mr-3"></i>
                    Dokumen
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-sm text-dark-muted mb-3">Foto KTP</p>
                        @if($kontrak->foto_ktp)
                        <div class="relative">
                            <div class="border-2 border-dark-border rounded-xl overflow-hidden max-w-sm">
                                <img src="{{ asset('storage/' . $kontrak->foto_ktp) }}" 
                                     alt="Foto KTP" 
                                     class="w-full h-auto object-cover">
                            </div>
                            <a href="{{ asset('storage/' . $kontrak->foto_ktp) }}" 
                               target="_blank"
                               class="inline-flex items-center mt-3 text-primary-400 hover:text-primary-300 transition">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Lihat Fullsize
                            </a>
                        </div>
                        @else
                        <div class="text-center py-4 border-2 border-dashed border-dark-border rounded-xl">
                            <i class="fas fa-file-image text-3xl text-dark-muted mb-2"></i>
                            <p class="text-dark-muted">Tidak ada dokumen</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Alasan Ditolak -->
            @if($kontrak->status_kontrak === 'ditolak' && $kontrak->alasan_ditolak)
            <div class="bg-red-900/10 border border-red-800/30 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="p-3 bg-red-900/30 rounded-lg mr-4">
                        <i class="fas fa-times-circle text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-red-300 mb-2">Alasan Penolakan</h3>
                        <p class="text-red-200">{{ $kontrak->alasan_ditolak }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            @if($kontrak->status_kontrak === 'aktif' && !$kontrak->sudahBerakhir)
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-cogs text-yellow-400 mr-3"></i>
                    Aksi Kontrak
                </h3>
                <div class="flex flex-wrap gap-4">
                    <button onclick="openExtendModal()" 
                            class="px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Perpanjang Kontrak
                    </button>
                    
                    <a href="{{ route('penghuni.pembayaran.create', ['kontrak_id' => $kontrak->id_kontrak]) }}" 
                       class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-credit-card mr-2"></i>
                        Bayar Sewa
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informasi Penghuni -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-user-circle text-blue-400 mr-3"></i>
                    Informasi Penghuni
                </h3>
                
                <div class="space-y-5">
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <i class="fas fa-user text-primary-400 w-4"></i>
                            <p class="text-sm text-dark-muted">Nama</p>
                        </div>
                        <p class="font-semibold text-white">{{ $kontrak->penghuni->nama ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <i class="fas fa-id-card text-green-400 w-4"></i>
                            <p class="text-sm text-dark-muted">NIK</p>
                        </div>
                        <p class="font-semibold text-white">{{ $kontrak->penghuni->nik ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <i class="fas fa-phone text-yellow-400 w-4"></i>
                            <p class="text-sm text-dark-muted">No. Telepon</p>
                        </div>
                        <p class="font-semibold text-white">{{ $kontrak->penghuni->no_hp ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <i class="fas fa-envelope text-purple-400 w-4"></i>
                            <p class="text-sm text-dark-muted">Email</p>
                        </div>
                        <p class="font-semibold text-white break-words">{{ $kontrak->penghuni->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Status Kontrak Timeline -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-history text-indigo-400 mr-3"></i>
                    Timeline Kontrak
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-white">Pendaftaran</p>
                            <p class="text-xs text-dark-muted">{{ $kontrak->tanggal_daftar->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($kontrak->tanggal_mulai)
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-blue-500 mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-white">Mulai Kontrak</p>
                            <p class="text-xs text-dark-muted">{{ $kontrak->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                    </div>
                    
                     @if($kontrak->tanggal_selesai)
                     <div class="flex items-center">
                         <div class="w-3 h-3 rounded-full bg-yellow-500 mr-3"></div>
                         <div>
                             <p class="text-sm font-medium text-white">Berakhir Kontrak</p>
                             <p class="text-xs text-dark-muted">{{ $kontrak->tanggal_selesai->format('d M Y') }}</p>
                         </div>
                     </div>
                     @endif
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-link text-primary-400 mr-3"></i>
                    Tautan Cepat
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('penghuni.pembayaran.index') }}" 
                       class="flex items-center justify-between p-3 rounded-lg bg-dark-border/30 hover:bg-dark-border/50 transition">
                        <div class="flex items-center">
                            <i class="fas fa-credit-card text-green-400 mr-3"></i>
                            <span class="text-white">Lihat Pembayaran</span>
                        </div>
                        <i class="fas fa-chevron-right text-dark-muted"></i>
                    </a>
                    
                    <a href="{{ route('penghuni.kontrak.index') }}" 
                       class="flex items-center justify-between p-3 rounded-lg bg-dark-border/30 hover:bg-dark-border/50 transition">
                        <div class="flex items-center">
                            <i class="fas fa-file-contract text-blue-400 mr-3"></i>
                            <span class="text-white">Semua Kontrak</span>
                        </div>
                        <i class="fas fa-chevron-right text-dark-muted"></i>
                    </a>
                    
                    <a href="{{ route('public.kos.show', $kontrak->kos->id_kos) }}" 
                       class="flex items-center justify-between p-3 rounded-lg bg-dark-border/30 hover:bg-dark-border/50 transition">
                        <div class="flex items-center">
                            <i class="fas fa-home text-yellow-400 mr-3"></i>
                            <span class="text-white">Detail Kos</span>
                        </div>
                        <i class="fas fa-chevron-right text-dark-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Extend Modal -->
<div id="extendModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-dark-card border border-dark-border rounded-2xl p-8 max-w-md w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-calendar-plus text-primary-400 mr-3"></i>
                Perpanjang Kontrak
            </h2>
            <button onclick="closeExtendModal()" class="text-dark-muted hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('penghuni.kontrak.extend', $kontrak->id_kontrak) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-white font-medium mb-3">
                    Durasi Perpanjangan <span class="text-red-400">*</span>
                </label>
                
                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <input type="number" 
                           name="durasi_perpanjangan" 
                           min="1" 
                           max="24"
                           required 
                           class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                           placeholder="Masukkan jumlah bulan">
                </div>
                
                @error('durasi_perpanjangan')
                <p class="text-red-400 text-sm mt-2">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
                @enderror
                
                <div class="mt-3 text-sm text-dark-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    Maksimal 24 bulan per perpanjangan
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-500 to-indigo-500 text-white px-6 py-3 rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300">
                    <i class="fas fa-check mr-2"></i>
                    Perpanjang
                </button>
                <button type="button" 
                        onclick="closeExtendModal()" 
                        class="flex-1 bg-dark-border text-white px-6 py-3 rounded-xl hover:bg-dark-border/80 transition">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openExtendModal() {
        document.getElementById('extendModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeExtendModal() {
        document.getElementById('extendModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('extendModal');
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeExtendModal();
            }
        });

        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeExtendModal();
            }
        });
    });
</script>
@endsection