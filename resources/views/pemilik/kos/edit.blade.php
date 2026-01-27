@extends('layouts.app')

@section('title', 'Edit Kos - AyoKos')

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
                                <a href="{{ route('pemilik.kos.edit', $kos->id_kos) }}" class="inline-flex items-center text-sm font-medium text-white">
                                    <i class="fas fa-pencil mr-2"></i>
                                    Edit Kos
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
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Edit Kos: {{ $kos->nama_kos }}</h1>
                        <p class="text-dark-muted">Perbarui informasi properti kos yang sudah ada</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500/20 to-indigo-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-primary-400 text-xl"></i>
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
                <form method="POST" action="{{ route('pemilik.kos.update', $kos->id_kos) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                        <i class="fas fa-home absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="nama_kos" value="{{ old('nama_kos', $kos->nama_kos) }}"
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
                                            required>{{ old('alamat', $kos->alamat) }}</textarea>
                                    </div>
                                </div>

                                <!-- Kecamatan -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Kecamatan <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-map-pin absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $kos->kecamatan) }}"
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
                                        <i class="fas fa-city absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="kota" value="{{ old('kota', $kos->kota) }}"
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
                                        <i class="fas fa-globe-asia absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="provinsi" value="{{ old('provinsi', $kos->provinsi) }}"
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
                                        <i class="fas fa-mail-bulk absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $kos->kode_pos) }}"
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
                                        <i class="fas fa-users absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <select name="jenis_kos"
                                            class="w-full pl-10 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition"
                                            required>
                                            <option value="">Pilih Jenis Kos</option>
                                            <option value="putra" {{ old('jenis_kos', $kos->jenis_kos) == 'putra' ? 'selected' : '' }}>Putra</option>
                                            <option value="putri" {{ old('jenis_kos', $kos->jenis_kos) == 'putri' ? 'selected' : '' }}>Putri</option>
                                            <option value="campuran" {{ old('jenis_kos', $kos->jenis_kos) == 'campuran' ? 'selected' : '' }}>Campuran</option>
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                                    </div>
                                </div>

                                <!-- Tipe Sewa -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Tipe Sewa <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <select name="tipe_sewa"
                                            class="w-full pl-10 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition"
                                            required>
                                            <option value="">Pilih Tipe Sewa</option>
                                            <option value="harian" {{ old('tipe_sewa', $kos->tipe_sewa) == 'harian' ? 'selected' : '' }}>Harian</option>
                                            <option value="mingguan" {{ old('tipe_sewa', $kos->tipe_sewa) == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                            <option value="bulanan" {{ old('tipe_sewa', $kos->tipe_sewa) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                            <option value="tahunan" {{ old('tipe_sewa', $kos->tipe_sewa) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
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
                                        placeholder="Deskripsikan keunggulan dan fasilitas kos...">{{ old('deskripsi', $kos->deskripsi) }}</textarea>
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
                                        placeholder="Tuliskan peraturan yang berlaku di kos...">{{ old('peraturan', $kos->peraturan) }}</textarea>
                                </div>
                            </div>

                            <!-- Status Kos -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-white mb-2">
                                    Status Kos <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-toggle-on absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                    <select name="status_kos"
                                        class="w-full pl-10 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition"
                                        required>
                                        <option value="aktif" {{ old('status_kos', $kos->status_kos) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status_kos', $kos->status_kos) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        <option value="pending" {{ old('status_kos', $kos->status_kos) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
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
                                        <i class="fas fa-location-arrow absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="latitude" id="latitude"
                                            value="{{ old('latitude', $kos->latitude ?? '') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition"
                                            placeholder="-6.208763" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Longitude</label>
                                    <div class="relative">
                                        <i class="fas fa-location-arrow absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                        <input type="text" name="longitude" id="longitude"
                                            value="{{ old('longitude', $kos->longitude ?? '') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition"
                                            placeholder="106.845599" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Pencarian Alamat -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-white mb-2">Cari Alamat</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <div class="relative">
                                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                            <input type="text" id="address-search" 
                                                class="w-full pl-10 pr-3 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition"
                                                placeholder="Masukkan alamat lengkap (contoh: Jl. Sudirman No. 123, Jakarta)">
                                        </div>
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" id="search-btn" 
                                            class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold flex items-center justify-center">
                                            <i class="fas fa-search mr-2"></i>
                                            Cari Alamat
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Lokasi -->
                            <div class="mb-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <button type="button" id="current-location-btn" 
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold flex items-center justify-center">
                                        <i class="fas fa-location-arrow mr-2"></i>
                                        Gunakan Lokasi Saat Ini
                                    </button>
                                    <button type="button" id="detect-nearby-btn" 
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        Cari Tempat Terdekat
                                    </button>
                                </div>
                                <p class="text-sm text-dark-muted mt-2 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Izinkan akses lokasi di browser untuk fitur "Sekitar Saya"
                                </p>
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
                                            <li><strong>Cari alamat</strong> atau <strong>klik tombol lokasi</strong> untuk mendapatkan posisi</li>
                                            <li><strong>Klik pada peta</strong> untuk menandai lokasi kos secara manual</li>
                                            <li><strong>Koordinat dan alamat</strong> akan otomatis terisi</li>
                                            <li><strong>Geser marker</strong> untuk menyesuaikan posisi yang lebih akurat</li>
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
                                @php
                                    $currentFacilities = $kos->fasilitas->pluck('id_fasilitas')->toArray();
                                @endphp
                                @foreach($fasilitas as $fasilitasItem)
                                    <label class="flex items-center space-x-3 p-3 bg-dark-bg/50 border border-dark-border rounded-xl hover:border-primary-500/50 transition cursor-pointer">
                                        <div class="relative">
                                            <input type="checkbox" name="fasilitas[]" value="{{ $fasilitasItem->id_fasilitas }}"
                                                class="rounded border-dark-border bg-dark-bg text-primary-600 focus:ring-primary-500/50 focus:ring-offset-dark-bg transition"
                                                {{ in_array($fasilitasItem->id_fasilitas, old('fasilitas', $currentFacilities)) ? 'checked' : '' }}>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-white">{{ $fasilitasItem->nama_fasilitas }}</span>
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Current Photo -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Foto Saat Ini</label>
                                    @if($kos->foto_utama)
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $kos->foto_utama) }}" alt="{{ $kos->nama_kos }}"
                                                class="w-full h-48 object-cover rounded-xl border border-dark-border">
                                            <div class="absolute top-2 left-2 px-2 py-1 bg-black/50 text-white text-xs rounded">
                                                Foto Utama
                                            </div>
                                        </div>
                                        <p class="text-sm text-dark-muted mt-2 flex items-center">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Kosongkan jika tidak ingin mengubah foto
                                        </p>
                                    @else
                                        <div class="w-full h-48 bg-dark-bg border-2 border-dashed border-dark-border rounded-xl flex flex-col items-center justify-center">
                                            <i class="fas fa-image text-4xl text-dark-muted mb-2"></i>
                                            <p class="text-dark-muted">Belum ada foto</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- New Photo -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Ganti Foto Utama</label>
                                    <div class="relative group">
                                        <div class="flex items-center justify-center w-full">
                                            <label for="foto-utama"
                                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-dark-border rounded-xl cursor-pointer bg-dark-bg/50 hover:bg-dark-bg hover:border-primary-500/50 transition">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="fas fa-cloud-upload-alt text-3xl text-dark-muted mb-2 group-hover:text-primary-400 transition"></i>
                                                    <p class="text-sm text-dark-muted mb-1">
                                                        <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                                    </p>
                                                    <p class="text-xs text-dark-muted/70">PNG, JPG, JPEG (Max. 2MB)</p>
                                                </div>
                                                <input id="foto-utama" name="foto_utama" type="file" class="hidden" accept="image/*">
                                            </label>
                                        </div>
                                    </div>
                                    <div id="new-photo-preview" class="mt-2"></div>
                                </div>
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
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        /* Map Container */
        #map {
            z-index: 1;
        }
        
        /* Marker Custom */
        .custom-marker {
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.5));
        }
        
        /* Notification Animation */
        .notification {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Modal Animation */
        #nearby-places-modal {
            animation: fadeIn 0.3s ease-out;
        }
        
        #nearby-places-modal > div {
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Place Item Hover */
        .place-item {
            transition: all 0.2s ease;
        }
        
        .place-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        
        /* Button Loading State */
        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        /* Scrollbar Styling */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #1f2937;
            border-radius: 3px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: #4b5563;
            border-radius: 3px;
        }
        
        /* Map Controls */
        .leaflet-control-zoom {
            border: 1px solid #334155 !important;
            border-radius: 8px !important;
            overflow: hidden;
        }
        
        .leaflet-control-zoom a {
            background: #1e293b !important;
            color: #e2e8f0 !important;
            border-bottom: 1px solid #334155 !important;
        }
        
        .leaflet-control-zoom a:hover {
            background: #334155 !important;
        }
        
        /* Accuracy Circle */
        .leaflet-overlay-pane svg path {
            pointer-events: none;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #map {
                height: 300px;
            }
            
            .flex-col.sm\:flex-row {
                flex-direction: column;
            }
            
            .flex-col.sm\:flex-row button {
                width: 100%;
                margin-bottom: 8px;
            }
        }
        
        /* File Preview */
        #new-photo-preview img {
            transition: opacity 0.3s ease;
        }
    </style>

    <script>
        // =============================================
        // SISTEM PETA KOMPLETE DENGAN GEOLOCATION
        // =============================================

        // Variabel global
        let map;
        let marker;
        let userLocationCircle;
        let isInitialized = false;

        // Default coordinates (Jakarta)
        const DEFAULT_COORDS = {
            lat: -6.208763,
            lng: 106.845599
        };

        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Starting map initialization...');
            
            // Initialize the application
            initMapSystem();
            
            // Setup file preview for foto utama
            setupFilePreview();
        });

        // =============================================
        // FUNGSI UTAMA - INISIALISASI SISTEM
        // =============================================

        function initMapSystem() {
            try {
                // Check if Leaflet is loaded
                if (typeof L === 'undefined') {
                    console.error('Leaflet library not loaded!');
                    showNotification('Error: Peta tidak dapat dimuat. Muat ulang halaman.', 'error');
                    return;
                }
                
                // Get initial coordinates
                const initialCoords = getInitialCoordinates();
                
                // Initialize map
                initializeMap(initialCoords);
                
                // Setup event listeners
                setupEventListeners();
                
                // If coordinates exist, set marker and reverse geocode
                if (initialCoords.lat !== DEFAULT_COORDS.lat || initialCoords.lng !== DEFAULT_COORDS.lng) {
                    setTimeout(() => {
                        updateMarker(initialCoords.lat, initialCoords.lng, 'Lokasi kos saat ini');
                        reverseGeocode(initialCoords.lat, initialCoords.lng);
                    }, 1000);
                }
                
                isInitialized = true;
                console.log('Map system initialized successfully');
                
            } catch (error) {
                console.error('Failed to initialize map system:', error);
                showNotification('Gagal memuat sistem peta. Coba muat ulang halaman.', 'error');
            }
        }

        // =============================================
        // FUNGSI INISIALISASI PETA
        // =============================================

        function initializeMap(coords) {
            // Create map instance
            map = L.map('map', {
                center: [coords.lat, coords.lng],
                zoom: 13,
                zoomControl: true,
                attributionControl: false
            });
            
            // Add dark theme tile layer
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);
            
            // Add custom attribution
            L.control.attribution({
                position: 'bottomright',
                prefix: '<a href="https://leafletjs.com" target="_blank" class="text-xs text-gray-400">Leaflet</a>'
            }).addTo(map);
            
            // Add click event to map
            map.on('click', onMapClick);
        }

        // =============================================
        // FUNGSI EVENT LISTENERS
        // =============================================

        function setupEventListeners() {
            // Address search
            const searchBtn = document.getElementById('search-btn');
            const addressInput = document.getElementById('address-search');
            
            if (searchBtn) {
                searchBtn.addEventListener('click', handleAddressSearch);
            }
            
            if (addressInput) {
                addressInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        handleAddressSearch();
                    }
                });
            }
            
            // Current location button
            const currentLocationBtn = document.getElementById('current-location-btn');
            if (currentLocationBtn) {
                currentLocationBtn.addEventListener('click', handleCurrentLocation);
            }
            
            // Detect nearby button
            const detectNearbyBtn = document.getElementById('detect-nearby-btn');
            if (detectNearbyBtn) {
                detectNearbyBtn.addEventListener('click', handleDetectNearby);
            }
            
            // Coordinate inputs change
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            if (latInput && lngInput) {
                let coordinateTimeout;
                
                latInput.addEventListener('input', function() {
                    clearTimeout(coordinateTimeout);
                    coordinateTimeout = setTimeout(() => {
                        updateMapFromCoordinates();
                    }, 1000);
                });
                
                lngInput.addEventListener('input', function() {
                    clearTimeout(coordinateTimeout);
                    coordinateTimeout = setTimeout(() => {
                        updateMapFromCoordinates();
                    }, 1000);
                });
            }
            
            // Track manual edits on address fields
            const addressFields = ['kecamatan', 'kota', 'provinsi', 'kode_pos', 'alamat'];
            addressFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('input', function() {
                        this.dataset.manualEdit = 'true';
                    });
                    
                    field.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            delete this.dataset.manualEdit;
                        }
                    });
                }
            });
        }

        // =============================================
        // FUNGSI UTILITY
        // =============================================

        function getInitialCoordinates() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            let lat = DEFAULT_COORDS.lat;
            let lng = DEFAULT_COORDS.lng;
            
            if (latInput && latInput.value) {
                const parsedLat = parseFloat(latInput.value);
                if (!isNaN(parsedLat)) lat = parsedLat;
            }
            
            if (lngInput && lngInput.value) {
                const parsedLng = parseFloat(lngInput.value);
                if (!isNaN(parsedLng)) lng = parsedLng;
            }
            
            return { lat, lng };
        }

        function updateCoordinates(lat, lng) {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            if (latInput) latInput.value = lat.toFixed(6);
            if (lngInput) lngInput.value = lng.toFixed(6);
        }

        function createCustomIcon() {
            return L.divIcon({
                html: `
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-home text-white text-sm"></i>
                        </div>
                        <div class="w-3 h-3 bg-primary-500 rounded-full absolute -bottom-1 left-1/2 transform -translate-x-1/2 rotate-45"></div>
                    </div>
                `,
                className: 'custom-marker',
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });
        }

        // =============================================
        // FUNGSI MARKER
        // =============================================

        function updateMarker(lat, lng, title = 'Lokasi Kos') {
            // Remove existing marker
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Create new marker
            marker = L.marker([lat, lng], {
                icon: createCustomIcon(),
                draggable: true
            }).addTo(map);
            
            // Set popup content
            marker.bindPopup(`
                <div class="text-sm p-2" style="max-width: 250px;">
                    <div class="font-semibold text-dark-bg mb-1">📍 ${title}</div>
                    <div class="text-gray-600 text-xs">Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</div>
                </div>
            `).openPopup();
            
            // Add drag event
            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
                reverseGeocode(position.lat, position.lng);
            });
            
            // Center map on marker
            map.setView([lat, lng], 16);
        }

        // =============================================
        // FUNGSI PENCARIAN ALAMAT
        // =============================================

        async function handleAddressSearch() {
            const addressInput = document.getElementById('address-search');
            const searchBtn = document.getElementById('search-btn');
            
            if (!addressInput || !searchBtn) return;
            
            const query = addressInput.value.trim();
            
            if (!query) {
                showNotification('Masukkan alamat untuk dicari', 'warning');
                return;
            }
            
            if (query.length < 3) {
                showNotification('Masukkan minimal 3 karakter', 'warning');
                return;
            }
            
            // Show loading state
            const originalText = searchBtn.innerHTML;
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...';
            
            try {
                const result = await geocodeAddress(query);
                
                if (result) {
                    updateMarker(result.lat, result.lng, result.display_name);
                    updateCoordinates(result.lat, result.lng);
                    fillAddressForm(result.address, result.display_name);
                    addressInput.value = result.display_name;
                    showNotification('Alamat ditemukan!', 'success');
                } else {
                    showNotification('Alamat tidak ditemukan', 'error');
                }
            } catch (error) {
                console.error('Address search error:', error);
                showNotification('Gagal mencari alamat', 'error');
            } finally {
                // Reset button
                searchBtn.disabled = false;
                searchBtn.innerHTML = originalText;
            }
        }

        async function geocodeAddress(query) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1&addressdetails=1`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'Referer': window.location.origin,
                            'User-Agent': 'AyoKos/1.0'
                        }
                    }
                );
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const results = await response.json();
                
                if (results && results.length > 0) {
                    const result = results[0];
                    return {
                        lat: parseFloat(result.lat),
                        lng: parseFloat(result.lon),
                        display_name: result.display_name,
                        address: result.address
                    };
                }
                
                return null;
            } catch (error) {
                console.error('Geocoding error:', error);
                throw error;
            }
        }

        // =============================================
        // FUNGSI LOKASI SAAT INI (GEOLOCATION)
        // =============================================

        async function handleCurrentLocation() {
            const button = document.getElementById('current-location-btn');
            
            if (!button) return;
            
            // Check if geolocation is supported
            if (!navigator.geolocation) {
                showNotification('Browser tidak mendukung geolocation', 'error');
                return;
            }
            
            // Show loading state
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mendeteksi...';
            
            // Request location
            navigator.geolocation.getCurrentPosition(
                // Success callback
                async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;
                    
                    console.log('Location obtained:', { lat, lng, accuracy });
                    
                    // Update marker and map
                    updateMarker(lat, lng, 'Lokasi Anda');
                    updateCoordinates(lat, lng);
                    
                    // Show accuracy circle
                    showAccuracyCircle(lat, lng, accuracy);
                    
                    // Reverse geocode to get address
                    await reverseGeocode(lat, lng);
                    
                    // Update search input
                    const addressInput = document.getElementById('address-search');
                    if (addressInput) {
                        addressInput.value = 'Lokasi saat ini';
                    }
                    
                    showNotification(`Lokasi ditemukan! Akurasi: ${Math.round(accuracy)}m`, 'success');
                    
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = originalText;
                },
                // Error callback
                (error) => {
                    console.error('Geolocation error:', error);
                    
                    let message = 'Gagal mendapatkan lokasi';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Izin lokasi ditolak. Izinkan akses lokasi di pengaturan browser.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            message = 'Permintaan lokasi timeout.';
                            break;
                    }
                    
                    showNotification(message, 'error');
                    
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = originalText;
                },
                // Options
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        function showAccuracyCircle(lat, lng, accuracy) {
            // Remove existing circle
            if (userLocationCircle) {
                map.removeLayer(userLocationCircle);
            }
            
            // Create new circle
            userLocationCircle = L.circle([lat, lng], {
                radius: accuracy,
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.1,
                weight: 1,
                dashArray: '5, 5'
            }).addTo(map);
            
            // Auto remove after 30 seconds
            setTimeout(() => {
                if (userLocationCircle) {
                    map.removeLayer(userLocationCircle);
                    userLocationCircle = null;
                }
            }, 30000);
        }

        // =============================================
        // FUNGSI TEMPAT TERDEKAT
        // =============================================

        async function handleDetectNearby() {
            const button = document.getElementById('detect-nearby-btn');
            
            if (!button) return;
            
            // First get current location
            if (!navigator.geolocation) {
                showNotification('Browser tidak mendukung geolocation', 'error');
                return;
            }
            
            // Show loading state
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...';
            
            try {
                // Get current position
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true,
                        timeout: 10000
                    });
                });
                
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Search for nearby places
                const places = await searchNearbyPlaces(lat, lng);
                
                // Show places in modal
                showNearbyPlacesModal(places, lat, lng);
                
            } catch (error) {
                console.error('Detect nearby error:', error);
                showNotification('Gagal mencari tempat terdekat', 'error');
            } finally {
                // Reset button
                button.disabled = false;
                button.innerHTML = originalText;
            }
        }

        async function searchNearbyPlaces(lat, lng, radius = 500) {
            try {
                // Simple query for places in Indonesia
                const query = `
                    [out:json][timeout:25];
                    (
                        node["amenity"](around:${radius},${lat},${lng});
                        way["amenity"](around:${radius},${lat},${lng});
                        node["shop"](around:${radius},${lat},${lng});
                        way["shop"](around:${radius},${lat},${lng});
                    );
                    out body;
                    >;
                    out skel qt;
                `;
                
                const response = await fetch('https://overpass-api.de/api/interpreter', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'data=' + encodeURIComponent(query)
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                // Process results
                const places = [];
                const seenNames = new Set();
                
                data.elements.forEach(element => {
                    if (element.tags && element.tags.name) {
                        const name = element.tags.name;
                        
                        if (!seenNames.has(name)) {
                            seenNames.add(name);
                            
                            const place = {
                                name: name,
                                type: element.tags.amenity || element.tags.shop || 'tempat',
                                lat: element.lat || (element.center && element.center.lat),
                                lng: element.lon || (element.center && element.center.lon),
                                tags: element.tags
                            };
                            
                            if (place.lat && place.lng) {
                                places.push(place);
                            }
                        }
                    }
                });
                
                return places.slice(0, 8); // Limit to 8 results
                
            } catch (error) {
                console.error('Nearby places search error:', error);
                return [];
            }
        }

        function showNearbyPlacesModal(places, userLat, userLng) {
            // Remove existing modal
            const existingModal = document.getElementById('nearby-places-modal');
            if (existingModal) existingModal.remove();
            
            // Create modal
            const modal = document.createElement('div');
            modal.id = 'nearby-places-modal';
            modal.className = 'fixed inset-0 bg-black/70 z-[10000] flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-dark-card border border-dark-border rounded-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-900/30 to-pink-900/30 border-b border-dark-border p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white mb-1">📍 Tempat Terdekat</h3>
                                <p class="text-sm text-dark-muted">Pilih lokasi terdekat untuk kos Anda</p>
                            </div>
                            <button type="button" id="close-modal" 
                                class="w-10 h-10 bg-dark-border rounded-lg flex items-center justify-center hover:bg-dark-border/80 transition">
                                <i class="fas fa-times text-white"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 overflow-y-auto max-h-[60vh]">
                        ${places.length === 0 ? `
                            <div class="text-center py-8">
                                <i class="fas fa-map-marker-slash text-4xl text-dark-muted mb-4"></i>
                                <p class="text-white font-medium mb-2">Tidak ada tempat terdeteksi</p>
                                <p class="text-dark-muted text-sm">Coba cari dengan radius yang lebih luas atau gunakan pencarian manual</p>
                            </div>
                        ` : `
                            <div class="space-y-3">
                                ${places.map((place, index) => `
                                    <div class="place-item bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-primary-500/50 transition cursor-pointer"
                                         data-lat="${place.lat}" 
                                         data-lng="${place.lng}"
                                         data-name="${place.name}">
                                        <div class="flex items-start">
                                            <div class="w-10 h-10 bg-purple-900/30 rounded-lg flex items-center justify-center mr-3 mt-1">
                                                <i class="fas fa-map-pin text-purple-400"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <h4 class="font-semibold text-white">${place.name}</h4>
                                                    <span class="text-xs bg-primary-900/30 text-primary-300 px-2 py-1 rounded">${place.type}</span>
                                                </div>
                                                <p class="text-sm text-dark-muted mb-2">${getDistance(userLat, userLng, place.lat, place.lng)} meter dari Anda</p>
                                                <button type="button" class="select-place-btn text-sm bg-primary-900/20 text-primary-300 hover:bg-primary-900/40 px-3 py-1 rounded-lg transition">
                                                    Pilih Lokasi Ini
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `}
                    </div>
                    
                    <!-- Footer -->
                    <div class="border-t border-dark-border p-4 bg-dark-bg/50">
                        <div class="flex justify-between">
                            <button type="button" id="use-my-exact-location" 
                                class="px-4 py-2 bg-blue-900/30 text-blue-300 rounded-lg hover:bg-blue-900/50 transition flex items-center">
                                <i class="fas fa-crosshairs mr-2"></i>
                                Gunakan Posisi Tepat Saya
                            </button>
                            <button type="button" id="cancel-modal" 
                                class="px-4 py-2 bg-dark-border text-white rounded-lg hover:bg-dark-border/80 transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Setup modal events
            setupModalEvents(places, userLat, userLng);
        }

        function setupModalEvents(places, userLat, userLng) {
            // Close modal
            document.getElementById('close-modal').addEventListener('click', closeModal);
            document.getElementById('cancel-modal').addEventListener('click', closeModal);
            
            // Click outside to close
            document.getElementById('nearby-places-modal').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });
            
            // Select place
            document.querySelectorAll('.place-item, .select-place-btn').forEach(element => {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    const item = this.closest('.place-item');
                    const lat = parseFloat(item.dataset.lat);
                    const lng = parseFloat(item.dataset.lng);
                    const name = item.dataset.name;
                    
                    updateMarker(lat, lng, name);
                    updateCoordinates(lat, lng);
                    reverseGeocode(lat, lng);
                    
                    // Update search input
                    const addressInput = document.getElementById('address-search');
                    if (addressInput) addressInput.value = name;
                    
                    closeModal();
                    showNotification(`Lokasi "${name}" dipilih`, 'success');
                });
            });
            
            // Use exact location
            document.getElementById('use-my-exact-location').addEventListener('click', function() {
                updateMarker(userLat, userLng, 'Posisi tepat Anda');
                updateCoordinates(userLat, userLng);
                reverseGeocode(userLat, userLng);
                
                closeModal();
                showNotification('Menggunakan posisi tepat Anda', 'success');
            });
        }

        function closeModal() {
            const modal = document.getElementById('nearby-places-modal');
            if (modal) modal.remove();
        }

        function getDistance(lat1, lng1, lat2, lng2) {
            const R = 6371000; // Earth radius in meters
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return Math.round(R * c);
        }

        // =============================================
        // FUNGSI REVERSE GEOCODE
        // =============================================

        async function reverseGeocode(lat, lng) {
            showNotification('Mengambil detail alamat...', 'info');
            
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'Referer': window.location.origin,
                            'User-Agent': 'AyoKos/1.0'
                        }
                    }
                );
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data && data.address) {
                    fillAddressForm(data.address, data.display_name);
                    showNotification('Alamat berhasil diambil!', 'success');
                }
            } catch (error) {
                console.error('Reverse geocode error:', error);
                showNotification('Gagal mengambil detail alamat', 'error');
            }
        }

        function fillAddressForm(addressData, displayName = '') {
            if (!addressData) return;
            
            // Map Nominatim fields to our form fields
            const fieldMap = {
                'kecamatan': addressData.suburb || addressData.village || addressData.city_district || '',
                'kota': addressData.city || addressData.town || addressData.municipality || '',
                'provinsi': addressData.state || addressData.region || '',
                'kode_pos': addressData.postcode || '',
                'alamat': displayName || ''
            };
            
            // Fill fields only if not manually edited
            for (const [fieldName, value] of Object.entries(fieldMap)) {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && !field.dataset.manualEdit && value) {
                    field.value = value;
                }
            }
        }

        // =============================================
        // FUNGSI EVENT HANDLERS
        // =============================================

        function onMapClick(e) {
            const { lat, lng } = e.latlng;
            updateMarker(lat, lng, 'Lokasi dipilih');
            updateCoordinates(lat, lng);
            reverseGeocode(lat, lng);
        }

        function updateMapFromCoordinates() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            if (!latInput || !lngInput) return;
            
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                updateMarker(lat, lng, 'Lokasi dari koordinat');
            }
        }

        // =============================================
        // FUNGSI NOTIFICATION
        // =============================================

        function showNotification(message, type = 'info') {
            // Remove existing notification
            const existing = document.querySelector('.fixed-notification');
            if (existing) existing.remove();
            
            // Notification config
            const config = {
                info: { bg: 'bg-blue-900/90', border: 'border-blue-700', icon: 'fa-info-circle' },
                success: { bg: 'bg-green-900/90', border: 'border-green-700', icon: 'fa-check-circle' },
                error: { bg: 'bg-red-900/90', border: 'border-red-700', icon: 'fa-exclamation-circle' },
                warning: { bg: 'bg-yellow-900/90', border: 'border-yellow-700', icon: 'fa-exclamation-triangle' }
            };
            
            const { bg, border, icon } = config[type] || config.info;
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed-notification fixed bottom-4 right-4 px-6 py-3 rounded-xl border ${border} ${bg} text-white z-[9999] shadow-2xl notification`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${icon} mr-3"></i>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(20px)';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // =============================================
        // FUNGSI PREVIEW FILE UPLOAD
        // =============================================

        function setupFilePreview() {
            const fotoInput = document.getElementById('foto-utama');
            if (!fotoInput) return;
            
            fotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    showNotification('Hanya file gambar yang diperbolehkan', 'error');
                    this.value = '';
                    return;
                }
                
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('Ukuran file maksimal 2MB', 'error');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Remove existing preview
                    const existingPreview = document.getElementById('new-photo-preview');
                    existingPreview.innerHTML = '';
                    
                    // Create preview
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'w-full h-48 object-cover rounded-xl border border-dark-border';
                    preview.alt = 'Preview foto baru';
                    
                    // Add remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'mt-2 px-4 py-2 bg-red-900/50 text-red-300 rounded-lg hover:bg-red-800/50 transition text-sm w-full';
                    removeBtn.innerHTML = '<i class="fas fa-trash mr-2"></i>Hapus Foto Baru';
                    removeBtn.onclick = function() {
                        fotoInput.value = '';
                        existingPreview.innerHTML = '';
                    };
                    
                    // Add to container
                    existingPreview.appendChild(preview);
                    existingPreview.appendChild(removeBtn);
                };
                reader.readAsDataURL(file);
            });
        }

        // =============================================
        // ERROR HANDLING GLOBAL
        // =============================================

        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
        });

        // Expose to global scope for debugging
        window.mapSystem = {
            getMap: () => map,
            getMarker: () => marker,
            getCoords: () => marker ? { lat: marker.getLatLng().lat, lng: marker.getLatLng().lng } : null,
            showNotification
        };
    </script>
@endsection