<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AyoKos')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --header-height: 64px;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Sidebar animation */
        .sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Card hover effects */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        /* Gradient backgrounds */
        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        /* Notification toast */
        .notification-toast {
            animation: slideIn 0.3s ease-out;
        }

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

        /* Dashboard specific */
        .dashboard-container {
            min-height: calc(100vh - var(--header-height));
        }

        /* Profile dropdown fix */
        .profile-menu {
            position: relative;
        }

        .profile-dropdown {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }

        .profile-menu:hover .profile-dropdown,
        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Bridge element for dropdown gap */
        .profile-bridge {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 20px;
            background: transparent;
        }

    </style>
</head>

<body class="bg-gradient-to-br from-slate-900 to-slate-800 text-slate-100 min-h-screen">
    <!-- Dynamic Header Based on Auth Status -->
    @if(auth()->guard('pemilik')->check() || auth()->guard('penghuni')->check())
        <!-- Dashboard Layout -->
        @includeWhen(auth()->guard('pemilik')->check(), 'layouts.partials.dashboard-pemilik')
        @includeWhen(auth()->guard('penghuni')->check(), 'layouts.partials.dashboard-penghuni')
    @else
        <!-- Public Layout -->
        <header class="bg-slate-800 border-b border-slate-700 shadow-lg sticky top-0 z-50">
            <div class="container mx-auto px-4 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-8">
                        <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-home text-white text-lg"></i>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                                AyoKos
                            </span>
                        </a>
                        <nav class="hidden md:flex gap-6">
                            <a href="{{ route('public.home') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('public.home') ? 'bg-blue-900/30 text-blue-300' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-700/50' }}">
                                <i class="fas fa-home w-5"></i>
                                <span>Home</span>
                            </a>
                            <a href="{{ route('public.kos.index') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('public.kos.index') ? 'bg-blue-900/30 text-blue-300' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-700/50' }}">
                                <i class="fas fa-search w-5"></i>
                                <span>Cari Kos</span>
                            </a>
                            <a href="{{ route('public.kos.peta') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('public.kos.peta') ? 'bg-blue-900/30 text-blue-300' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-700/50' }}">
                                <i class="fas fa-map-marker-alt w-5"></i>
                                <span>Peta</span>
                            </a>
                        </nav>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 rounded-lg font-medium border border-slate-700 text-slate-100 hover:bg-slate-700/50 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 rounded-lg font-medium bg-gradient-to-r from-blue-500 to-indigo-500 text-white hover:from-blue-600 hover:to-indigo-600 transition-colors shadow-lg">
                            Daftar
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer (Only for Public Pages) -->
        <footer class="bg-slate-800 border-t border-slate-700 mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-home text-white"></i>
                            </div>
                            <span class="text-xl font-bold text-white">AyoKos</span>
                        </div>
                        <p class="text-slate-400">Platform pencarian kos terbaik di Indonesia dengan pengalaman modern.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Tautan Cepat</h4>
                        <ul class="space-y-2 text-slate-400">
                            <li><a href="{{ route('public.home') }}" class="hover:text-blue-300 transition-colors">Home</a></li>
                            <li><a href="{{ route('public.kos.index') }}" class="hover:text-blue-300 transition-colors">Cari Kos</a></li>
                            <li><a href="{{ route('public.kos.peta') }}" class="hover:text-blue-300 transition-colors">Peta Kos</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Bantuan</h4>
                        <ul class="space-y-2 text-slate-400">
                            <li><a href="{{ route('public.about') }}" class="hover:text-blue-300 transition-colors">Tentang Kami</a></li>
                            <li><a href="{{ route('public.howto') }}" class="hover:text-blue-300 transition-colors">Cara Memesan</a></li>
                            <li><a href="{{ route('public.terms') }}" class="hover:text-blue-300 transition-colors">Syarat & Ketentuan</a></li>
                            <li><a href="{{ route('public.privacy') }}" class="hover:text-blue-300 transition-colors">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Kontak</h4>
                        <ul class="space-y-2 text-slate-400">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-envelope w-4"></i>
                                <span>valorant270306@gmail.com</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-phone w-4"></i>
                                <span>+62 82121730722</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt w-4"></i>
                                <span>Bandung, Indonesia</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-700 mt-8 pt-8 text-center text-slate-400">
                    <p>&copy; {{ date('Y') }} AyoKos. All rights reserved.</p>
                </div>
            </div>
        </footer>
    @endif

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-modal-close></div>
        <div class="relative bg-slate-800 border border-slate-700 rounded-2xl w-full max-w-md overflow-hidden">
            <div class="border-b border-slate-700 p-6">
                <h5 class="text-xl font-semibold text-white" id="logoutModalLabel">Konfirmasi Logout</h5>
            </div>
            <div class="p-6 text-center">
                <div class="mb-4 inline-block">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-500/20 to-red-600/20 flex items-center justify-center mx-auto">
                        <i class="fas fa-sign-out-alt text-red-400 text-2xl"></i>
                    </div>
                </div>
                <h5 class="text-lg font-medium text-white mb-2">Apakah Anda yakin ingin logout?</h5>
                <p class="text-slate-400 mb-6">Anda akan keluar dari akun ini dan harus login kembali untuk mengakses dashboard.</p>
            </div>
            <div class="border-t border-slate-700 p-6 flex justify-end gap-3">
                <button type="button" class="modal-close-btn px-5 py-2.5 bg-slate-700 text-slate-100 rounded-lg hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-colors shadow-lg">
                        Ya, Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Notification Modal -->
    @if(session('success'))
        <div id="successModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-modal-close></div>
            <div class="relative bg-slate-800 border border-slate-700 rounded-2xl w-full max-w-md overflow-hidden">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                    <h5 class="text-lg font-medium text-white mb-2">Sukses!</h5>
                    <p class="text-slate-400">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Script -->
    <script>
        class Modal {
            constructor(modalId) {
                this.modal = document.getElementById(modalId);
                this.init();
            }

            init() {
                if (!this.modal) return;

                // Close buttons
                const closeButtons = this.modal.querySelectorAll('.modal-close-btn, [data-modal-close]');
                closeButtons.forEach(btn => {
                    btn.addEventListener('click', () => this.hide());
                });

                // Close on outside click
                this.modal.addEventListener('click', (e) => {
                    if (e.target === this.modal) {
                        this.hide();
                    }
                });

                // Close on Escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                        this.hide();
                    }
                });
            }

            show() {
                this.modal.classList.remove('hidden');
                this.modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            hide() {
                this.modal.classList.add('hidden');
                this.modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }
    </script>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Sidebar functionality
        let sidebarCollapsed = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const dashboardHeader = document.getElementById('dashboardHeader');
            const toggleIcon = document.getElementById('sidebarToggleIcon');
            const toggleBtn = document.getElementById('desktopSidebarToggle');
            const logoText = document.querySelector('.logo-text');

            sidebarCollapsed = !sidebarCollapsed;

            if (sidebarCollapsed) {
                // Collapse: Width becomes 0
                sidebar.classList.remove('w-64', 'md:w-64');
                sidebar.classList.add('w-0', 'overflow-hidden');

                // Main Content: Margin handled by flexbox

                // Header: Margin becomes 0
                if (dashboardHeader) {
                    dashboardHeader.classList.remove('md:ml-64');
                    dashboardHeader.classList.add('md:ml-0');
                }

                // Icon: Point Right
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');

                // Button Position: Move to left edge (0)
                if (toggleBtn) {
                    toggleBtn.classList.remove('left-64');
                    toggleBtn.classList.add('left-0');
                    toggleBtn.classList.remove('rounded-r-lg');
                    toggleBtn.classList.add('rounded-lg');
                }

                if (logoText) logoText.classList.add('hidden');
            } else {
                // Expand: Width becomes 64
                sidebar.classList.remove('w-0', 'overflow-hidden');
                sidebar.classList.add('w-64', 'md:w-64');

                // Main Content: Margin handled by flexbox

                // Header: Margin becomes 64
                if (dashboardHeader) {
                    dashboardHeader.classList.remove('md:ml-0');
                    dashboardHeader.classList.add('md:ml-64');
                }

                // Icon: Point Left
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');

                // Button Position: Move to sidebar edge (64)
                if (toggleBtn) {
                    toggleBtn.classList.remove('left-0');
                    toggleBtn.classList.add('left-64');
                    toggleBtn.classList.remove('rounded-lg');
                    toggleBtn.classList.add('rounded-r-lg');
                }

                if (logoText) logoText.classList.remove('hidden');
            }
        }

        // Profile dropdown fix
        function setupProfileDropdown() {
            const profileButtons = document.querySelectorAll('.profile-menu');

            profileButtons.forEach(button => {
                const dropdown = button.querySelector('.profile-dropdown');

                // Create bridge element
                const bridge = document.createElement('div');
                bridge.className = 'profile-bridge';
                button.appendChild(bridge);

                // Show dropdown when hovering button or bridge
                button.addEventListener('mouseenter', () => {
                    dropdown.classList.add('show');
                });

                button.addEventListener('mouseleave', (e) => {
                    // Check if mouse is moving to dropdown
                    if (!dropdown.contains(e.relatedTarget)) {
                        dropdown.classList.remove('show');
                    }
                });

                // Keep dropdown open when hovering dropdown
                dropdown.addEventListener('mouseenter', () => {
                    dropdown.classList.add('show');
                });

                dropdown.addEventListener('mouseleave', () => {
                    dropdown.classList.remove('show');
                });
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setupProfileDropdown();

            // Mobile sidebar toggle
            const mobileToggle = document.getElementById('mobileSidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (mobileToggle) {
                mobileToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 768) {
                    const isClickInsideSidebar = sidebar.contains(e.target);
                    const isClickOnToggle = mobileToggle && mobileToggle.contains(e.target);

                    if (!isClickInsideSidebar && !isClickOnToggle && !sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.add('-translate-x-full');
                    }
                }
            });

            // Initialize modals
            const logoutModal = new Modal('logoutModal');
            const successModal = new Modal('successModal');

            // Show success modal if there's a success message
            @if(session('success'))
                setTimeout(() => {
                    successModal.show();
                    setTimeout(() => {
                        successModal.hide();
                    }, 3000);
                }, 500);
            @endif

            // Expose modals to global scope for other scripts
            window.logoutModal = logoutModal;
            window.successModal = successModal;
        });

        // Function to show logout modal (call this from your logout button)
        function showLogoutModal() {
            if (window.logoutModal) {
                window.logoutModal.show();
            }
        }
    </script>
    @stack('scripts')
</body>

</html>