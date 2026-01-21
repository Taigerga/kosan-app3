@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="p-4 md:p-6">
    <div class="max-w-3xl mx-auto">
        <div class="space-y-6">
        <!-- Breadcrumb -->
        <div class="bg-dark-card/50 border border-dark-border rounded-xl p-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('penghuni.dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                            <i class="fas fa-gauge mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                            <a href="{{ route('penghuni.pembayaran.index') }}"
                                class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                                <i class="fas fa-credit-card mr-2"></i>
                                Riwayat Pembayaran
                            </a>
                        </div>
                    </li>
                    <li class="inline-flex items-center">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-white">
                                <i class="fas fa-eye mr-2"></i>
                                Detail Pembayaran
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <!-- Header -->
        <div class="mb-6">
            <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2 flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-receipt text-white text-xl"></i>
                        </div>
                        <span>Detail Pembayaran</span>
                    </h1>
                    <p class="text-dark-muted">Informasi lengkap transaksi pembayaran kos</p>
                </div>
                <a href="{{ route('penghuni.pembayaran.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-dark-card border border-dark-border text-dark-text rounded-xl hover:border-primary-500 hover:text-primary-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
            <!-- Status Banner -->
            <div class="bg-gradient-to-r 
                {{ $pembayaran->status_pembayaran == 'lunas' ? 'from-green-900/50 to-emerald-900/50 border-b border-green-800/30' : 
                   ($pembayaran->status_pembayaran == 'pending' ? 'from-yellow-900/50 to-amber-900/50 border-b border-yellow-800/30' : 
                   ($pembayaran->status_pembayaran == 'terlambat' ? 'from-red-900/50 to-rose-900/50 border-b border-red-800/30' : 
                   'from-gray-900/50 to-slate-900/50 border-b border-gray-800/30')) }} p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <span class="text-xs font-medium text-white/70">Status Pembayaran</span>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="px-4 py-2 rounded-full text-sm font-medium
                                {{ $pembayaran->status_pembayaran == 'lunas' ? 'bg-green-900/80 text-green-300' : 
                                   ($pembayaran->status_pembayaran == 'pending' ? 'bg-yellow-900/80 text-yellow-300' : 
                                   ($pembayaran->status_pembayaran == 'terlambat' ? 'bg-red-900/80 text-red-300' : 
                                   'bg-gray-900/80 text-gray-300')) }} shadow-lg">
                                {{ ucfirst($pembayaran->status_pembayaran) }}
                            </span>
                            <span class="text-white font-bold text-xl">
                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 text-right">
                        <span class="text-xs text-white/70 block">No. Transaksi</span>
                        <span class="text-sm text-white font-mono">#PAY-{{ str_pad($pembayaran->id_pembayaran, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Kos Information -->
                        <div class="bg-dark-bg/30 border border-dark-border rounded-xl p-5">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-home text-primary-400 mr-3"></i>
                                Informasi Kos
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-muted">Nama Kos</span>
                                    <span class="font-medium text-white">{{ $pembayaran->kontrak->kos->nama_kos ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-muted">Alamat</span>
                                    <span class="font-medium text-white text-right">{{ $pembayaran->kontrak->kos->alamat ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-muted">Kamar</span>
                                    <span class="font-medium text-white">No. {{ $pembayaran->kontrak->kamar->nomor_kamar ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-dark-bg/30 border border-dark-border rounded-xl p-5">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-credit-card text-purple-400 mr-3"></i>
                                Metode Pembayaran
                            </h3>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas 
                                            {{ $pembayaran->metode_pembayaran == 'transfer' ? 'fa-university' : 
                                               ($pembayaran->metode_pembayaran == 'cash' ? 'fa-money-bill-wave' : 'fa-qrcode') }} 
                                            text-purple-400"></i>
                                    </div>
                                    <div>
                                        <span class="block font-medium text-white capitalize">{{ $pembayaran->metode_pembayaran }}</span>
                                        <span class="text-xs text-dark-muted">Metode yang digunakan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Payment Timeline -->
                        <div class="bg-dark-bg/30 border border-dark-border rounded-xl p-5">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-calendar-alt text-blue-400 mr-3"></i>
                                Timeline Pembayaran
                            </h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-900/30 flex items-center justify-center">
                                            <i class="fas fa-calendar text-blue-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="block text-sm text-dark-muted">Jatuh Tempo</span>
                                            <span class="text-white font-medium">{{ \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full bg-dark-border text-dark-muted">Tanggal</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full 
                                            {{ $pembayaran->tanggal_bayar ? 'bg-green-900/30' : 'bg-gray-900/30' }} flex items-center justify-center">
                                            <i class="fas 
                                                {{ $pembayaran->tanggal_bayar ? 'fa-check text-green-400' : 'fa-clock text-gray-400' }} 
                                                text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="block text-sm text-dark-muted">Tanggal Bayar</span>
                                            <span class="text-white font-medium">
                                                {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d M Y H:i') : 'Menunggu pembayaran' }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $pembayaran->tanggal_bayar ? 'bg-green-900/30 text-green-300' : 'bg-yellow-900/30 text-yellow-300' }}">
                                        {{ $pembayaran->tanggal_bayar ? 'Telah Dibayar' : 'Pending' }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-purple-900/30 flex items-center justify-center">
                                            <i class="fas fa-calendar-check text-purple-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="block text-sm text-dark-muted">Periode</span>
                                            <span class="text-white font-medium">{{ \Carbon\Carbon::createFromFormat('Y-m', $pembayaran->bulan_tahun)->format('F Y') }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full bg-dark-border text-dark-muted">Bulan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="bg-dark-bg/30 border border-dark-border rounded-xl p-5">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-info-circle text-indigo-400 mr-3"></i>
                                Informasi Tambahan
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="text-dark-muted">Keterangan</span>
                                    <span class="font-medium text-white text-right">{{ $pembayaran->keterangan ?? 'Tidak ada keterangan' }}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-dark-muted">Dibuat pada</span>
                                    <span class="font-medium text-white">{{ $pembayaran->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-dark-muted">Terakhir diupdate</span>
                                    <span class="font-medium text-white">{{ $pembayaran->updated_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bukti Pembayaran Section -->
                @if($pembayaran->bukti_pembayaran)
                <div class="mt-8 border-t border-dark-border pt-8">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-file-image text-green-400 mr-3"></i>
                        Bukti Pembayaran
                    </h3>
                    <div class="bg-dark-bg/30 border border-dark-border rounded-xl p-5">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                            <div>
                                <span class="block text-sm text-dark-muted mb-1">File Bukti Pembayaran</span>
                                <span class="text-white font-medium">bukti_pembayaran_{{ $pembayaran->id_pembayaran }}.jpg</span>
                            </div>
                            <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Buka di Tab Baru
                            </a>
                        </div>
                        
                        <div class="border border-dark-border rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                 alt="Bukti Pembayaran" 
                                 class="w-full h-auto max-h-96 object-contain bg-dark-bg">
                        </div>
                        
                        <div class="mt-4 text-xs text-dark-muted text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Klik gambar untuk memperbesar
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-8 border-t border-dark-border pt-8">
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-white mb-2">Tidak Ada Bukti Pembayaran</h4>
                        <p class="text-dark-muted">Bukti pembayaran belum diunggah</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="border-t border-dark-border p-6">
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex items-center space-x-2 text-dark-muted">
                        <i class="fas fa-question-circle"></i>
                        <span class="text-sm">Butuh bantuan? Hubungi admin</span>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('penghuni.pembayaran.index') }}" 
                           class="px-5 py-2.5 bg-dark-card border border-dark-border text-white rounded-xl hover:border-primary-500 hover:text-primary-300 transition">
                            <i class="fas fa-list mr-2"></i>
                            Riwayat Pembayaran
                        </a>
                        @if($pembayaran->status_pembayaran == 'pending')
                        <a href="#" 
                           class="px-5 py-2.5 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition shadow-lg">
                            <i class="fas fa-credit-card mr-2"></i>
                            Bayar Sekarang
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Status Summary -->
            <div class="bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-lg 
                        {{ $pembayaran->status_pembayaran == 'lunas' ? 'bg-green-900/30' : 
                           ($pembayaran->status_pembayaran == 'pending' ? 'bg-yellow-900/30' : 
                           ($pembayaran->status_pembayaran == 'terlambat' ? 'bg-red-900/30' : 
                           'bg-gray-900/30')) }} flex items-center justify-center">
                        <i class="fas 
                            {{ $pembayaran->status_pembayaran == 'lunas' ? 'fa-check-circle text-green-400' : 
                               ($pembayaran->status_pembayaran == 'pending' ? 'fa-clock text-yellow-400' : 
                               ($pembayaran->status_pembayaran == 'terlambat' ? 'fa-exclamation-circle text-red-400' : 
                               'fa-question-circle text-gray-400')) }}"></i>
                    </div>
                    <div>
                        <span class="block text-sm text-dark-muted">Status</span>
                        <span class="font-semibold text-white">{{ ucfirst($pembayaran->status_pembayaran) }}</span>
                    </div>
                </div>
            </div>

            <!-- Amount Summary -->
            <div class="bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-blue-400"></i>
                    </div>
                    <div>
                        <span class="block text-sm text-dark-muted">Jumlah</span>
                        <span class="font-semibold text-white">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Method Summary -->
            <div class="bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-900/30 flex items-center justify-center">
                        <i class="fas fa-wallet text-purple-400"></i>
                    </div>
                    <div>
                        <span class="block text-sm text-dark-muted">Metode</span>
                        <span class="font-semibold text-white capitalize">{{ $pembayaran->metode_pembayaran }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal for Image -->
@if($pembayaran->bukti_pembayaran)
<div id="imageModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="relative max-w-4xl max-h-[90vh]">
        <button onclick="closeImageModal()" 
                class="absolute -top-12 right-0 text-white hover:text-primary-300 text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
             alt="Bukti Pembayaran" 
             class="max-w-full max-h-[80vh] object-contain">
    </div>
</div>

<script>
    function openImageModal() {
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Click image to open modal
    document.addEventListener('DOMContentLoaded', function() {
        const image = document.querySelector('img[alt="Bukti Pembayaran"]');
        if (image) {
            image.addEventListener('click', openImageModal);
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target.id === 'imageModal') {
                closeImageModal();
            }
        });
    });
</script>
@endif
@endsection