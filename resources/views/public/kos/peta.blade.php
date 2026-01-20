@extends('layouts.app')

@section('title', 'Peta Kos - AyoKos')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-800 to-indigo-900 text-white py-12 md:py-16 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 left-0 w-64 h-64 bg-blue-400 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl">
            </div>
            <div
                class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-500 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl">
            </div>
        </div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Animated Map Icon -->
                <div
                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                    <i class="fas fa-map-marked-alt text-white text-2xl"></i>
                </div>

                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                    🗺️ Peta <span class="text-blue-300">Kos</span> Tersedia
                </h1>

                <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                    Temukan kos terdekat di lokasi yang Anda inginkan dengan peta interaktif
                </p>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-w-3xl mx-auto">
                    <div class="bg-blue-900/30 backdrop-blur-sm rounded-xl p-4 text-center border border-blue-700/30">
                        <div class="text-xl md:text-2xl font-bold text-white mb-1">{{ $kos->count() }}</div>
                        <div class="text-xs text-blue-200">Total Kos</div>
                    </div>

                    <div class="bg-blue-900/30 backdrop-blur-sm rounded-xl p-4 text-center border border-blue-700/30">
                        <div class="text-xl md:text-2xl font-bold text-white mb-1">
                            {{ $kos->where('jenis_kos', 'putra')->count() }}</div>
                        <div class="text-xs text-blue-200">Kos Putra</div>
                    </div>

                    <div class="bg-pink-900/30 backdrop-blur-sm rounded-xl p-4 text-center border border-pink-700/30">
                        <div class="text-xl md:text-2xl font-bold text-white mb-1">
                            {{ $kos->where('jenis_kos', 'putri')->count() }}</div>
                        <div class="text-xs text-pink-200">Kos Putri</div>
                    </div>

                    <div class="bg-purple-900/30 backdrop-blur-sm rounded-xl p-4 text-center border border-purple-700/30">
                        <div class="text-xl md:text-2xl font-bold text-white mb-1">
                            {{ $kos->where('jenis_kos', 'campuran')->count() }}</div>
                        <div class="text-xs text-purple-200">Kos Campuran</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Filter -->
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 lg:col-span-1">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-filter text-blue-400 mr-3"></i>
                    Filter
                </h2>

                <div class="space-y-5">
                    <!-- Jenis Kos -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2 flex items-center">
                            <i class="fas fa-users mr-2 text-blue-400"></i>
                            Jenis Kos
                        </label>
                        <select id="filter-jenis"
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors appearance-none">
                            <option value="">Semua Jenis</option>
                            <option value="putra">Putra</option>
                            <option value="putri">Putri</option>
                            <option value="campuran">Campuran</option>
                        </select>
                    </div>

                    <!-- Tipe Sewa -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-emerald-400"></i>
                            Tipe Sewa
                        </label>
                        <select id="filter-tipe"
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors appearance-none">
                            <option value="">Semua Tipe</option>
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan">Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2 flex items-center">
                            <i class="fas fa-tag mr-2 text-yellow-400"></i>
                            Rentang Harga
                        </label>
                        <select id="filter-harga"
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition-colors appearance-none">
                            <option value="">Semua Harga</option>
                            <option value="0-500000">≤ Rp 500rb</option>
                            <option value="500000-1000000">Rp 500rb - 1jt</option>
                            <option value="1000000-2000000">Rp 1jt - 2jt</option>
                            <option value="2000000-999999999">≥ Rp 2jt</option>
                        </select>
                    </div>

                    <!-- Find Nearby Button -->
                    <div class="border-t border-slate-700 pt-4 mt-2">
                        <button id="find-nearby"
                            class="w-full bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white py-3 rounded-xl transition-all duration-300 flex items-center justify-center gap-3 group shadow-lg hover:shadow-xl hover:-translate-y-1">
                            <i class="fas fa-location-arrow text-lg group-hover:animate-pulse"></i>
                            <span class="font-medium">Cari Kos Terdekat</span>
                        </button>
                        <p class="text-xs text-slate-400 mt-2 text-center">(Maksimal 1 km dari lokasi Anda)</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3">
                        <button id="apply-filter"
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-2.5 rounded-xl transition-all duration-300 shadow hover:shadow-md flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i>
                            Terapkan
                        </button>
                        <button id="reset-filter"
                            class="bg-slate-700 hover:bg-slate-600 text-white py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-undo mr-2"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Nearby Info -->
                <div id="nearby-info"
                    class="mt-6 p-4 bg-emerald-900/20 rounded-xl border border-emerald-800/30 hidden backdrop-blur-sm">
                    <h3 class="font-semibold text-emerald-300 mb-2 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 animate-pulse"></i>
                        Kos Terdekat Ditemukan
                    </h3>
                    <div id="nearby-count" class="text-2xl font-bold text-emerald-400">0</div>
                    <p class="text-sm text-emerald-300/80 mt-1">kos dalam radius 1 km</p>
                    <button id="clear-nearby"
                        class="w-full mt-3 bg-emerald-900/30 hover:bg-emerald-900/50 text-emerald-300 py-2 text-sm rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Hapus Filter Jarak
                    </button>
                </div>

                <!-- Quick Links -->
                <div class="mt-6 pt-6 border-t border-slate-700">
                    <h3 class="font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-bolt text-yellow-400 mr-3"></i>
                        Akses Cepat
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('public.kos.index') }}"
                            class="flex items-center text-blue-300 hover:text-blue-200 text-sm transition-all duration-300 group">
                            <div
                                class="w-8 h-8 bg-blue-900/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-900/50 transition-colors">
                                <i class="fas fa-search text-blue-400"></i>
                            </div>
                            <span>Cari Kos Berdasarkan List</span>
                        </a>

                        @auth('penghuni')
                            <a href="{{ route('penghuni.dashboard') }}"
                                class="flex items-center text-emerald-300 hover:text-emerald-200 text-sm transition-all duration-300 group">
                                <div
                                    class="w-8 h-8 bg-emerald-900/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-emerald-900/50 transition-colors">
                                    <i class="fas fa-home text-emerald-400"></i>
                                </div>
                                <span>Dashboard Penghuni</span>
                            </a>
                        @elseauth('pemilik')
                            <a href="{{ route('pemilik.dashboard') }}"
                                class="flex items-center text-blue-300 hover:text-blue-200 text-sm transition-all duration-300 group">
                                <div
                                    class="w-8 h-8 bg-blue-900/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-900/50 transition-colors">
                                    <i class="fas fa-user-tie text-blue-400"></i>
                                </div>
                                <span>Dashboard Pemilik</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="flex items-center text-orange-300 hover:text-orange-200 text-sm transition-all duration-300 group">
                                <div
                                    class="w-8 h-8 bg-orange-900/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-900/50 transition-colors">
                                    <i class="fas fa-lock text-orange-400"></i>
                                </div>
                                <span>Login untuk Fitur Lebih</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 lg:col-span-3">
                <!-- Route Control Panel -->
                <div id="route-control-panel"
                    class="mb-4 p-4 bg-gradient-to-r from-emerald-900/20 to-green-900/20 border border-emerald-800/30 rounded-xl hidden backdrop-blur-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-emerald-300 flex items-center">
                                <i class="fas fa-route mr-2"></i>
                                <span id="route-title">Rute Menuju Kos</span>
                            </h3>
                            <p id="route-distance" class="text-sm text-emerald-300/80 mt-1">Memuat rute...</p>
                        </div>
                        <div class="flex gap-2">
                            <button id="print-route"
                                class="bg-emerald-900/30 hover:bg-emerald-900/50 text-emerald-300 px-3 py-2 rounded-lg text-sm transition-colors flex items-center">
                                <i class="fas fa-print mr-1"></i>Cetak
                            </button>
                            <button id="close-route"
                                class="bg-red-900/30 hover:bg-red-900/50 text-red-300 px-3 py-2 rounded-lg text-sm transition-colors flex items-center">
                                <i class="fas fa-times mr-1"></i>Tutup
                            </button>
                        </div>
                    </div>
                    <div id="route-instructions" class="mt-3 text-sm text-emerald-200/80 max-h-32 overflow-y-auto pr-2">
                        <!-- Instruksi rute akan ditampilkan di sini -->
                    </div>
                </div>

                <!-- Map Element -->
                <div id="map" style="height: 600px; width: 100%;"
                    class="rounded-xl border border-slate-700 overflow-hidden"></div>

                <!-- Legend -->
                <div class="mt-4 flex flex-wrap gap-4 justify-center">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded"></div>
                        <span class="text-sm text-slate-400">Kos Putra</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-pink-500 rounded"></div>
                        <span class="text-sm text-slate-400">Kos Putri</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-purple-500 rounded"></div>
                        <span class="text-sm text-slate-400">Kos Campuran</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-emerald-500 rounded"></div>
                        <span class="text-sm text-slate-400">Lokasi Anda</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm text-slate-400">Radius 1 km</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4" style="background: linear-gradient(45deg, #10b981, #059669);"></div>
                        <span class="text-sm text-slate-400">Rute</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nearby Kos List (Mobile View) -->
        <div id="nearby-kos-list" class="mt-8 hidden">
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-map-marker-alt text-emerald-400 mr-3"></i>
                        Kos Terdekat (Dalam 1 km)
                    </h2>
                    <button id="hide-nearby-list" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <!-- Kos terdekat akan ditampilkan di sini -->
                </div>
            </div>
        </div>

        <!-- Kos List (Mobile View) -->
        <div class="mt-8 lg:hidden">
            <h2 class="text-xl font-semibold text-white mb-4">🏠 Daftar Kos Terdekat</h2>
            <div class="grid grid-cols-1 gap-4">
                @foreach($kos->take(5) as $k)
                        <div
                            class="bg-slate-800 border border-slate-700 rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-white">{{ $k->nama_kos }}</h3>
                                    <p class="text-sm text-slate-400 mt-1">{{ $k->alamat }}</p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="text-xs px-2 py-1 rounded-full 
                                                {{ $k->jenis_kos == 'putra' ? 'bg-blue-900/30 text-blue-300' :
                    ($k->jenis_kos == 'putri' ? 'bg-pink-900/30 text-pink-300' :
                        'bg-purple-900/30 text-purple-300') }}">
                                            {{ ucfirst($k->jenis_kos) }}
                                        </span>
                                        <span class="text-xs text-slate-400">{{ $k->kamar_count ?? 0 }} Kamar</span>
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="mb-2">
                                        @if(($k->kamar->min('harga') ?? 0) > 0)
                                            <span class="text-sm font-bold text-white">
                                                Rp {{ number_format($k->kamar->min('harga'), 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-sm font-bold text-red-400">
                                                Kamar tidak tersedia
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('public.kos.show', $k->id_kos) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg text-sm transition-all duration-300 shadow hover:shadow-md">
                                        <i class="fas fa-eye mr-1 text-xs"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <style>
        /* Map styling */
        #map {
            height: 600px;
            width: 100%;
            z-index: 1;
            border-radius: 0.75rem;
        }

        .leaflet-container {
            font-family: 'Inter', sans-serif;
            background: #0f172a !important;
        }

        .leaflet-popup-content {
            min-width: 250px;
            margin: 8px;
            background: #1e293b;
            color: #e2e8f0;
            border-radius: 0.5rem;
        }

        .leaflet-popup-content-wrapper {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .leaflet-popup-tip {
            background: #1e293b;
        }

        .leaflet-control-zoom a {
            background: #1e293b !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        .leaflet-control-zoom a:hover {
            background: #334155 !important;
        }

        .distance-badge {
            background: linear-gradient(to right, #10b981, #34d399);
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-top: 4px;
            display: inline-block;
        }

        .nearby-marker {
            animation: pulse 2s infinite;
            filter: drop-shadow(0 0 8px rgba(34, 197, 94, 0.6));
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                filter: drop-shadow(0 0 8px rgba(34, 197, 94, 0.6));
            }

            50% {
                transform: scale(1.1);
                filter: drop-shadow(0 0 12px rgba(34, 197, 94, 0.8));
            }

            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 8px rgba(34, 197, 94, 0.6));
            }
        }

        .leaflet-control-custom {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.375rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .leaflet-control-custom a {
            color: #e2e8f0 !important;
            background: #1e293b !important;
            border-radius: 0.375rem !important;
            width: 36px !important;
            height: 36px !important;
            line-height: 36px !important;
            text-align: center !important;
        }

        .leaflet-control-custom a:hover {
            background: #334155 !important;
        }

        /* Custom legend */
        .legend {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
            border-radius: 0.75rem !important;
            padding: 12px !important;
            color: #e2e8f0 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }

        .legend h4 {
            color: #e2e8f0 !important;
            margin-bottom: 8px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
        }

        .legend div {
            color: #94a3b8 !important;
            font-size: 13px !important;
            margin-bottom: 6px !important;
        }

        .leaflet-touch .leaflet-control-layers,
        .leaflet-touch .leaflet-bar {
            border: 1px solid #334155 !important;
        }

        .leaflet-control-attribution {
            background: rgba(30, 41, 59, 0.9) !important;
            color: #94a3b8 !important;
            border: 1px solid #334155 !important;
            border-radius: 0.25rem !important;
            padding: 2px 8px !important;
        }

        .leaflet-control-attribution a {
            color: #60a5fa !important;
        }

        /* Routing Machine Custom Styling */
        .leaflet-routing-container {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
            border-radius: 0.75rem !important;
            color: #e2e8f0 !important;
            max-width: 350px !important;
            max-height: 400px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }

        .leaflet-routing-alt {
            background: #1e293b !important;
            color: #e2e8f0 !important;
            max-height: 200px !important;
        }

        .leaflet-routing-alt table {
            color: #e2e8f0 !important;
        }

        .leaflet-routing-alt tr:hover {
            background: #334155 !important;
        }

        .leaflet-routing-alt::-webkit-scrollbar {
            width: 6px;
        }

        .leaflet-routing-alt::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .leaflet-routing-alt::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 3px;
        }

        .leaflet-routing-alt h2,
        .leaflet-routing-alt h3 {
            color: #e2e8f0 !important;
        }

        .leaflet-routing-icon {
            background-color: #4ade80 !important;
            border-radius: 50% !important;
        }

        .leaflet-routing-geocoder {
            margin: 8px !important;
        }

        .leaflet-routing-geocoder input {
            background: #334155 !important;
            color: #e2e8f0 !important;
            border: 1px solid #475569 !important;
            border-radius: 0.375rem !important;
            padding: 8px 12px !important;
        }

        .leaflet-routing-geocoder button {
            background: linear-gradient(to right, #10b981, #34d399) !important;
            color: white !important;
            border: none !important;
            border-radius: 0.375rem !important;
            padding: 8px 16px !important;
            margin-top: 8px !important;
            cursor: pointer !important;
        }

        .leaflet-routing-geocoder button:hover {
            background: linear-gradient(to right, #34d399, #10b981) !important;
        }

        .leaflet-routing-collapse-btn {
            background: #334155 !important;
            color: #e2e8f0 !important;
            border-radius: 0.375rem !important;
        }

        /* Custom notification */
        .custom-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 12px 16px;
            border-radius: 0.75rem;
            background: #1e293b;
            border: 1px solid #334155;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            color: #e2e8f0;
            max-width: 300px;
            animation: slideInRight 0.3s ease-out;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .custom-notification.success {
            border-left: 4px solid #10b981;
        }

        .custom-notification.error {
            border-left: 4px solid #ef4444;
        }

        .custom-notification.warning {
            border-left: 4px solid #f59e0b;
        }

        .custom-notification.info {
            border-left: 4px solid #3b82f6;
        }

        /* Route control panel */
        #route-control-panel {
            transition: all 0.3s ease;
        }

        .route-instruction-item {
            padding: 8px 12px;
            margin-bottom: 6px;
            background: rgba(30, 41, 59, 0.5);
            border-radius: 0.5rem;
            border-left: 3px solid #10b981;
        }

        .route-instruction-item:hover {
            background: rgba(30, 41, 59, 0.8);
        }

        .route-step-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 12px;
        }
    </style>
@endpush

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM loaded, initializing map...');

            // Elemen map
            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.error('Map element not found!');
                return;
            }

            // Default coordinates (Jakarta)
            const defaultLat = -6.208763;
            const defaultLng = 106.845599;

            // Initialize map
            let map;
            try {
                map = L.map('map').setView([defaultLat, defaultLng], 12);
                console.log('Map initialized successfully');
            } catch (error) {
                console.error('Error initializing map:', error);
                return;
            }

            // Add tile layer with dark theme
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                maxZoom: 19,
                subdomains: 'abcd'
            }).addTo(map);

            // Custom icons based on kos type
            const icons = {
                putra: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    className: 'kos-marker'
                }),
                putri: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    className: 'kos-marker'
                }),
                campuran: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    className: 'kos-marker'
                }),
                user: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    className: 'user-marker'
                }),
                destination: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-gold.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    className: 'destination-marker'
                })
            };

            // Store all markers and kos data
            let markers = [];
            let userLocationMarker = null;
            let radiusCircle = null;
            let currentUserLocation = null;
            let routingControl = null;

            // Function to calculate distance between two coordinates in kilometers
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371; // Radius of the earth in km
                const dLat = deg2rad(lat2 - lat1);
                const dLon = deg2rad(lon2 - lon1);
                const a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                const distance = R * c; // Distance in km
                return distance;
            }

            function deg2rad(deg) {
                return deg * (Math.PI / 180);
            }

            // Function to format distance
            function formatDistance(distance) {
                if (distance < 1) {
                    return Math.round(distance * 1000) + ' m';
                }
                return distance.toFixed(1) + ' km';
            }

            // Function to show notification
            function showNotification(message, type = 'info') {
                // Remove existing notification
                const existingNotification = document.querySelector('.custom-notification');
                if (existingNotification) {
                    existingNotification.remove();
                }

                // Create new notification
                const notification = document.createElement('div');
                notification.className = `custom-notification ${type}`;
                notification.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas ${type === 'success' ? 'fa-check-circle text-emerald-400' :
                        type === 'error' ? 'fa-times-circle text-red-400' :
                            type === 'warning' ? 'fa-exclamation-triangle text-yellow-400' :
                                'fa-info-circle text-blue-400'
                    } mr-3"></i>
                            <span>${message}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="ml-4 text-slate-400 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    `;

                document.body.appendChild(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }

            // Function to remove existing route
            function removeRoute() {
                if (routingControl) {
                    map.removeControl(routingControl);
                    routingControl = null;
                }

                // Hide route control panel
                const routePanel = document.getElementById('route-control-panel');
                if (routePanel) {
                    routePanel.classList.add('hidden');
                }
            }

            // Function to show route
            // GANTI fungsi showRoute dengan yang ini:
            function showRoute(fromLat, fromLng, toLat, toLng, kosName, kosAddress, kosId = null) {
                // Remove existing route
                removeRoute();

                // Cari marker kos asli berdasarkan ID atau koordinat
                let originalKosMarker = null;
                if (kosId) {
                    originalKosMarker = markers.find(k => k.id === kosId)?.marker;
                } else {
                    // Fallback: cari berdasarkan koordinat
                    originalKosMarker = markers.find(k =>
                        Math.abs(k.lat - toLat) < 0.0001 &&
                        Math.abs(k.lng - toLng) < 0.0001
                    )?.marker;
                }

                // Create routing control
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(fromLat, fromLng),
                        L.latLng(toLat, toLng)
                    ],
                    routeWhileDragging: false,
                    showAlternatives: false,
                    fitSelectedRoutes: true,
                    show: false, // Hide default instructions panel
                    router: L.Routing.osrmv1({
                        serviceUrl: 'https://router.project-osrm.org/route/v1'
                    }),
                    lineOptions: {
                        styles: [
                            {
                                color: '#10b981',
                                weight: 5,
                                opacity: 0.8
                            }
                        ]
                    },
                    createMarker: function (i, waypoint, n) {
                        if (i === 0) {
                            // Marker untuk titik awal (lokasi pengguna)
                            return L.marker(waypoint.latLng, {
                                icon: icons.user
                            }).bindPopup(`
                                    <div class="p-2">
                                        <div class="text-emerald-400 text-lg mb-1 text-center">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="font-medium text-white">📍 Lokasi Anda</div>
                                    </div>
                                `);
                        } else {
                            // Untuk titik tujuan, KOSONGKAN - jangan buat marker baru
                            // Karena marker kos asli sudah ada
                            return null;
                        }
                    }
                }).addTo(map);

                // Event ketika route ditemukan
                routingControl.on('routesfound', function (e) {
                    const routes = e.routes;
                    const route = routes[0];

                    if (route && route.summary) {
                        const distance = (route.summary.totalDistance / 1000).toFixed(1);
                        const time = Math.round(route.summary.totalTime / 60);

                        // Update route control panel
                        const routePanel = document.getElementById('route-control-panel');
                        const routeTitle = document.getElementById('route-title');
                        const routeDistance = document.getElementById('route-distance');
                        const routeInstructions = document.getElementById('route-instructions');

                        if (routePanel) {
                            routePanel.classList.remove('hidden');
                            routeTitle.textContent = `Rute ke ${kosName}`;
                            routeDistance.textContent = `📏 ${distance} km • ⏱️ ${time} menit`;

                            // Update instruksi rute
                            if (routeInstructions && route.instructions) {
                                routeInstructions.innerHTML = '';
                                route.instructions.forEach((instruction, index) => {
                                    const step = document.createElement('div');
                                    step.className = 'route-instruction-item';
                                    step.innerHTML = `
                                            <div class="flex items-start">
                                                <div class="route-step-icon">${index + 1}</div>
                                                <div>
                                                    <div class="text-white text-sm">${instruction.text}</div>
                                                    <div class="text-emerald-300/70 text-xs mt-1">
                                                        ${formatDistance(instruction.distance / 1000)}
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                    routeInstructions.appendChild(step);
                                });
                            }
                        }

                        // Buka popup kos asli (jika ada)
                        if (originalKosMarker) {
                            // Tunda sedikit agar routing selesai dulu
                            setTimeout(() => {
                                originalKosMarker.openPopup();
                            }, 500);
                        }

                        showNotification(`Rute ke ${kosName} ditemukan (${distance} km)`, 'success');
                    }
                });

                routingControl.on('routingerror', function (e) {
                    console.error('Routing error:', e.error);
                    showNotification('Tidak dapat menemukan rute. Coba lagi nanti.', 'error');
                });

                // Fit bounds untuk menampilkan seluruh rute
                setTimeout(() => {
                    if (routingControl && routingControl.getPlan()) {
                        const bounds = L.latLngBounds([
                            [fromLat, fromLng],
                            [toLat, toLng]
                        ]);
                        map.fitBounds(bounds, { padding: [50, 50] });
                    }
                }, 100);
            }

            // FITUR 1: Ketika tombol "Cari Kos Terdekat" ditekan, langsung muncul rute ke kos terdekat
            function findNearestKosAndShowRoute(userLat, userLng) {
                let nearestKos = null;
                let minDistance = Infinity;

                // Cari kos terdekat dalam radius 1 km
                markers.forEach(kos => {
                    const distance = calculateDistance(userLat, userLng, kos.lat, kos.lng);

                    if (distance <= 1 && distance < minDistance) {
                        minDistance = distance;
                        nearestKos = {
                            ...kos,
                            distance: distance
                        };
                    }
                });

                // Jika ditemukan kos terdekat dalam 1 km, tampilkan rute
                if (nearestKos) {
                    showRoute(
                        userLat,
                        userLng,
                        nearestKos.lat,
                        nearestKos.lng,
                        nearestKos.nama,
                        nearestKos.alamat,
                        nearestKos.id // Kirim ID kos untuk referensi
                    );

                    // Highlight the nearest kos marker
                    if (nearestKos.marker && nearestKos.marker._icon) {
                        nearestKos.marker._icon.classList.add('nearby-marker');

                        // Buka popup kos asli
                        setTimeout(() => {
                            nearestKos.marker.openPopup();
                        }, 1000);
                    }

                    showNotification(`Menampilkan rute ke kos terdekat: ${nearestKos.nama} (${formatDistance(minDistance)})`, 'success');
                    return nearestKos;
                }

                return null;
            }

            // FITUR 2: Ketika marker kos ditekan, muncul tombol untuk menampilkan rute
            function createKosPopupContent(kos) {
                return `
                        <div class="p-3" style="min-width: 250px; background: #1e293b; color: #e2e8f0;">
                            <h3 class="font-bold text-lg text-white mb-2">${kos.nama}</h3>
                            <p class="text-sm text-slate-400 mb-3">${kos.alamat}</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    ${kos.jenis == 'putra' ? 'bg-blue-900/30 text-blue-300' :
                        (kos.jenis == 'putri' ? 'bg-pink-900/30 text-pink-300' :
                            'bg-purple-900/30 text-purple-300')}">
                                    ${kos.jenis.charAt(0).toUpperCase() + kos.jenis.slice(1)}
                                </span>
                                <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/30 text-emerald-300">
                                    ${kos.tipe.charAt(0).toUpperCase() + kos.tipe.slice(1)}
                                </span>
                            </div>
                            <div id="distance-${kos.id}" class="distance-badge mt-2 hidden">
                                Jarak: <span class="font-bold">0 km</span>
                            </div>
                            <div class="mt-4 flex flex-col space-y-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        ${kos.minHarga > 0 ?
                        `<span class="text-sm font-bold text-white">
                                                Mulai Rp ${kos.minHarga.toLocaleString('id-ID')}
                                            </span>` :
                        `<span class="text-sm font-bold text-red-400">
                                                Kamar tidak tersedia
                                            </span>`
                    }
                                    </div>
                                    <span class="text-xs text-slate-400">${kos.kamarCount} Kamar</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <a href="${kos.detailUrl}" 
                                    class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-3 py-2 rounded-lg text-sm transition-all duration-300 inline-flex items-center justify-center shadow hover:shadow-md" style="color: white !important;">
                                        <i class="fas fa-eye mr-1 text-xs"></i>
                                        Detail
                                    </a>
                                    <button onclick="window.showRouteToKos(${kos.lat}, ${kos.lng}, '${kos.nama.replace(/'/g, "\\'")}', '${kos.alamat.replace(/'/g, "\\'")}')" 
                                    class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white px-3 py-2 rounded-lg text-sm transition-all duration-300 inline-flex items-center justify-center shadow hover:shadow-md">
                                        <i class="fas fa-route mr-1 text-xs"></i>
                                        Rute
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
            }

            // Global function to show route to specific kos (called from popup)
            window.showRouteToKos = function (kosLat, kosLng, kosName, kosAddress) {
                if (!currentUserLocation) {
                    // If user location not available, ask for permission
                    if (navigator.geolocation) {
                        showNotification('Meminta izin lokasi untuk menampilkan rute...', 'info');

                        navigator.geolocation.getCurrentPosition(function (position) {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;

                            currentUserLocation = { lat: userLat, lng: userLng };

                            // Add user marker
                            if (userLocationMarker) {
                                map.removeLayer(userLocationMarker);
                            }

                            userLocationMarker = L.marker([userLat, userLng], {
                                icon: icons.user
                            }).addTo(map);

                            // Show route
                            showRoute(userLat, userLng, kosLat, kosLng, kosName, kosAddress);

                        }, function (error) {
                            console.error('Geolocation error:', error);
                            showNotification('Izin lokasi diperlukan untuk menampilkan rute', 'error');
                        });
                    } else {
                        showNotification('Browser tidak mendukung geolocation', 'error');
                    }
                } else {
                    // Show route using known user location
                    showRoute(
                        currentUserLocation.lat,
                        currentUserLocation.lng,
                        kosLat,
                        kosLng,
                        kosName,
                        kosAddress
                    );
                }
            };

            // Function to find nearby kos (within 1 km)
            function findNearbyKos(userLat, userLng) {
                console.log('Finding nearby kos from:', userLat, userLng);

                currentUserLocation = { lat: userLat, lng: userLng };

                map.setView([userLat, userLng], 15);

                // Clear previous radius circle
                if (radiusCircle) {
                    map.removeLayer(radiusCircle);
                }

                // Add radius circle (1 km)
                radiusCircle = L.circle([userLat, userLng], {
                    color: '#f59e0b',
                    fillColor: '#fef3c7',
                    fillOpacity: 0.15,
                    radius: 1000, // 1 km in meters
                    className: 'radius-circle'
                }).addTo(map);

                let nearbyCount = 0;
                const nearbyKos = [];

                // Check each kos
                markers.forEach(kos => {
                    const distance = calculateDistance(userLat, userLng, kos.lat, kos.lng);

                    if (distance <= 1) { // Within 1 km
                        nearbyCount++;
                        nearbyKos.push({
                            ...kos,
                            distance: distance
                        });

                        // Add animation to nearby markers
                        if (kos.marker && kos.marker._icon) {
                            kos.marker._icon.classList.add('nearby-marker');
                        }

                        // Bring to front
                        kos.marker.bringToFront();
                    } else {
                        // Remove animation
                        if (kos.marker && kos.marker._icon) {
                            kos.marker._icon.classList.remove('nearby-marker');
                        }
                    }
                });

                console.log('Nearby kos found:', nearbyCount);

                // Show nearby info
                const nearbyInfo = document.getElementById('nearby-info');
                if (nearbyInfo) {
                    nearbyInfo.classList.remove('hidden');
                    document.getElementById('nearby-count').textContent = nearbyCount;
                }

                // Show nearby kos list for mobile
                if (nearbyKos.length > 0) {
                    showNearbyKosList(nearbyKos);
                }

                // Fit map to show all nearby kos and user location
                if (nearbyKos.length > 0) {
                    const bounds = L.latLngBounds([[userLat, userLng]]);
                    nearbyKos.forEach(kos => {
                        bounds.extend([kos.lat, kos.lng]);
                    });
                    map.fitBounds(bounds, { padding: [50, 50] });
                }

                return nearbyKos;
            }

            // Function to show nearby kos list
            function showNearbyKosList(nearbyKos) {
                const container = document.getElementById('nearby-kos-list');
                if (!container) return;

                const listContainer = container.querySelector('.grid');

                // Sort by distance
                nearbyKos.sort((a, b) => a.distance - b.distance);

                // Clear previous content
                listContainer.innerHTML = '';

                // Add each nearby kos
                nearbyKos.forEach(kos => {
                    const kosElement = document.createElement('div');
                    kosElement.className = 'bg-slate-800/50 border border-slate-700 rounded-xl p-4 hover:border-emerald-500/50 transition-all duration-300 hover:shadow-lg hover:-translate-y-1';
                    kosElement.innerHTML = `
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-white mb-2">${kos.nama}</h3>
                                    <p class="text-sm text-slate-400 mb-3">${kos.alamat}</p>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs px-2 py-1 rounded-full ${kos.jenis == 'putra' ? 'bg-blue-900/30 text-blue-300' : (kos.jenis == 'putri' ? 'bg-pink-900/30 text-pink-300' : 'bg-purple-900/30 text-purple-300')}">
                                            ${kos.jenis.charAt(0).toUpperCase() + kos.jenis.slice(1)}
                                        </span>
                                        <span class="text-xs text-slate-400">${kos.kamarCount} Kamar</span>
                                        <span class="text-xs bg-gradient-to-r from-emerald-900/30 to-green-900/30 text-emerald-300 px-2 py-1 rounded-full">
                                            <i class="fas fa-ruler mr-1"></i>
                                            ${formatDistance(kos.distance)}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="mb-2">
                                        ${kos.minHarga > 0 ?
                            `<span class="text-sm font-bold text-white">
                                                Rp ${kos.minHarga.toLocaleString('id-ID')}
                                            </span>` :
                            `<span class="text-sm font-bold text-red-400">
                                                Kamar tidak tersedia
                                            </span>`
                        }
                                    </div>
                                    <div class="flex flex-col space-y-1">
                                        <a href="${kos.detailUrl}" 
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg text-sm transition-all duration-300 shadow hover:shadow-md">
                                            <i class="fas fa-eye mr-1 text-xs"></i>
                                            Detail
                                        </a>
                                        <button onclick="window.showRouteToKos(${kos.lat}, ${kos.lng}, '${kos.nama.replace(/'/g, "\\'")}', '${kos.alamat.replace(/'/g, "\\'")}')" 
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-lg text-sm transition-all duration-300 shadow hover:shadow-md">
                                            <i class="fas fa-route mr-1 text-xs"></i>
                                            Rute
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    listContainer.appendChild(kosElement);
                });

                // Show the container
                container.classList.remove('hidden');
                container.scrollIntoView({ behavior: 'smooth' });
            }

            // Function to clear nearby filter
            function clearNearbyFilter() {
                console.log('Clearing nearby filter');

                // Remove radius circle
                if (radiusCircle) {
                    map.removeLayer(radiusCircle);
                    radiusCircle = null;
                }

                // Remove user location marker
                if (userLocationMarker) {
                    map.removeLayer(userLocationMarker);
                    userLocationMarker = null;
                }

                // Remove route
                removeRoute();

                // Remove animation from all markers
                markers.forEach(kos => {
                    if (kos.marker && kos.marker._icon) {
                        kos.marker._icon.classList.remove('nearby-marker');
                    }
                });

                // Hide nearby info
                const nearbyInfo = document.getElementById('nearby-info');
                if (nearbyInfo) {
                    nearbyInfo.classList.add('hidden');
                }

                // Hide nearby kos list
                const nearbyList = document.getElementById('nearby-kos-list');
                if (nearbyList) {
                    nearbyList.classList.add('hidden');
                }

                currentUserLocation = null;
            }

            // Add markers for each kos
            @foreach($kos as $k)
                @if($k->latitude && $k->longitude)
                    try {
                        const kosData = {
                            id: {{ $k->id_kos }},
                            jenis: '{{ $k->jenis_kos }}',
                            tipe: '{{ $k->tipe_sewa }}',
                            harga: {{ $k->kamar->min('harga') ?? 0 }},
                            lat: {{ $k->latitude }},
                            lng: {{ $k->longitude }},
                            nama: '{{ addslashes($k->nama_kos) }}',
                            alamat: '{{ addslashes($k->alamat) }}',
                            detailUrl: '{{ route('public.kos.show', $k->id_kos) }}',
                            minHarga: {{ $k->kamar->min('harga') ?? 0 }},
                            kamarCount: {{ $k->kamar_count ?? 0 }}
                                    };

                        const marker = L.marker([{{ $k->latitude }}, {{ $k->longitude }}], {
                            icon: icons['{{ $k->jenis_kos }}'] || icons.campuran,
                            riseOnHover: true
                        }).addTo(map);

                        marker.bindPopup(createKosPopupContent(kosData), {
                            maxWidth: 300,
                            className: 'custom-popup'
                        });

                        // Store marker reference
                        kosData.marker = marker;
                        markers.push(kosData);

                    } catch (error) {
                        console.error('Error adding marker for kos {{ $k->id_kos }}:', error);
                    }
                @endif
            @endforeach

            console.log('Total markers added:', markers.length);

            // Event listener for "Cari Kos Terdekat" button
            document.getElementById('find-nearby').addEventListener('click', function () {
                console.log('Finding nearby kos...');

                if (navigator.geolocation) {
                    // Show loading state
                    const button = this;
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mencari lokasi...';
                    button.disabled = true;

                    navigator.geolocation.getCurrentPosition(function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        console.log('User location obtained:', lat, lng);

                        // Add or update user location marker
                        if (userLocationMarker) {
                            map.removeLayer(userLocationMarker);
                        }

                        userLocationMarker = L.marker([lat, lng], {
                            icon: icons.user
                        }).addTo(map).bindPopup(`
                                <div class="p-2 text-center">
                                    <div class="text-emerald-400 text-lg mb-1">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="font-medium text-white">📍 Lokasi Anda Sekarang</div>
                                    <div class="text-xs text-slate-400 mt-1">${lat.toFixed(6)}, ${lng.toFixed(6)}</div>
                                </div>
                            `).openPopup();

                        // Restore button state
                        button.innerHTML = originalText;
                        button.disabled = false;

                        // FITUR 1: Cari kos terdekat dan tampilkan rute otomatis
                        const nearestKos = findNearestKosAndShowRoute(lat, lng);

                        // Juga tampilkan semua kos dalam radius 1 km
                        findNearbyKos(lat, lng);

                    }, function (error) {
                        console.error('Geolocation error:', error);
                        showNotification('Tidak dapat mengakses lokasi. Pastikan izin lokasi diaktifkan.', 'error');

                        // Restore button state
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    });
                } else {
                    showNotification('Geolocation tidak didukung oleh browser ini.', 'error');
                }
            });

            // Event listener for "Hapus Filter Jarak" button
            document.getElementById('clear-nearby')?.addEventListener('click', clearNearbyFilter);

            // Event listener for "Tutup" button on mobile list
            document.getElementById('hide-nearby-list')?.addEventListener('click', function () {
                const nearbyList = document.getElementById('nearby-kos-list');
                if (nearbyList) {
                    nearbyList.classList.add('hidden');
                }
            });

            // Event listener for "Tutup Rute" button
            document.getElementById('close-route')?.addEventListener('click', removeRoute);

            // Event listener for "Cetak Rute" button
            document.getElementById('print-route')?.addEventListener('click', function () {
                const routeInstructions = document.getElementById('route-instructions');
                if (routeInstructions) {
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(`
                            <html>
                                <head>
                                    <title>Rute ke Kos</title>
                                    <style>
                                        body { font-family: Arial, sans-serif; padding: 20px; background: #0f172a; color: #e2e8f0; }
                                        h1 { color: #e2e8f0; }
                                        .instruction { margin: 10px 0; padding: 10px; border-left: 3px solid #10b981; background: #1e293b; }
                                        .distance { color: #94a3b8; font-size: 0.9em; }
                                        hr { border-color: #334155; }
                                    </style>
                                </head>
                                <body>
                                    <h1>${document.getElementById('route-title').textContent}</h1>
                                    <p>${document.getElementById('route-distance').textContent}</p>
                                    <hr>
                                    ${routeInstructions.innerHTML}
                                </body>
                            </html>
                        `);
                    printWindow.document.close();
                    printWindow.print();
                }
            });

            // Filter functionality
            document.getElementById('apply-filter')?.addEventListener('click', function () {
                const jenisFilter = document.getElementById('filter-jenis').value;
                const tipeFilter = document.getElementById('filter-tipe').value;
                const hargaFilter = document.getElementById('filter-harga').value;

                markers.forEach(item => {
                    let show = true;

                    // Filter by jenis
                    if (jenisFilter && item.jenis !== jenisFilter) {
                        show = false;
                    }

                    // Filter by tipe
                    if (tipeFilter && item.tipe !== tipeFilter) {
                        show = false;
                    }

                    // Filter by harga
                    if (hargaFilter) {
                        const [min, max] = hargaFilter.split('-').map(Number);
                        if (item.harga < min || item.harga > max) {
                            show = false;
                        }
                    }

                    // Additional filter for nearby kos
                    if (currentUserLocation && radiusCircle) {
                        const distance = calculateDistance(
                            currentUserLocation.lat,
                            currentUserLocation.lng,
                            item.lat,
                            item.lng
                        );
                        if (distance > 1) {
                            show = false;
                        }
                    }

                    // Show/hide marker
                    if (show) {
                        if (!map.hasLayer(item.marker)) {
                            map.addLayer(item.marker);
                        }
                    } else {
                        if (map.hasLayer(item.marker)) {
                            map.removeLayer(item.marker);
                        }
                    }
                });
            });

            // Reset filter
            document.getElementById('reset-filter')?.addEventListener('click', function () {
                document.getElementById('filter-jenis').value = '';
                document.getElementById('filter-tipe').value = '';
                document.getElementById('filter-harga').value = '';

                markers.forEach(item => {
                    if (!map.hasLayer(item.marker)) {
                        map.addLayer(item.marker);
                    }
                });

                // Also clear nearby filter
                clearNearbyFilter();
            });

            // Add custom geolocation button to map
            const locateButton = L.control({ position: 'topleft' });
            locateButton.onAdd = function (map) {
                const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                div.innerHTML = '<a href="#" title="Lokasi Saya" style="padding: 6px; background: #1e293b; color: #e2e8f0; border-radius: 4px; display: block; font-size: 16px; line-height: 1;"><i class="fas fa-location-arrow"></i></a>';
                div.onclick = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    document.getElementById('find-nearby').click();
                };
                return div;
            };
            locateButton.addTo(map);

            // Add custom legend
            const legend = L.control({ position: 'bottomright' });
            legend.onAdd = function (map) {
                const div = L.DomUtil.create('div', 'legend');
                div.innerHTML = `
                        <h4 style="font-weight: 600; margin-bottom: 8px; color: #e2e8f0; font-size: 14px;">Keterangan:</h4>
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png" style="width: 16px; height: 24px; margin-right: 8px; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));">
                            <span style="font-size: 13px; color: #94a3b8;">Kos Putra</span>
                        </div>
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png" style="width: 16px; height: 24px; margin-right: 8px; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));">
                            <span style="font-size: 13px; color: #94a3b8;">Kos Putri</span>
                        </div>
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png" style="width: 16px; height: 24px; margin-right: 8px; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));">
                            <span style="font-size: 13px; color: #94a3b8;">Kos Campuran</span>
                        </div>
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png" style="width: 16px; height: 24px; margin-right: 8px; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));">
                            <span style="font-size: 13px; color: #94a3b8;">Lokasi Anda</span>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <div style="width: 16px; height: 16px; border-radius: 50%; background-color: #f59e0b; margin-right: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></div>
                            <span style="font-size: 13px; color: #94a3b8;">Radius 1 km</span>
                        </div>
                    `;
                return div;
            };
            legend.addTo(map);

            // Force map resize
            setTimeout(() => {
                map.invalidateSize();
                console.log('Map resized');
            }, 100);

            // Map loaded event
            map.whenReady(() => {
                console.log('Map fully loaded');
            });
        });
    </script>
@endpush