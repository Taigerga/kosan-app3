<!-- Dashboard Header -->
<header class="bg-slate-800 border-b border-slate-700 h-16 flex items-center sticky top-0 z-[1002]">
    <div id="dashboardHeader" class="flex-1 px-4 transition-all duration-300 md:ml-64">
        <div class="flex items-center justify-between">
            <!-- Mobile Toggle -->
            <button id="mobileSidebarToggle" class="md:hidden text-slate-400 hover:text-slate-100">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Logo and Title -->
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">Dashboard Pemilik</h1>
                        <p class="text-xs text-slate-400">Kelola properti Anda</p>
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center gap-4">
                <!-- Notifications -->
                <button class="relative p-2 text-slate-400 hover:text-slate-100">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- Profile Menu -->
                <div class="profile-menu relative">
                    <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-700/50">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full flex items-center justify-center">
                            <span
                                class="text-white font-medium">{{ substr(Auth::guard('pemilik')->user()->nama, 0, 1) }}</span>
                        </div>
                        <span
                            class="text-sm font-medium text-white hidden md:inline">{{ Auth::guard('pemilik')->user()->nama }}</span>
                        <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    <div
                        class="profile-dropdown absolute right-0 mt-2 w-64 bg-slate-800 rounded-xl shadow-2xl border border-slate-700 py-2 z-[1001]">
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-slate-700">
                            <p class="text-sm font-semibold text-white">{{ Auth::guard('pemilik')->user()->nama }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::guard('pemilik')->user()->email }}</p>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('pemilik.dashboard') }}"
                                class="flex items-center px-4 py-2.5 text-slate-100 hover:bg-slate-700 hover:text-white transition-colors">
                                <i class="fas fa-tachometer-alt w-5 mr-3 text-blue-400"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('pemilik.kontrak.index') }}"
                                class="flex items-center px-4 py-2.5 text-slate-100 hover:bg-slate-700 hover:text-white transition-colors">
                                <i class="fas fa-file-contract w-5 mr-3 text-green-400"></i>
                                <span>Kelola Kontrak</span>
                            </a>
                            <a href="{{ route('pemilik.reviews.index') }}"
                                class="flex items-center px-4 py-2.5 text-slate-100 hover:bg-slate-700 hover:text-white transition-colors">
                                <i class="fas fa-star w-5 mr-3 text-yellow-400"></i>
                                <span>Ulasan Kos</span>
                            </a>
                            <a href="{{ route('pemilik.pembayaran.index') }}"
                                class="flex items-center px-4 py-2.5 text-slate-100 hover:bg-slate-700 hover:text-white transition-colors">
                                <i class="fas fa-credit-card w-5 mr-3 text-purple-400"></i>
                                <span>Pembayaran</span>
                            </a>
                            <a href="{{ route('pemilik.profile.show') }}"
                                class="flex items-center px-4 py-2.5 text-slate-100 hover:bg-slate-700 hover:text-white transition-colors">
                                <i class="fas fa-user-cog w-5 mr-3 text-blue-400"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('public.kos.peta') }}"
                                class="flex items-center px-4 py-2.5 text-slate-100 hover:bg-slate-700 hover:text-white transition-colors">
                                <i class="fas fa-map-marked-alt w-5 mr-3 text-orange-400"></i>
                                <span>Peta Kos</span>
                            </a>
                        </div>

                        <!-- Logout -->
                        <div class="border-t border-slate-700 pt-2">
                            <button type="button"
                                class="flex items-center w-full text-left px-4 py-2.5 text-red-400 hover:bg-red-900/20 transition-colors"
                                onclick="showLogoutModal()">
                                <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Layout -->
<div class="flex min-h-[calc(100vh-64px)] relative">
    <!-- Sidebar -->
    <aside id="sidebar"
        class="bg-slate-800 border-r border-slate-700 w-64 md:w-64 flex-shrink-0 fixed md:relative h-full md:h-auto z-[1005] -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out">
        <!-- Sidebar Header -->
        <div class="p-4 border-b border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-white text-sm"></i>
                    </div>
                    <span class="logo-text font-bold text-white">AyoKos</span>
                </div>
                <!-- Toggle Button Moved to Main Content -->
            </div>
        </div>

        <!-- Navigation -->
        <nav class="p-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('pemilik.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg font-medium {{ request()->routeIs('pemilik.dashboard') ? 'bg-blue-900/30 text-blue-300 border-l-4 border-blue-500' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pemilik.kos.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg font-medium {{ request()->routeIs('pemilik.kos.*') ? 'bg-blue-900/30 text-blue-300 border-l-4 border-blue-500' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-home w-5"></i>
                        <span class="sidebar-text">Kelola Kos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pemilik.kamar.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg font-medium {{ request()->routeIs('pemilik.kamar.*') ? 'bg-blue-900/30 text-blue-300 border-l-4 border-blue-500' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-bed w-5"></i>
                        <span class="sidebar-text">Kelola Kamar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pemilik.kontrak.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg font-medium {{ request()->routeIs('pemilik.kontrak.*') ? 'bg-blue-900/30 text-blue-300 border-l-4 border-blue-500' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-file-contract w-5"></i>
                        <span class="sidebar-text">Kelola Kontrak</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pemilik.reviews.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg font-medium {{ request()->routeIs('pemilik.reviews.*') ? 'bg-blue-900/30 text-blue-300 border-l-4 border-blue-500' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-star w-5"></i>
                        <span class="sidebar-text">Ulasan Kos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pemilik.pembayaran.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg font-medium {{ request()->routeIs('pemilik.pembayaran.*') ? 'bg-blue-900/30 text-blue-300 border-l-4 border-blue-500' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-credit-card w-5"></i>
                        <span class="sidebar-text">Pembayaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pemilik.analisis.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg font-medium {{ request()->routeIs('pemilik.analisis.*') ? 'bg-blue-900/30 text-blue-300 border-l-4 border-blue-500' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span class="sidebar-text">Analisis Data</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Quick Stats -->
        <div class="p-4 border-t border-slate-700">
            <div class="text-xs text-slate-400 mb-2">Statistik Cepat</div>
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400">Total Kos</span>
                    <span class="font-bold text-white">{{ Auth::guard('pemilik')->user()->kos()->count() }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400">Kamar Tersedia</span>
                    <span class="font-bold text-green-400">
                        {{ Auth::guard('pemilik')->user()->kos()->withCount(['kamar' => fn($q) => $q->where('status_kamar', 'tersedia')])->get()->sum('kamar_count') }}
                    </span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Sidebar Toggle Button (Desktop) -->
    <button id="desktopSidebarToggle" onclick="toggleSidebar()"
        class="hidden md:flex fixed top-20 left-64 z-[1004] bg-slate-800 border border-slate-700 text-slate-400 hover:text-white p-1 rounded-r-lg shadow-lg items-center justify-center w-8 h-10 transition-all duration-300">
        <i id="sidebarToggleIcon" class="fas fa-chevron-left text-xs"></i>
    </button>

    <!-- Main Content -->
    <main id="mainContent" class="flex-1 transition-all duration-300">
        <div class="p-4 md:p-6">
            @yield('content')
        </div>
    </main>
</div>