@extends('layouts.app')

@section('title', 'Tambah Kamar - AyoKos')

@section('content')
<div class="max-w-4xl mx-auto p-4 md:p-6">
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
                        <a href="{{ route('pemilik.kamar.index') }}" class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                            <i class="fas fa-bed mr-2"></i>
                            Kelola Kamar
                        </a>
                    </div>
                </li>
                <li class="inline-flex items-center">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                        <span class="inline-flex items-center text-sm font-medium text-white">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Kamar
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <!-- Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">

            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-4">Tambah Kamar Baru</h1>
                <p class="text-dark-muted mt-1 mb-4">Isi form berikut untuk menambahkan kamar baru ke kos Anda</p>
            </div>
        </div>
        


    @if($errors->any())
        <div class="mb-6 p-4 bg-red-900/30 border border-red-800/50 rounded-xl">
            <div class="flex items-start space-x-3">
                <div class="p-2 bg-red-900/50 rounded-lg">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-white font-medium mb-1">Ada beberapa kesalahan:</h3>
                    <ul class="text-red-300 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                        <li class="flex items-center">
                            <i class="fas fa-circle text-xs mr-2"></i>
                            {{ $error }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
        <form method="POST" action="{{ route('pemilik.kamar.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Form Content -->
            <div class="p-6">
                <div class="space-y-8">
                    <!-- Section 1: Informasi Dasar -->
                    <div class="border-b border-dark-border pb-8">
                        <h2 class="text-lg font-bold text-white mb-6 flex items-center">
                            <i class="fas fa-info-circle text-primary-400 mr-3"></i>
                            Informasi Dasar Kamar
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pilih Kos -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-3">
                                    Pilih Kos <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-home absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                    <select name="id_kos" 
                                            class="w-full pl-12 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition"
                                            required>
                                        <option value="">Pilih Kos...</option>
                                        @foreach($kos as $k)
                                        <option value="{{ $k->id_kos }}" {{ old('id_kos') == $k->id_kos ? 'selected' : '' }}>
                                            {{ $k->nama_kos }} - {{ $k->alamat }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                                </div>
                                <p class="text-sm text-dark-muted mt-2">Pilih kos tempat kamar ini akan ditambahkan</p>
                            </div>

                            <!-- Nomor Kamar -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-3">
                                    Nomor Kamar <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-hashtag absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                    <input type="text" 
                                           name="nomor_kamar" 
                                           value="{{ old('nomor_kamar') }}" 
                                           class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                           placeholder="A1, B2, 101"
                                           required 
                                           maxlength="10">
                                </div>
                                <p class="text-sm text-dark-muted mt-2">Nomor unik untuk identifikasi kamar</p>
                            </div>

                            <!-- Tipe Kamar -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-3">
                                    Tipe Kamar <span class="text-red-400">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    @php
                                        $tipeOptions = [
                                            'Standar' => ['color' => 'from-blue-500 to-blue-600', 'icon' => 'fa-home'],
                                            'Deluxe' => ['color' => 'from-purple-500 to-purple-600', 'icon' => 'fa-crown'],
                                            'VIP' => ['color' => 'from-yellow-500 to-yellow-600', 'icon' => 'fa-gem'],
                                            'Superior' => ['color' => 'from-green-500 to-green-600', 'icon' => 'fa-star'],
                                            'Ekonomi' => ['color' => 'from-gray-500 to-gray-600', 'icon' => 'fa-wallet'],
                                        ];
                                    @endphp
                                    @foreach($tipeOptions as $value => $style)
                                    <label class="cursor-pointer">
                                        <input type="radio" 
                                               name="tipe_kamar" 
                                               value="{{ $value }}" 
                                               class="hidden peer"
                                               {{ old('tipe_kamar') == $value ? 'checked' : '' }}
                                               required>
                                        <div class="p-4 border-2 border-dark-border rounded-xl peer-checked:border-primary-500 peer-checked:bg-primary-900/20 transition-all duration-300">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br {{ $style['color'] }} rounded-lg flex items-center justify-center">
                                                    <i class="fas {{ $style['icon'] }} text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <span class="block font-medium text-white">{{ $value }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Harga Sewa -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-3">
                                    Harga Sewa per Bulan <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-money-bill-wave absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                    <input type="number" 
                                           name="harga" 
                                           value="{{ old('harga') }}" 
                                           class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                           placeholder="1500000"
                                           required 
                                           min="0">
                                </div>
                                <p class="text-sm text-dark-muted mt-2">Harga sewa dalam Rupiah</p>
                            </div>

                            <!-- Luas Kamar -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-3">
                                    Luas Kamar <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-ruler-combined absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                                    <input type="text" 
                                           name="luas_kamar" 
                                           value="{{ old('luas_kamar') }}" 
                                           class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                           placeholder="3x4, 4x4"
                                           required
                                           maxlength="20">
                                </div>
                                <p class="text-sm text-dark-muted mt-2">Ukuran kamar dalam meter (panjang x lebar)</p>
                            </div>

                            <!-- Kapasitas -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-3">
                                    Kapasitas <span class="text-red-400">*</span>
                                </label>
                                <div class="grid grid-cols-4 gap-3">
                                    @for($i = 1; $i <= 4; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" 
                                               name="kapasitas" 
                                               value="{{ $i }}" 
                                               class="hidden peer"
                                               {{ old('kapasitas') == $i ? 'checked' : '' }}
                                               required>
                                        <div class="p-4 border-2 border-dark-border rounded-xl text-center peer-checked:border-primary-500 peer-checked:bg-primary-900/20 transition-all duration-300">
                                            <div class="text-2xl font-bold text-white mb-1">{{ $i }}</div>
                                            <div class="text-xs text-dark-muted">
                                                @if($i == 1) 1 Orang @else {{ $i }} Orang @endif
                                            </div>
                                        </div>
                                    </label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Fasilitas Kamar -->
                    <div class="border-b border-dark-border pb-8">
                        <h2 class="text-lg font-bold text-white mb-6 flex items-center">
                            <i class="fas fa-list-check text-green-400 mr-3"></i>
                            Fasilitas Kamar
                        </h2>
                        
                        <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @php
                                    $facilityGroups = [
                                        'Kamar Mandi' => ['Kamar mandi dalam', 'Water heater'],
                                        'Elektronik' => ['AC', 'Kipas angin', 'TV', 'Kulkas mini', 'WiFi'],
                                        'Furniture' => ['Kasur', 'Lemari', 'Meja belajar', 'Kursi'],
                                        'Lainnya' => ['Dapur', 'Jendela', 'Balkon']
                                    ];
                                @endphp
                                
                                @foreach($facilityGroups as $group => $facilities)
                                <div>
                                    <h4 class="text-sm font-medium text-dark-muted mb-3">{{ $group }}</h4>
                                    <div class="space-y-2">
                                        @foreach($facilities as $facility)
                                        @php
                                            $icons = [
                                                'Kamar mandi dalam' => 'fa-bath',
                                                'Water heater' => 'fa-temperature-high',
                                                'AC' => 'fa-snowflake',
                                                'Kipas angin' => 'fa-fan',
                                                'TV' => 'fa-tv',
                                                'Kulkas mini' => 'fa-refrigerator',
                                                'WiFi' => 'fa-wifi',
                                                'Kasur' => 'fa-bed',
                                                'Lemari' => 'fa-archive',
                                                'Meja belajar' => 'fa-table',
                                                'Kursi' => 'fa-chair',
                                                'Dapur' => 'fa-kitchen-set',
                                                'Jendela' => 'fa-window-maximize',
                                                'Balkon' => 'fa-building'
                                            ];
                                        @endphp
                                        <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-dark-border/30 rounded-lg transition">
                                            <input type="checkbox" 
                                                   name="fasilitas_kamar[]" 
                                                   value="{{ $facility }}" 
                                                   class="w-4 h-4 bg-dark-bg border-dark-border rounded text-primary-500 focus:ring-primary-500 focus:ring-2"
                                                   {{ in_array($facility, old('fasilitas_kamar', [])) ? 'checked' : '' }}>
                                            <div class="flex-1 flex items-center">
                                                <i class="fas {{ $icons[$facility] ?? 'fa-check' }} w-5 text-dark-muted mr-2"></i>
                                                <span class="text-sm text-white">{{ $facility }}</span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <p class="text-sm text-dark-muted mt-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih fasilitas yang tersedia di kamar ini
                            </p>
                        </div>
                    </div>

                    <!-- Section 3: Foto & Status -->
                    <div>
                        <h2 class="text-lg font-bold text-white mb-6 flex items-center">
                            <i class="fas fa-camera text-yellow-400 mr-3"></i>
                            Foto & Status Kamar
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Foto Kamar -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-3">
                                    Foto Kamar
                                </label>
                                <div class="border-2 border-dashed border-dark-border rounded-xl p-6 text-center hover:border-primary-500/50 transition">
                                    <div class="mb-4">
                                        <div class="w-16 h-16 bg-dark-border/50 rounded-full flex items-center justify-center mx-auto">
                                            <i class="fas fa-camera text-2xl text-dark-muted"></i>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <input type="file" 
                                               name="foto_kamar" 
                                               id="foto_kamar"
                                               class="hidden"
                                               accept="image/*">
                                        <label for="foto_kamar" 
                                               class="inline-block px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg cursor-pointer transition">
                                            <i class="fas fa-upload mr-2"></i>
                                            Unggah Foto
                                        </label>
                                    </div>
                                    <p class="text-sm text-dark-muted">Format: JPG, PNG, JPEG (max 2 MB)</p>
                                    <div id="file-name" class="text-xs text-primary-400 mt-2"></div>

                                    {{-- PREVIEW BARU --}}
                                    <div id="preview-wrap" class="hidden mt-4 flex justify-center">
                                        <img id="preview-img" class="max-w-full max-h-48 rounded-xl border border-dark-border" alt="Preview">
                                    </div>
                                </div>
                            </div>

                            <!-- Status Kamar -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-3">
                                    Status Kamar <span class="text-red-400">*</span>
                                </label>
                                <div class="space-y-3">
                                    @php
                                        $statusOptions = [
                                            'tersedia' => ['color' => 'from-green-500 to-green-600', 'icon' => 'fa-check-circle', 'label' => 'Tersedia'],
                                            'terisi' => ['color' => 'from-blue-500 to-blue-600', 'icon' => 'fa-user-check', 'label' => 'Terisi'],
                                            'maintenance' => ['color' => 'from-yellow-500 to-yellow-600', 'icon' => 'fa-tools', 'label' => 'Maintenance'],
                                        ];
                                    @endphp
                                    
                                    @foreach($statusOptions as $value => $style)
                                    <label class="cursor-pointer block">
                                        <input type="radio" 
                                               name="status_kamar" 
                                               value="{{ $value }}" 
                                               class="hidden peer"
                                               {{ old('status_kamar') == $value ? 'checked' : ($value == 'tersedia' && !old('status_kamar') ? 'checked' : '') }}
                                               required>
                                        <div class="p-4 border-2 border-dark-border rounded-xl peer-checked:border-primary-500 peer-checked:bg-primary-900/20 transition-all duration-300">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br {{ $style['color'] }} rounded-lg flex items-center justify-center">
                                                        <i class="fas {{ $style['icon'] }} text-white"></i>
                                                    </div>
                                                    <div>
                                                        <span class="block font-medium text-white">{{ $style['label'] }}</span>
                                                        <span class="text-xs text-dark-muted">
                                                            @if($value == 'tersedia')
                                                            Kamar siap disewa
                                                            @elseif($value == 'terisi')
                                                            Kamar sedang ditempati
                                                            @else
                                                            Kamar sedang diperbaiki
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="w-6 h-6 border-2 border-dark-border rounded-full peer-checked:border-primary-500 peer-checked:bg-primary-500 flex items-center justify-center">
                                                    <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-10 pt-8 border-t border-dark-border flex flex-col sm:flex-row justify-between space-y-4 sm:space-y-0">
                    <div>
                        <a href="{{ route('pemilik.kamar.index') }}" 
                           class="inline-flex items-center px-6 py-3 border-2 border-dark-border text-white rounded-xl hover:border-dark-muted hover:text-dark-muted transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="button" onclick="resetForm()"
                                class="px-6 py-3 border-2 border-dark-border text-white rounded-xl hover:border-red-500 hover:text-red-400 transition">
                            <i class="fas fa-redo mr-2"></i>
                            Reset Form
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Kamar Baru
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div id="resetModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm" aria-hidden="true">
    <div class="fixed inset-0" data-modal-close></div>
    <div class="relative bg-dark-card border border-dark-border rounded-2xl w-full max-w-md overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 duration-300 pointer-events-auto" id="resetModalContent">
        <div class="p-6 text-center">
            <div class="mb-4 inline-block">
                <div class="w-16 h-16 rounded-full bg-orange-500/20 flex items-center justify-center mx-auto shadow-inner">
                    <i class="fas fa-exclamation-triangle text-orange-400 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-white mb-2 tracking-tight">Konfirmasi Reset</h3>
            <p class="text-dark-muted mb-6 px-4">Apakah Anda yakin ingin mengosongkan semua isian form? Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" data-modal-close
                        class="flex-1 px-6 py-2.5 bg-dark-border/50 text-white rounded-xl hover:bg-dark-border transition duration-200 font-medium">
                    Batal
                </button>
                <button type="button" id="confirmResetBtn"
                        class="flex-1 px-6 py-2.5 bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-red-600 transition duration-300 shadow-lg hover:shadow-orange-500/20 active:scale-95">
                    Ya, Reset Form
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let resetModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Modal using class from app.blade.php
        // Wrap in a small timeout to ensure Modal class is globally available
        setTimeout(() => {
            if (typeof Modal !== 'undefined') {
                resetModal = new Modal('resetModal');
                
                // Custom animation for the modal content
                const originalShow = resetModal.show.bind(resetModal);
                const originalHide = resetModal.hide.bind(resetModal);
                const modalContent = document.getElementById('resetModalContent');
                
                if (modalContent) {
                    resetModal.show = function() {
                        originalShow();
                        setTimeout(() => {
                            modalContent.classList.remove('scale-95', 'opacity-0');
                            modalContent.classList.add('scale-100', 'opacity-100');
                        }, 10);
                    };
                    
                    resetModal.hide = function() {
                        modalContent.classList.remove('scale-100', 'opacity-100');
                        modalContent.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            originalHide();
                        }, 300);
                    };
                }
            } else {
                console.error('Modal class not found. Make sure app.blade.php defines it.');
            }
        }, 100);

        // Handle confirm reset button click
        const confirmBtn = document.getElementById('confirmResetBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                executeReset();
                if (resetModal) resetModal.hide();
            });
        }
        
        // Setup specialized modal closing if not already handled
        document.querySelectorAll('[data-modal-close]').forEach(el => {
            el.addEventListener('click', () => {
                if (resetModal) resetModal.hide();
            });
        });
    });

    // File input display with preview
    document.getElementById('foto_kamar').addEventListener('change', function (e) {
        const fileName   = document.getElementById('file-name');
        const previewImg = document.getElementById('preview-img');
        const previewWrap= document.getElementById('preview-wrap');

        if (this.files && this.files[0]) {
            const file = this.files[0];

            if (!file.type.startsWith('image/')) {
                showToast('File harus berupa gambar', 'error');
                this.value = '';
                if (fileName) fileName.classList.add('hidden');
                if (previewWrap) previewWrap.classList.add('hidden');
                return;
            }

            if (fileName) {
                fileName.textContent = file.name;
                fileName.classList.remove('hidden');
            }

            const url = URL.createObjectURL(file);
            if (previewImg) {
                previewImg.src = url;
                if (previewWrap) previewWrap.classList.remove('hidden');
                previewImg.onload = () => URL.revokeObjectURL(url);
            }
        } else {
            if (fileName) fileName.classList.add('hidden');
            if (previewWrap) previewWrap.classList.add('hidden');
        }
    });

    // Function called by the Reset Form button
    function resetForm() {
        if (resetModal) {
            resetModal.show();
        } else {
            // Fallback to native confirm if Modal class failed to load
            if (confirm('Apakah Anda yakin ingin mengosongkan semua isian form?')) {
                executeReset();
            }
        }
    }

    // Logic to actually clear the form
    function executeReset() {
        const form = document.querySelector('form');
        if (form) form.reset();

        // Clear file display elements
        const fileName = document.getElementById('file-name');
        const previewWrap = document.getElementById('preview-wrap');
        const previewImg = document.getElementById('preview-img');
        
        if (fileName) {
            fileName.textContent = '';
            fileName.classList.add('hidden');
        }
        if (previewWrap) previewWrap.classList.add('hidden');
        if (previewImg) previewImg.src = '';

        // Restore radio button defaults
        const statusTersedia = document.querySelector('input[name="status_kamar"][value="tersedia"]');
        if (statusTersedia) statusTersedia.checked = true;
        
        const kapasitasOne = document.querySelector('input[name="kapasitas"][value="1"]');
        if (kapasitasOne) kapasitasOne.checked = true;

        showToast('Form berhasil dikosongkan', 'success');
    }

    // Form validation on submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const form = this;
        let isValid = true;
        let firstInvalidField = null;
        
        // Clear previous error states
        form.querySelectorAll('.border-rose-500').forEach(el => {
            el.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/20');
        });

        // 1. Validate required inputs/selects
        const requiredInputs = form.querySelectorAll('input[required]:not([type="radio"]):not([type="checkbox"]), select[required]');
        requiredInputs.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
                field.classList.add('border-rose-500', 'ring-2', 'ring-rose-500/20');
            }
        });

        // 2. Validate radio groups (tipe_kamar, kapasitas, status_kamar)
        const radioGroups = ['tipe_kamar', 'kapasitas', 'status_kamar'];
        radioGroups.forEach(groupName => {
            const radios = form.querySelectorAll(`input[name="${groupName}"]`);
            if (radios.length > 0) {
                const isRequired = Array.from(radios).some(r => r.hasAttribute('required'));
                if (isRequired) {
                    const checked = form.querySelector(`input[name="${groupName}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        // Target the main container for this section
                        const radioContainer = radios[0].closest('.grid') || radios[0].closest('.space-y-3');
                        if (radioContainer) {
                            radioContainer.classList.add('border-rose-500', 'ring-2', 'ring-rose-500/20', 'p-2', 'rounded-xl');
                            radioContainer.querySelectorAll('.border-2').forEach(el => {
                                el.classList.add('border-rose-500/50');
                            });
                        }
                        if (!firstInvalidField) firstInvalidField = radioContainer || radios[0];
                    }
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            if (firstInvalidField) {
                const scrollTarget = firstInvalidField.closest('div') || firstInvalidField;
                scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // If it's a normal input, focus it
                if (firstInvalidField.focus && !firstInvalidField.readOnly) {
                    firstInvalidField.focus();
                }
            }
            showToast('Harap isi semua field yang wajib diisi', 'error');
        }
    });

    // Premium Toast notification system
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        
        const styles = {
            success: 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400 glow-emerald',
            error: 'bg-rose-500/10 border-rose-500/20 text-rose-400 glow-rose',
            info: 'bg-blue-500/10 border-blue-500/20 text-blue-400 glow-blue'
        };
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };
        
        const styleClass = styles[type] || styles.info;
        const iconClass = icons[type] || icons.info;

        toast.className = `fixed top-6 right-6 px-6 py-4 rounded-2xl shadow-2xl z-[10001] border backdrop-blur-md transform transition-all duration-500 translate-x-12 opacity-0 ${styleClass}`;
        
        toast.innerHTML = `
            <div class="flex items-center space-x-4 min-w-[280px]">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center ${type === 'success' ? 'bg-emerald-500/20' : type === 'error' ? 'bg-rose-500/20' : 'bg-blue-500/20'}">
                    <i class="fas ${iconClass} text-lg"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-white text-sm tracking-tight capitalize leading-none text-nowrap">${type}</p>
                    <p class="text-white/70 text-xs mt-1.5">${message}</p>
                </div>
                <button class="text-white/30 hover:text-white transition-colors ml-2 p-1" onclick="this.closest('.fixed').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-12', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-12', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);
    }
</script>
@endpush

@push('styles')
<style>
    /* Custom styles for radio and checkbox */
    input[type="radio"]:checked + div {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    input[type="checkbox"]:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    label:has(input[type="checkbox"]:checked) .fa-check {
        color: #3b82f6;
    }
    
    #foto_kamar + label:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    input:focus, select:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    input, select, textarea, button, label {
        transition: all 0.2s ease;
    }

    /* Glow effects for toasts */
    .glow-emerald { box-shadow: 0 10px 40px -10px rgba(16, 185, 129, 0.4); }
    .glow-rose { box-shadow: 0 10px 40px -10px rgba(244, 63, 94, 0.4); }
    .glow-blue { box-shadow: 0 10px 40px -10px rgba(59, 130, 246, 0.4); }
</style>
@endpush
@endsection