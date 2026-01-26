@extends('layouts.app')

@section('title', 'Bayar Sewa - AyoKos')

@section('content')
    <div class="p-4 md:p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <div class="bg-dark-card/50 border border-dark-border rounded-xl p-4 mb-6">
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
                                    class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Riwayat Pembayaran
                                </a>
                            </div>
                        </li>
                        <li class="inline-flex items-center">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                                <span class="ml-1 text-sm font-medium text-white">
                                    <i class="fas fa-plus mr-2"></i>
                                    Buat Pembayaran
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-900/50 to-emerald-900/50 border border-green-800/30 rounded-2xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Bayar Sewa Kos</h1>
                        <p class="text-dark-muted">Bayar sewa bulanan atau bayar di muka untuk beberapa bulan</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages from Session -->
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

            <!-- Info Kontrak -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-file-contract text-primary-400 mr-3"></i>
                    Informasi Kontrak
                </h2>

                @if($kontrakAktif->count() > 1)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-white mb-3">Pilih Kontrak *</label>
                        <div class="relative">
                            <i class="fas fa-home absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                            <select id="kontrak-select"
                                class="w-full pl-12 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition">
                                @foreach($kontrakAktif as $k)
                                    <option value="{{ $k->id_kontrak }}" data-harga="{{ $k->harga_sewa }}"
                                        data-mulai="{{ $k->tanggal_mulai ? $k->tanggal_mulai->format('d M Y') : '-' }}"
                                        data-selesai="{{ $k->tanggal_selesai ? $k->tanggal_selesai->format('d M Y') : '-' }}"
                                        data-pemilik="{{ $k->kos->pemilik->nama }}" data-kos="{{ $k->kos->nama_kos }}"
                                        data-kamar="{{ $k->kamar->nomor_kamar }}"
                                        data-grace-period="{{ $k->tanggal_selesai ? \Carbon\Carbon::parse($k->tanggal_selesai)->addDays(7)->format('d M Y') : '-' }}"
                                        data-nama-bank="{{ $k->kos->pemilik->nama_bank ?? 'Belum Diatur' }}"
                                        data-nomor-rekening="{{ $k->kos->pemilik->nomor_rekening ?? '-' }}"
                                        @if($k->id_kontrak == $selectedKontrak->id_kontrak) selected @endif>
                                        {{ $k->kos->nama_kos }} - Kamar {{ $k->kamar->nomor_kamar }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                        </div>
                    </div>
                @endif

                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm kontrak-info bg-dark-bg/50 p-4 rounded-xl border border-dark-border">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-building text-primary-400 w-5"></i>
                        <div>
                            <div class="text-dark-muted text-xs">Kos</div>
                            <div class="font-medium text-white" id="info-kos">{{ $selectedKontrak->kos->nama_kos }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-door-closed text-green-400 w-5"></i>
                        <div>
                            <div class="text-dark-muted text-xs">Kamar</div>
                            <div class="font-medium text-white" id="info-kamar">{{ $selectedKontrak->kamar->nomor_kamar }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-tag text-yellow-400 w-5"></i>
                        <div>
                            <div class="text-dark-muted text-xs">Harga Sewa</div>
                            <div class="font-medium text-white">Rp <span
                                    id="info-harga">{{ number_format($selectedKontrak->harga_sewa, 0, ',', '.') }}</span>/{{ strtolower($unitLabel) }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar-alt text-blue-400 w-5"></i>
                        <div>
                            <div class="text-dark-muted text-xs">Periode Kontrak</div>
                            <div class="font-medium text-white" id="info-periode">
                                @if($selectedKontrak->tanggal_mulai && $selectedKontrak->tanggal_selesai)
                                    {{ \Carbon\Carbon::parse($selectedKontrak->tanggal_mulai)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($selectedKontrak->tanggal_selesai)->format('d M Y') }}
                                @else
                                    <span class="text-yellow-400">Menunggu pembayaran pertama</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2 flex items-center space-x-2 bg-yellow-900/20 p-3 rounded-lg">
                        <i class="fas fa-clock text-yellow-400 w-5"></i>
                        <div>
                            <div class="text-dark-muted text-xs">Tenggat Pembayaran</div>
                            <div class="font-medium text-yellow-300" id="info-grace-period">
                                @if($selectedKontrak->tanggal_selesai)
                                    {{ \Carbon\Carbon::parse($selectedKontrak->tanggal_selesai)->addDays(7)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="text-xs text-dark-muted mt-1">(7 hari setelah kontrak berakhir)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-money-check-alt text-green-400 mr-3"></i>
                    Formulir Pembayaran
                </h2>

                @if($errors->any())
                    <div class="bg-red-900/30 border border-red-800 text-red-300 px-4 py-3 rounded-xl mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <div>
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('penghuni.pembayaran.store') }}" enctype="multipart/form-data"
                    id="paymentForm">
                    @csrf
                    <input type="hidden" id="id_kontrak" name="id_kontrak" value="{{ $selectedKontrak->id_kontrak }}">

                    <div class="space-y-6">

                        <!-- Jumlah Waktu Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-3">Bayar Berapa {{ $unitLabel }}?
                                *</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($paymentOptions as $option)
                                    <label
                                        class="flex items-center p-3 border-2 border-dark-border rounded-xl cursor-pointer hover:border-primary-500/50 bg-dark-bg/50 transition-all duration-200 jumlah-bulan-option group">
                                        <input type="radio" name="jumlah_waktu" value="{{ $option['value'] }}"
                                            data-harga="{{ $option['total'] }}"
                                            data-max-date="{{ $option['max_date'] ? $option['max_date']->format('d M Y') : '' }}"
                                            class="mr-3 jumlah-bulan-radio" @if($loop->first) checked @endif>
                                        <div class="flex-1">
                                            <div class="font-semibold text-white text-sm">{{ $option['label'] }}</div>
                                            <div class="text-xs text-dark-muted">Rp
                                                {{ number_format($option['total'], 0, ',', '.') }}</div>
                                            @if($option['max_date'])
                                                <div class="text-xs text-yellow-400 mt-1 hidden md:block"
                                                    id="max-date-{{ $option['value'] }}">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Max: {{ $option['max_date']->format('d M Y') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div
                                            class="w-6 h-6 rounded-full border-2 border-dark-border group-hover:border-primary-500 flex items-center justify-center">
                                            <div class="w-3 h-3 rounded-full bg-primary-500 hidden radio-checked"></div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Preview Masa Pembayaran -->
                        <div id="masa-pembayaran-preview"
                            class="bg-yellow-900/20 border border-yellow-800/30 rounded-xl p-4">
                            <h3 class="font-semibold text-yellow-300 mb-3 flex items-center">
                                <i class="fas fa-calendar-day mr-2"></i>
                                Masa Pembayaran
                            </h3>
                            <div class="text-sm text-yellow-200 grid grid-cols-2 gap-2">
                                <div>
                                    <div class="text-yellow-400/80 text-xs">Mulai</div>
                                    <div id="preview-mulai">{{ $unitLabel == 'Hari' ? 'Hari berikutnya yang belum dibayar' : ($unitLabel == 'Minggu' ? 'Minggu berikutnya yang belum dibayar' : ($unitLabel == 'Tahun' ? 'Tahun berikutnya yang belum dibayar' : 'Bulan berikutnya yang belum dibayar')) }}</div>
                                </div>
                                <div>
                                    <div class="text-yellow-400/80 text-xs">Selesai</div>
                                    <div id="preview-selesai" class="font-medium">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Pembayaran Summary -->
                        <div
                            class="bg-gradient-to-r from-green-900/30 to-emerald-900/30 border border-green-800/30 rounded-xl p-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-green-300 mb-1">Harga/{{ $unitLabel }}:</p>
                                    <p id="harga-per-bulan" class="text-xl md:text-2xl font-bold text-white">
                                        Rp {{ number_format($selectedKontrak->harga_sewa, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-green-300 mb-1">Total Pembayaran:</p>
                                    <p id="total-bayar" class="text-xl md:text-2xl font-bold text-green-400">Rp
                                        {{ number_format($selectedKontrak->harga_sewa, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-3">Metode Pembayaran *</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label
                                    class="flex items-center p-3 border border-dark-border rounded-xl cursor-pointer hover:border-primary-500 bg-dark-bg/50 transition-all duration-200">
                                    <input type="radio" name="metode_pembayaran" value="transfer" class="mr-3" checked>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-900/30 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-university text-blue-400"></i>
                                        </div>
                                        <span class="text-white">Transfer Bank</span>
                                    </div>
                                    <div
                                        class="ml-auto w-4 h-4 rounded-full border-2 border-dark-border flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full bg-primary-500 radio-checked"></div>
                                    </div>
                                </label>
                                <label
                                    class="flex items-center p-3 border border-dark-border rounded-xl cursor-pointer hover:border-green-500 bg-dark-bg/50 transition-all duration-200">
                                    <input type="radio" name="metode_pembayaran" value="qris" class="mr-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-green-900/30 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-qrcode text-green-400"></i>
                                        </div>
                                        <span class="text-white">QRIS</span>
                                    </div>
                                    <div
                                        class="ml-auto w-4 h-4 rounded-full border-2 border-dark-border flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full bg-green-500 hidden"></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Info Rekening -->
                        <div class="bg-green-900/20 border border-green-800/30 rounded-xl p-5">
                            <h3 class="font-semibold text-green-300 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informasi Transfer
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <div class="text-green-400/80 text-xs mb-1">Bank</div>
                                    <div class="text-white font-medium" id="info-nama-bank">
                                        {{ $selectedKontrak->kos->pemilik->nama_bank ?? 'Belum Diatur' }}</div>
                                </div>
                                <div>
                                    <div class="text-green-400/80 text-xs mb-1">No. Rekening</div>
                                    <div class="text-white font-medium" id="info-nomor-rekening">
                                        {{ $selectedKontrak->kos->pemilik->nomor_rekening ?? '-' }}</div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="text-green-400/80 text-xs mb-1">Atas Nama</div>
                                    <div class="text-white font-medium" id="info-pemilik">
                                        {{ $selectedKontrak->kos->pemilik->nama }}</div>
                                </div>
                            </div>
                            <div class="mt-3 text-xs text-green-400/80 bg-green-900/10 p-2 rounded-lg">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Harap transfer sesuai jumlah dan tambahkan kode unik
                            </div>
                        </div>

                        <!-- Bukti Pembayaran -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-white mb-3">
                                Upload Bukti Pembayaran *
                                <span class="text-xs text-dark-muted">(Format: JPG, PNG, maksimal 2MB)</span>
                            </label>
                            <div id="upload-area"
                                class="mt-1 border-2 border-dashed border-dark-border rounded-xl hover:border-primary-500/50 transition-all duration-200 cursor-pointer bg-dark-bg/50 overflow-hidden">
                                <div class="p-6 text-center">
                                    <div
                                        class="w-16 h-16 bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-primary-400"></i>
                                    </div>
                                    <div class="flex text-sm text-dark-muted justify-center">
                                        <label for="bukti_pembayaran"
                                            class="relative cursor-pointer bg-dark-card px-4 py-2 rounded-lg font-medium text-primary-400 hover:text-primary-300 transition">
                                            <span>Klik untuk upload file</span>
                                            <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="sr-only"
                                                accept="image/jpeg,image/png,image/jpg" required>
                                        </label>
                                        <p class="pl-3 self-center">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-dark-muted mt-2">PNG, JPG, JPEG up to 2MB</p>
                                    <div id="file-name" class="text-sm mt-4"></div>
                                    <div id="file-error" class="text-sm text-red-400 mt-2 hidden"></div>
                                    <div id="preview-container" class="mt-4"></div>
                                </div>
                            </div>
                            @error('bukti_pembayaran')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Penting -->
                        <div class="bg-blue-900/20 border border-blue-800/30 rounded-xl p-5">
                            <h3 class="font-semibold text-blue-300 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informasi Pembayaran
                            </h3>
                            <ul class="space-y-2">
                                <li class="flex items-start text-sm text-blue-200">
                                    <i class="fas fa-calendar text-blue-400 mr-3 mt-0.5"></i>
                                    <span>Anda dapat membayar maksimal <strong class="text-white">{{ $maxLimit }} {{ strtolower($unitLabel) }} ke
                                            depan</strong></span>
                                </li>
                                <li class="flex items-start text-sm text-blue-200">
                                    <i class="fas fa-clock text-blue-400 mr-3 mt-0.5"></i>
                                    <span>Setelah kontrak berakhir, ada <strong class="text-white">grace period 7
                                            hari</strong> untuk membayar</span>
                                </li>
                                <li class="flex items-start text-sm text-blue-200">
                                    <i class="fas fa-redo text-blue-400 mr-3 mt-0.5"></i>
                                    <span>Pembayaran advance akan <strong class="text-white">memperpanjang kontrak
                                            otomatis</strong></span>
                                </li>
                                <li class="flex items-start text-sm text-blue-200">
                                    <i class="fas fa-times-circle text-blue-400 mr-3 mt-0.5"></i>
                                    <span>Setelah grace period, harus perpanjang kontrak untuk membayar lagi</span>
                                </li>
                                <li class="flex items-start text-sm text-blue-200">
                                    <i class="fas fa-check-circle text-blue-400 mr-3 mt-0.5"></i>
                                    <span>Satu pembayaran = satu bukti transfer untuk multiple {{ strtolower($unitLabel) }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('penghuni.pembayaran.index') }}"
                                class="flex-1 bg-dark-bg border border-dark-border text-white px-6 py-3 rounded-xl hover:border-dark-border/80 hover:bg-dark-bg/80 transition-all duration-200 font-medium text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-primary-500 to-indigo-500 text-white px-6 py-3 rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                                <i class="fas fa-upload mr-2"></i>
                                Upload Bukti Bayar
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========================================
            // INISIALISASI ELEMEN DOM
            // ========================================

            // Kontrak dropdown
            const kontrakSelect = document.getElementById('kontrak-select');
            const idKontrakInput = document.getElementById('id_kontrak');

            // Info Kontrak Elements
            const infoKos = document.getElementById('info-kos');
            const infoKamar = document.getElementById('info-kamar');
            const infoHarga = document.getElementById('info-harga');
            const infoPeriode = document.getElementById('info-periode');
            const infoGrace = document.getElementById('info-grace-period');
            const infoPemilik = document.getElementById('info-pemilik');
            const infoNamaBank = document.getElementById('info-nama-bank');
            const infoNomorRekening = document.getElementById('info-nomor-rekening');

            // Pembayaran Elements
            const hargaPerBulanElement = document.getElementById('harga-per-bulan');
            const totalBayarElement = document.getElementById('total-bayar');
            const previewSelesai = document.getElementById('preview-selesai');
            const jumlahTimeInputs = document.querySelectorAll('input[name="jumlah_waktu"]');
            const unitLabel = "{{ $unitLabel ?? 'Bulan' }}";

            // File Upload Elements
            const fileInput = document.getElementById('bukti_pembayaran');
            const uploadArea = document.getElementById('upload-area');
            const fileNameDisplay = document.getElementById('file-name');
            const fileErrorDisplay = document.getElementById('file-error');
            const previewContainer = document.getElementById('preview-container');


            // ========================================
            // HELPER FUNCTIONS
            // ========================================

            /**
             * Format angka ke format Rupiah
             */
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            /**
             * Mendapatkan harga kontrak yang sedang aktif/dipilih
             */
            function getCurrentHarga() {
                if (kontrakSelect) {
                    const selectedOption = kontrakSelect.options[kontrakSelect.selectedIndex];
                    return parseInt(selectedOption.getAttribute('data-harga'));
                }
                const hargaText = infoHarga.textContent.replace(/\./g, '');
                return parseInt(hargaText) || 0;
            }

            /**
             * Update radio button visual states
             */
            function updateRadioVisuals() {
                // Update jumlah bulan radio visuals
                document.querySelectorAll('.jumlah-bulan-option').forEach(option => {
                    const radio = option.querySelector('input[type="radio"]');
                    const checkIndicator = option.querySelector('.radio-checked');
                    if (radio && checkIndicator) {
                        if (radio.checked) {
                            option.classList.add('border-primary-500', 'bg-primary-900/20');
                            option.classList.remove('border-dark-border');
                            checkIndicator.classList.remove('hidden');
                        } else {
                            option.classList.remove('border-primary-500', 'bg-primary-900/20');
                            option.classList.add('border-dark-border');
                            checkIndicator.classList.add('hidden');
                        }
                    }
                });

                // Update metode pembayaran radio visuals
                document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
                    const label = radio.closest('label');
                    const checkIndicator = label?.querySelector('.radio-checked');
                    if (checkIndicator) {
                        if (radio.checked) {
                            label.classList.add('border-primary-500');
                            checkIndicator.classList.remove('hidden');
                        } else {
                            label.classList.remove('border-primary-500');
                            checkIndicator.classList.add('hidden');
                        }
                    }
                });
            }


            // ========================================
            // FUNGSI UPDATE INFO KONTRAK
            // ========================================

            /**
             * Update semua informasi kontrak berdasarkan pilihan dropdown
             */
            function updateKontrakInfo() {
                if (!kontrakSelect) return;

                const selectedOption = kontrakSelect.options[kontrakSelect.selectedIndex];

                // Ambil data dari option yang dipilih
                const harga = parseInt(selectedOption.getAttribute('data-harga'));
                const kos = selectedOption.getAttribute('data-kos');
                const kamar = selectedOption.getAttribute('data-kamar');
                const mulai = selectedOption.getAttribute('data-mulai');
                const selesai = selectedOption.getAttribute('data-selesai');
                const pemilik = selectedOption.getAttribute('data-pemilik');
                const gracePeriod = selectedOption.getAttribute('data-grace-period');
                const namaBank = selectedOption.getAttribute('data-nama-bank');
                const nomorRekening = selectedOption.getAttribute('data-nomor-rekening');
                const kontrakId = selectedOption.value;

                // Update semua elemen info
                if (infoKos) infoKos.textContent = kos;
                if (infoKamar) infoKamar.textContent = kamar;
                if (infoHarga) infoHarga.textContent = formatRupiah(harga);
                if (infoPeriode) infoPeriode.textContent = `${mulai} - ${selesai}`;
                if (infoGrace) infoGrace.textContent = gracePeriod;
                if (infoPemilik) infoPemilik.textContent = pemilik;
                if (infoNamaBank) infoNamaBank.textContent = namaBank;
                if (infoNomorRekening) infoNomorRekening.textContent = nomorRekening;
                if (hargaPerBulanElement) hargaPerBulanElement.textContent = `Rp ${formatRupiah(harga)}`;
                if (idKontrakInput) idKontrakInput.value = kontrakId;

                // Update total pembayaran
                updateTotalBayar(harga);
            }


            // ========================================
            // FUNGSI UPDATE TOTAL BAYAR
            // ========================================

            /**
             * Update total bayar berdasarkan jumlah waktu yang dipilih
             */
            function updateTotalBayar(hargaPerUnit) {
                const selectedJumlah = document.querySelector('input[name="jumlah_waktu"]:checked');

                if (!selectedJumlah) {
                    return;
                }

                const jumlahWaktu = parseInt(selectedJumlah.value);
                const preCalcTotal = selectedJumlah.getAttribute('data-harga');
                const total = preCalcTotal ? parseInt(preCalcTotal) : (jumlahWaktu * hargaPerUnit);

                // Update tampilan total
                if (totalBayarElement) {
                    totalBayarElement.textContent = `Rp ${formatRupiah(total)}`;
                }

                // Update preview masa pembayaran
                if (previewSelesai) {
                     const maxDate = selectedJumlah.getAttribute('data-max-date');
                     if (maxDate) {
                         previewSelesai.innerHTML = `Sampai <span class="text-white">${maxDate}</span>`;
                     } else {
                         previewSelesai.textContent = `${jumlahWaktu} ${unitLabel} ke depan`;
                     }
                }

                updateRadioVisuals();
            }


            // ========================================
            // FUNGSI FILE UPLOAD PREVIEW
            // ========================================

            /**
             * Reset tampilan upload area
             */
            function resetUploadDisplay() {
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = '';
                    fileNameDisplay.className = 'text-sm mt-4';
                }

                if (fileErrorDisplay) {
                    fileErrorDisplay.textContent = '';
                    fileErrorDisplay.classList.add('hidden');
                }

                if (uploadArea) {
                    uploadArea.classList.remove('border-primary-500', 'border-red-500');
                    uploadArea.classList.add('border-dark-border');
                }

                if (previewContainer) {
                    previewContainer.innerHTML = '';
                }
            }

            /**
             * Tampilkan error upload
             */
            function showUploadError(message) {
                if (fileErrorDisplay) {
                    fileErrorDisplay.innerHTML = `
                        <div class="flex items-center text-red-400">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>${message}</span>
                        </div>
                    `;
                    fileErrorDisplay.classList.remove('hidden');
                }

                if (uploadArea) {
                    uploadArea.classList.add('border-red-500');
                    uploadArea.classList.remove('border-dark-border');
                }
            }

            /**
             * Tampilkan success upload dengan preview
             */
            function showUploadSuccess(file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);

                // Tampilkan nama file
                if (fileNameDisplay) {
                    fileNameDisplay.innerHTML = `
                        <div class="flex items-center text-green-400">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="font-medium">File berhasil dipilih</span>
                        </div>
                        <div class="text-sm text-dark-muted mt-1 ml-7">
                            ${fileName} (${fileSize} MB)
                        </div>
                    `;
                }

                // Update upload area styling
                if (uploadArea) {
                    uploadArea.classList.add('border-primary-500');
                    uploadArea.classList.remove('border-dark-border');
                }

                // Buat preview gambar
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewContainer) {
                        previewContainer.innerHTML = `
                            <div class="relative inline-block">
                                <img src="${e.target.result}" 
                                     class="max-w-full max-h-64 rounded-lg shadow-lg border-2 border-primary-500/50">
                                <div class="absolute top-2 right-2 bg-dark-card/80 backdrop-blur-sm rounded-full w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-image text-primary-400"></i>
                                </div>
                            </div>
                        `;
                    }
                };

                reader.readAsDataURL(file);
            }

            /**
             * Validasi file upload
             */
            function validateFile(file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                // Validasi tipe file
                if (!allowedTypes.includes(file.type)) {
                    return {
                        valid: false,
                        error: 'Format file tidak didukung. Gunakan JPG, PNG, atau JPEG.'
                    };
                }

                // Validasi ukuran file
                if (file.size > maxSize) {
                    return {
                        valid: false,
                        error: 'Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil.'
                    };
                }

                return { valid: true };
            }

            /**
             * Handle file input change
             */
            function handleFileChange(e) {
                resetUploadDisplay();

                const files = e.target.files;

                if (!files || files.length === 0) {
                    return;
                }

                const file = files[0];

                // Validasi file
                const validation = validateFile(file);

                if (!validation.valid) {
                    showUploadError(validation.error);
                    e.target.value = '';
                    return;
                }

                // Tampilkan success dan preview
                showUploadSuccess(file);
            }


            // ========================================
            // DRAG & DROP FUNCTIONALITY
            // ========================================

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                if (uploadArea) {
                    uploadArea.classList.add('border-primary-500', 'bg-primary-900/10');
                }
            }

            function unhighlight(e) {
                if (uploadArea) {
                    uploadArea.classList.remove('border-primary-500', 'bg-primary-900/10');
                }
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0 && fileInput) {
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            /**
             * Setup drag and drop
             */
            function setupDragAndDrop() {
                if (!uploadArea) return;

                // Prevent defaults
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, preventDefaults, false);
                });

                // Highlight on drag
                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, highlight, false);
                });

                // Unhighlight on leave/drop
                ['dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, unhighlight, false);
                });

                // Handle drop
                uploadArea.addEventListener('drop', handleDrop, false);


            }


            // ========================================
            // EVENT LISTENERS
            // ========================================

            /**
             * Setup semua event listeners
             */
            function setupEventListeners() {
                // Dropdown kontrak
                if (kontrakSelect) {
                    kontrakSelect.addEventListener('change', function() {
                         window.location.href = '?id_kontrak=' + this.value;
                    });
                }

                // Radio button jumlah waktu
                jumlahTimeInputs.forEach(input => {
                    input.addEventListener('change', function () {
                        const harga = getCurrentHarga();
                        updateTotalBayar(harga);
                    });
                });

                // Radio button metode pembayaran
                document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
                    radio.addEventListener('change', updateRadioVisuals);
                });

                // File input
                if (fileInput) {
                    fileInput.addEventListener('change', handleFileChange);
                }

                // Form submission validation
                const paymentForm = document.getElementById('paymentForm');
                if (paymentForm) {
                    paymentForm.addEventListener('submit', function (e) {
                        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                            e.preventDefault();
                            showUploadError('Harap pilih file bukti pembayaran terlebih dahulu');
                            return false;
                        }

                        const file = fileInput.files[0];
                        const validation = validateFile(file);

                        if (!validation.valid) {
                            e.preventDefault();
                            showUploadError(validation.error);
                            return false;
                        }

                        // Show loading state
                        const submitBtn = paymentForm.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengunggah...';
                            submitBtn.disabled = true;
                        }
                    });
                }

                // Drag and drop
                setupDragAndDrop();
            }


            // ========================================
            // INISIALISASI
            // ========================================

            /**
             * Inisialisasi aplikasi
             */
            function init() {
                // Setup event listeners
                setupEventListeners();

                // Update visual state pertama kali
                updateRadioVisuals();

                // Update info kontrak pertama kali (jika ada dropdown)
                if (kontrakSelect) {
                    updateKontrakInfo();
                } else {
                    // Jika tidak ada dropdown, update total bayar langsung
                    const harga = getCurrentHarga();
                    updateTotalBayar(harga);
                }
            }

            // Jalankan inisialisasi
            init();
        });
    </script>

@endsection