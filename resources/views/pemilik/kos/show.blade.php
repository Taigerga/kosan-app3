@extends('layouts.app')

@section('title', $kos->nama_kos . ' - AyoKos')

@section('content')
    <div class="container mx-auto px-4 py-6 md:py-8">
        <div class="max-w-7xl mx-auto">
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
                                <a href="{{ route('pemilik.kos.show', $kos->id_kos) }}" class="inline-flex items-center text-sm font-medium text-white">
                                    <i class="fas fa-eye mr-2"></i>
                                    Detail Kos
                                </a>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-home text-white text-lg"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $kos->nama_kos }}</h1>
                                <div class="flex items-center text-dark-muted">
                                    <i class="fas fa-map-marker-alt text-sm mr-2 text-primary-400"></i>
                                    <span>{{ $kos->alamat }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                            {{ $kos->status_kos == 'aktif' ? 'bg-green-900/30 text-green-300 border border-green-700/30' : 
                               ($kos->status_kos == 'pending' ? 'bg-yellow-900/30 text-yellow-300 border border-yellow-700/30' : 
                               'bg-red-900/30 text-red-300 border border-red-700/30') }}">
                            <i class="fas 
                                {{ $kos->status_kos == 'aktif' ? 'fa-check-circle' : 
                                   ($kos->status_kos == 'pending' ? 'fa-clock' : 'fa-times-circle') }} mr-2"></i>
                            {{ ucfirst($kos->status_kos) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Information & Rooms -->
                <div class="space-y-6">
                    <!-- Information Card -->
                    <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-900/30 to-indigo-900/30 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-primary-400 text-lg"></i>
                            </div>
                            <h2 class="text-xl font-bold text-white">Informasi Kos</h2>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div>
                                        <div class="text-sm text-dark-muted mb-1 flex items-center">
                                            <i class="fas fa-map-pin mr-2 text-primary-400"></i>
                                            Alamat
                                        </div>
                                        <p class="text-white">{{ $kos->alamat }}</p>
                                    </div>
                                    <div>
                                        <div class="text-sm text-dark-muted mb-1 flex items-center">
                                            <i class="fas fa-city mr-2 text-blue-400"></i>
                                            Kota
                                        </div>
                                        <p class="text-white">{{ $kos->kota }}</p>
                                    </div>
                                    <div>
                                        <div class="text-sm text-dark-muted mb-1 flex items-center">
                                            <i class="fas fa-users mr-2 text-green-400"></i>
                                            Jenis Kos
                                        </div>
                                        <p class="text-white capitalize">{{ $kos->jenis_kos }}</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <div>
                                        <div class="text-sm text-dark-muted mb-1 flex items-center">
                                            <i class="fas fa-calendar-alt mr-2 text-purple-400"></i>
                                            Tipe Sewa
                                        </div>
                                        <p class="text-white capitalize">{{ $kos->tipe_sewa }}</p>
                                    </div>
                                    <div>
                                        <div class="text-sm text-dark-muted mb-1 flex items-center">
                                            <i class="fas fa-code-branch mr-2 text-yellow-400"></i>
                                            Kecamatan
                                        </div>
                                        <p class="text-white">{{ $kos->kecamatan }}</p>
                                    </div>
                                    <div>
                                        <div class="text-sm text-dark-muted mb-1 flex items-center">
                                            <i class="fas fa-globe mr-2 text-red-400"></i>
                                            Provinsi
                                        </div>
                                        <p class="text-white">{{ $kos->provinsi }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Coordinates -->
                            <div class="pt-4 border-t border-dark-border">
                                <div class="text-sm text-dark-muted mb-2 flex items-center">
                                    <i class="fas fa-location-dot mr-2 text-orange-400"></i>
                                    Koordinat
                                </div>
                                <div class="bg-dark-bg border border-dark-border rounded-lg p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-mono text-sm">
                                            {{ $kos->latitude }}, {{ $kos->longitude }}
                                        </span>
                                        <button onclick="copyCoordinates()" 
                                                class="text-dark-muted hover:text-primary-400 transition">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kamar di Kos Ini -->
                    @if($kos->kamar->count() > 0)
                    <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-900/30 to-emerald-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-bed text-green-400 text-lg"></i>
                                </div>
                                <h2 class="text-xl font-bold text-white">Kamar Tersedia</h2>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-primary-900/30 text-primary-300">
                                {{ $kos->kamar->count() }} Kamar
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($kos->kamar as $kamar)
                            <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-primary-500/50 transition-all duration-300">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-white mb-1">Kamar {{ $kamar->nomor_kamar }}</h3>
                                        <p class="text-sm text-dark-muted">{{ $kamar->tipe_kamar }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $kamar->status_kamar == 'tersedia' ? 'bg-green-900/30 text-green-300' : 
                                           ($kamar->status_kamar == 'terisi' ? 'bg-blue-900/30 text-blue-300' : 
                                           'bg-yellow-900/30 text-yellow-300') }}">
                                        {{ ucfirst($kamar->status_kamar) }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between mt-4">
                                    <div class="text-sm text-dark-muted">
                                        <i class="fas fa-expand-arrows-alt mr-1"></i>
                                        {{ $kamar->luas_kamar ?? 'N/A' }}
                                    </div>
                                    <div class="text-lg font-bold text-white">
                                        Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column: Map & Features -->
                <div class="space-y-6">
                    <!-- Lokasi di Peta -->
                    <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-900/30 to-cyan-900/30 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-map-marked-alt text-blue-400 text-lg"></i>
                            </div>
                            <h2 class="text-xl font-bold text-white">Lokasi di Peta</h2>
                        </div>
                        
                        <div id="map" class="h-96 w-full rounded-xl border border-dark-border mb-4"></div>
                        
                        <div class="flex items-center justify-between text-sm text-dark-muted bg-dark-bg/50 border border-dark-border rounded-lg p-3">
                            <div class="flex items-center">
                                <i class="fas fa-location-dot mr-2 text-orange-400"></i>
                                <span>Koordinat GPS</span>
                            </div>
                            <span class="font-mono text-white">
                                {{ $kos->latitude }}, {{ $kos->longitude }}
                            </span>
                        </div>
                    </div>

                    <!-- Fasilitas -->
                    @if($kos->fasilitas->count() > 0)
                    <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-900/30 to-pink-900/30 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-list-check text-purple-400 text-lg"></i>
                            </div>
                            <h2 class="text-xl font-bold text-white">Fasilitas Kos</h2>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($kos->fasilitas as $fasilitas)
                            <div class="flex items-center p-3 bg-dark-bg/50 border border-dark-border rounded-lg hover:border-primary-500/30 transition">
                                <div class="w-8 h-8 bg-primary-900/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-{{ $fasilitas->icon ?? 'check' }} text-primary-400 text-sm"></i>
                                </div>
                                <span class="text-sm text-white">{{ $fasilitas->nama_fasilitas }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 bg-dark-card border border-dark-border rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Kelola Kos</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('pemilik.kos.index') }}" 
                       class="flex items-center px-5 py-3 bg-dark-border text-white rounded-xl hover:bg-dark-border/80 transition font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar
                    </a>
                    <a href="{{ route('pemilik.kos.edit', $kos->id_kos) }}" 
                       class="flex items-center px-5 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 transition font-medium shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Kos
                    </a>
                    <a href="{{ route('pemilik.kamar.create') }}?kos={{ $kos->id_kos }}" 
                       class="flex items-center px-5 py-3 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-xl hover:from-green-700 hover:to-emerald-800 transition font-medium shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Kamar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map { 
            height: 384px;
            width: 100%;
            border-radius: 12px;
            z-index: 1;
        }
        
        .leaflet-container {
            font-family: 'Inter', sans-serif;
        }
        
        .custom-popup .leaflet-popup-content-wrapper {
            background: #1e293b;
            color: #e2e8f0;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
        }
        
        .custom-popup .leaflet-popup-content {
            margin: 0;
            padding: 0;
        }
        
        .custom-popup .leaflet-popup-tip {
            background: #1e293b;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($kos->latitude && $kos->longitude)
                const lat = {{ $kos->latitude }};
                const lng = {{ $kos->longitude }};
                
                // Initialize map dengan dark theme
                const map = L.map('map', {
                    zoomControl: true,
                    scrollWheelZoom: true
                }).setView([lat, lng], 15);

                // Add dark tile layer
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    subdomains: 'abcd',
                    maxZoom: 19
                }).addTo(map);

                // Custom marker icon dengan gradient
                const customIcon = L.divIcon({
                    html: `
                        <div class="relative">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-home text-white text-sm"></i>
                            </div>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-gradient-to-br from-primary-500 to-indigo-500 rotate-45"></div>
                        </div>
                    `,
                    className: 'custom-marker',
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -35]
                });

                // Add marker
                const marker = L.marker([lat, lng], { 
                    icon: customIcon,
                    riseOnHover: true
                }).addTo(map);

                // Custom popup content
                const popupContent = `
                    <div class="p-4 max-w-xs">
                        <div class="flex items-start mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-home text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg mb-1">{{ $kos->nama_kos }}</h3>
                                <p class="text-sm text-dark-muted">{{ Str::limit($kos->alamat, 60) }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-dark-muted">
                                <i class="fas fa-users w-4 mr-2 text-primary-400"></i>
                                <span>{{ ucfirst($kos->jenis_kos) }}</span>
                            </div>
                            <div class="flex items-center text-dark-muted">
                                <i class="fas fa-tag w-4 mr-2 text-green-400"></i>
                                <span>{{ ucfirst($kos->tipe_sewa) }}</span>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-dark-border">
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full 
                                {{ $kos->status_kos == 'aktif' ? 'bg-green-900/30 text-green-300' : 
                                   ($kos->status_kos == 'pending' ? 'bg-yellow-900/30 text-yellow-300' : 
                                   'bg-red-900/30 text-red-300') }}">
                                <i class="fas 
                                    {{ $kos->status_kos == 'aktif' ? 'fa-check-circle' : 
                                       ($kos->status_kos == 'pending' ? 'fa-clock' : 'fa-times-circle') }} mr-1"></i>
                                {{ ucfirst($kos->status_kos) }}
                            </span>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent, {
                    className: 'custom-popup',
                    maxWidth: 350,
                    closeButton: true
                }).openPopup();

                // Add zoom control dengan style custom
                L.control.zoom({
                    position: 'topright'
                }).addTo(map);

            @else
                // Jika koordinat tidak ada
                document.getElementById('map').innerHTML = `
                    <div class="h-full flex items-center justify-center bg-gradient-to-br from-dark-border/20 to-dark-bg/50 rounded-xl">
                        <div class="text-center p-8">
                            <div class="w-20 h-20 bg-gradient-to-br from-dark-border to-dark-border/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-map text-3xl text-dark-muted"></i>
                            </div>
                            <h3 class="text-white font-medium mb-2">Lokasi Belum Ditentukan</h3>
                            <p class="text-dark-muted text-sm mb-4">Edit kos untuk menambahkan koordinat lokasi</p>
                            <a href="{{ route('pemilik.kos.edit', $kos->id_kos) }}" 
                               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm">
                                <i class="fas fa-edit mr-2"></i>
                                Tambah Lokasi
                            </a>
                        </div>
                    </div>
                `;
            @endif
        });

        function copyCoordinates() {
            const coords = "{{ $kos->latitude }}, {{ $kos->longitude }}";
            navigator.clipboard.writeText(coords).then(() => {
                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-900/90 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slideIn';
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>Koordinat disalin!</span>
                    </div>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            });
        }
    </script>
@endsection