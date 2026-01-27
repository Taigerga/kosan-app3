@extends('layouts.app')

@section('title', 'Dashboard Penghuni - AyoKos')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                        <i class="fas fa-gauge mr-3"></i>
                        Halo, {{ $user->nama }}! 🎉</h1>
                    <p class="text-dark-muted">Kelola hunian dan aktivitas sewa Anda dengan mudah</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-900/30 text-green-300 border border-green-700/30">
                        <i class="fas fa-user mr-2"></i>
                        Penghuni Kos
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Kos Aktif Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-green-900/30">
                        <i class="fas fa-home text-green-400 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium px-2 py-1 rounded-full bg-green-900/20 text-green-300">
                        {{ $kontrakAktif->count() > 0 ? '+' . $kontrakAktif->count() : '0' }}
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">{{ $kontrakAktif->count() }}</h3>
                <p class="text-sm text-dark-muted">Kos Aktif</p>
            </div>

            <!-- Total Pembayaran Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-purple-900/30">
                        <i class="fas fa-wallet text-purple-400 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium px-2 py-1 rounded-full bg-purple-900/20 text-purple-300">
                        Total
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h3>
                <p class="text-sm text-dark-muted">Total Pembayaran</p>
            </div>

            <!-- Status Penghuni Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg 
                        {{ $user->status_penghuni == 'aktif' ? 'bg-blue-900/30' :
        ($user->status_penghuni == 'calon' ? 'bg-yellow-900/30' : 'bg-red-900/30') }}">
                        <i
                            class="fas 
                            {{ $user->status_penghuni == 'aktif' ? 'fa-check-circle text-blue-400' :
        ($user->status_penghuni == 'calon' ? 'fa-clock text-yellow-400' : 'fa-times-circle text-red-400') }} text-xl"></i>
                    </div>
                    <span
                        class="text-sm font-medium px-2 py-1 rounded-full 
                        {{ $user->status_penghuni == 'aktif' ? 'bg-blue-900/20 text-blue-300' :
        ($user->status_penghuni == 'calon' ? 'bg-yellow-900/20 text-yellow-300' : 'bg-red-900/20 text-red-300') }}">
                        Status
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1 capitalize">{{ ucfirst($user->status_penghuni) }}</h3>
                <p class="text-sm text-dark-muted">Status Penghuni</p>
            </div>

            <!-- Kontrak Berakhir Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-orange-900/30">
                        <i class="fas fa-clock text-orange-400 text-xl"></i>
                    </div>
                    @php
                        $berakhirSegera = $kontrakAktif->filter(function ($kontrak) {
                            return $kontrak->sisaHari <= 30 && !$kontrak->sudahBerakhir;
                        })->count();
                    @endphp
                    <span class="text-sm font-medium px-2 py-1 rounded-full 
                        {{ $berakhirSegera > 0 ? 'bg-orange-900/20 text-orange-300' : 'bg-green-900/20 text-green-300' }}">
                        {{ $berakhirSegera > 0 ? 'Perhatian' : 'Aman' }}
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">{{ $berakhirSegera }}</h3>
                <p class="text-sm text-dark-muted">Akan Berakhir</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-400 mr-3"></i>
                Aksi Cepat
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <a href="{{ route('public.kos.index') }}"
                    class="bg-gradient-to-br from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-search text-lg mb-1"></i>
                    <span class="text-sm font-medium">Cari Kos</span>
                </a>
                <a href="{{ route('penghuni.kontrak.index') }}"
                    class="bg-gradient-to-br from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-file-contract text-lg mb-1"></i>
                    <span class="text-sm font-medium">Kontrak Saya</span>
                </a>
                <a href="{{ route('penghuni.pembayaran.index') }}"
                    class="bg-gradient-to-br from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-credit-card text-lg mb-1"></i>
                    <span class="text-sm font-medium">Pembayaran</span>
                </a>
                <a href="{{ route('penghuni.reviews.history') }}"
                    class="bg-gradient-to-br from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-star text-lg mb-1"></i>
                    <span class="text-sm font-medium">Review Saya</span>
                </a>
                <a href="{{ route('penghuni.analisis.index') }}"
                    class="bg-gradient-to-br from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-chart-bar text-lg mb-1"></i>
                    <span class="text-sm font-medium">Analisis Saya</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kontrak Aktif Section -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-file-contract text-green-400 mr-3"></i>
                        Kontrak Aktif
                    </h2>
                    @if($kontrakAktif->count() > 0)
                        <span class="bg-green-900/30 text-green-300 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $kontrakAktif->count() }} aktif
                        </span>
                    @endif
                </div>

                @if($kontrakAktif->count() > 0)
                    <div class="space-y-4">
                        @foreach($kontrakAktif->take(3) as $kontrak)
                                <div
                                    class="bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-green-500/50 transition-all duration-300">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="font-semibold text-white">{{ $kontrak->kos->nama_kos }}</h3>
                                                <span class="text-xs px-2 py-1 rounded-full 
                                                    {{ $kontrak->statusWarna == 'green' ? 'bg-green-900/30 text-green-300' :
                            ($kontrak->statusWarna == 'yellow' ? 'bg-yellow-900/30 text-yellow-300' :
                                ($kontrak->statusWarna == 'red' ? 'bg-red-900/30 text-red-300' :
                                    'bg-gray-900/30 text-gray-300')) }}">
                                                    {{ $kontrak->statusText ?? ($kontrak->sudahBerakhir ? 'Berakhir' : 'Aktif') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-dark-muted mb-3">Kamar {{ $kontrak->kamar->nomor_kamar }}</p>

                                            {{-- Progress bar sementara dihapus untuk menghindari error --}}
                                            @if($kontrak->persentaseAkhir !== null)
                                            <div class="mb-3">
                                                <div class="flex justify-between text-xs text-dark-muted mb-1">
                                                    <span>Sisa waktu kontrak</span>
                                                    <span>{{ round($kontrak->persentaseAkhir) }}%</span>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-dark-muted">
                                                    @if($kontrak->sisaHari !== null)
                                                        {{ $kontrak->sisaHari }} hari tersisa
                                                    @else
                                                        {{ $kontrak->statusText }}
                                                    @endif
                                                </span>
                                                <span class="font-bold text-white">
                                                    Rp {{ number_format($kontrak->harga_sewa, 0, ',', '.') }}/bulan
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach

                        @if($kontrakAktif->count() > 3)
                            <div class="text-center pt-2">
                                <a href="{{ route('penghuni.kontrak.index') }}"
                                    class="inline-flex items-center text-green-400 hover:text-green-300 text-sm font-medium">
                                    Lihat semua {{ $kontrakAktif->count() }} kontrak
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-home text-green-400 text-2xl"></i>
                        </div>
                        <p class="text-dark-muted mb-3">Belum ada kontrak aktif</p>
                        <a href="{{ route('public.kos.index') }}"
                            class="text-green-400 hover:text-green-300 text-sm font-medium">
                            <i class="fas fa-search mr-1"></i>
                            Cari kos sekarang
                        </a>
                    </div>
                @endif
            </div>

            <!-- Riwayat Pembayaran Section -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-history text-purple-400 mr-3"></i>
                        Riwayat Pembayaran
                    </h2>
                    @if($pembayaranTerakhir->count() > 0)
                        <a href="{{ route('penghuni.pembayaran.index') }}"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                            Lihat Semua
                        </a>
                    @endif
                </div>

                @if($pembayaranTerakhir->count() > 0)
                    <div class="space-y-4">
                        @foreach($pembayaranTerakhir->take(5) as $pembayaran)
                                <div
                                    class="flex items-center justify-between border-b border-dark-border pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full 
                                            {{ $pembayaran->status_pembayaran == 'lunas' ? 'bg-green-900/30' :
                            ($pembayaran->status_pembayaran == 'pending' ? 'bg-yellow-900/30' :
                                ($pembayaran->status_pembayaran == 'terlambat' ? 'bg-red-900/30' :
                                    'bg-gray-900/30')) }} flex items-center justify-center">
                                            <i class="fas fa-{{ $pembayaran->status_pembayaran == 'lunas' ? 'check' : 'clock' }} 
                                                {{ $pembayaran->status_pembayaran == 'lunas' ? 'text-green-400' :
                            ($pembayaran->status_pembayaran == 'pending' ? 'text-yellow-400' :
                                ($pembayaran->status_pembayaran == 'terlambat' ? 'text-red-400' :
                                    'text-gray-400')) }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $pembayaran->kontrak->kos->nama_kos }}</p>
                                            <p class="text-xs text-dark-muted">{{ $pembayaran->bulan_tahun }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-white">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                                        <span class="inline-block px-2 py-1 text-xs rounded-full 
                                            {{ $pembayaran->status_pembayaran == 'lunas' ? 'bg-green-900/30 text-green-300' :
                            ($pembayaran->status_pembayaran == 'pending' ? 'bg-yellow-900/30 text-yellow-300' :
                                ($pembayaran->status_pembayaran == 'terlambat' ? 'bg-red-900/30 text-red-300' :
                                    'bg-gray-900/30 text-gray-300')) }}">
                                            {{ ucfirst($pembayaran->status_pembayaran) }}
                                        </span>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-credit-card text-purple-400 text-2xl"></i>
                        </div>
                        <p class="text-dark-muted mb-3">Belum ada pembayaran</p>
                        @if($kontrakAktif->count() > 0)
                            <a href="{{ route('penghuni.pembayaran.create') }}"
                                class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                                <i class="fas fa-credit-card mr-1"></i>
                                Bayar sekarang
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kontrak Akan Berakhir -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-hourglass-end text-orange-400 mr-3"></i>
                        Kontrak Berakhir Segera
                    </h2>
                    @php
                        $kontrakBerakhirSegera = $kontrakAktif->filter(function ($kontrak) {
                            return $kontrak->sisaHari <= 15 && !$kontrak->sudahBerakhir;
                        });
                    @endphp
                    @if($kontrakBerakhirSegera->count() > 0)
                        <span class="bg-orange-900/30 text-orange-300 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $kontrakBerakhirSegera->count() }} kontrak
                        </span>
                    @endif
                </div>

                @if($kontrakBerakhirSegera->count() > 0)
                    <div class="space-y-4">
                        @foreach($kontrakBerakhirSegera->take(3) as $kontrak)
                            <div class="bg-orange-900/10 border border-orange-800/30 rounded-xl p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-white mb-2">{{ $kontrak->kos->nama_kos }}</h3>
                                        <p class="text-sm text-dark-muted mb-2">Kamar {{ $kontrak->kamar->nomor_kamar }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-orange-300">
                                                <i class="fas fa-calendar-day mr-1"></i>
                                                {{ \Carbon\Carbon::parse($kontrak->tanggal_selesai)->format('d M Y') }}
                                            </span>
                                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-red-900/30 text-red-300">
                                                {{ $kontrak->sisaHari }} hari lagi
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($kontrakBerakhirSegera->count() > 3)
                            <div class="text-center pt-2">
                                <a href="{{ route('penghuni.kontrak.index') }}"
                                    class="inline-flex items-center text-orange-400 hover:text-orange-300 text-sm font-medium">
                                    Lihat semua {{ $kontrakBerakhirSegera->count() }} kontrak
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-orange-400 text-2xl"></i>
                        </div>
                        <p class="text-dark-muted">Tidak ada kontrak yang akan berakhir</p>
                        <p class="text-sm text-dark-muted/70">Semua kontrak masih memiliki waktu yang cukup</p>
                    </div>
                @endif
            </div>

            <!-- Informasi Status -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-400 mr-3"></i>
                    Informasi Status
                </h2>

                <div class="space-y-4">
                    <!-- Status Card -->
                    <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-white">Status Saat Ini</h3>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $user->status_penghuni == 'aktif' ? 'bg-green-900/30 text-green-300' :
        ($user->status_penghuni == 'calon' ? 'bg-yellow-900/30 text-yellow-300' :
            'bg-red-900/30 text-red-300') }}">
                                {{ ucfirst($user->status_penghuni) }}
                            </span>
                        </div>
                        <p class="text-sm text-dark-muted">
                            @if($user->status_penghuni == 'aktif')
                                Anda adalah penghuni aktif dengan {{ $kontrakAktif->count() }} kontrak aktif.
                            @elseif($user->status_penghuni == 'calon')
                                Anda adalah calon penghuni. Segera lakukan pembayaran untuk mengaktifkan kontrak.
                            @else
                                Status penghuni Anda nonaktif. Hubungi admin untuk informasi lebih lanjut.
                            @endif
                        </p>
                    </div>

                    <!-- Kontak Bantuan -->
                    <div class="bg-blue-900/10 border border-blue-800/30 rounded-xl p-4">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-headset text-blue-400 mr-2"></i>
                            Kontak Bantuan
                        </h3>
                        <ul class="space-y-2 text-sm text-dark-muted">
                            <li class="flex items-center">
                                <i class="fas fa-envelope w-4 mr-3 text-blue-400"></i>
                                <span>valorant270306@gmail.com</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone w-4 mr-3 text-blue-400"></i>
                                <span>082121730722</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-clock w-4 mr-3 text-blue-400"></i>
                                <span>08:00 - 17:00</span>
                            </li>
                        </ul>
                    </div>

                    <!-- CTA Button -->
                    <div class="text-center pt-2">
                        <a href="{{ route('public.kos.index') }}"
                            class="inline-flex items-center justify-center w-full py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            Cari Kos Lainnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Notification Modal -->


    <script>
        // Initialize tooltips if any
        document.addEventListener('DOMContentLoaded', function () {
            // Add any initialization code here
        });
    </script>
@endsection