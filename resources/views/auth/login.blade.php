<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AyoKos</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --dark-bg: #0f172a;
            --dark-card: #1e293b;
            --dark-border: #334155;
            --dark-text: #e2e8f0;
            --dark-muted: #94a3b8;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--dark-text);
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(79, 70, 229, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(139, 92, 246, 0.08) 0%, transparent 50%);
            z-index: -1;
        }
        
        .login-card {
            background: var(--dark-card);
            border-radius: 20px;
            border: 1px solid var(--dark-border);
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(99, 102, 241, 0.2);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .login-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }
        
        .form-control {
            background: rgba(30, 41, 59, 0.7);
            border: 2px solid var(--dark-border);
            border-radius: 12px;
            padding: 14px 16px;
            color: var(--dark-text);
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            background: rgba(30, 41, 59, 0.9);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            color: var(--dark-text);
        }
        
        .form-control::placeholder {
            color: var(--dark-muted);
        }
        
        .form-label {
            color: var(--dark-text);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
            background: linear-gradient(135deg, var(--primary-dark) 0%, #4338ca 100%);
        }
        
        .role-selection {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .role-card {
            flex: 1;
            background: rgba(30, 41, 59, 0.7);
            border: 2px solid var(--dark-border);
            border-radius: 12px;
            padding: 20px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .role-card:hover {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }
        
        .role-card.active {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.15);
            box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.3);
        }
        
        .role-card i {
            font-size: 28px;
            margin-bottom: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .role-card .fw-medium {
            color: var(--dark-text);
            font-size: 1rem;
            margin-bottom: 4px;
        }
        
        .role-card small {
            color: var(--dark-muted);
            font-size: 0.8rem;
        }
        
        /* Custom Toggle Button */
        .btn-outline-secondary {
            background: var(--dark-bg);
            border: 2px solid var(--dark-border);
            border-left: none;
            color: var(--dark-muted);
            transition: all 0.3s;
        }
        
        .btn-outline-secondary:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        /* Toast Notifications */
        .custom-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .toast.bg-success {
            background: rgba(34, 197, 94, 0.15) !important;
            border-color: rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
        
        .toast.bg-danger {
            background: rgba(239, 68, 68, 0.15) !important;
            border-color: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        
        .toast .btn-close {
            filter: invert(1) brightness(2);
        }
        
        /* Links */
        a.text-decoration-none {
            color: var(--primary);
            transition: all 0.3s;
        }
        
        a.text-decoration-none:hover {
            color: var(--primary-dark);
            text-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }
        
        a.text-primary-emphasis {
            color: var(--dark-muted) !important;
        }
        
        a.text-primary-emphasis:hover {
            color: var(--primary) !important;
        }
        
        /* Invalid Feedback */
        .invalid-feedback {
            color: #fca5a5;
            font-size: 0.85rem;
            margin-top: 4px;
        }
        
        .is-invalid {
            border-color: #fca5a5 !important;
        }
        
        .is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(252, 165, 165, 0.15) !important;
        }
        
        /* Divider */
        .border-top {
            border-color: var(--dark-border) !important;
        }
        
        /* Input Group Fix */
        .input-group .form-control {
            border-right: none;
        }
        
        .input-group .btn {
            border-left: none;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        
        /* Container Adjustments */
        .p-4, .p-md-5 {
            padding: 30px !important;
        }
        
        /* Text Muted */
        .text-muted {
            color: var(--dark-muted) !important;
        }
        
        /* Focus State for Radio */
        .role-card:focus-within {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }
        
        /* Animation for toast */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .custom-toast .toast {
            animation: slideInRight 0.5s ease-out;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .login-card {
                margin: 20px;
                width: calc(100% - 40px);
            }
            
            .role-selection {
                flex-direction: column;
            }
            
            .role-card {
                padding: 16px;
            }
            
            .p-4, .p-md-5 {
                padding: 25px !important;
            }
        }
        
        /* Floating Particles */
        .particle {
            position: fixed;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
            animation: float 15s infinite linear;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-100px) rotate(180deg);
            }
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <!-- Floating Particles -->
    <div id="particles"></div>
    
    <!-- Toast Notifications -->
    <div class="custom-toast">
        @if(session('success'))
            <div class="toast align-items-center border-0 mb-3 bg-success" role="alert" 
                 aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" 
                            data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        @if($errors->has('login'))
            <div class="toast align-items-center border-0 bg-danger" role="alert" 
                 aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first('login') }}
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" 
                            data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    <div class="login-card" style="width: 100%; max-width: 450px;">
        <!-- Header -->
        <div class="login-header">
            <h1><i class="fas fa-home me-2"></i>AyoKos</h1>
            <p>Masuk ke akun Anda</p>
        </div>

        <!-- Login Form -->
        <div class="p-4 p-md-5">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email/Username -->
                <div class="mb-4">
                    <label for="username" class="form-label fw-semibold">
                        <i class="fas fa-user me-2" style="color: var(--primary);"></i>Username atau Email
                    </label>
                    <input type="text" name="username" id="username" 
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}" 
                           placeholder="Masukkan username atau email" 
                           required autofocus>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">
                        <i class="fas fa-lock me-2" style="color: var(--primary);"></i>Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Masukkan password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-3">
                        <i class="fas fa-user-tag me-2" style="color: var(--primary);"></i>Login Sebagai
                    </label>
                    <div class="role-selection">
                        <div class="role-card @if(old('role') == 'penghuni') active @endif" onclick="selectRole('penghuni')">
                            <input type="radio" name="role" value="penghuni" 
                                   id="rolePenghuni" {{ old('role') == 'penghuni' ? 'checked' : '' }} hidden>
                            <i class="fas fa-user"></i>
                            <div class="fw-medium">Penghuni</div>
                            <small class="text-muted">Pencari kos</small>
                        </div>
                        <div class="role-card @if(old('role') == 'pemilik') active @endif" onclick="selectRole('pemilik')">
                            <input type="radio" name="role" value="pemilik" 
                                   id="rolePemilik" {{ old('role') == 'pemilik' ? 'checked' : '' }} hidden>
                            <i class="fas fa-building"></i>
                            <div class="fw-medium">Pemilik</div>
                            <small class="text-muted">Pemilik kos</small>
                        </div>
                    </div>
                    @error('role')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Login Button -->
                <div class="mb-4">
                    <button type="submit" class="btn btn-login w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>
                </div>

                <!-- Register Link -->
                <div class="p-4 text-center border-top">
                    <p class="text-muted mb-2">Belum punya akun?</p>
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                        <i class="fas fa-user-plus me-1"></i>Daftar Sekarang
                    </a>
                </div>
                <div class="p-4 text-center pt-3">
                    <a href="{{ route('public.home') }}" class="text-decoration-none fw-semibold text-primary-emphasis">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
                    </a>
                </div>                
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Generate floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const colors = ['#6366f1', '#4f46e5', '#8b5cf6'];
            
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random properties
                const size = Math.random() * 20 + 5;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const left = Math.random() * 100;
                const duration = Math.random() * 20 + 10;
                const delay = Math.random() * 5;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.background = color;
                particle.style.left = `${left}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.opacity = Math.random() * 0.1 + 0.05;
                
                particlesContainer.appendChild(particle);
            }
        }
        
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Role Selection
        function selectRole(role) {
            // Uncheck all
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('active');
            });
            
            // Check selected
            const radio = document.getElementById(`role${role.charAt(0).toUpperCase() + role.slice(1)}`);
            radio.checked = true;
            radio.closest('.role-card').classList.add('active');
        }

        // Initialize toasts
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toastEl => {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            });
            
            // Auto-select role based on old input
            const oldRole = "{{ old('role') }}";
            if (oldRole) {
                selectRole(oldRole);
            }
        });
    </script>
</body>
</html>