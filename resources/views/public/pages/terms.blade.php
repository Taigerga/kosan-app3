@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - AyoKos')
@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Hero Header -->
    <div class="bg-gradient-to-br from-primary-900/50 to-indigo-900/50 border border-primary-800/30 rounded-2xl p-8 md:p-12">
        <div class="max-w-3xl mx-auto text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-file-contract text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Syarat & Ketentuan</h1>
            <p class="text-lg text-primary-200 max-w-2xl mx-auto">
                Mohon baca dengan seksama syarat dan ketentuan penggunaan platform AyoKos.
            </p>
            <div class="mt-6 text-sm text-primary-300 bg-primary-900/30 px-4 py-2 rounded-lg inline-block">
                <i class="fas fa-clock mr-2"></i>
                Terakhir diperbarui: {{ date('d F Y') }}
            </div>
        </div>
    </div>

    <!-- Important Notice -->
    <div class="bg-yellow-900/20 border border-yellow-800/30 rounded-xl p-5">
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
                <div class="w-10 h-10 bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-white mb-2">Penting</h3>
                <p class="text-yellow-200">
                    Dengan mengakses atau menggunakan platform AyoKos, Anda menyetujui untuk terikat dengan syarat dan ketentuan berikut.
                </p>
            </div>
        </div>
    </div>

    <!-- Terms Content -->
    <div class="bg-dark-card border border-dark-border rounded-2xl p-6 md:p-8">
        <div class="space-y-10">
            <!-- Section 1 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500/20 to-primary-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-primary-400">1</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Definisi</h2>
                        <div class="space-y-3 text-dark-muted">
                            <p><strong class="text-primary-300">"Platform"</strong> mengacu pada website, aplikasi mobile, dan layanan lainnya yang disediakan oleh AyoKos.</p>
                            <p><strong class="text-primary-300">"Pengguna"</strong> adalah individu yang mengakses atau menggunakan Platform, termasuk Penghuni dan Pemilik Kos.</p>
                            <p><strong class="text-primary-300">"Penghuni"</strong> adalah pengguna yang mencari, menyewa, atau tinggal di kos yang terdaftar di Platform.</p>
                            <p><strong class="text-primary-300">"Pemilik Kos"</strong> adalah pengguna yang memiliki, mengelola, atau menyewakan kos melalui Platform.</p>
                            <p><strong class="text-primary-300">"Konten"</strong> mencakup teks, gambar, video, dan materi lainnya yang diunggah ke Platform.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500/20 to-emerald-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-green-400">2</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Pendaftaran dan Akun</h2>
                        <div class="space-y-3 text-dark-muted">
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">1.</span>
                                <span>Anda harus berusia minimal 17 tahun untuk menggunakan Platform ini.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">2.</span>
                                <span>Informasi yang Anda berikan selama pendaftaran harus akurat, lengkap, dan terbaru.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">3.</span>
                                <span>Anda bertanggung jawab penuh atas kerahasiaan informasi akun Anda.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">4.</span>
                                <span>AyoKos berhak menangguhkan atau menghentikan akun yang melanggar syarat dan ketentuan.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">5.</span>
                                <span>Setiap pengguna hanya boleh memiliki satu akun, kecuali dengan izin tertulis dari AyoKos.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-orange-400">3</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Penggunaan Platform</h2>
                        <div class="space-y-4 text-dark-muted">
                            <p class="text-white font-medium">Anda setuju untuk tidak:</p>
                            <ul class="space-y-2 pl-5">
                                <li class="flex items-start">
                                    <i class="fas fa-ban text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Menggunakan Platform untuk tujuan ilegal atau tidak sah</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-ban text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Melanggar hak kekayaan intelektual pihak lain</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-ban text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Mengunggah konten yang mengandung virus atau kode berbahaya</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-ban text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Melakukan scraping atau pengumpulan data secara otomatis tanpa izin</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-ban text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Mengganggu atau mengganggu integritas Platform</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-ban text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Mencoba mendapatkan akses tidak sah ke sistem atau jaringan kami</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-ban text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Menyebarkan informasi palsu atau menyesatkan</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-ban text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Melakukan transaksi di luar Platform untuk menghindari komisi</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 4 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-blue-400">4</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Kontrak Sewa dan Pembayaran</h2>
                        <div class="space-y-4 text-dark-muted">
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">1.</span>
                                <span>Kontrak sewa merupakan perjanjian langsung antara Penghuni dan Pemilik Kos.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">2.</span>
                                <span>AyoKos berperan sebagai platform perantara dan tidak bertanggung jawab atas pelaksanaan kontrak.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">3.</span>
                                <span>Semua transaksi pembayaran harus dilakukan melalui sistem yang disediakan Platform.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">4.</span>
                                <span>Pembatalan kontrak setelah disetujui dikenakan ketentuan yang disepakati dalam kontrak.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">5.</span>
                                <span>AyoKos berhak mengenakan biaya layanan sesuai dengan ketentuan yang berlaku.</span>
                            </div>
                            <div class="bg-primary-900/20 border border-primary-800/30 rounded-xl p-4 mt-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-primary-400 mt-1 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-primary-300 mb-1">Catatan</p>
                                        <p class="text-sm text-primary-200">
                                            Selalu simpan bukti pembayaran dan komunikasi penting selama proses sewa.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 5 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-purple-400">5</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Konten Pengguna</h2>
                        <div class="space-y-3 text-dark-muted">
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">1.</span>
                                <span>Anda mempertahankan kepemilikan atas konten yang Anda unggah ke Platform.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">2.</span>
                                <span>Dengan mengunggah konten, Anda memberikan AyoKos lisensi untuk menggunakan, menampilkan, dan mendistribusikan konten tersebut di Platform.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">3.</span>
                                <span>Anda bertanggung jawab penuh atas konten yang Anda unggah, termasuk keaslian dan legalitasnya.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">4.</span>
                                <span>AyoKos berhak menghapus konten yang melanggar syarat dan ketentuan tanpa pemberitahuan sebelumnya.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">5.</span>
                                <span>Dilarang mengunggah konten yang mengandung materi pornografi, kekerasan, atau diskriminatif.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 6 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500/20 to-red-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-red-400">6</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Batasan Tanggung Jawab</h2>
                        <div class="space-y-4 text-dark-muted">
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">1.</span>
                                <span>Platform disediakan "sebagaimana adanya" tanpa jaminan apapun.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">2.</span>
                                <span>AyoKos tidak bertanggung jawab atas:</span>
                            </div>
                            <ul class="space-y-2 pl-5">
                                <li class="flex items-start">
                                    <i class="fas fa-times text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Keterlambatan atau gangguan dalam layanan</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-times text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Kerugian atau kerusakan yang timbul dari penggunaan Platform</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-times text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Konten yang diunggah oleh pengguna lain</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-times text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Perselisihan antara Penghuni dan Pemilik Kos</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-times text-red-400 mt-1 mr-3 text-sm"></i>
                                    <span>Kerusakan atau kehilangan properti selama masa sewa</span>
                                </li>
                            </ul>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">3.</span>
                                <span>Tanggung jawab AyoKos dibatasi sesuai dengan ketentuan hukum yang berlaku.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 7 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-500/20 to-cyan-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-cyan-400">7</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Perubahan Syarat dan Ketentuan</h2>
                        <div class="space-y-3 text-dark-muted">
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">1.</span>
                                <span>AyoKos berhak mengubah syarat dan ketentuan ini kapan saja.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">2.</span>
                                <span>Perubahan akan diberitahukan melalui Platform atau email.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">3.</span>
                                <span>Penggunaan Platform yang berlanjut setelah perubahan berarti Anda menerima syarat dan ketentuan yang baru.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">4.</span>
                                <span>Tanggal efektif akan dicantumkan pada halaman ini.</span>
                            </div>
                            <div class="bg-dark-border/30 border border-dark-border rounded-xl p-4 mt-4">
                                <div class="flex items-start">
                                    <i class="fas fa-lightbulb text-yellow-400 mt-1 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-300 mb-1">Saran</p>
                                        <p class="text-sm text-dark-muted">
                                            Periksa halaman ini secara berkala untuk mengetahui pembaruan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 8 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-gray-500/20 to-gray-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-gray-400">8</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Hukum yang Berlaku</h2>
                        <div class="space-y-3 text-dark-muted">
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">1.</span>
                                <span>Syarat dan ketentuan ini diatur oleh hukum Republik Indonesia.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">2.</span>
                                <span>Setiap sengketa yang timbul akan diselesaikan melalui jalur musyawarah terlebih dahulu.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">3.</span>
                                <span>Jika musyawarah gagal, sengketa akan diselesaikan melalui pengadilan yang berwenang di Jakarta.</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-primary-400 font-bold mr-2">4.</span>
                                <span>Klausul yang tidak dapat dilaksanakan tidak akan mempengaruhi keberlakuan klausul lainnya.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 9 -->
            <section>
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-indigo-400">9</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white mb-2">Hubungi Kami</h2>
                        <div class="space-y-4 text-dark-muted">
                            <p>Jika Anda memiliki pertanyaan tentang Syarat dan Ketentuan ini, silakan hubungi kami:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-primary-900/30 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-envelope text-primary-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-white mb-1">Email</p>
                                            <p class="text-primary-300">valorant270306@gmail.com</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-dark-bg/50 border border-dark-border rounded-xl p-4">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-primary-900/30 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-headset text-primary-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-white mb-1">Layanan Pelanggan</p>
                                            <p class="text-primary-300">+62 82121730722</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Acceptance Section -->
        <div class="mt-12 pt-8 border-t border-dark-border">
            <div class="bg-green-900/20 border border-green-800/30 rounded-xl p-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-bold text-white mb-4 text-lg">Dengan menggunakan AyoKos, saya menyatakan:</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-green-900/30 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                    <i class="fas fa-check text-green-400 text-sm"></i>
                                </div>
                                <span class="text-green-200">Saya telah membaca dan memahami Syarat & Ketentuan ini</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-green-900/30 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                    <i class="fas fa-check text-green-400 text-sm"></i>
                                </div>
                                <span class="text-green-200">Saya setuju untuk terikat dengan semua ketentuan yang tercantum</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-green-900/30 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                    <i class="fas fa-check text-green-400 text-sm"></i>
                                </div>
                                <span class="text-green-200">Saya akan mematuhi semua peraturan yang berlaku</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-6 md:mt-0 md:ml-8 text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-500/20 to-emerald-600/20 rounded-2xl flex items-center justify-center mb-3">
                            <i class="fas fa-file-signature text-green-400 text-3xl"></i>
                        </div>
                        <p class="text-sm text-green-300 font-medium">Menyetujui</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection