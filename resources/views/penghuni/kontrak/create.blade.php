@extends('layouts.app')

@section('title', 'Ajukan Kontrak - AyoKos')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="bg-dark-card/50 border border-dark-border rounded-xl p-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('penghuni.dashboard') }}"
                                class="inline-flex items-center text-sm text-dark-muted hover:text-primary-300">
                                <i class="fas fa-gauge mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-dark-muted text-xs"></i>
                                <a href="{{ route('public.kos.show', $kos->id_kos) }}"
                                    class="ml-1 md:ml-3 text-sm text-dark-muted hover:text-primary-300">
                                    <i class="fas fa-file-contract mr-2"></i>
                                    {{ Str::limit($kos->nama_kos, 20) }}
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-dark-muted text-xs"></i>
                                <span class="ml-1 md:ml-3 text-sm font-medium text-primary-300">
                                    <i class="fas fa-plus mr-2"></i>
                                    Ajukan Kontrak
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Header Section -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Ajukan Kontrak Kos</h1>
                        <p class="text-dark-muted">Lengkapi formulir untuk mengajukan kontrak sewa kamar</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('public.kos.show', $kos->id_kos) }}"
                            class="inline-flex items-center px-4 py-2 border border-dark-border text-dark-text rounded-lg hover:border-primary-500 hover:text-primary-300 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Detail Kos
                        </a>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="bg-red-900/30 border border-red-800 text-red-300 px-4 py-3 rounded-xl mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Informasi Kos -->
            <div class="bg-blue-900/20 border border-blue-800/30 rounded-2xl p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-home text-blue-400 mr-3"></i>
                    Informasi Kos
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-building text-blue-400 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-dark-muted">Nama Kos</p>
                            <p class="font-medium text-white">{{ $kos->nama_kos }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-400 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-dark-muted">Lokasi</p>
                            <p class="font-medium text-white">{{ $kos->alamat }}, {{ $kos->kota }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users text-blue-400 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-dark-muted">Jenis Kos</p>
                            <p class="font-medium text-white">{{ ucfirst($kos->jenis_kos) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-blue-400 mr-3 w-5"></i>
                        <div>
                            <p class="text-sm text-dark-muted">Status</p>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-900/30 text-green-300">
                                {{ ucfirst($kos->status_kos) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pengajuan -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-file-contract text-primary-400 mr-3"></i>
                    Formulir Pengajuan Kontrak
                </h2>

                @if($errors->any())
                    <div class="bg-red-900/20 border border-red-800 text-red-300 px-4 py-3 rounded-xl mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>Terjadi kesalahan. Silakan periksa formulir Anda.</span>
                        </div>
                        <ul class="mt-2 ml-6 list-disc text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('penghuni.kontrak.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_kos" value="{{ $kos->id_kos }}">

                    <div class="space-y-6">
                        <!-- Pilih Kamar -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-bed text-primary-400 mr-2"></i>
                                Pilih Kamar *
                            </label>
                            <select id="id_kamar" name="id_kamar"
                                class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                required>
                                <option value="">-- Pilih Kamar --</option>
                                @foreach($kos->kamar as $kamar)
                                    @if($kamar->status_kamar == 'tersedia')
                                        <option value="{{ $kamar->id_kamar }}" data-harga="{{ $kamar->harga }}"
                                            data-tipe="{{ $kamar->tipe_kamar }}" data-luas="{{ $kamar->luas_kamar }}"
                                            data-nomor="{{ $kamar->nomor_kamar }}" data-kapasitas="{{ $kamar->kapasitas }}">
                                            Kamar {{ $kamar->nomor_kamar }} - {{ $kamar->tipe_kamar }}
                                            @if($kamar->luas_kamar)
                                                ({{ $kamar->luas_kamar }})
                                            @endif
                                            - Rp {{ number_format($kamar->harga, 0, ',', '.') }}/
                                            @if($kos->tipe_sewa == 'harian')
                                                hari
                                            @elseif($kos->tipe_sewa == 'mingguan')
                                                minggu
                                            @elseif($kos->tipe_sewa == 'bulanan')
                                                bulan
                                            @elseif($kos->tipe_sewa == 'tahunan')
                                                tahun
                                            @else
                                                bulan
                                            @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('id_kamar')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Kamar Detail Info -->
                            <div id="kamar-detail" class="mt-4 p-4 bg-dark-bg border border-dark-border rounded-xl hidden">
                                <h3 class="font-medium text-white mb-3 flex items-center">
                                    <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                                    Detail Kamar
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-dark-muted">Nomor Kamar</p>
                                        <p id="detail-nomor" class="font-medium text-white">-</p>
                                    </div>
                                    <div>
                                        <p class="text-dark-muted">Tipe Kamar</p>
                                        <p id="detail-tipe" class="font-medium text-white">-</p>
                                    </div>
                                    <div>
                                        <p class="text-dark-muted">Luas</p>
                                        <p id="detail-luas" class="font-medium text-white">-</p>
                                    </div>
                                    <div>
                                        <p class="text-dark-muted">Kapasitas</p>
                                        <p id="detail-kapasitas" class="font-medium text-white">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Durasi Sewa -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-calendar-alt text-primary-400 mr-2"></i>
                                Durasi Sewa *
                            </label>
                            <select id="durasi_sewa" name="durasi_sewa"
                                class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                required>
                                @if($kos->tipe_sewa == 'harian')
                                    <option value="1">1 Hari</option>
                                    <option value="3" selected>3 Hari</option>
                                    <option value="7">7 Hari</option>
                                    <option value="14">14 Hari</option>
                                    <option value="30">30 Hari</option>
                                @elseif($kos->tipe_sewa == 'mingguan')
                                    <option value="1">1 Minggu</option>
                                    <option value="2" selected>2 Minggu</option>
                                    <option value="4">4 Minggu</option>
                                    <option value="8">8 Minggu</option>
                                    <option value="12">12 Minggu</option>
                                @elseif($kos->tipe_sewa == 'tahunan')
                                    <option value="1" selected>1 Tahun</option>
                                    <option value="2">2 Tahun</option>
                                    <option value="3">3 Tahun</option>
                                @else
                                    <option value="1">1 Bulan</option>
                                    <option value="3" selected>3 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">12 Bulan</option>
                                @endif
                            </select>
                            @error('durasi_sewa')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total Biaya Summary -->
                        <div class="bg-green-900/20 border border-green-800/30 rounded-2xl p-5">
                            <h3 class="font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-calculator text-green-400 mr-3"></i>
                                Ringkasan Biaya
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-dark-muted">Harga/
                                        @if($kos->tipe_sewa == 'harian')
                                            Hari
                                        @elseif($kos->tipe_sewa == 'mingguan')
                                            Minggu
                                        @elseif($kos->tipe_sewa == 'bulanan')
                                            Bulan
                                        @elseif($kos->tipe_sewa == 'tahunan')
                                            Tahun
                                        @else
                                            Bulan
                                        @endif
                                    </p>
                                    <p id="harga-per-bulan" class="text-lg font-bold text-white">Rp 0</p>
                                </div>
                                <div>
                                    <p class="text-sm text-dark-muted">Total Biaya</p>
                                    <p id="total-biaya" class="text-2xl font-bold text-green-400">Rp 0</p>
                                </div>
                            </div>
                            <div class="mt-3 text-sm text-dark-muted" id="detail-kamar-summary">
                                <i class="fas fa-info-circle mr-2"></i>
                                Pilih kamar dan durasi untuk melihat detail
                            </div>
                        </div>

                        <!-- Data Diri -->
                        <div class="border-t border-dark-border pt-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-user-circle text-blue-400 mr-3"></i>
                                Data Diri
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-dark-bg border border-dark-border rounded-xl p-4">
                                    <p class="text-sm text-dark-muted mb-1">No. Handphone</p>
                                    <p class="font-medium text-white">{{ auth('penghuni')->user()->no_hp }}</p>
                                    <p class="text-xs text-dark-muted mt-2">
                                        <i class="fas fa-whatsapp text-green-400 mr-1"></i>
                                        Notifikasi WhatsApp akan dikirim ke nomor ini
                                    </p>
                                </div>
                                <div class="bg-dark-bg border border-dark-border rounded-xl p-4">
                                    <p class="text-sm text-dark-muted mb-1">Email</p>
                                    <p class="font-medium text-white">{{ auth('penghuni')->user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Dokumen -->
                        <div class="border-t border-dark-border pt-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-file-upload text-purple-400 mr-3"></i>
                                Dokumen Persyaratan
                            </h3>

                            <div class="space-y-4">
                                <!-- KTP Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">
                                        Foto KTP *
                                        <span class="text-xs text-dark-muted ml-2">(JPG/PNG, max 2MB)</span>
                                    </label>
                                    <div
                                        class="border-2 border-dashed border-dark-border rounded-xl p-6 text-center hover:border-primary-500/50 transition">
                                        <input type="file" name="foto_ktp" id="foto_ktp" class="hidden" accept="image/*"
                                            required>
                                        <label for="foto_ktp" class="cursor-pointer">
                                            <div class="mb-3">
                                                <div
                                                    class="w-16 h-16 bg-primary-900/30 rounded-full flex items-center justify-center mx-auto">
                                                    <i class="fas fa-id-card text-2xl text-primary-400"></i>
                                                </div>
                                            </div>
                                            <div class="text-primary-400 font-medium">Upload Foto KTP</div>
                                            <div class="text-xs text-dark-muted mt-1">
                                                Klik atau drag & drop file ke sini
                                            </div>
                                        </label>
                                        <div id="ktp-preview" class="mt-4 hidden">
                                            <p class="text-sm text-dark-muted mb-2">Preview:</p>
                                            <img src="" alt="Preview KTP"
                                                class="max-h-40 mx-auto rounded-lg border border-dark-border">
                                        </div>
                                    </div>
                                    @error('foto_ktp')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Penting -->
                        <div class="bg-yellow-900/20 border border-yellow-800/30 rounded-2xl p-5">
                            <h3 class="font-semibold text-white mb-3 flex items-center">
                                <i class="fas fa-info-circle text-yellow-400 mr-3"></i>
                                Informasi Penting
                            </h3>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <div
                                        class="w-6 h-6 rounded-full bg-green-900/30 flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-check text-green-400 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-dark-muted">Setelah mengajukan, Anda akan menerima notifikasi
                                        WhatsApp</span>
                                </li>
                                <li class="flex items-start">
                                    <div
                                        class="w-6 h-6 rounded-full bg-blue-900/30 flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-clock text-blue-400 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-dark-muted">Pemilik akan meninjau dalam 1-3 hari kerja</span>
                                </li>
                                <li class="flex items-start">
                                    <div
                                        class="w-6 h-6 rounded-full bg-purple-900/30 flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-chart-line text-purple-400 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-dark-muted">Status bisa dipantau di dashboard Anda</span>
                                </li>
                                <li class="flex items-start">
                                    <div
                                        class="w-6 h-6 rounded-full bg-red-900/30 flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-phone-alt text-red-400 text-xs"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm text-dark-muted">Notifikasi WhatsApp akan dikirim ke:</span>
                                        <p class="text-sm font-medium text-white mt-1">{{ auth('penghuni')->user()->no_hp }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 pt-6 border-t border-dark-border flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('public.kos.show', $kos->id_kos) }}"
                            class="flex-1 px-6 py-3 bg-dark-border text-white rounded-xl hover:bg-dark-border/80 transition text-center">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-xl hover:from-primary-600 hover:to-indigo-600 font-semibold transition shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Ajukan Kontrak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elements
            const kamarSelect = document.getElementById('id_kamar');
            const durasiSelect = document.getElementById('durasi_sewa');
            const hargaPerBulanElement = document.getElementById('harga-per-bulan');
            const totalBiayaElement = document.getElementById('total-biaya');
            const detailKamarSummary = document.getElementById('detail-kamar-summary');
            const kamarDetailBox = document.getElementById('kamar-detail');

            // Kamar detail elements
            const detailNomor = document.getElementById('detail-nomor');
            const detailTipe = document.getElementById('detail-tipe');
            const detailLuas = document.getElementById('detail-luas');
            const detailKapasitas = document.getElementById('detail-kapasitas');

            // File upload preview
            const ktpInput = document.getElementById('foto_ktp');
            const ktpPreview = document.getElementById('ktp-preview');

            // Kamar data from server
            const kamarData = {};
            @foreach($kos->kamar as $kamar)
                @if($kamar->status_kamar == 'tersedia')
                    kamarData[{{ $kamar->id_kamar }}] = {
                        nomor: "{{ $kamar->nomor_kamar }}",
                        tipe: "{{ $kamar->tipe_kamar }}",
                        luas: "{{ $kamar->luas_kamar }}",
                        harga: {{ $kamar->harga }},
                        kapasitas: {{ $kamar->kapasitas }}
                        };
                @endif
            @endforeach

                // Helper function to format currency
                function formatRupiah(angka) {
                    if (!angka || isNaN(angka) || angka === 0) {
                        return 'Rp 0';
                    }
                    return 'Rp ' + angka.toLocaleString('id-ID');
                }

            // Calculate and update prices
            function updatePrices() {
                const selectedKamarId = kamarSelect.value;

                if (!selectedKamarId) {
                    // No kamar selected
                    hargaPerBulanElement.textContent = 'Rp 0';
                    totalBiayaElement.textContent = 'Rp 0';
                    detailKamarSummary.innerHTML = '<i class="fas fa-info-circle mr-2"></i>Pilih kamar dan durasi untuk melihat detail';
                    kamarDetailBox.classList.add('hidden');
                    return;
                }

                // Get selected kamar data
                const kamar = kamarData[selectedKamarId];
                if (!kamar) return;

                // Get selected durasi
                const durasi = parseInt(durasiSelect.value) || 1;

                // Calculate totals
                const total = kamar.harga * durasi;

                // Update display
                hargaPerBulanElement.textContent = formatRupiah(kamar.harga);
                totalBiayaElement.textContent = formatRupiah(total);

                // Update kamar summary
            const tipeSewa = '{{ $kos->tipe_sewa }}';
            let unitLabel = 'bulan';
            if (tipeSewa === 'harian') unitLabel = 'hari';
            else if (tipeSewa === 'mingguan') unitLabel = 'minggu';
            else if (tipeSewa === 'tahunan') unitLabel = 'tahun';
            
            let summary = `Kamar ${kamar.nomor} - ${kamar.tipe}`;
            if (kamar.luas) summary += ` • ${kamar.luas}`;
            summary += ` • ${durasi} ${unitLabel}`;
            detailKamarSummary.textContent = summary;

                // Update kamar detail box
                detailNomor.textContent = kamar.nomor;
                detailTipe.textContent = kamar.tipe;
                detailLuas.textContent = kamar.luas || '-';
                detailKapasitas.textContent = kamar.kapasitas + ' orang';
                kamarDetailBox.classList.remove('hidden');
            }

            // Kamar selection event
            kamarSelect.addEventListener('change', updatePrices);

            // Durasi selection event
            durasiSelect.addEventListener('change', updatePrices);

            // File preview for KTP
            ktpInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    // Check file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File KTP terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        ktpPreview.querySelector('img').src = e.target.result;
                        ktpPreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    ktpPreview.classList.add('hidden');
                }
            });

            // Drag and drop for file upload
            const dropZone = document.querySelector('label[for="foto_ktp"]');
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropZone.parentElement.classList.add('border-primary-500', 'bg-primary-900/10');
            }

            function unhighlight() {
                dropZone.parentElement.classList.remove('border-primary-500', 'bg-primary-900/10');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    ktpInput.files = files;
                    ktpInput.dispatchEvent(new Event('change'));
                }
            }

            // Initial calculation
            if (kamarSelect.value) {
                updatePrices();
            }

            // Form validation before submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                // Basic validation
                if (!kamarSelect.value) {
                    e.preventDefault();
                    alert('Silakan pilih kamar terlebih dahulu');
                    kamarSelect.focus();
                    return false;
                }

                if (!durasiSelect.value) {
                    e.preventDefault();
                    alert('Silakan pilih durasi sewa');
                    durasiSelect.focus();
                    return false;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengajukan...';
                submitBtn.disabled = true;

                // Re-enable after 5 seconds if something goes wrong
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
        });
    </script>
@endsection