@extends('layouts.app')

@section('title', 'Dashboard Pemilik - AyoKos')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                        <i class="fas fa-home mr-3"></i>
                        Selamat datang, {{ $user->nama }}! 👋</h1>
                    <p class="text-dark-muted">Kelola properti kos Anda dengan mudah dan efisien</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-900/30 text-primary-300 border border-primary-700/30">
                        <i class="fas fa-user-tie mr-2"></i>
                        Pemilik Kos
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Kos Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-primary-900/30">
                        <i class="fas fa-home text-primary-400 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium px-2 py-1 rounded-full bg-primary-900/20 text-primary-300">
                        {{ $statistics['total_kos'] > 0 ? '+' . $statistics['total_kos'] : '0' }}
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">{{ $statistics['total_kos'] }}</h3>
                <p class="text-sm text-dark-muted">Total Kos</p>
            </div>

            <!-- Total Kamar Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-green-900/30">
                        <i class="fas fa-bed text-green-400 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium px-2 py-1 rounded-full bg-green-900/20 text-green-300">
                        {{ $statistics['total_kamar'] > 0 ? '+' . $statistics['total_kamar'] : '0' }}
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">{{ $statistics['total_kamar'] }}</h3>
                <p class="text-sm text-dark-muted">Total Kamar</p>
            </div>

            <!-- Kamar Tersedia Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-blue-900/30">
                        <i class="fas fa-door-open text-blue-400 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium px-2 py-1 rounded-full bg-blue-900/20 text-blue-300">
                        {{ $statistics['kamar_tersedia'] > 0 ? '+' . $statistics['kamar_tersedia'] : '0' }}
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">{{ $statistics['kamar_tersedia'] }}</h3>
                <p class="text-sm text-dark-muted">Kamar Tersedia</p>
            </div>

            <!-- Pendapatan Bulan Ini Card -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-purple-900/30">
                        <i class="fas fa-wallet text-purple-400 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium px-2 py-1 rounded-full bg-purple-900/20 text-purple-300">
                        Bulan Ini
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                <p class="text-sm text-dark-muted">Pendapatan Bulan Ini</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-400 mr-3"></i>
                Aksi Cepat
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <a href="{{ route('pemilik.kos.index') }}"
                    class="bg-gradient-to-br from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-home text-lg mb-1"></i>
                    <span class="text-sm font-medium">Kelola Kos</span>
                </a>
                <a href="{{ route('pemilik.kamar.index') }}"
                    class="bg-gradient-to-br from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-bed text-lg mb-1"></i>
                    <span class="text-sm font-medium">Kelola Kamar</span>
                </a>
                <a href="{{ route('pemilik.kontrak.index') }}"
                    class="bg-gradient-to-br from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-file-contract text-lg mb-1"></i>
                    <span class="text-sm font-medium">Kelola Kontrak</span>
                </a>
                <a href="{{ route('pemilik.pembayaran.index') }}"
                    class="bg-gradient-to-br from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-credit-card text-lg mb-1"></i>
                    <span class="text-sm font-medium">Pembayaran</span>
                </a>
                <a href="{{ route('pemilik.analisis.index') }}"
                    class="bg-gradient-to-br from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-center py-3 rounded-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <i class="fas fa-chart-bar text-lg mb-1"></i>
                    <span class="text-sm font-medium">Analisis Data</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kos Saya Section -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-building text-primary-400 mr-3"></i>
                        Kos Saya
                    </h2>
                    <a href="{{ route('pemilik.kos.create') }}"
                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah
                    </a>
                </div>

                @if($kos->count() > 0)
                    <div class="space-y-4">
                        @foreach($kos->take(3) as $k)
                                <div
                                    class="bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-primary-500/50 transition-all duration-300">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="font-semibold text-white">{{ $k->nama_kos }}</h3>
                                                <span class="text-xs px-2 py-1 rounded-full 
                                                    {{ $k->status_kos == 'aktif' ? 'bg-green-900/30 text-green-300' :
                            ($k->status_kos == 'pending' ? 'bg-yellow-900/30 text-yellow-300' :
                                'bg-red-900/30 text-red-300') }}">
                                                    {{ ucfirst($k->status_kos) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-dark-muted mb-3">{{ $k->alamat }}</p>
                                            <div class="flex items-center space-x-4 text-xs">
                                                <span class="flex items-center text-dark-muted">
                                                    <i class="fas fa-bed mr-1"></i>
                                                    {{ $k->kamar_count }} Kamar
                                                </span>
                                                <span class="flex items-center text-dark-muted">
                                                    <i class="fas fa-users mr-1"></i>
                                                    {{ $k->jenis_kos }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ route('pemilik.kos.show', $k->id_kos) }}"
                                                class="p-2 text-green-400 hover:text-green-300 hover:bg-green-900/20 rounded-lg transition">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pemilik.kos.edit', $k->id_kos) }}"
                                                class="p-2 text-primary-400 hover:text-primary-300 hover:bg-primary-900/20 rounded-lg transition">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        @endforeach

                        @if($kos->count() > 3)
                            <div class="text-center pt-2">
                                <a href="{{ route('pemilik.kos.index') }}"
                                    class="inline-flex items-center text-primary-400 hover:text-primary-300 text-sm font-medium">
                                    Lihat semua {{ $kos->count() }} kos
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-home text-primary-400 text-2xl"></i>
                        </div>
                        <p class="text-dark-muted mb-3">Belum ada kos terdaftar</p>
                        <a href="{{ route('pemilik.kos.create') }}"
                            class="text-primary-400 hover:text-primary-300 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah kos pertama Anda
                        </a>
                    </div>
                @endif
            </div>

            <!-- Kamar Terbaru Section -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-door-closed text-green-400 mr-3"></i>
                        Kamar Terbaru
                    </h2>
                    <a href="{{ route('pemilik.kamar.create') }}"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah
                    </a>
                </div>

                @if($kamar->count() > 0)
                    <div class="space-y-4">
                        @foreach($kamar->take(3) as $km)
                                <div
                                    class="bg-dark-bg/50 border border-dark-border rounded-xl p-4 hover:border-green-500/50 transition-all duration-300">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="font-semibold text-white">Kamar {{ $km->nomor_kamar }}</h3>
                                                <span class="text-xs px-2 py-1 rounded-full 
                                                    {{ $km->status_kamar == 'tersedia' ? 'bg-green-900/30 text-green-300' :
                            ($km->status_kamar == 'terisi' ? 'bg-blue-900/30 text-blue-300' :
                                'bg-yellow-900/30 text-yellow-300') }}">
                                                    {{ ucfirst($km->status_kamar) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-dark-muted mb-2">{{ $km->kos->nama_kos }}</p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-dark-muted">
                                                    {{ $km->tipe_kamar }}
                                                </span>
                                                <span class="text-sm font-bold text-white">
                                                    Rp {{ number_format($km->harga, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach

                        @if($kamar->count() > 3)
                            <div class="text-center pt-2">
                                <a href="{{ route('pemilik.kamar.index') }}"
                                    class="inline-flex items-center text-green-400 hover:text-green-300 text-sm font-medium">
                                    Lihat semua {{ $kamar->count() }} kamar
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bed text-green-400 text-2xl"></i>
                        </div>
                        <p class="text-dark-muted mb-3">Belum ada kamar terdaftar</p>
                        <a href="{{ route('pemilik.kamar.create') }}"
                            class="text-green-400 hover:text-green-300 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah kamar pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Permohonan Pending -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-clock text-yellow-400 mr-3"></i>
                        Permohonan Pending
                    </h2>
                    <span class="bg-yellow-900/30 text-yellow-300 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $kontrakPending->count() }} menunggu
                    </span>
                </div>

                @if($kontrakPending->count() > 0)
                    <div class="space-y-4">
                        @foreach($kontrakPending->take(3) as $kontrak)
                            <div class="bg-yellow-900/10 border border-yellow-800/30 rounded-xl p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="font-semibold text-white">{{ $kontrak->penghuni->nama }}</h3>
                                            <span class="text-xs text-dark-muted">
                                                {{ $kontrak->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-dark-muted mb-3">
                                            {{ $kontrak->kos->nama_kos }} - Kamar {{ $kontrak->kamar->nomor_kamar }}
                                        </p>
                                        <div class="flex space-x-2">
                                            <button onclick="showApproveModal('{{ route('pemilik.kontrak.approve', $kontrak->id_kontrak) }}', '{{ $kontrak->penghuni->nama ?? 'Penghuni' }}')"
                                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                                                <i class="fas fa-check mr-1"></i>
                                                Setujui
                                            </button>
                                            <button
                                                onclick="showRejectModal({{ $kontrak->id_kontrak }}, '{{ $kontrak->penghuni->nama ?? 'Penghuni' }}')"
                                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                                                <i class="fas fa-times mr-1"></i>
                                                Tolak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($kontrakPending->count() > 3)
                            <div class="text-center pt-2">
                                <a href="{{ route('pemilik.kontrak.index') }}"
                                    class="inline-flex items-center text-yellow-400 hover:text-yellow-300 text-sm font-medium">
                                    Lihat semua {{ $kontrakPending->count() }} permohonan
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-yellow-400 text-2xl"></i>
                        </div>
                        <p class="text-dark-muted">Tidak ada permohonan pending</p>
                    </div>
                @endif
            </div>

            <!-- Pembayaran Terbaru -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-credit-card text-purple-400 mr-3"></i>
                    Pembayaran Terbaru
                </h2>

                @if($pembayaranTerbaru->count() > 0)
                    <div class="space-y-4">
                        @foreach($pembayaranTerbaru->take(5) as $pembayaran)
                                <div
                                    class="flex items-center justify-between border-b border-dark-border pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full 
                                            {{ $pembayaran->status_pembayaran == 'lunas' ? 'bg-green-900/30' :
                            ($pembayaran->status_pembayaran == 'pending' ? 'bg-yellow-900/30' :
                                ($pembayaran->status_pembayaran == 'terlambat' ? 'bg-red-900/30' :
                                    'bg-gray-900/30')) }} flex items-center justify-center">
                                            <i class="fas fa-{{ $pembayaran->status_pembayaran == 'lunas' ? 'check' : 'clock' }} 
                                                {{ $pembayaran->status_pembayaran == 'lunas' ? 'text-green-400' :
                            ($pembayaran->status_pembayaran == 'pending' ? 'text-yellow-400' :
                                ($pembayaran->status_pembayaran == 'terlambat' ? 'text-red-400' :
                                    'text-gray-400')) }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $pembayaran->penghuni->nama }}</p>
                                            <p class="text-xs text-dark-muted">{{ $pembayaran->kontrak->kos->nama_kos }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-white">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                                        <span class="inline-block px-2 py-1 text-xs rounded-full 
                                            {{ $pembayaran->status_pembayaran == 'lunas' ? 'bg-green-900/30 text-green-300' :
                            ($pembayaran->status_pembayaran == 'pending' ? 'bg-yellow-900/30 text-yellow-300' :
                                ($pembayaran->status_pembayaran == 'terlambat' ? 'bg-red-900/30 text-red-300' :
                                    'bg-gray-900/30 text-gray-300')) }}">
                                            {{ ucfirst($pembayaran->status_pembayaran) }}
                                        </span>
                                    </div>
                                </div>
                        @endforeach

                        @if($pembayaranTerbaru->count() > 5)
                            <div class="text-center pt-4">
                                <a href="{{ route('pemilik.pembayaran.index') }}"
                                    class="inline-flex items-center text-purple-400 hover:text-purple-300 text-sm font-medium">
                                    Lihat semua pembayaran
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-credit-card text-purple-400 text-2xl"></i>
                        </div>
                        <p class="text-dark-muted">Belum ada pembayaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success Notification Modal -->


    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border border-dark-border w-96 shadow-2xl rounded-2xl bg-dark-card">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-times-circle text-red-400 mr-2"></i>
                    Tolak Permohonan Kontrak
                </h3>
                <p class="text-sm text-dark-muted mb-4" id="rejectUserName">
                    Alasan penolakan untuk: <span class="text-white font-medium"></span>
                </p>
                
                <form method="POST" action="" id="rejectForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-white mb-2">Alasan Penolakan *</label>
                        <textarea name="alasan_ditolak" 
                                  class="w-full px-3 py-2 bg-dark-bg border border-dark-border text-white rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/30"
                                  rows="4" 
                                  placeholder="Berikan alasan penolakan yang jelas..."
                                  required></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeRejectModal()"
                                class="px-4 py-2 bg-dark-border text-white rounded-lg hover:bg-dark-border/80 transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition shadow-lg">
                            <i class="fas fa-times mr-2"></i>
                            Tolak Kontrak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border border-dark-border w-96 shadow-2xl rounded-2xl bg-dark-card">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                    Setujui Permohonan Kontrak
                </h3>
                <p class="text-sm text-dark-muted mb-4" id="approveUserName">
                    Konfirmasi persetujuan untuk: <span class="text-white font-medium"></span>
                </p>
                
                <form method="POST" action="" id="approveForm">
                    @csrf
                    
                    <p class="text-sm text-gray-300 mb-6">
                        Apakah Anda yakin ingin menyetujui kontrak ini? Status kamar akan berubah menjadi terisi dan kontrak akan aktif.
                    </p>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeApproveModal()"
                                class="px-4 py-2 bg-dark-border text-white rounded-lg hover:bg-dark-border/80 transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition shadow-lg">
                            <i class="fas fa-check mr-2"></i>
                            Setujui Kontrak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Reject modal functionality
        function showRejectModal(kontrakId, userName) {
            document.querySelector('#rejectUserName span').textContent = userName;
            document.getElementById('rejectForm').action = '/pemilik/kontrak/' + kontrakId + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectForm').reset();
        }

        // Approve modal functionality
        function showApproveModal(actionUrl, userName) {
            document.querySelector('#approveUserName span').textContent = userName;
            document.getElementById('approveForm').action = actionUrl;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const rejectModal = document.getElementById('rejectModal');
            const approveModal = document.getElementById('approveModal');
            
            if (event.target === rejectModal) {
                closeRejectModal();
            }
            if (event.target === approveModal) {
                closeApproveModal();
            }
        }
    </script>
@endsection