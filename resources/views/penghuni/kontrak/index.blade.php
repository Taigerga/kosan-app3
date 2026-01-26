@extends('layouts.app')

@section('title', 'Riwayat Kontrak - AyoKos')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="bg-dark-card/50 border border-dark-border rounded-xl p-4">
        <nav class="flex" aria-label="Breadcrumb">
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
                        <a href="{{ route('penghuni.kontrak.index') }}" class="inline-flex items-center text-sm font-medium text-white">
                            <i class="fas fa-file-contract mr-2"></i>
                            Riwayat Kontrak
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                <i class="fas fa-file-contract mr-3"></i>
                    Riwayat Kontrak Saya</h1>
                <p class="text-dark-muted">Kelola dan pantau semua kontrak kos Anda</p>
            </div>
            <a href="{{ route('public.kos.index') }}" 
            class="mt-4 md:mt-0 px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i>
                Ajukan Kontrak Baru
            </a>
        </div>
    </div>
    <!-- Notifications -->
    @if(session('success'))
    <div class="bg-green-900/30 border border-green-800/50 text-green-300 px-4 py-3 rounded-xl backdrop-blur-sm">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-400"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-900/30 border border-red-800/50 text-red-300 px-4 py-3 rounded-xl backdrop-blur-sm">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-red-400"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Kontrak List -->
    <div class="space-y-4">
        @forelse($kontrak as $k)
        <div class="card-hover bg-dark-card border border-dark-border rounded-2xl p-5 transition-all duration-300
            {{ $k->status_kontrak == 'aktif' ? 'hover:border-green-500/50' : 
               ($k->status_kontrak == 'pending' ? 'hover:border-yellow-500/50' : 
               ($k->status_kontrak == 'ditolak' ? 'hover:border-red-500/50' : 'hover:border-gray-500/50')) }}">
            
            <div class="flex flex-col lg:flex-row lg:items-start justify-between">
                <!-- Left Content -->
                <div class="flex-1">
                    <!-- Header with Status -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                        <div class="flex items-center space-x-3 mb-3 md:mb-0">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br 
                                {{ $k->status_kontrak == 'aktif' ? 'from-green-900/30 to-green-800/30' : 
                                   ($k->status_kontrak == 'pending' ? 'from-yellow-900/30 to-yellow-800/30' : 
                                   ($k->status_kontrak == 'ditolak' ? 'from-red-900/30 to-red-800/30' : 'from-gray-900/30 to-gray-800/30')) }}
                                flex items-center justify-center">
                                <i class="fas fa-home 
                                    {{ $k->status_kontrak == 'aktif' ? 'text-green-400' : 
                                       ($k->status_kontrak == 'pending' ? 'text-yellow-400' : 
                                       ($k->status_kontrak == 'ditolak' ? 'text-red-400' : 'text-gray-400')) }}"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">{{ $k->kos->nama_kos }}</h3>
                                <div class="flex items-center space-x-3 mt-1">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $k->status_kontrak == 'aktif' ? 'bg-green-900/30 text-green-300 border border-green-800/30' : 
                                           ($k->status_kontrak == 'pending' ? 'bg-yellow-900/30 text-yellow-300 border border-yellow-800/30' : 
                                           ($k->status_kontrak == 'ditolak' ? 'bg-red-900/30 text-red-300 border border-red-800/30' : 'bg-gray-900/30 text-gray-300 border border-gray-800/30')) }}">
                                        {{ ucfirst($k->status_kontrak) }}
                                    </span>
                                    <span class="text-xs text-dark-muted">
                                        {{ $k->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price -->
                        <div class="text-right">
                            <div class="text-xl font-bold text-white">
                                Rp {{ number_format($k->harga_sewa, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-dark-muted">per bulan</div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <!-- Kamar Info -->
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-3">
                            <div class="flex items-center space-x-2 mb-1">
                                <i class="fas fa-door-closed text-primary-400 text-sm"></i>
                                <span class="text-sm text-dark-muted">Kamar</span>
                            </div>
                            <div class="text-white font-medium">{{ $k->kamar->nomor_kamar }}</div>
                            <div class="text-xs text-dark-muted">{{ $k->kamar->tipe_kamar }}</div>
                        </div>

                        <!-- Durasi -->
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-3">
                            <div class="flex items-center space-x-2 mb-1">
                                <i class="fas fa-calendar-alt text-green-400 text-sm"></i>
                                <span class="text-sm text-dark-muted">Durasi</span>
                            </div>
                            <div class="text-white font-medium">{{ $k->durasi_sewa }} bulan</div>
                        </div>

                        <!-- Tanggal Mulai -->
                        @if($k->tanggal_mulai)
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-3">
                            <div class="flex items-center space-x-2 mb-1">
                                <i class="fas fa-play-circle text-blue-400 text-sm"></i>
                                <span class="text-sm text-dark-muted">Mulai</span>
                            </div>
                            <div class="text-white font-medium">{{ $k->tanggal_mulai ? $k->tanggal_mulai->format('d M Y') : '-' }}</div>
                        </div>
                        @endif

                        <!-- Tanggal Selesai -->
                        @if($k->tanggal_selesai)
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-3">
                            <div class="flex items-center space-x-2 mb-1">
                                <i class="fas fa-flag-checkered text-purple-400 text-sm"></i>
                                <span class="text-sm text-dark-muted">Selesai</span>
                            </div>
                            <div class="text-white font-medium">{{ $k->tanggal_selesai ? $k->tanggal_selesai->format('d M Y') : '-' }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Rejection Reason -->
                    @if($k->alasan_ditolak)
                    <div class="mt-4 p-4 bg-red-900/20 border border-red-800/30 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-exclamation-triangle text-red-400 mt-1"></i>
                            <div>
                                <div class="text-sm font-medium text-red-300 mb-1">Alasan Penolakan</div>
                                <p class="text-sm text-red-200/80">{{ $k->alasan_ditolak }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 lg:mt-0 lg:ml-4 flex lg:flex-col space-x-2 lg:space-x-0 lg:space-y-2">
                    <a href="{{ route('penghuni.kontrak.show', $k->id_kontrak) }}" 
                       class="flex items-center justify-center px-4 py-2 bg-gradient-to-r from-primary-500 to-indigo-500 hover:from-primary-600 hover:to-indigo-600 text-white rounded-lg transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-eye mr-2"></i>
                        <span class="hidden lg:inline">Detail</span>
                    </a>
                    
                    <!-- Additional actions based on status -->
                    @if($k->status_kontrak == 'aktif' && $k->tanggal_selesai > now())
                    <a href="{{ route('penghuni.pembayaran.create') }}?kontrak={{ $k->id_kontrak }}" 
                       class="flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-credit-card mr-2"></i>
                        <span class="hidden lg:inline">Bayar</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-8 text-center">
            <div class="w-24 h-24 bg-gradient-to-br from-primary-900/30 to-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-home text-4xl text-primary-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">Belum Ada Kontrak</h3>
            <p class="text-dark-muted mb-6 max-w-md mx-auto">
                Anda belum memiliki riwayat kontrak kos. Mulai cari kos yang sesuai dengan kebutuhan Anda.
            </p>
            <a href="{{ route('public.kos.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-search mr-2"></i>
                Cari Kos Sekarang
            </a>
        </div>
        @endforelse
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-dark-border">
            <div class="flex items-center justify-between">
                <div class="text-sm text-dark-muted">
                    Menampilkan {{ $kontrak->firstItem() }} - {{ $kontrak->lastItem() }} dari {{ $kontrak->total() }} kontrak
                </div>
                <div class="flex space-x-2">
                    {{ $kontrak->links('vendor.pagination.custom-dark') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    @if($kontrak->count() > 0)
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-dark-card border border-dark-border rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">{{ $kontrak->count() }}</div>
                    <div class="text-sm text-dark-muted">Total Kontrak</div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center">
                    <i class="fas fa-file-contract text-blue-400"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-dark-card border border-dark-border rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">
                        {{ $kontrak->where('status_kontrak', 'aktif')->count() }}
                    </div>
                    <div class="text-sm text-dark-muted">Aktif</div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-900/30 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-dark-card border border-dark-border rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">
                        {{ $kontrak->where('status_kontrak', 'pending')->count() }}
                    </div>
                    <div class="text-sm text-dark-muted">Menunggu</div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-900/30 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-400"></i>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection