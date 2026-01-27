@extends('layouts.app')

@section('title', 'Kelola Kos - AyoKos')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <div class="bg-dark-card/50 border border-dark-border rounded-xl p-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('pemilik.dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-dark-muted hover:text-white transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-dark-muted text-xs mx-2"></i>
                            <a href="{{ route('pemilik.kos.index') }}"
                                class="inline-flex items-center text-sm font-medium text-white">
                                <i class="fas fa-file-contract mr-2"></i>
                                Kelola Kos
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-900/30 to-indigo-900/30 border border-primary-800/30 rounded-2xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                        <i class="fas fa-home mr-3"></i>
                        Kelola Kos</h1>
                    <p class="text-primary-200">Kelola semua properti kos Anda di satu tempat</p>
                </div>
                <a href="{{ route('pemilik.kos.create') }}"
                    class="mt-4 md:mt-0 px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Kos Baru
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6 mb-6">
            <form method="GET" action="{{ route('pemilik.kos.index') }}">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 transition"
                                placeholder="Cari nama kos, alamat, kecamatan, atau kota...">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-primary-600 to-indigo-600 text-white rounded-xl hover:from-primary-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                            <i class="fas fa-search mr-2"></i>
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('pemilik.kos.index') }}"
                                class="px-6 py-3 bg-dark-border border border-dark-border text-white rounded-xl hover:bg-dark-border/80 transition font-semibold">
                                <i class="fas fa-times mr-2"></i>
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-900/30 border border-green-800/50 text-green-300 px-4 py-3 rounded-xl mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Kos List -->
        @if($kos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kos as $item)
                    <div
                        class="card-hover bg-dark-card border border-dark-border rounded-2xl overflow-hidden transition-all duration-300">
                        <!-- Foto Kos -->
                        <div class="relative h-56 overflow-hidden">
                            @if($item->foto_utama)
                                <img src="{{ asset('storage/' . $item->foto_utama) }}" alt="{{ $item->nama_kos }}"
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-dark-border to-dark-bg flex items-center justify-center">
                                    <i class="fas fa-home text-4xl text-dark-muted"></i>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm
                                    {{ $item->status_kos == 'aktif' ? 'bg-green-900/80 text-green-300' :
                        ($item->status_kos == 'pending' ? 'bg-yellow-900/80 text-yellow-300' :
                            'bg-red-900/80 text-red-300') }}">
                                    {{ ucfirst($item->status_kos) }}
                                </span>
                            </div>

                            <!-- Overlay on Hover -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>

                        <!-- Info Kos -->
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-lg font-semibold text-white truncate">{{ $item->nama_kos }}</h3>
                            </div>

                            <div class="flex items-center text-dark-muted text-sm mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>
                                <span class="line-clamp-1">{{ $item->alamat }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm mb-4">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center text-dark-muted">
                                        <i class="fas fa-bed mr-2 text-green-400"></i>
                                        {{ $item->kamar_count }} Kamar
                                    </span>
                                    <span class="flex items-center text-dark-muted">
                                        <i class="fas fa-users mr-2 text-blue-400"></i>
                                        {{ ucfirst($item->jenis_kos) }}
                                    </span>
                                </div>
                                <span class="font-semibold text-primary-300">
                                    {{ ucfirst($item->tipe_sewa) }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center pt-4 border-t border-dark-border">
                                <!-- Left Side: Detail Button -->
                                <a href="{{ route('pemilik.kos.show', $item->id_kos) }}"
                                    class="inline-flex items-center text-primary-400 hover:text-primary-300 font-medium group">
                                    <i class="fas fa-eye mr-2 group-hover:scale-110 transition-transform"></i>
                                    Detail
                                </a>

                                <!-- Right Side: Edit, Kamar, Delete -->
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('pemilik.kos.edit', $item->id_kos) }}"
                                        class="inline-flex items-center text-blue-400 hover:text-blue-300 font-medium group">
                                        <i class="fas fa-edit mr-2 group-hover:scale-110 transition-transform"></i>
                                        Edit
                                    </a>

                                    <a href="{{ route('pemilik.kamar.index', ['kos' => $item->id_kos]) }}"
                                        class="inline-flex items-center text-green-400 hover:text-green-300 font-medium group">
                                        <i class="fas fa-bed mr-2 group-hover:scale-110 transition-transform"></i>
                                        Kamar
                                    </a>

                                    <button type="button"
                                        onclick="showDeleteModal('{{ route('pemilik.kos.destroy', $item->id_kos) }}', '{{ $item->nama_kos }}')"
                                        class="inline-flex items-center text-red-400 hover:text-red-300 font-medium group">
                                        <i class="fas fa-trash mr-2 group-hover:scale-110 transition-transform"></i>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-dark-card border border-dark-border rounded-2xl p-8">
                <div class="text-center">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-primary-900/30 to-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-home text-4xl text-primary-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Belum Ada Kos</h3>
                    <p class="text-dark-muted mb-6 max-w-md mx-auto">
                        Mulai dengan menambahkan kos pertama Anda untuk mengelola properti Anda
                    </p>
                    <a href="{{ route('pemilik.kos.create') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Kos Pertama
                    </a>
                </div>
            </div>
        @endif

        <!-- Table Footer -->
        @if($kos->hasPages())
            <div class="px-6 py-4 border-t border-dark-border">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-dark-muted">
                        Menampilkan {{ $kos->firstItem() }} - {{ $kos->lastItem() }} dari {{ $kos->total() }} kos
                    </div>
                    <div class="flex space-x-2">
                        {{ $kos->links('vendor.pagination.custom-dark') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Back to Dashboard -->
    <div class="flex justify-center pt-6">
        <a href="{{ route('pemilik.dashboard') }}"
            class="inline-flex items-center px-5 py-2.5 bg-dark-card border border-dark-border text-white rounded-xl hover:border-primary-500 hover:text-primary-300 transition-all duration-300 group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Dashboard
        </a>
    </div>
    </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="relative bg-dark-card border border-dark-border rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
            <div class="p-6 text-center">
                <div class="mb-4 inline-block">
                    <div class="w-16 h-16 rounded-full bg-red-900/30 flex items-center justify-center mx-auto">
                        <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Hapus Kos?</h3>
                <p class="text-dark-muted mb-1">Apakah Anda yakin ingin menghapus <span id="kosNama"
                        class="font-semibold text-white"></span>?</p>
                <p class="text-red-400 text-sm mb-6">Semua data kamar dan kontrak yang terkait dengan kos ini juga akan
                    terhapus secara permanen.</p>

                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-5 py-2.5 bg-dark-border text-white rounded-xl hover:bg-dark-border/80 transition">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl hover:from-red-600 hover:to-rose-700 transition shadow-lg shadow-red-900/20">
                            Ya, Hapus Kos
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(action, nama) {
            document.getElementById('deleteForm').action = action;
            document.getElementById('kosNama').textContent = nama;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close on ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endsection