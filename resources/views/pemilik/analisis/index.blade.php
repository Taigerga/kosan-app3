@extends('layouts.app')

@section('title', 'Analisis Data - AyoKos')

@section('content')
<div class="space-y-6">
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
                        <a href="{{ route('pemilik.analisis.index') }}" class="inline-flex items-center text-sm font-medium text-white">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Analisis Data
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>  
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
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
                <a href="{{ route('pemilik.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-dark-border hover:bg-dark-border/80 text-white rounded-xl transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-primary-600/90 to-primary-700/90 border border-primary-500/30 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm mr-4">
                    <i class="fas fa-wallet text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-primary-100 font-medium mb-1">Total Pendapatan Tahun Ini</p>
                    <p class="text-2xl font-bold text-white">
                        Rp {{ number_format($pendapatanPerKos->sum('total_pendapatan'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Penghuni -->
        <div class="bg-gradient-to-br from-green-600/90 to-emerald-700/90 border border-green-500/30 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm mr-4">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-green-100 font-medium mb-1">Total Penghuni Aktif</p>
                    <p class="text-2xl font-bold text-white">
                        {{ $penghuniPerKos->sum('jumlah_penghuni') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Okupansi -->
        <div class="bg-gradient-to-br from-purple-600/90 to-indigo-700/90 border border-purple-500/30 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm mr-4">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-purple-100 font-medium mb-1">Rata-rata Okupansi</p>
                    <p class="text-2xl font-bold text-white">
                        @php
                            $terisi = $statusKamar->where('status_kamar', 'terisi')->first()->jumlah ?? 0;
                            $total = $statusKamar->sum('jumlah') ?: 1;
                            $okupansi = ($terisi / $total) * 100;
                        @endphp
                        {{ number_format($okupansi, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart 1: Pendapatan 6 Bulan Terakhir -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-money-bill-wave text-primary-400 mr-3"></i>
                    Trend Pendapatan (6 Bulan)
                </h2>
                <span class="text-xs px-3 py-1 rounded-full bg-primary-900/30 text-primary-300">
                    {{ date('Y') }}
                </span>
            </div>
            <div class="h-72">
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Status Kamar -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-bed text-green-400 mr-3"></i>
                    Distribusi Status Kamar
                </h2>
                <span class="text-xs px-3 py-1 rounded-full bg-green-900/30 text-green-300">
                    {{ $statusKamar->sum('jumlah') }} Kamar
                </span>
            </div>
            <div class="h-72">
                <canvas id="statusKamarChart"></canvas>
            </div>
            <!-- Legend -->
            <div class="grid grid-cols-3 gap-3 mt-4">
                @foreach($statusKamar as $status)
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2
                            @if($status->status_kamar == 'tersedia') bg-green-500
                            @elseif($status->status_kamar == 'terisi') bg-blue-500
                            @else bg-yellow-500 @endif">
                        </div>
                        <span class="text-sm text-dark-muted">
                            {{ ucfirst($status->status_kamar) }}
                        </span>
                        <span class="ml-auto text-sm font-medium text-white">
                            {{ $status->jumlah }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart 3: Jenis Kos -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-home text-blue-400 mr-3"></i>
                    Distribusi Jenis Kos
                </h2>
                <span class="text-xs px-3 py-1 rounded-full bg-blue-900/30 text-blue-300">
                    {{ $jenisKos->sum('jumlah') }} Kos
                </span>
            </div>
            <div class="h-72">
                <canvas id="jenisKosChart"></canvas>
            </div>
        </div>

        <!-- Chart 4: Status Kontrak -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-file-contract text-purple-400 mr-3"></i>
                    Status Kontrak
                </h2>
                <span class="text-xs px-3 py-1 rounded-full bg-purple-900/30 text-purple-300">
                    {{ $statusKontrak->sum('jumlah') }} Kontrak
                </span>
            </div>
            <div class="h-72">
                <canvas id="statusKontrakChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Row 3: Tabel Data -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tabel: Pendapatan per Kos -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-trophy text-yellow-400 mr-3"></i>
                    Pendapatan per Kos
                </h2>
                <span class="text-xs px-3 py-1 rounded-full bg-yellow-900/30 text-yellow-300">
                    Tahun {{ date('Y') }}
                </span>
            </div>
            
            <div class="space-y-4">
                @foreach($pendapatanPerKos as $kos)
                    <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-primary-500/50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-home text-primary-400"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-white">{{ $kos->nama_kos }}</h3>
                                    <p class="text-xs text-dark-muted">Kos terbaik</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-green-400">
                                    Rp {{ number_format($kos->total_pendapatan, 0, ',', '.') }}
                                </p>
                                <div class="w-32 h-1 bg-dark-border rounded-full overflow-hidden mt-1">
                                    <div class="h-full bg-green-500 rounded-full" 
                                         style="width: {{ ($kos->total_pendapatan / ($pendapatanPerKos->max('total_pendapatan') ?: 1)) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($pendapatanPerKos->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-dark-border rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-dark-muted text-2xl"></i>
                        </div>
                        <p class="text-dark-muted">Belum ada data pendapatan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabel: Penghuni per Kos -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-user-friends text-blue-400 mr-3"></i>
                    Penghuni per Kos
                </h2>
                <span class="text-xs px-3 py-1 rounded-full bg-blue-900/30 text-blue-300">
                    Penghuni Aktif
                </span>
            </div>
            
            <div class="space-y-4">
                @foreach($penghuniPerKos as $kos)
                    <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-blue-500/50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-white">{{ $kos->nama_kos }}</h3>
                                    <p class="text-xs text-dark-muted">{{ $kos->jumlah_penghuni }} penghuni</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center justify-end">
                                    <div class="w-24 h-6 bg-dark-border rounded-full overflow-hidden mr-3">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" 
                                             style="width: {{ ($kos->jumlah_penghuni / ($penghuniPerKos->max('jumlah_penghuni') ?: 1)) * 100 }}%">
                                        </div>
                                    </div>
                                    <span class="text-lg font-bold text-white">
                                        {{ $kos->jumlah_penghuni }}
                                    </span>
                                </div>
                                <p class="text-xs text-dark-muted mt-1">
                                    {{ round(($kos->jumlah_penghuni / ($penghuniPerKos->sum('jumlah_penghuni') ?: 1)) * 100, 1) }}% dari total
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($penghuniPerKos->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-dark-border rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-dark-muted text-2xl"></i>
                        </div>
                        <p class="text-dark-muted">Belum ada data penghuni</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart.js theme configuration
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = '#334155';
        
        // Data from PHP
        const pendapatanData = @json($pendapatanPerBulan);
        const statusKamarData = @json($statusKamar);
        const jenisKosData = @json($jenisKos);
        const statusKontrakData = @json($statusKontrak);

        // Chart 1: Pendapatan 6 Bulan Terakhir
        const pendapatanCtx = document.getElementById('pendapatanChart').getContext('2d');
        new Chart(pendapatanCtx, {
            type: 'line',
            data: {
                labels: pendapatanData.map(item => {
                    const [year, month] = item.bulan.split('-');
                    return new Date(year, month-1).toLocaleDateString('id-ID', { month: 'short' });
                }),
                datasets: [{
                    label: 'Pendapatan',
                    data: pendapatanData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        }
                    }
                },
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
                        backgroundColor: 'rgba(30, 41, 59, 0.9)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5e1',
                        borderColor: '#334155',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Chart 2: Status Kamar
        const statusKamarCtx = document.getElementById('statusKamarChart').getContext('2d');
        new Chart(statusKamarCtx, {
            type: 'doughnut',
            data: {
                labels: statusKamarData.map(item => {
                    return item.status_kamar === 'tersedia' ? 'Tersedia' :
                           item.status_kamar === 'terisi' ? 'Terisi' : 'Maintenance';
                }),
                datasets: [{
                    data: statusKamarData.map(item => item.jumlah),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',   // Green
                        'rgba(59, 130, 246, 0.8)',  // Blue
                        'rgba(234, 179, 8, 0.8)'    // Yellow
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(234, 179, 8)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#e2e8f0',
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.9)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5e1',
                        borderColor: '#334155'
                    }
                }
            }
        });

        // Chart 3: Jenis Kos
        const jenisKosCtx = document.getElementById('jenisKosChart').getContext('2d');
        new Chart(jenisKosCtx, {
            type: 'bar',
            data: {
                labels: jenisKosData.map(item => {
                    return item.jenis_kos === 'putra' ? 'Putra' : 
                           item.jenis_kos === 'putri' ? 'Putri' : 'Campuran';
                }),
                datasets: [{
                    label: 'Jumlah Kos',
                    data: jenisKosData.map(item => item.jumlah),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',   // Blue
                        'rgba(244, 63, 94, 0.7)',    // Pink
                        'rgba(139, 92, 246, 0.7)'    // Purple
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(244, 63, 94)',
                        'rgb(139, 92, 246)'
                    ],
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.9)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5e1'
                    }
                }
            }
        });

        // Chart 4: Status Kontrak
        const statusKontrakCtx = document.getElementById('statusKontrakChart').getContext('2d');
        new Chart(statusKontrakCtx, {
            type: 'pie',
            data: {
                labels: statusKontrakData.map(item => {
                    const status = item.status_kontrak;
                    return status === 'aktif' ? 'Aktif' :
                           status === 'pending' ? 'Pending' :
                           status === 'selesai' ? 'Selesai' : 'Ditolak';
                }),
                datasets: [{
                    data: statusKontrakData.map(item => item.jumlah),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',   // Green
                        'rgba(234, 179, 8, 0.8)',   // Yellow
                        'rgba(59, 130, 246, 0.8)',  // Blue
                        'rgba(239, 68, 68, 0.8)'    // Red
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(59, 130, 246)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#e2e8f0',
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.9)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5e1',
                        borderColor: '#334155'
                    }
                }
            }
        });
    });
</script>
@endsection