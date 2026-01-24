@extends('layouts.app')

@section('title', 'Kelola Kamar - AyoKos')

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
                            <a href="{{ route('pemilik.kamar.index') }}"
                                class="inline-flex items-center text-sm font-medium text-white">
                                <i class="fas fa-bed mr-2"></i>
                                Kelola Kamar
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
                        <i class="fas fa-bed mr-3"></i>
                        Kelola Kamar</h1>
                    <p class="text-green-100">Kelola semua kamar kos Anda di satu tempat yang terorganisir</p>
                </div>
                <a href="{{ route('pemilik.kamar.create') }}"
                    class="mt-4 md:mt-0 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Kamar Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-900/30 border border-green-800/30 text-green-300 px-4 py-3 rounded-xl mb-6 flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-400"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-filter text-primary-400 mr-3"></i>
                Filter Kamar
            </h2>
            <form method="GET" action="{{ route('pemilik.kamar.index') }}"
                class="space-y-4 md:space-y-0 md:grid md:grid-cols-4 md:gap-4">
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Pilih Kos</label>
                    <div class="relative">
                        <i class="fas fa-home absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                        <select name="kos"
                            class="w-full pl-10 pr-4 py-2.5 bg-dark-bg border border-dark-border text-white rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition">
                            <option value="">Semua Kos</option>
                            @foreach($kos as $k)
                                <option value="{{ $k->id_kos }}" {{ request('kos') == $k->id_kos ? 'selected' : '' }}>
                                    {{ $k->nama_kos }}
                                </option>
                            @endforeach
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Status</label>
                    <div class="relative">
                        <i class="fas fa-circle absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                        <select name="status"
                            class="w-full pl-10 pr-4 py-2.5 bg-dark-bg border border-dark-border text-white rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition">
                            <option value="">Semua Status</option>
                            <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance
                            </option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Tipe Kamar</label>
                    <div class="relative">
                        <i class="fas fa-bed absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-muted"></i>
                        <select name="tipe"
                            class="w-full pl-10 pr-4 py-2.5 bg-dark-bg border border-dark-border text-white rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 appearance-none transition">
                            <option value="">Semua Tipe</option>
                            <option value="Standar" {{ request('tipe') == 'Standar' ? 'selected' : '' }}>Standar</option>
                            <option value="Deluxe" {{ request('tipe') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                            <option value="VIP" {{ request('tipe') == 'VIP' ? 'selected' : '' }}>VIP</option>
                            <option value="Superior" {{ request('tipe') == 'Superior' ? 'selected' : '' }}>Superior</option>
                            <option value="Ekonomi" {{ request('tipe') == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-6 py-2.5 bg-gradient-to-r from-primary-500 to-indigo-500 hover:from-primary-600 hover:to-indigo-600 text-white font-medium rounded-lg transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-filter mr-2"></i>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Kamar -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-green-900/30">
                        <i class="fas fa-bed text-green-400 text-xl"></i>
                    </div>
                     <span class="text-sm font-medium px-2 py-1 rounded-full bg-green-900/20 text-green-300">
                         {{ $stats['tersedia'] }}
                     </span>
                </div>
                 <h3 class="text-2xl font-bold text-white mb-1">{{ $stats['total_kamar'] }}</h3>
                <p class="text-sm text-dark-muted">Total Kamar</p>
            </div>

            <!-- Tersedia -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-blue-900/30">
                        <i class="fas fa-door-open text-blue-400 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium px-2 py-1 rounded-full bg-blue-900/20 text-blue-300">
                        {{ $kamar->where('status_kamar', 'tersedia')->count() }}
                    </span>
                </div>
                 <h3 class="text-2xl font-bold text-white mb-1">{{ $stats['tersedia'] }}</h3>
                <p class="text-sm text-dark-muted">Tersedia</p>
            </div>

            <!-- Terisi -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-purple-900/30">
                        <i class="fas fa-users text-purple-400 text-xl"></i>
                    </div>
                     <span class="text-sm font-medium px-2 py-1 rounded-full bg-purple-900/20 text-purple-300">
                         {{ $stats['terisi'] }}
                     </span>
                </div>
                 <h3 class="text-2xl font-bold text-white mb-1">{{ $stats['terisi'] }}</h3>
                <p class="text-sm text-dark-muted">Terisi</p>
            </div>

            <!-- Maintenance -->
            <div class="card-hover bg-dark-card border border-dark-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-yellow-900/30">
                        <i class="fas fa-tools text-yellow-400 text-xl"></i>
                    </div>
                     <span class="text-sm font-medium px-2 py-1 rounded-full bg-yellow-900/20 text-yellow-300">
                         {{ $stats['maintenance'] }}
                     </span>
                </div>
                 <h3 class="text-2xl font-bold text-white mb-1">{{ $stats['maintenance'] }}
                 </h3>
                <p class="text-sm text-dark-muted">Maintenance</p>
            </div>
        </div>

        <!-- Kamar List -->
        <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-dark-border">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-list mr-3 text-primary-400"></i>
                    Daftar Kamar ({{ $kamar->count() }})
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-dark-border">
                    <thead>
                        <tr class="bg-dark-bg/50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                <i class="fas fa-bed mr-2"></i>Kamar & Kos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Tipe & Fasilitas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                <i class="fas fa-money-bill-wave mr-2"></i>Harga
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                <i class="fas fa-circle mr-2"></i>Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-muted uppercase tracking-wider">
                                <i class="fas fa-edit mr-2"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-border">
                        @forelse($kamar as $item)
                                        <tr class="hover:bg-dark-bg/30 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 w-14 h-14 bg-dark-bg border border-dark-border rounded-lg overflow-hidden">
                                                        @if($item->foto_kamar)
                                                            <img src="{{ asset('storage/' . $item->foto_kamar) }}" alt="Foto Kamar"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full bg-dark-bg flex items-center justify-center">
                                                                <i class="fas fa-bed text-dark-muted text-lg"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-bold text-white">
                                                                Kamar {{ $item->nomor_kamar }}
                                                            </div>
                                                        </div>
                                                        <div class="text-sm text-primary-300 font-medium mt-1">
                                                            {{ $item->kos->nama_kos }}
                                                        </div>
                                                        <div class="text-xs text-dark-muted mt-1">
                                                            <i class="fas fa-ruler-combined mr-1"></i>
                                                            {{ $item->luas_kamar ?? 'N/A' }} •
                                                            <i class="fas fa-user mr-1 ml-2"></i>
                                                            {{ $item->kapasitas }} orang
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="mb-2">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-dark-bg text-primary-300 border border-primary-500/30">
                                                        <i class="fas fa-star mr-1 text-xs"></i>
                                                        {{ $item->tipe_kamar }}
                                                    </span>
                                                </div>
                                                <div class="text-sm text-dark-muted max-w-xs truncate">
                                                    @if($item->fasilitas_kamar)
                                                        @php
                                                            if (is_array($item->fasilitas_kamar)) {
                                                                $fasilitas = $item->fasilitas_kamar;
                                                            } else {
                                                                $fasilitas = json_decode($item->fasilitas_kamar, true) ?? [];
                                                            }
                                                        @endphp

                                                        @if(is_array($fasilitas) && count($fasilitas) > 0)
                                                            @foreach(array_slice($fasilitas, 0, 2) as $fasilitasItem)
                                                                <span class="inline-block text-xs px-2 py-1 rounded-lg bg-dark-border/30 mr-1 mb-1">
                                                                    <i class="fas fa-check text-green-400 mr-1"></i>
                                                                    {{ $fasilitasItem }}
                                                                </span>
                                                            @endforeach
                                                            @if(count($fasilitas) > 2)
                                                                <span class="text-xs text-dark-muted/70">
                                                                    +{{ count($fasilitas) - 2 }} lagi
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-lg font-bold text-white">
                                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                </div>
                                                <div class="text-xs text-dark-muted">
                                                    per bulan
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full 
                                                    {{ $item->status_kamar == 'tersedia' ? 'bg-green-900/30 text-green-300 border border-green-700/30' :
                            ($item->status_kamar == 'terisi' ? 'bg-blue-900/30 text-blue-300 border border-blue-700/30' :
                                'bg-yellow-900/30 text-yellow-300 border border-yellow-700/30') }}">
                                                    <i class="fas 
                                                        {{ $item->status_kamar == 'tersedia' ? 'fa-door-open' :
                            ($item->status_kamar == 'terisi' ? 'fa-user-check' : 'fa-tools') }} 
                                                        mr-1.5 text-xs"></i>
                                                    {{ ucfirst($item->status_kamar) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-3">
                                                    <a href="{{ route('pemilik.kamar.edit', $item->id_kamar) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-primary-900/30 hover:bg-primary-900/50 text-primary-300 border border-primary-700/30 rounded-lg text-sm font-medium transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                                        <i class="fas fa-edit mr-2 text-xs"></i>
                                                        Edit
                                                    </a>
                                                    <button type="button"
                                                        onclick="showDeleteModal('{{ route('pemilik.kamar.destroy', $item->id_kamar) }}', '{{ $item->nomor_kamar }}')"
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-900/30 hover:bg-red-900/50 text-red-300 border border-red-700/30 rounded-lg text-sm font-medium transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                                        <i class="fas fa-trash-alt mr-2 text-xs"></i>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-20 h-20 bg-green-900/20 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-bed text-green-400 text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-white mb-2">Belum ada kamar</h3>
                                        <p class="text-dark-muted mb-4">Mulai tambahkan kamar pertama Anda</p>
                                        <a href="{{ route('pemilik.kamar.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition-all duration-300">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah Kamar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table Footer -->
        @if($kamar->hasPages())
            <div class="px-6 py-4 border-t border-dark-border">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-dark-muted">
                        Menampilkan {{ $kamar->firstItem() }} - {{ $kamar->lastItem() }} dari {{ $kamar->total() }} kamar
                    </div>
                    <div class="flex space-x-2">
                        {{ $kamar->links('vendor.pagination.custom-dark') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-8 flex justify-between items-center">
        <a href="{{ route('pemilik.dashboard') }}"
            class="inline-flex items-center px-4 py-2.5 bg-dark-border hover:bg-dark-border/80 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Dashboard
        </a>

        @if($kamar->count() > 0)
            <div class="text-sm text-dark-muted">
                <i class="fas fa-info-circle mr-2 text-primary-400"></i>
                Menampilkan {{ $kamar->count() }} kamar
            </div>
        @endif
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
                <h3 class="text-xl font-bold text-white mb-2">Hapus Kamar?</h3>
                <p class="text-dark-muted mb-1">Apakah Anda yakin ingin menghapus <span
                        class="font-semibold text-white">Kamar <span id="kamarNomor"></span></span>?</p>
                <p class="text-red-400 text-sm mb-6">Data kamar dan sejarah penyewaan terkait kamar ini akan terhapus secara
                    permanen.</p>

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
                            Ya, Hapus Kamar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(action, nomor) {
            document.getElementById('deleteForm').action = action;
            document.getElementById('kamarNomor').textContent = nomor;
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