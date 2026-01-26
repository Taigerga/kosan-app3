@extends('layouts.app')

@section('title', 'Riwayat Pembayaran - AyoKos')

@section('content')
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
                                class="inline-flex items-center text-sm font-medium text-white">
                                <i class="fas fa-credit-card  mr-2"></i>
                                Riwayat Pembayaran
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6 mb-6">
            <div
                class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                    <i class="fas fa-credit-card mr-2"></i>    
                    Riwayat Pembayaran</h1>
                    <p class="text-dark-muted">Kelola dan lacak semua pembayaran kos Anda</p>
                </div>

                @if($kontrakAktif->count() > 0)
                    <a href="{{ route('penghuni.pembayaran.create') }}"
                        class="mt-4 md:mt-0 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-credit-card mr-2"></i>
                        Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-900/30 border border-green-800/50 text-green-300 px-4 py-3 rounded-xl mb-6 backdrop-blur-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-900/30 border border-red-800/50 text-red-300 px-4 py-3 rounded-xl mb-6 backdrop-blur-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Kontrak Aktif Section -->
        @if($kontrakAktif->count() > 0)
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-file-contract text-blue-400 mr-3"></i>
                        Kontrak Aktif
                        <span class="ml-3 bg-blue-900/30 text-blue-300 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $kontrakAktif->count() }} kontrak
                        </span>
                    </h2>
                </div>

                <div class="space-y-4">
                    @foreach($kontrakAktif as $kontrak)
                        <div
                            class="bg-dark-bg/50 border border-dark-border rounded-xl p-5 hover:border-blue-500/50 transition-all duration-300">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-home text-blue-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-dark-muted mb-1">Kos</p>
                                        <p class="font-medium text-white truncate">{{ $kontrak->kos->nama_kos }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-green-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-wallet text-green-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-dark-muted mb-1">Harga Sewa</p>
                                        <p class="font-medium text-white">Rp
                                            {{ number_format($kontrak->harga_sewa, 0, ',', '.') }}/bulan
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-purple-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-dark-muted mb-1">Periode</p>
                                        <p class="font-medium text-white">
                                            @if($kontrak->tanggal_mulai && $kontrak->tanggal_selesai)
                                                {{ $kontrak->tanggal_mulai->format('d M Y') }} -
                                                {{ $kontrak->tanggal_selesai->format('d M Y') }}
                                            @else
                                                <span class="text-yellow-400">Menunggu pembayaran pertama</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination Kontrak -->
                <div class="px-6 py-4 border-t border-dark-border">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-dark-muted">
                            Menampilkan {{ $kontrakAktif->firstItem() ?? 0 }} - {{ $kontrakAktif->lastItem() ?? 0 }} dari
                            {{ $kontrakAktif->total() }} kontrak
                        </div>
                        <div class="flex space-x-2">
                            {{ $kontrakAktif->links('vendor.pagination.custom-dark') }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No Active Contracts -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-home text-yellow-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Tidak Ada Kontrak Aktif</h3>
                <p class="text-dark-muted mb-4">Anda harus memiliki kontrak aktif untuk melakukan pembayaran.</p>
                <a href="{{ route('public.kos.index') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                    <i class="fas fa-search mr-1"></i>
                    Cari kos sekarang
                </a>
            </div>
        @endif

        <!-- Riwayat Pembayaran Table -->
        <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-dark-border">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-history text-purple-400 mr-3"></i>
                    Riwayat Pembayaran
                </h2>
            </div>

            @if($pembayaran->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-dark-bg/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Bulan
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-money-bill-wave mr-2"></i>
                                        Jumlah
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Metode
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-tag mr-2"></i>
                                        Status
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2"></i>
                                        Tanggal
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-cog mr-2"></i>
                                        Aksi
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-border">
                            @foreach($pembayaran as $bayar)
                                        <tr class="hover:bg-dark-bg/30 transition-colors">
                                            <td class="px-6 py-4">
                                                <div>
                                                    <div class="text-sm font-medium text-white">
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bayar->bulan_tahun)->format('F Y') }}
                                                    </div>
                                                    @if($bayar->keterangan == 'Pembayaran di muka')
                                                        <div class="mt-1">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-900/30 text-orange-300">
                                                                <i class="fas fa-rocket mr-1 text-xs"></i>
                                                                Advance
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-white">
                                                    Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-900/30 text-blue-300 capitalize">
                                                    <i class="fas {{ $bayar->metode_pembayaran == 'transfer' ? 'fa-university' :
                                ($bayar->metode_pembayaran == 'cash' ? 'fa-money-bill' : 'fa-qrcode') }} mr-1 text-xs">
                                                    </i>
                                                    {{ $bayar->metode_pembayaran }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                                                                                                    {{ $bayar->status_pembayaran == 'lunas' ? 'bg-green-900/30 text-green-300' :
                                ($bayar->status_pembayaran == 'pending' ? 'bg-yellow-900/30 text-yellow-300' :
                                    ($bayar->status_pembayaran == 'terlambat' ? 'bg-red-900/30 text-red-300' :
                                        'bg-gray-900/30 text-gray-300')) }}">
                                                    <i
                                                        class="fas {{ $bayar->status_pembayaran == 'lunas' ? 'fa-check-circle' :
                                ($bayar->status_pembayaran == 'pending' ? 'fa-clock' :
                                    ($bayar->status_pembayaran == 'terlambat' ? 'fa-exclamation-triangle' : 'fa-question-circle')) }} mr-1 text-xs">
                                                    </i>
                                                    {{ ucfirst($bayar->status_pembayaran) }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="text-sm text-dark-muted">
                                                    @if($bayar->tanggal_bayar)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-calendar-check mr-2 text-primary-400"></i>
                                                            {{ $bayar->tanggal_bayar->format('d M Y') }}
                                                        </div>
                                                    @else
                                                        <span class="text-dark-muted/50">-</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <a href="{{ route('penghuni.pembayaran.show', $bayar->id_pembayaran) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-dark-border text-dark-text hover:text-white rounded-lg text-sm font-medium hover:bg-dark-border/80 transition-all duration-300">
                                                    <i class="fas fa-eye mr-1 text-xs"></i>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Pembayaran -->
                <div class="px-6 py-4 border-t border-dark-border">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-dark-muted">
                            Menampilkan {{ $pembayaran->firstItem() ?? 0 }} - {{ $pembayaran->lastItem() ?? 0 }} dari
                            {{ $pembayaran->total() }} pembayaran
                        </div>
                        <div class="flex space-x-2">
                            {{ $pembayaran->links('vendor.pagination.custom-dark') }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div
                        class="w-16 h-16 bg-dark-bg border border-dark-border rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-dark-muted text-2xl"></i>
                    </div>
                    <p class="text-dark-muted">Belum ada riwayat pembayaran</p>
                </div>
            @endif
        </div>

        <!-- Informasi Penting -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Ketentuan Pembayaran -->
            <div
                class="bg-dark-card border border-dark-border rounded-2xl p-6 hover:border-yellow-500/50 transition-all duration-300 card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-900/30 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-file-contract text-yellow-400 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white">Ketentuan Pembayaran</h3>
                </div>

                <ul class="space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check text-yellow-400 mt-1 mr-3 text-sm"></i>
                        <span class="text-sm text-dark-muted">Pembayaran jatuh tempo setiap tanggal 1</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-yellow-400 mt-1 mr-3 text-sm"></i>
                        <span class="text-sm text-dark-muted">Denda keterlambatan: Rp 50.000/hari</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-yellow-400 mt=1 mr-3 text-sm"></i>
                        <span class="text-sm text-dark-muted">Masa tenggang: 7 hari setelah jatuh tempo</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-yellow-400 mt-1 mr-3 text-sm"></i>
                        <span class="text-sm text-dark-muted">Konfirmasi pembayaran maksimal 24 jam</span>
                    </li>
                </ul>
            </div>

            <!-- Cara Pembayaran -->
            <div
                class="bg-dark-card border border-dark-border rounded-2xl p-6 hover:border-blue-500/50 transition-all duration-300 card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-900/30 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-credit-card text-blue-400 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white">Cara Pembayaran</h3>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-300">1</span>
                        </div>
                        <span class="text-sm text-dark-muted">Klik tombol "Bayar Sekarang"</span>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-300">2</span>
                        </div>
                        <span class="text-sm text-dark-muted">Upload bukti transfer/pembayaran</span>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-300">3</span>
                        </div>
                        <span class="text-sm text-dark-muted">Tunggu konfirmasi dari pemilik</span>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-xs font-bold text-blue-300">4</span>
                        </div>
                        <span class="text-sm text-dark-muted">Status akan berubah menjadi "Lunas"</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
            <div class="bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-dark-muted mb-1">Total Pembayaran</p>
                        <p class="text-xl font-bold text-white">
                            Rp {{ number_format($pembayaran->sum('jumlah'), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wallet text-purple-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-dark-muted mb-1">Pembayaran Lunas</p>
                        <p class="text-xl font-bold text-green-400">
                            {{ $pembayaran->where('status_pembayaran', 'lunas')->count() }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-dark-muted mb-1">Menunggu Konfirmasi</p>
                        <p class="text-xl font-bold text-yellow-400">
                            {{ $pembayaran->where('status_pembayaran', 'pending')->count() }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom table styles */
        table {
            min-width: 1000px;
        }

        @media (max-width: 1024px) {
            table {
                min-width: unset;
            }
        }

        /* Row hover effect */
        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: rgba(30, 41, 59, 0.3);
        }

        /* Card hover effect */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection