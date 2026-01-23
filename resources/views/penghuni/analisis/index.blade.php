@extends('layouts.app')

@section('title', 'Analisis Data Penghuni - AyoKos')

@section('content')

    <div class="space-y-6">
        <!-- Breadcrumb -->
        <div class="bg-dark-card/50 border border-dark-border rounded-xl p-4 mb-6">
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
                            <a href="{{ route('penghuni.analisis.index') }}" class="inline-flex items-center text-sm font-medium text-white">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Analisis Data Saya
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-3">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">
                            <i class="fas fa-chart-bar text-white mr-3"></i>    
                            Analisis Data Kosan</h1>
                            <p class="text-dark-muted">Analisis statistik dan visualisasi data properti Anda</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('penghuni.dashboard') }}" 
                    class="inline-flex items-center px-4 py-2.5 bg-dark-border hover:bg-dark-border/80 text-white rounded-xl transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                    <button id="exportPdfPenghuni" 
                        class="inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl transition">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Kontrak -->
            <div class="bg-gradient-to-br from-primary-600/20 to-indigo-600/20 border border-primary-500/30 rounded-2xl p-5 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-primary-900/30 backdrop-blur-sm">
                        <i class="fas fa-file-contract text-primary-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-dark-muted">Total Kontrak</h3>
                        <p class="text-2xl font-bold text-white">{{ $statistikRingkasan['total_kontrak'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="bg-gradient-to-br from-orange-500/20 to-amber-700/20 border border-orange-500/30 rounded-2xl p-5 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-orange-900/30 backdrop-blur-sm">
                        <i class="fas fa-wallet text-orange-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-dark-muted">Total Pengeluaran</h3>
                        <p class="text-2xl font-bold text-white">
                            Rp {{ number_format($statistikRingkasan['total_pembayaran'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Review Diberikan -->
            <div class="bg-gradient-to-br from-yellow-600/20 to-amber-600/20 border border-yellow-500/30 rounded-2xl p-5 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-yellow-900/30 backdrop-blur-sm">
                        <i class="fas fa-star text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-dark-muted">Review Diberikan</h3>
                        <p class="text-2xl font-bold text-white">{{ $statistikRingkasan['jumlah_review'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Rating Rata-rata -->
            <div class="bg-gradient-to-br from-purple-600/20 to-pink-600/20 border border-purple-500/30 rounded-2xl p-5 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-purple-900/30 backdrop-blur-sm">
                        <i class="fas fa-chart-line text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-dark-muted">Rating Rata-rata</h3>
                        <p class="text-2xl font-bold text-white">{{ number_format($statistikRingkasan['rata_rata_rating'], 1) }}/5</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Chart 1: Riwayat Pengeluaran 6 Bulan -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-wallet text-green-400 mr-3"></i>
                        Riwayat Pengeluaran
                    </h2>
                    <span class="text-xs px-3 py-1 rounded-full bg-green-900/30 text-green-300">
                        6 Bulan Terakhir
                    </span>
                </div>
                <div class="h-80">
                    <canvas id="pengeluaranChart"></canvas>
                </div>
                <div class="mt-4 p-3 bg-dark-bg/50 rounded-xl">
                    <p class="text-sm text-dark-muted flex items-center">
                        <i class="fas fa-chart-bar text-primary-400 mr-2"></i>
                        Total pengeluaran 6 bulan terakhir: 
                        <span class="font-bold text-green-400 ml-1">
                            Rp {{ number_format($pembayaranPerBulan->sum('total'), 0, ',', '.') }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Chart 2: Status Pembayaran -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-credit-card text-purple-400 mr-3"></i>
                    Distribusi Status Pembayaran
                </h2>
                <div class="h-80">
                    <canvas id="statusPembayaranChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-2">
                    @foreach($statusPembayaran as $status)
                        <div class="text-center p-2 bg-dark-bg/30 rounded-lg">
                            <div class="inline-block w-2 h-2 rounded-full mb-1
                                @if($status->status_pembayaran == 'lunas') bg-green-500
                                @elseif($status->status_pembayaran == 'pending') bg-yellow-500
                                @elseif($status->status_pembayaran == 'terlambat') bg-red-500
                                @else bg-gray-500 @endif">
                            </div>
                            <div class="text-xs font-medium text-dark-muted">
                                {{ ucfirst($status->status_pembayaran) }}
                            </div>
                            <div class="text-xs font-bold text-white">
                                {{ $status->jumlah }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Chart 3: Preferensi Jenis Kos -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-home text-blue-400 mr-3"></i>
                    Preferensi Jenis Kos
                </h2>
                <div class="h-80">
                    <canvas id="jenisKosChart"></canvas>
                </div>
                @if($jenisKosDisewa->isNotEmpty())
                <div class="mt-4 p-3 bg-dark-bg/50 rounded-xl">
                    <p class="text-sm text-dark-muted flex items-center">
                        <i class="fas fa-crown text-yellow-400 mr-2"></i>
                        Jenis kos favorit: 
                        <span class="font-bold text-primary-400 ml-1">
                            {{ ucfirst($jenisKosDisewa->sortByDesc('jumlah_sewa')->first()->jenis_kos) }}
                        </span>
                        ({{ $jenisKosDisewa->sortByDesc('jumlah_sewa')->first()->jumlah_sewa }}x)
                    </p>
                </div>
                @endif
            </div>

            <!-- Chart 4: Distribusi Rating Review -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-3"></i>
                        Distribusi Rating Review
                    </h2>
                    @if($statistikRingkasan['rata_rata_rating'] > 0)
                    <div class="flex items-center bg-yellow-900/30 px-3 py-1 rounded-full">
                        <span class="text-yellow-400 font-bold mr-1">⭐</span>
                        <span class="text-sm font-bold text-white">
                            {{ number_format($statistikRingkasan['rata_rata_rating'], 1) }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="h-80">
                    <canvas id="ratingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Row 3: Tabel Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Tabel: Riwayat Kontrak -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-history text-primary-400 mr-3"></i>
                    Riwayat Kontrak
                </h2>
                <div class="overflow-x-auto rounded-xl border border-dark-border">
                    <table class="min-w-full divide-y divide-dark-border">
                        <thead class="bg-dark-bg">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">Kos</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">Durasi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-border">
                            @foreach($riwayatKontrak->take(5) as $kontrak)
                                <tr class="hover:bg-dark-border/30 transition">
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-white">{{ $kontrak->kos->nama_kos }}</div>
                                        <div class="text-xs text-dark-muted">Kamar {{ $kontrak->kamar->nomor_kamar }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-white">{{ $kontrak->durasi_sewa }} bulan</div>
                                        <div class="text-xs text-dark-muted">
                                            {{ \Carbon\Carbon::parse($kontrak->tanggal_mulai)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $kontrak->status_kontrak == 'aktif' ? 'bg-green-900/30 text-green-300' : 
                                               ($kontrak->status_kontrak == 'selesai' ? 'bg-blue-900/30 text-blue-300' : 
                                               ($kontrak->status_kontrak == 'pending' ? 'bg-yellow-900/30 text-yellow-300' : 
                                               'bg-red-900/30 text-red-300')) }}">
                                            {{ ucfirst($kontrak->status_kontrak) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($riwayatKontrak->count() > 5)
                <div class="text-center pt-4 border-t border-dark-border mt-4">
                    <a href="{{ route('penghuni.kontrak.index') }}" 
                       class="inline-flex items-center text-primary-400 hover:text-primary-300 text-sm font-medium">
                        Lihat semua {{ $riwayatKontrak->count() }} kontrak
                        <i class="fas fa-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
                @endif
            </div>

            <!-- Tabel: Preferensi Tipe Kamar -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-bed text-green-400 mr-3"></i>
                    Preferensi Tipe Kamar
                </h2>
                <div class="overflow-x-auto rounded-xl border border-dark-border">
                    <table class="min-w-full divide-y divide-dark-border">
                        <thead class="bg-dark-bg">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">Tipe Kamar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">Jumlah Sewa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">Harga Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-border">
                            @foreach($tipeKamarDisewa as $tipe)
                                <tr class="hover:bg-dark-border/30 transition">
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-white">{{ $tipe->tipe_kamar }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-white">{{ $tipe->jumlah_sewa }} kali</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-bold text-green-400">
                                            Rp {{ number_format($tipe->rata_rata_harga, 0, ',', '.') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Insight Section -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-lightbulb text-yellow-400 mr-3"></i>
                Insight untuk Anda
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $kosFavorit = $jenisKosDisewa->sortByDesc('jumlah_sewa')->first();
                    $tipeFavorit = $tipeKamarDisewa->sortByDesc('jumlah_sewa')->first();
                    $rataPengeluaran = $pembayaranPerBulan->avg('total') ?? 0;
                @endphp
                
                <!-- Insight 1: Jenis Kos Favorit -->
                <div class="bg-primary-900/20 border border-primary-500/30 p-4 rounded-xl">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-crown text-primary-400"></i>
                        </div>
                        <h3 class="font-semibold text-white">Jenis Kos Favorit</h3>
                    </div>
                    <p class="text-sm text-dark-muted">
                        Anda paling sering menyewa kos 
                        <span class="font-bold text-primary-300">{{ $kosFavorit->jenis_kos ?? '-' }}</span> 
                        sebanyak {{ $kosFavorit->jumlah_sewa ?? 0 }} kali.
                    </p>
                </div>

                <!-- Insight 2: Pengeluaran Rata-rata -->
                <div class="bg-green-900/20 border border-green-500/30 p-4 rounded-xl">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-green-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-green-400"></i>
                        </div>
                        <h3 class="font-semibold text-white">Pengeluaran Rata-rata</h3>
                    </div>
                    <p class="text-sm text-dark-muted">
                        Rata-rata pengeluaran per bulan: 
                        <span class="font-bold text-green-300">
                            Rp {{ number_format($rataPengeluaran, 0, ',', '.') }}
                        </span>
                    </p>
                </div>

                <!-- Insight 3: Preferensi Kamar -->
                <div class="bg-purple-900/20 border border-purple-500/30 p-4 rounded-xl">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-bed text-purple-400"></i>
                        </div>
                        <h3 class="font-semibold text-white">Preferensi Kamar</h3>
                    </div>
                    <p class="text-sm text-dark-muted">
                        Tipe kamar favorit: 
                        <span class="font-bold text-purple-300">{{ $tipeFavorit->tipe_kamar ?? '-' }}</span>
                        dengan harga rata-rata 
                        <span class="font-bold text-purple-300">
                            Rp {{ number_format($tipeFavorit->rata_rata_harga ?? 0, 0, ',', '.') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart.js default color palette for dark theme
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.borderColor = '#334155';
            Chart.defaults.backgroundColor = '#1e293b';

            // Data dari PHP
            const pengeluaranData = @json($pembayaranPerBulan);
            const statusPembayaranData = @json($statusPembayaran);
            const jenisKosData = @json($jenisKosDisewa);
            const reviewData = @json($reviewStats);
            const durasiData = @json($durasiTinggal);
            const tipeKamarData = @json($tipeKamarDisewa);

            // Helper function untuk format bulan
            function formatBulanLabel(dateString) {
                const [year, month] = dateString.split('-');
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 
                                   'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                return `${monthNames[parseInt(month)-1]} ${year}`;
            }

            // Chart 1: Riwayat Pengeluaran (Line Chart)
            if (document.getElementById('pengeluaranChart')) {
                const pengeluaranCtx = document.getElementById('pengeluaranChart').getContext('2d');
                new Chart(pengeluaranCtx, {
                    type: 'line',
                    data: {
                        labels: pengeluaranData.map(item => formatBulanLabel(item.bulan)),
                        datasets: [{
                            label: 'Total Pengeluaran',
                            data: pengeluaranData.map(item => item.total),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#e2e8f0',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#e2e8f0',
                                bodyColor: '#94a3b8',
                                borderColor: '#334155',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return 'Pengeluaran: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: '#334155'
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#334155'
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    callback: function(value) {
                                        if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                        if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Chart 2: Status Pembayaran (Doughnut)
            if (document.getElementById('statusPembayaranChart')) {
                const statusPembayaranCtx = document.getElementById('statusPembayaranChart').getContext('2d');
                new Chart(statusPembayaranCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusPembayaranData.map(item => ucFirst(item.status_pembayaran)),
                        datasets: [{
                            data: statusPembayaranData.map(item => item.jumlah),
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)',   // Green - lunas
                                'rgba(245, 158, 11, 0.8)',   // Yellow - pending
                                'rgba(239, 68, 68, 0.8)',    // Red - terlambat
                                'rgba(107, 114, 128, 0.8)'   // Gray - lainnya
                            ],
                            borderColor: '#1e293b',
                            borderWidth: 2,
                            hoverBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#e2e8f0',
                                    font: {
                                        size: 11
                                    },
                                    boxWidth: 12,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#e2e8f0',
                                bodyColor: '#94a3b8',
                                borderColor: '#334155',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} transaksi (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Chart 3: Preferensi Jenis Kos (Bar Chart)
            if (document.getElementById('jenisKosChart')) {
                const jenisKosCtx = document.getElementById('jenisKosChart').getContext('2d');
                new Chart(jenisKosCtx, {
                    type: 'bar',
                    data: {
                        labels: jenisKosData.map(item => {
                            const jenis = item.jenis_kos;
                            return jenis === 'putra' ? 'Putra' : 
                                   jenis === 'putri' ? 'Putri' : 'Campuran';
                        }),
                        datasets: [{
                            label: 'Jumlah Sewa',
                            data: jenisKosData.map(item => item.jumlah_sewa),
                            backgroundColor: 'rgba(139, 92, 246, 0.7)',
                            borderColor: 'rgb(139, 92, 246)',
                            borderWidth: 2,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#e2e8f0'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#e2e8f0',
                                bodyColor: '#94a3b8',
                                borderColor: '#334155',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: '#334155',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#334155',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Chart 4: Distribusi Rating (Bar Chart Horizontal)
            if (document.getElementById('ratingChart')) {
                const ratingCtx = document.getElementById('ratingChart').getContext('2d');
                
                // Prepare rating data
                const ratingMap = {};
                for (let i = 1; i <= 5; i++) {
                    ratingMap[i] = 0;
                }
                
                if (reviewData && reviewData.length > 0) {
                    reviewData.forEach(item => {
                        ratingMap[item.rating_bulat] = item.jumlah;
                    });
                }

                new Chart(ratingCtx, {
                    type: 'bar',
                    data: {
                        labels: ['★', '★★', '★★★', '★★★★', '★★★★★'],
                        datasets: [{
                            label: 'Jumlah Review',
                            data: [ratingMap[1], ratingMap[2], ratingMap[3], ratingMap[4], ratingMap[5]],
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.7)',
                                'rgba(245, 158, 11, 0.7)',
                                'rgba(234, 179, 8, 0.7)',
                                'rgba(34, 197, 94, 0.7)',
                                'rgba(34, 197, 94, 0.9)'
                            ],
                            borderColor: [
                                'rgb(239, 68, 68)',
                                'rgb(245, 158, 11)',
                                'rgb(234, 179, 8)',
                                'rgb(34, 197, 94)',
                                'rgb(34, 197, 94)'
                            ],
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#e2e8f0',
                                bodyColor: '#94a3b8',
                                borderColor: '#334155',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    color: '#334155',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    stepSize: 1
                                }
                            },
                            y: {
                                grid: {
                                    color: '#334155',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            }
                        }
                    }
                });
            }

            // Helper functions
            function ucFirst(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>

    <style>
        /* Custom chart styles for dark theme */
        .chart-container {
            position: relative;
        }
        
        /* Custom scrollbar for tables */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #1e293b;
            border-radius: 3px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        /* Table hover effects */
        tbody tr {
            transition: background-color 0.2s ease;
        }
        
        tbody tr:hover {
            background-color: rgba(51, 65, 85, 0.3);
        }
        
        /* Card animations */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        
        /* Insight cards hover */
        .grid > div:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
        }
    </style>
<!-- Include library PDF dan script export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<!-- Data penghuni untuk PDF (hidden element) -->
<div id="penghuniData" 
     data-nama="{{ auth()->guard('penghuni')->user()->nama ?? 'Penghuni' }}"
     style="display: none;">
</div>

<!-- Include PDF export script dari blade file -->
@include('penghuni.analisis.pdf-export')

@endsection