@extends('layouts.app')

@section('title', 'Tambah Kos - AyoKos')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
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
                                <a href="{{ route('pemilik.kos.index') }}" class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                                    <i class="fas fa-file-contract mr-2"></i>
                                    Kelola Kos
                                </a>
                            </div>
                        </li>
                        <li class="inline-flex items-center">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                                <a href="{{ route('pemilik.kos.create') }}" class="inline-flex items-center text-sm font-medium text-white">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Kos
                                </a>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Tambah Kos Baru</h1>
                        <p class="text-dark-muted">Lengkapi formulir untuk menambahkan properti kos baru ke sistem</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-primary-500/20 to-indigo-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus text-primary-400 text-xl"></i>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-900/30 border border-red-800/50 text-red-300 rounded-xl p-4 mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong class="font-semibold">Terjadi kesalahan:</strong>
                    </div>
                    <ul class="text-sm list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <form method="POST" action="{{ route('pemilik.kos.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-8">
                        <!-- Informasi Dasar -->
                        <div class="border-b border-dark-border pb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-primary-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-info-circle text-primary-400"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-white">🏠 Informasi Dasar</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama Kos -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Nama Kos <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-home absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="nama_kos" value="{{ old('nama_kos') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                            placeholder="Contoh: Kos Bahagia Sentosa" required maxlength="255">
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Alamat Lengkap <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-map-marker-alt absolute left-3 top-3 text-dark-muted"></i>
                                        <textarea name="alamat" rows="3"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition resize-none"
                                            placeholder="Jl. Merdeka No. 123, Kelurahan..."
                                            required>{{ old('alamat') }}</textarea>
                                    </div>
                                </div>

                                <!-- Kecamatan -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Kecamatan <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-map-pin absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                            required maxlength="100">
                                    </div>
                                </div>

                                <!-- Kota -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Kota <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-city absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="kota" value="{{ old('kota') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                            required maxlength="100">
                                    </div>
                                </div>

                                <!-- Provinsi -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Provinsi <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-globe-asia absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="provinsi" value="{{ old('provinsi') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                            required maxlength="100">
                                    </div>
                                </div>

                                <!-- Kode Pos -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Kode Pos
                                    </label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-mail-bulk absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="kode_pos" value="{{ old('kode_pos') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                            maxlength="10">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="border-b border-dark-border pb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-alt text-blue-400"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-white">📋 Informasi Tambahan</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Jenis Kos -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Jenis Kos <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-users absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <select name="jenis_kos"
                                            class="w-full pl-10 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition"
                                            required>
                                            <option value="">Pilih Jenis Kos</option>
                                            <option value="putra" {{ old('jenis_kos') == 'putra' ? 'selected' : '' }}>Putra
                                            </option>
                                            <option value="putri" {{ old('jenis_kos') == 'putri' ? 'selected' : '' }}>Putri
                                            </option>
                                            <option value="campuran" {{ old('jenis_kos') == 'campuran' ? 'selected' : '' }}>
                                                Campuran</option>
                                        </select>
                                        <i
                                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                                    </div>
                                </div>

                                <!-- Tipe Sewa -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Tipe Sewa <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <select name="tipe_sewa"
                                            class="w-full pl-10 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition"
                                            required>
                                            <option value="">Pilih Tipe Sewa</option>
                                            <option value="harian" {{ old('tipe_sewa') == 'harian' ? 'selected' : '' }}>Harian
                                            </option>
                                            <option value="mingguan" {{ old('tipe_sewa') == 'mingguan' ? 'selected' : '' }}>
                                                Mingguan</option>
                                            <option value="bulanan" {{ old('tipe_sewa') == 'bulanan' ? 'selected' : '' }}>
                                                Bulanan</option>
                                            <option value="tahunan" {{ old('tipe_sewa') == 'tahunan' ? 'selected' : '' }}>
                                                Tahunan</option>
                                        </select>
                                        <i
                                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-white mb-2">
                                    Deskripsi Kos
                                </label>
                                <div class="relative">
                                    <i class="fas fa-align-left absolute left-3 top-3 text-dark-muted"></i>
                                    <textarea name="deskripsi" rows="4"
                                        class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition resize-none"
                                        placeholder="Deskripsikan keunggulan dan fasilitas kos...">{{ old('deskripsi') }}</textarea>
                                </div>
                            </div>

                            <!-- Peraturan -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-white mb-2">
                                    Peraturan Kos
                                </label>
                                <div class="relative">
                                    <i class="fas fa-clipboard-list absolute left-3 top-3 text-dark-muted"></i>
                                    <textarea name="peraturan" rows="4"
                                        class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition resize-none"
                                        placeholder="Tuliskan peraturan yang berlaku di kos...">{{ old('peraturan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Map Section -->
                        <div class="border-b border-dark-border pb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-map-marked-alt text-green-400"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-white">🗺️ Pilih Lokasi di Peta</h2>
                            </div>

                            <!-- Koordinat Input -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Latitude</label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-location-arrow absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="latitude" id="latitude"
                                            value="{{ old('latitude', $kos->latitude ?? '') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition"
                                            placeholder="-6.208763">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Longitude</label>
                                    <div class="relative">
                                        <i
                                            class="fas fa-location-arrow absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="longitude" id="longitude"
                                            value="{{ old('longitude', $kos->longitude ?? '') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition"
                                            placeholder="106.845599">
                                    </div>
                                </div>
                            </div>

                            <!-- Search Box -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-white mb-2">Cari Alamat</label>
                                <div class="flex space-x-2">
                                    <div class="relative flex-1">
                                        <i
                                            class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" id="address-search"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition"
                                            placeholder="Ketik alamat kos...">
                                    </div>
                                    <button type="button" id="search-btn"
                                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <i class="fas fa-search mr-2"></i>
                                        Cari
                                    </button>
                                </div>
                            </div>

                            <!-- Map Container -->
                            <div id="map" class="h-96 w-full rounded-xl border-2 border-dark-border mb-6 bg-dark-bg"></div>

                            <!-- Instructions -->
                            <div class="bg-green-900/20 border border-green-800/30 rounded-xl p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-green-400 mt-1 mr-3"></i>
                                    <div>
                                        <p class="text-sm text-green-300 font-medium mb-1">Petunjuk Penggunaan:</p>
                                        <ol class="text-sm text-green-200/80 list-decimal list-inside space-y-1">
                                            <li>Klik pada peta untuk menandai lokasi kos</li>
                                            <li>Atau gunakan pencarian alamat di atas</li>
                                            <li>Koordinat akan otomatis terisi</li>
                                            <li>Alamat akan otomatis terisi saat memilih lokasi</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fasilitas Umum -->
                        <div class="border-b border-dark-border pb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-concierge-bell text-purple-400"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-white">🏗️ Fasilitas Umum</h2>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($fasilitas as $fasilitasItem)
                                    <label
                                        class="flex items-center space-x-3 p-3 bg-dark-bg/50 border border-dark-border rounded-xl hover:border-primary-500/50 transition cursor-pointer">
                                        <div class="relative">
                                            <input type="checkbox" name="fasilitas[]" value="{{ $fasilitasItem->id_fasilitas }}"
                                                class="rounded border-dark-border bg-dark-bg text-primary-600 focus:ring-primary-500/50 focus:ring-offset-dark-bg transition"
                                                {{ in_array($fasilitasItem->id_fasilitas, old('fasilitas', [])) ? 'checked' : '' }}>
                                        </div>
                                        <div class="flex-1">
                                            <span
                                                class="text-sm font-medium text-white">{{ $fasilitasItem->nama_fasilitas }}</span>
                                            <span class="text-xs text-dark-muted block">{{ $fasilitasItem->kategori }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Foto Utama -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-yellow-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-camera text-yellow-400"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-white">📷 Foto Utama</h2>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Foto Utama Kos</label>
                                <div class="relative group">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="foto-utama"
                                            class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-dark-border rounded-xl cursor-pointer bg-dark-bg/50 hover:bg-dark-bg hover:border-primary-500/50 transition">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i
                                                    class="fas fa-cloud-upload-alt text-3xl text-dark-muted mb-2 group-hover:text-primary-400 transition"></i>
                                                <p class="text-sm text-dark-muted mb-1">
                                                    <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                                </p>
                                                <p class="text-xs text-dark-muted/70">PNG, JPG, JPEG (Max. 2MB)</p>
                                            </div>
                                            <input id="foto-utama" name="foto_utama" type="file" class="hidden"
                                                accept="image/*">
                                        </label>
                                    </div>
                                </div>
                                <p class="text-sm text-dark-muted mt-3 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Gambar utama yang akan ditampilkan di halaman pencarian
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('pemilik.kos.index') }}"
                            class="flex-1 sm:flex-none px-6 py-3 bg-dark-border border border-dark-border text-white rounded-xl hover:bg-dark-border/80 transition flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="submit"
                            class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-primary-600 to-indigo-600 text-white rounded-xl hover:from-primary-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Kos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Geocoder (Nominatim - OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <style>
        /* Map custom styling */
        #map {
            z-index: 1;
        }

        .leaflet-control-geocoder-form {
            background: #1e293b;
            border: 1px solid #334155;
        }

        .leaflet-control-geocoder-form input {
            background: #0f172a;
            color: #e2e8f0;
            border: 1px solid #334155;
        }

        .leaflet-control-geocoder-alternatives {
            background: #1e293b;
            border: 1px solid #334155;
        }

        .leaflet-control-geocoder-alternatives a {
            color: #e2e8f0;
            background: #1e293b;
        }

        .leaflet-control-geocoder-alternatives a:hover {
            background: #334155;
        }

        /* File upload preview */
        #foto-utama-preview {
            max-height: 200px;
            object-fit: cover;
        }
    </style>

    <script>
        // File upload preview
        document.getElementById('foto-utama').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Remove existing preview if any
                    const existingPreview = document.getElementById('foto-utama-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    // Create preview image
                    const preview = document.createElement('img');
                    preview.id = 'foto-utama-preview';
                    preview.src = e.target.result;
                    preview.className = 'w-full h-48 object-cover rounded-xl mt-2 border border-dark-border';

                    // Insert after the file input container
                    const container = document.querySelector('input[name="foto_utama"]').closest('div');
                    container.appendChild(preview);

                    // Update label
                    const label = container.querySelector('label');
                    label.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Default coordinates (Jakarta)
            const defaultLat = -6.208763;
            const defaultLng = 106.845599;

            // Get current values or use defaults
            const currentLat = document.getElementById('latitude').value || defaultLat;
            const currentLng = document.getElementById('longitude').value || defaultLng;

            // Initialize map with dark theme
            const map = L.map('map', {
                zoomControl: true,
                attributionControl: false
            }).setView([currentLat, currentLng], 13);

            // Add dark tile layer
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            // Add attribution
            L.control.attribution({
                position: 'bottomright'
            }).addTo(map);

            // Marker variable
            let marker = null;

            // Custom icon
            const customIcon = L.divIcon({
                html: `
                    <div class="relative">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-home text-white text-xs"></i>
                        </div>
                        <div class="w-2 h-2 bg-primary-500 rounded-full absolute -bottom-1 left-1/2 transform -translate-x-1/2"></div>
                    </div>
                `,
                className: 'custom-marker',
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });

            // Set initial marker if coordinates exist
            if (currentLat && currentLng) {
                marker = L.marker([currentLat, currentLng], { icon: customIcon })
                    .addTo(map)
                    .bindPopup('<div class="text-sm font-semibold text-dark-bg">📍 Lokasi Kos Saat Ini</div>')
                    .openPopup();
            }

            // DEBOUNCE untuk mencegah API call berlebihan
            let reverseGeocodeTimeout = null;
            let lastProcessedCoords = null;

            // Fungsi Reverse Geocoding dengan debounce
            function reverseGeocode(lat, lng, force = false) {
                // Cek jika koordinat sama dengan sebelumnya (kecuali force)
                const currentCoords = `${lat.toFixed(6)},${lng.toFixed(6)}`;
                if (!force && lastProcessedCoords === currentCoords) {
                    return Promise.resolve(null);
                }

                // Clear timeout sebelumnya
                if (reverseGeocodeTimeout) {
                    clearTimeout(reverseGeocodeTimeout);
                }

                return new Promise((resolve) => {
                    reverseGeocodeTimeout = setTimeout(() => {
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.address) {
                                    const address = data.address;
                                    lastProcessedCoords = currentCoords;

                                    console.log('Address data:', data);

                                    const result = {
                                        // Prioritaskan data berdasarkan level administrasi Indonesia
                                        kecamatan: address.suburb || address.village || address.town || address.city_district || '',
                                        kota: address.city || address.town || address.municipality || address.county || '',
                                        provinsi: address.state || address.region || '',
                                        kode_pos: address.postcode || '',
                                        alamat_lengkap: data.display_name || ''
                                    };
                                    resolve(result);
                                } else {
                                    resolve(null);
                                }
                            })
                            .catch(error => {
                                console.error('Reverse geocode error:', error);
                                resolve(null);
                            });
                    }, 500); // Debounce 500ms
                });
            }

            // Fungsi untuk mengisi form otomatis (dengan overwrite hanya jika kosong atau ada data baru)
            function fillAddressForm(addressData) {
                if (!addressData) return;

                // Element form
                const kecamatanInput = document.querySelector('input[name="kecamatan"]');
                const kotaInput = document.querySelector('input[name="kota"]');
                const provinsiInput = document.querySelector('input[name="provinsi"]');
                const kodePosInput = document.querySelector('input[name="kode_pos"]');
                const alamatTextarea = document.querySelector('textarea[name="alamat"]');

                // Hanya isi jika field kosong ATAU user belum pernah edit manual
                // Kita tandai field yang sudah diedit manual
                if (!kecamatanInput.dataset.manualEdit && addressData.kecamatan) {
                    kecamatanInput.value = addressData.kecamatan;
                }

                if (!kotaInput.dataset.manualEdit && addressData.kota) {
                    kotaInput.value = addressData.kota;
                }

                if (!provinsiInput.dataset.manualEdit && addressData.provinsi) {
                    provinsiInput.value = addressData.provinsi;
                }

                if (!kodePosInput.dataset.manualEdit && addressData.kode_pos) {
                    kodePosInput.value = addressData.kode_pos;
                }

                // Untuk alamat, isi jika belum pernah diedit manual
                if (!alamatTextarea.dataset.manualEdit && addressData.alamat_lengkap) {
                    alamatTextarea.value = addressData.alamat_lengkap;
                }
            }

            // Track field yang sudah diedit manual
            const formInputs = document.querySelectorAll('input[name="kecamatan"], input[name="kota"], input[name="provinsi"], input[name="kode_pos"], textarea[name="alamat"]');
            formInputs.forEach(input => {
                input.addEventListener('input', function () {
                    this.dataset.manualEdit = 'true';
                });

                // Reset manual edit jika user clear field
                input.addEventListener('blur', function () {
                    if (!this.value.trim()) {
                        delete this.dataset.manualEdit;
                    }
                });
            });

            // Click event to add marker dengan reverse geocoding OTOMATIS
            map.on('click', async function (e) {
                const { lat, lng } = e.latlng;

                // Remove existing marker
                if (marker) {
                    map.removeLayer(marker);
                }

                // Add new marker dengan loading state
                marker = L.marker([lat, lng], { icon: customIcon })
                    .addTo(map)
                    .bindPopup('<div class="text-sm"><div class="flex items-center"><i class="fas fa-spinner fa-spin mr-2"></i>Mengambil alamat...</div></div>')
                    .openPopup();

                // Update coordinate inputs
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);

                // Lakukan reverse geocoding OTOMATIS
                try {
                    const addressData = await reverseGeocode(lat, lng, true);

                    if (addressData) {
                        // Update popup
                        marker.setPopupContent(`
                            <div class="text-sm" style="max-width: 250px;">
                                <div class="font-semibold text-dark-bg mb-1">📍 Lokasi Dipilih</div>
                                <div class="text-gray-600 text-xs">${addressData.alamat_lengkap || 'Alamat ditemukan'}</div>
                            </div>
                        `);

                        // Isi form otomatis
                        fillAddressForm(addressData);
                    } else {
                        marker.setPopupContent('<div class="text-sm"><div class="font-semibold text-dark-bg">📍 Lokasi Dipilih</div><div class="text-gray-600 text-xs">Tidak dapat mengambil detail alamat</div></div>');
                    }
                } catch (error) {
                    marker.setPopupContent('<div class="text-sm"><div class="font-semibold text-dark-bg">📍 Lokasi Dipilih</div><div class="text-gray-600 text-xs">Error mengambil alamat</div></div>');
                    console.error('Error:', error);
                }
            });

            // Add geocoder control dengan auto-fill
            const geocoder = L.Control.geocoder({
                defaultMarkGeocode: false,
                geocoder: L.Control.Geocoder.nominatim({
                    geocodingQueryParams: {
                        'countrycodes': 'id',
                        'accept-language': 'id',
                        'addressdetails': 1
                    }
                }),
                position: 'topleft',
                placeholder: 'Cari alamat...',
                errorMessage: 'Alamat tidak ditemukan.',
                showResultIcons: true
            })
                .on('markgeocode', function (e) {
                    const { center, name, properties } = e.geocode;
                    const { lat, lng } = center;

                    // Remove existing marker
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    // Add new marker
                    marker = L.marker([lat, lng], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(name)
                        .openPopup();

                    // Update coordinate inputs
                    document.getElementById('latitude').value = lat.toFixed(6);
                    document.getElementById('longitude').value = lng.toFixed(6);

                    // Center map
                    map.setView([lat, lng], 16);

                    // Isi form dengan data address
                    if (properties && properties.address) {
                        fillAddressForm({
                            kecamatan: properties.address.suburb || properties.address.village || '',
                            kota: properties.address.city || properties.address.town || '',
                            provinsi: properties.address.state || '',
                            kode_pos: properties.address.postcode || '',
                            alamat_lengkap: name
                        });
                    } else {
                        // Fallback ke reverse geocoding
                        reverseGeocode(lat, lng, true).then(fillAddressForm);
                    }
                })
                .addTo(map);

            // Manual search function dengan auto-fill
            document.getElementById('search-btn').addEventListener('click', async function () {
                const query = document.getElementById('address-search').value;
                if (!query) return;

                try {
                    // Use Nominatim API
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1&addressdetails=1`
                    );
                    const data = await response.json();

                    if (data && data.length > 0) {
                        const result = data[0];
                        const lat = parseFloat(result.lat);
                        const lng = parseFloat(result.lon);

                        // Remove existing marker
                        if (marker) {
                            map.removeLayer(marker);
                        }

                        // Add new marker
                        marker = L.marker([lat, lng], { icon: customIcon })
                            .addTo(map)
                            .bindPopup(result.display_name)
                            .openPopup();

                        // Update coordinate inputs
                        document.getElementById('latitude').value = lat.toFixed(6);
                        document.getElementById('longitude').value = lng.toFixed(6);

                        // Center map
                        map.setView([lat, lng], 16);

                        // Isi form dengan data address
                        if (result.address) {
                            fillAddressForm({
                                kecamatan: result.address.suburb || result.address.village || result.address.town || '',
                                kota: result.address.city || result.address.town || result.address.municipality || '',
                                provinsi: result.address.state || result.address.region || '',
                                kode_pos: result.address.postcode || '',
                                alamat_lengkap: result.display_name
                            });
                        }
                    } else {
                        // Show error notification
                        const searchInput = document.getElementById('address-search');
                        searchInput.classList.add('border-red-500');
                        setTimeout(() => searchInput.classList.remove('border-red-500'), 2000);
                    }
                } catch (error) {
                    console.error('Error searching address:', error);
                }
            });

            // TWO-WAY BINDING: Saat koordinat diubah manual, update map
            let coordinateTimeout = null;

            function updateMapFromCoordinates() {
                const lat = parseFloat(document.getElementById('latitude').value);
                const lng = parseFloat(document.getElementById('longitude').value);

                if (!isNaN(lat) && !isNaN(lng)) {
                    // Clear timeout sebelumnya
                    if (coordinateTimeout) {
                        clearTimeout(coordinateTimeout);
                    }

                    // Debounce untuk mencegah API spam
                    coordinateTimeout = setTimeout(() => {
                        // Remove existing marker
                        if (marker) {
                            map.removeLayer(marker);
                        }

                        // Add new marker
                        marker = L.marker([lat, lng], { icon: customIcon })
                            .addTo(map)
                            .bindPopup('Lokasi dari input manual')
                            .openPopup();

                        // Center map
                        map.setView([lat, lng], 16);

                        // OTOMATIS lakukan reverse geocoding
                        reverseGeocode(lat, lng, true).then(fillAddressForm);
                    }, 1000);
                }
            }

            // Listen to coordinate changes dengan debounce
            document.getElementById('latitude').addEventListener('input', updateMapFromCoordinates);
            document.getElementById('longitude').addEventListener('input', updateMapFromCoordinates);

            // TWO-WAY BINDING: Saat alamat diubah manual, coba geocode
            let addressSearchTimeout = null;

            document.querySelector('textarea[name="alamat"]').addEventListener('input', function () {
                // Mark as manually edited
                this.dataset.manualEdit = 'true';

                // Clear timeout sebelumnya
                if (addressSearchTimeout) {
                    clearTimeout(addressSearchTimeout);
                }

                // Debounce untuk mencegah API spam
                addressSearchTimeout = setTimeout(() => {
                    const address = this.value.trim();
                    if (address.length > 10) { // Hanya search jika alamat cukup panjang
                        searchAndUpdateFromAddress(address);
                    }
                }, 1500);
            });

            async function searchAndUpdateFromAddress(address) {
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&countrycodes=id&limit=1`
                    );
                    const data = await response.json();

                    if (data && data.length > 0) {
                        const result = data[0];
                        const lat = parseFloat(result.lat);
                        const lng = parseFloat(result.lon);

                        // Update coordinate inputs
                        document.getElementById('latitude').value = lat.toFixed(6);
                        document.getElementById('longitude').value = lng.toFixed(6);

                        // Update map
                        if (marker) {
                            map.removeLayer(marker);
                        }

                        marker = L.marker([lat, lng], { icon: customIcon })
                            .addTo(map)
                            .bindPopup(result.display_name)
                            .openPopup();

                        map.setView([lat, lng], 16);
                    }
                } catch (error) {
                    console.error('Address geocode error:', error);
                }
            }

            // Enter key for search
            document.getElementById('address-search').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('search-btn').click();
                }
            });

            // Initial reverse geocode jika ada koordinat awal
            if (currentLat && currentLng && currentLat !== defaultLat && currentLng !== defaultLng) {
                reverseGeocode(parseFloat(currentLat), parseFloat(currentLng), true)
                    .then(fillAddressForm)
                    .catch(console.error);
            }
        });
    </script>
@endsection