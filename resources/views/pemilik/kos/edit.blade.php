@extends('layouts.app')

@section('title', 'Edit Kos - Kosan App')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-home text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Edit Kos: {{ $kos->nama_kos }}</h1>
                        <p class="text-dark-muted">Perbarui informasi kos di form berikut</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('pemilik.kos.index') }}" 
               class="px-4 py-2.5 bg-dark-border text-white rounded-xl hover:bg-dark-border/80 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-900/20 border border-red-800/30 rounded-2xl p-4">
        <div class="flex items-center space-x-3 mb-2">
            <div class="w-8 h-8 bg-red-900/30 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <h3 class="text-white font-medium">Terdapat kesalahan:</h3>
        </div>
        <ul class="text-red-300 text-sm space-y-1 ml-11">
            @foreach($errors->all() as $error)
            <li class="flex items-center">
                <i class="fas fa-circle text-xs mr-2"></i>
                {{ $error }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <form method="POST" action="{{ route('pemilik.kos.update', $kos->id_kos) }}" enctype="multipart/form-data" id="editKosForm">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Informasi Dasar -->
                <div class="border-b border-dark-border pb-8">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-info-circle text-primary-400 mr-3"></i>
                        Informasi Dasar
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Kos -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-signature mr-2 text-primary-400"></i>
                                Nama Kos *
                            </label>
                            <input type="text" name="nama_kos" value="{{ old('nama_kos', $kos->nama_kos) }}" 
                                   class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                   required maxlength="255">
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>
                                Alamat Lengkap *
                            </label>
                            <textarea name="alamat" rows="3"
                                      class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                      id="alamatInput"
                                      required>{{ old('alamat', $kos->alamat) }}</textarea>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-location-dot mr-2 text-primary-400"></i>
                                Kecamatan *
                            </label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $kos->kecamatan) }}" 
                                   class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                   id="kecamatanInput"
                                   required maxlength="100">
                        </div>

                        <!-- Kota -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-city mr-2 text-primary-400"></i>
                                Kota *
                            </label>
                            <input type="text" name="kota" value="{{ old('kota', $kos->kota) }}" 
                                   class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                   id="kotaInput"
                                   required maxlength="100">
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-globe mr-2 text-primary-400"></i>
                                Provinsi *
                            </label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $kos->provinsi) }}" 
                                   class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                   id="provinsiInput"
                                   required maxlength="100">
                        </div>

                        <!-- Kode Pos -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-mailbox mr-2 text-primary-400"></i>
                                Kode Pos
                            </label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $kos->kode_pos) }}" 
                                   class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                   id="kodePosInput"
                                   maxlength="10">
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="border-b border-dark-border pb-8">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-clipboard-list text-green-400 mr-3"></i>
                        Informasi Tambahan
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Jenis Kos -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-users mr-2 text-green-400"></i>
                                Jenis Kos *
                            </label>
                            <select name="jenis_kos" 
                                    class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition appearance-none"
                                    required>
                                <option value="putra" {{ old('jenis_kos', $kos->jenis_kos) == 'putra' ? 'selected' : '' }}>Putra</option>
                                <option value="putri" {{ old('jenis_kos', $kos->jenis_kos) == 'putri' ? 'selected' : '' }}>Putri</option>
                                <option value="campuran" {{ old('jenis_kos', $kos->jenis_kos) == 'campuran' ? 'selected' : '' }}>Campuran</option>
                            </select>
                        </div>

                        <!-- Tipe Sewa -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-green-400"></i>
                                Tipe Sewa *
                            </label>
                            <select name="tipe_sewa" 
                                    class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition appearance-none"
                                    required>
                                <option value="harian" {{ old('tipe_sewa', $kos->tipe_sewa) == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="bulanan" {{ old('tipe_sewa', $kos->tipe_sewa) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahunan" {{ old('tipe_sewa', $kos->tipe_sewa) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>

                        <!-- Status Kos -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-toggle-on mr-2 text-green-400"></i>
                                Status Kos *
                            </label>
                            <select name="status_kos" 
                                    class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition appearance-none"
                                    required>
                                <option value="aktif" {{ old('status_kos', $kos->status_kos) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status_kos', $kos->status_kos) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="pending" {{ old('status_kos', $kos->status_kos) == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-white mb-2">
                            <i class="fas fa-align-left mr-2 text-green-400"></i>
                            Deskripsi Kos
                        </label>
                        <textarea name="deskripsi" rows="4"
                                  class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition">{{ old('deskripsi', $kos->deskripsi) }}</textarea>
                    </div>

                    <!-- Peraturan -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-white mb-2">
                            <i class="fas fa-gavel mr-2 text-green-400"></i>
                            Peraturan Kos
                        </label>
                        <textarea name="peraturan" rows="4"
                                  class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition">{{ old('peraturan', $kos->peraturan) }}</textarea>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="border-b border-dark-border pb-8">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-map-marked-alt text-yellow-400 mr-3"></i>
                        Pilih Lokasi di Peta
                    </h2>
                    
                    <!-- Koordinat Input -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-latitude mr-2 text-yellow-400"></i>
                                Latitude
                            </label>
                            <input type="text" name="latitude" id="latitude" 
                                   value="{{ old('latitude', $kos->latitude ?? '') }}"
                                   class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                   placeholder="-6.208763"
                                   readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-longitude mr-2 text-yellow-400"></i>
                                Longitude
                            </label>
                            <input type="text" name="longitude" id="longitude" 
                                   value="{{ old('longitude', $kos->longitude ?? '') }}"
                                   class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                   placeholder="106.845599"
                                   readonly>
                        </div>
                    </div>
                    
                    <!-- Search Box -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-white mb-2">
                            <i class="fas fa-search mr-2 text-yellow-400"></i>
                            Cari Alamat
                        </label>
                        <div class="flex space-x-2">
                            <input type="text" id="address-search" 
                                   class="flex-1 px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                   placeholder="Ketik alamat kos...">
                            <button type="button" id="search-btn" 
                                    class="px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg">
                                <i class="fas fa-search mr-2"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                    
                    <!-- Map Container -->
                    <div id="map" class="h-96 w-full rounded-xl border border-dark-border mb-6"></div>
                    
                    <!-- Instructions -->
                    <div class="bg-primary-900/20 border border-primary-800/30 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-primary-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info-circle text-primary-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-primary-200 font-medium mb-1">Petunjuk Penggunaan:</p>
                                <ul class="text-xs text-primary-300 space-y-1">
                                    <li class="flex items-center">
                                        <i class="fas fa-map-pin text-xs mr-2"></i>
                                        <span>Klik pada peta untuk menandai lokasi kos</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-search text-xs mr-2"></i>
                                        <span>Atau gunakan pencarian alamat di atas</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-sync-alt text-xs mr-2"></i>
                                        <span>Semua field akan otomatis terisi berdasarkan lokasi yang dipilih</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fasilitas Umum -->
                <div class="border-b border-dark-border pb-8">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-wrench text-purple-400 mr-3"></i>
                        Fasilitas Umum
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @php
                            $currentFacilities = $kos->fasilitas->pluck('id_fasilitas')->toArray();
                        @endphp
                        @foreach($fasilitas as $fasilitasItem)
                        <label class="flex items-center space-x-3 p-3 bg-dark-bg/50 border border-dark-border rounded-xl hover:border-primary-500/50 transition cursor-pointer">
                            <input type="checkbox" name="fasilitas[]" value="{{ $fasilitasItem->id_fasilitas }}" 
                                   class="w-5 h-5 rounded border-dark-border bg-dark-bg text-primary-500 focus:ring-primary-500 focus:ring-2"
                                   {{ in_array($fasilitasItem->id_fasilitas, old('fasilitas', $currentFacilities)) ? 'checked' : '' }}>
                            <span class="text-sm text-white">{{ $fasilitasItem->nama_fasilitas }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Foto Utama -->
                <div>
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-camera text-orange-400 mr-3"></i>
                        Foto Utama
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Photo -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-image mr-2 text-orange-400"></i>
                                Foto Saat Ini
                            </label>
                            @if($kos->foto_utama)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $kos->foto_utama) }}" 
                                     alt="{{ $kos->nama_kos }}" 
                                     class="w-full h-64 object-cover rounded-xl border border-dark-border">
                                <div class="absolute top-2 left-2 px-2 py-1 bg-black/50 text-white text-xs rounded">
                                    Foto Utama
                                </div>
                            </div>
                            @else
                            <div class="w-full h-64 bg-dark-bg border-2 border-dashed border-dark-border rounded-xl flex flex-col items-center justify-center">
                                <i class="fas fa-image text-4xl text-dark-muted mb-2"></i>
                                <p class="text-dark-muted">Belum ada foto</p>
                            </div>
                            @endif
                        </div>

                        <!-- New Photo -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-upload mr-2 text-orange-400"></i>
                                Ganti Foto Utama
                            </label>
                            <div class="border-2 border-dashed border-dark-border rounded-xl p-6 hover:border-primary-500 transition group">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                                        <i class="fas fa-cloud-upload-alt text-orange-400 text-2xl"></i>
                                    </div>
                                    <p class="text-white font-medium mb-2">Upload Foto Baru</p>
                                    <p class="text-dark-muted text-sm mb-4">Drag & drop atau klik untuk memilih file</p>
                                    <input type="file" name="foto_utama" 
                                           class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-500 file:text-white hover:file:bg-primary-600"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <p class="text-xs text-dark-muted mt-3">Kosongkan jika tidak ingin mengubah foto</p>
                                </div>
                            </div>
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-4 hidden">
                                <label class="block text-sm font-medium text-white mb-2">Preview:</label>
                                <img id="previewImage" class="w-full h-48 object-cover rounded-xl border border-dark-border">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-8 border-t border-dark-border flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('pemilik.kos.index') }}" 
                   class="px-6 py-3.5 bg-dark-border text-white rounded-xl hover:bg-dark-border/80 transition font-medium flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Batalkan
                </a>
                <button type="submit" 
                        class="px-6 py-3.5 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl font-medium flex items-center justify-center flex-1">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Leaflet Geocoder -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default coordinates (Jakarta)
        const defaultLat = -6.208763;
        const defaultLng = 106.845599;
        
        // Get current values or use defaults
        const currentLat = document.getElementById('latitude').value || defaultLat;
        const currentLng = document.getElementById('longitude').value || defaultLng;
        
        // Initialize map with dark theme
        const map = L.map('map', {
            zoomControl: true,
            preferCanvas: true
        }).setView([currentLat, currentLng], 13);
        
        // Dark theme tile layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);
        
        // Custom marker icon
        const customIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        
        // Marker variable
        let marker = null;
        
        // Set initial marker if coordinates exist
        if (currentLat && currentLng) {
            marker = L.marker([currentLat, currentLng], { 
                icon: customIcon,
                draggable: true 
            })
            .addTo(map)
            .bindPopup('Lokasi Kos Saat Ini')
            .openPopup();
            
            // Reverse geocode initial location
            reverseGeocode(currentLat, currentLng);
        }
        
        // Marker drag end event
        if (marker) {
            marker.on('dragend', function(e) {
                const { lat, lng } = e.target.getLatLng();
                updateCoordinates(lat, lng);
                reverseGeocode(lat, lng);
            });
        }
        
        // Click event to add/move marker
        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            
            // Remove existing marker
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new draggable marker
            marker = L.marker([lat, lng], { 
                icon: customIcon,
                draggable: true 
            })
            .addTo(map)
            .bindPopup('Lokasi Kos Dipilih')
            .openPopup();
            
            // Update coordinates
            updateCoordinates(lat, lng);
            
            // Reverse geocode the location
            reverseGeocode(lat, lng);
            
            // Marker drag end event
            marker.on('dragend', function(e) {
                const { lat: newLat, lng: newLng } = e.target.getLatLng();
                updateCoordinates(newLat, newLng);
                reverseGeocode(newLat, newLng);
            });
        });
        
        // Add geocoder control with custom styling
        L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: 'Cari alamat...',
            errorMessage: 'Alamat tidak ditemukan',
            geocoder: L.Control.Geocoder.nominatim({
                geocodingQueryParams: {
                    'countrycodes': 'id',
                    'accept-language': 'id',
                    'addressdetails': 1
                }
            })
        })
        .on('markgeocode', function(e) {
            const { center, name, properties } = e.geocode;
            const { lat, lng } = center;
            
            // Remove existing marker
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new draggable marker
            marker = L.marker([lat, lng], { 
                icon: customIcon,
                draggable: true 
            })
            .addTo(map)
            .bindPopup(name)
            .openPopup();
            
            // Update coordinates
            updateCoordinates(lat, lng);
            
            // Update address fields from geocoder result
            updateAddressFields(properties.address);
            
            // Center map
            map.setView([lat, lng], 16);
            
            // Marker drag end event
            marker.on('dragend', function(e) {
                const { lat: newLat, lng: newLng } = e.target.getLatLng();
                updateCoordinates(newLat, newLng);
                reverseGeocode(newLat, newLng);
            });
        })
        .addTo(map);
        
        // Manual search function
        document.getElementById('search-btn').addEventListener('click', performSearch);
        
        // Enter key for search
        document.getElementById('address-search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        function performSearch() {
            const query = document.getElementById('address-search').value.trim();
            if (!query) {
                showNotification('Masukkan alamat yang ingin dicari', 'warning');
                return;
            }
            
            showNotification('Mencari alamat...', 'info');
            
            // Use Nominatim API with address details
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&addressdetails=1&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const result = data[0];
                        const lat = parseFloat(result.lat);
                        const lng = parseFloat(result.lon);
                        
                        // Remove existing marker
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        
                        // Add new draggable marker
                        marker = L.marker([lat, lng], { 
                            icon: customIcon,
                            draggable: true 
                        })
                        .addTo(map)
                        .bindPopup(result.display_name)
                        .openPopup();
                        
                        // Update coordinates
                        updateCoordinates(lat, lng);
                        
                        // Update address fields
                        if (result.address) {
                            updateAddressFields(result.address);
                        }
                        
                        // Center map
                        map.setView([lat, lng], 16);
                        
                        // Update search input
                        document.getElementById('address-search').value = result.display_name;
                        
                        showNotification('Alamat ditemukan', 'success');
                        
                        // Marker drag end event
                        marker.on('dragend', function(e) {
                            const { lat: newLat, lng: newLng } = e.target.getLatLng();
                            updateCoordinates(newLat, newLng);
                            reverseGeocode(newLat, newLng);
                        });
                    } else {
                        showNotification('Alamat tidak ditemukan. Coba dengan kata kunci yang lebih spesifik.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error searching address:', error);
                    showNotification('Terjadi error saat mencari alamat.', 'error');
                });
        }
        
        // Reverse geocode function
        function reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&zoom=18`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        updateAddressFields(data.address);
                    }
                })
                .catch(error => {
                    console.error('Reverse geocode error:', error);
                });
        }
        
        // Update address fields function
        function updateAddressFields(address) {
            // Update alamat
            const alamat = [
                address.road || address.neighbourhood || address.suburb || '',
                address.village || address.town || address.city || address.county || '',
                address.state || address.province || '',
                'Indonesia'
            ].filter(Boolean).join(', ');
            
            document.getElementById('alamatInput').value = alamat;
            
            // Update kecamatan
            const kecamatan = address.suburb || address.village || address.town || address.county || '';
            document.getElementById('kecamatanInput').value = kecamatan;
            
            // Update kota
            const kota = address.city || address.town || address.county || address.state || '';
            document.getElementById('kotaInput').value = kota;
            
            // Update provinsi
            const provinsi = address.state || address.province || '';
            document.getElementById('provinsiInput').value = provinsi;
            
            // Update kode pos jika tersedia
            if (address.postcode) {
                document.getElementById('kodePosInput').value = address.postcode;
            }
        }
        
        // Update coordinate inputs
        function updateCoordinates(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        }
        
        // Show notification
        function showNotification(message, type = 'info') {
            const colors = {
                info: 'bg-blue-900/20 text-blue-300 border-blue-800/30',
                success: 'bg-green-900/20 text-green-300 border-green-800/30',
                warning: 'bg-yellow-900/20 text-yellow-300 border-yellow-800/30',
                error: 'bg-red-900/20 text-red-300 border-red-800/30'
            };
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-3 rounded-xl border ${colors[type]} z-[1000] max-w-md animate-slideIn`;
            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'error' ? 'times-circle' : 'info-circle'}"></i>
                    <span class="text-sm">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Image preview function
        window.previewImage = function(input) {
            const preview = document.getElementById('previewImage');
            const previewContainer = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
            }
        }
        
        // Add custom CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .leaflet-control-geocoder-form input {
                background: #1e293b !important;
                color: #e2e8f0 !important;
                border: 1px solid #334155 !important;
                border-radius: 8px !important;
            }
            
            .leaflet-control-geocoder-form input:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3) !important;
            }
            
            .leaflet-control-geocoder-alternatives {
                background: #1e293b !important;
                border: 1px solid #334155 !important;
                border-radius: 8px !important;
                margin-top: 4px !important;
            }
            
            .leaflet-control-geocoder-alternatives a {
                color: #e2e8f0 !important;
                border-bottom: 1px solid #334155 !important;
            }
            
            .leaflet-control-geocoder-alternatives a:hover {
                background: #334155 !important;
                color: #3b82f6 !important;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endsection