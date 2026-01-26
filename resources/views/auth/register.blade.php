<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AyoKos</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --dark-bg: #0f172a;
            --dark-card: #1e293b;
            --dark-border: #334155;
            --dark-text: #e2e8f0;
            --dark-muted: #94a3b8;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--dark-text);
        }
        
        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        
        .register-card {
            background: var(--dark-card);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            border: 1px solid var(--dark-border);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            max-width: 500px;
        }
        
        .register-card:hover {
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6);
            transform: translateY(-2px);
        }
        
        .register-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .register-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .register-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        
        .register-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.875rem;
            position: relative;
            z-index: 2;
        }
        
        .register-header p {
            opacity: 0.9;
            font-size: 0.95rem;
            position: relative;
            z-index: 2;
        }
        
        .form-control {
            background: var(--dark-bg);
            border: 2px solid var(--dark-border);
            border-radius: 12px;
            padding: 0.875rem 1rem;
            color: var(--dark-text);
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        
        .form-control:focus {
            background: var(--dark-bg);
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
            color: var(--dark-text);
        }
        
        .form-control::placeholder {
            color: var(--dark-muted);
        }
        
        .btn-register {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.875rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            font-family: 'Inter', sans-serif;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
            padding: 0 2rem;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 22px;
            left: 50px;
            right: 50px;
            height: 2px;
            background: var(--dark-border);
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }
        
        .step-number {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--dark-bg);
            border: 2px solid var(--dark-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--dark-muted);
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }
        
        .step.active .step-number {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }
        
        .step-label {
            font-size: 0.75rem;
            color: var(--dark-muted);
            text-align: center;
            font-weight: 500;
        }
        
        .step.active .step-label {
            color: #0ea5e9;
            font-weight: 600;
        }
        
        .form-step {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-step.active {
            display: block;
        }
        
        .role-selection {
            display: flex;
            gap: 1rem;
            margin: 1.5rem 0;
        }
        
        .role-card {
            flex: 1;
            border: 2px solid var(--dark-border);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--dark-bg);
        }
        
        .role-card:hover {
            border-color: #0ea5e9;
            transform: translateY(-2px);
        }
        
        .role-card.active {
            border-color: #0ea5e9;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.2);
        }
        
        .role-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #0ea5e9;
            display: block;
        }
        
        .role-card .fw-semibold {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: var(--dark-text);
        }
        
        .role-card small {
            color: var(--dark-muted);
            font-size: 0.75rem;
        }
        
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            gap: 1rem;
        }
        
        .btn-navigation {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        
        .btn-outline-secondary {
            background: var(--dark-bg);
            border-color: var(--dark-border);
            color: var(--dark-text);
        }
        
        .btn-outline-secondary:hover {
            background: var(--dark-border);
            border-color: var(--dark-muted);
            color: var(--dark-text);
        }
        
        .file-upload {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload label {
            display: block;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px dashed #0ea5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--dark-bg);
        }
        
        .file-upload label:hover {
            background: rgba(14, 165, 233, 0.1);
            transform: scale(1.05);
        }
        
        .file-upload .preview {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: none;
            border: 3px solid #0ea5e9;
        }
        
        /* Custom Toasts */
        .custom-toast {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
        }
        
        .custom-toast .toast {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            color: var(--dark-text);
            backdrop-filter: blur(10px);
        }
        
        .toast.bg-success {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%) !important;
            border-color: #059669;
        }
        
        .toast.bg-danger {
            background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 100%) !important;
            border-color: #ef4444;
        }
        
        /* Error styling */
        .is-invalid {
            border-color: #ef4444 !important;
        }
        
        .invalid-feedback {
            display: block;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.375rem;
            font-weight: 500;
        }
        
        /* Checkbox styling */
        .form-check-input {
            background-color: var(--dark-bg);
            border: 2px solid var(--dark-border);
        }
        
        .form-check-input:checked {
            background-color: #0ea5e9;
            border-color: #0ea5e9;
        }
        
        .form-check-label a {
            color: #0ea5e9;
            text-decoration: none;
        }
        
        .form-check-label a:hover {
            text-decoration: underline;
        }
        
        /* Alert styling */
        .alert-info {
            background: rgba(14, 165, 233, 0.1);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: var(--dark-text);
            border-radius: 12px;
        }
        
        /* Input group styling */
        .input-group .btn-outline-secondary {
            background: var(--dark-bg);
            border: 2px solid var(--dark-border);
            color: var(--dark-muted);
        }
        
        .input-group .btn-outline-secondary:hover {
            background: var(--dark-border);
            color: var(--dark-text);
        }
        
        /* Radio button styling */
        .form-check-input[type="radio"] {
            cursor: pointer;
        }
        
        .form-check-input[type="radio"]:checked {
            background-color: #0ea5e9;
            border-color: #0ea5e9;
        }
        
        .form-check-label {
            color: var(--dark-text);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Textarea styling */
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Link styling */
        a {
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 500;
        }
        
        a:hover {
            color: #38bdf8;
            text-decoration: underline;
        }
        
        /* Content padding */
        .register-content {
            padding: 2rem;
        }
        
        @media (max-width: 768px) {
            .register-container {
                padding: 1rem;
            }
            
            .register-header {
                padding: 1.5rem;
            }
            
            .register-content {
                padding: 1.5rem;
            }
            
            .step-indicator {
                padding: 0 1rem;
            }
            
            .step-indicator::before {
                left: 30px;
                right: 30px;
            }
            
            .role-selection {
                flex-direction: column;
            }
            
        }
        
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Toast Notifications -->
        <div class="custom-toast">
            @if(session('success'))
                <div class="toast align-items-center text-white bg-success border-0 mb-3" role="alert" 
                     aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                                data-bs-dismiss="toast"></button>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="toast align-items-center text-white bg-danger border-0" role="alert" 
                     aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>
                                <strong>Terjadi kesalahan:</strong>
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                                data-bs-dismiss="toast"></button>
                    </div>
                </div>
            @endif
        </div>

        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <h1><i class="fas fa-user-plus me-2"></i>Daftar Akun Baru</h1>
                <p>Bergabung dengan AyoKos dalam beberapa langkah mudah</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Data Pribadi</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Data Akun</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Konfirmasi</div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="register-content">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
                    @csrf
                    
                    <!-- Step 1: Personal Data -->
                    <div class="form-step active" id="step1">
                        <h4 class="mb-4 text-white"><i class="fas fa-user me-2 text-primary"></i>Data Pribadi</h4>
                        
                        <div class="mb-4">
                            <label for="nama" class="form-label fw-semibold text-white">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" 
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama') }}" 
                                   placeholder="Masukkan nama lengkap" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-semibold text-white">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" 
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" 
                                       placeholder="email@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="no_hp_display" class="form-label fw-semibold text-white">No. HP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" 
                                        style="background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%); 
                                                color: white; 
                                                border-color: #0ea5e9;
                                                min-width: 50px; 
                                                user-select: none;
                                                font-weight: 600;">
                                        62
                                    </span>
                                    <input type="tel" id="no_hp_display" 
                                        class="form-control @error('no_hp') is-invalid @enderror"
                                        value="{{ old('no_hp') ? (str_starts_with(old('no_hp'), '62') ? substr(old('no_hp'), 2) : old('no_hp')) : '' }}"
                                        placeholder="81234567890"
                                        required>
                                    <!-- Hidden input untuk menyimpan nilai lengkap -->
                                    <input type="hidden" name="no_hp" id="no_hp" value="{{ old('no_hp') }}">
                                </div>
                                <small class="text-muted">Masukkan nomor setelah 62, contoh: 81234567890</small>
                                @error('no_hp')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">

                            <div class="col-md-6 mb-4">
                                <label for="tanggal_lahir" class="form-label fw-semibold text-white">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                       class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                       value="{{ old('tanggal_lahir') }}" 
                                       max="{{ date('Y-m-d', strtotime('-17 years')) }}"
                                       required>
                                <div class="invalid-feedback" id="tanggal_lahir_error">
                                    @error('tanggal_lahir')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-white">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" 
                                           value="L" id="jenisKelaminL" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jenisKelaminL">
                                        <i class="fas fa-male me-1"></i> Laki-laki
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" 
                                           value="P" id="jenisKelaminP" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jenisKelaminP">
                                        <i class="fas fa-female me-1"></i> Perempuan
                                    </label>
                                </div>
                            </div>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="alamat" class="form-label fw-semibold text-white">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" id="alamat" 
                                      class="form-control @error('alamat') is-invalid @enderror"
                                      rows="3" 
                                      placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-navigation">
                            <button type="button" class="btn btn-outline-secondary btn-navigation" disabled>
                                <i class="fas fa-chevron-left me-1"></i>Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary btn-navigation" onclick="nextStep()">
                                Selanjutnya <i class="fas fa-chevron-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Account Data -->
                    <div class="form-step" id="step2">
                        <h4 class="mb-4 text-white"><i class="fas fa-key me-2 text-primary"></i>Data Akun</h4>
                        
                        <div class="mb-4">
                            <label for="username" class="form-label fw-semibold text-white">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" 
                                   class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}" 
                                   placeholder="Pilih username unik" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label fw-semibold text-white">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" 
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Minimal 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold text-white">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="form-control"
                                           placeholder="Ulangi password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-white">Foto Profil (Opsional)</label>
                            <div class="file-upload">
                                <input type="file" name="foto_profil" id="foto_profil" accept="image/*" onchange="previewImage(this)">
                                <label for="foto_profil" id="fileUploadLabel">
                                    <i class="fas fa-camera fa-2x text-muted"></i>
                                </label>
                                <img id="imagePreview" class="preview" alt="Preview">
                            </div>
                            <p class="text-muted text-center small">Klik untuk upload foto (opsional, max 2MB)</p>
                        </div>
                        
                        <div class="form-navigation">
                            <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep()">
                                <i class="fas fa-chevron-left me-1"></i>Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary btn-navigation" onclick="nextStep()">
                                Selanjutnya <i class="fas fa-chevron-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Confirmation -->
                    <div class="form-step" id="step3">
                        <h4 class="mb-4 text-white"><i class="fas fa-check-circle me-2 text-primary"></i>Konfirmasi Pendaftaran</h4>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-white mb-3">Daftar Sebagai <span class="text-danger">*</span></label>
                            <div class="role-selection">
                                <div class="role-card @if(old('role') == 'penghuni') active @endif" onclick="selectRole('penghuni')">
                                    <input type="radio" name="role" value="penghuni" 
                                           id="rolePenghuni" {{ old('role') == 'penghuni' ? 'checked' : '' }} hidden required>
                                    <i class="fas fa-user"></i>
                                    <div class="fw-semibold">Penghuni</div>
                                    <small class="text-white">Saya ingin mencari kos</small>
                                </div>
                                <div class="role-card @if(old('role') == 'pemilik') active @endif" onclick="selectRole('pemilik')">
                                    <input type="radio" name="role" value="pemilik" 
                                           id="rolePemilik" {{ old('role') == 'pemilik' ? 'checked' : '' }} hidden required>
                                    <i class="fas fa-building"></i>
                                    <div class="fw-semibold">Pemilik</div>
                                    <small class="text-white">Saya ingin menyewakan kos</small>
                                </div>
                            </div>
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Penting:</strong> Pastikan data yang Anda isi sudah benar. Data tidak dapat diubah setelah pendaftaran.
                        </div>
                        
                        <div class="form-navigation">
                            <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep()">
                                <i class="fas fa-chevron-left me-1"></i>Sebelumnya
                            </button>
                            <button type="submit" class="btn btn-register btn-navigation">
                                <i class="fas fa-user-plus me-1"></i>Daftar Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Login Link -->
            <div class="p-4 text-center border-top border-dark-border">
                <p class="text-muted mb-2">Sudah punya akun?</p>
                <a href="{{ route('login') }}" class="fw-semibold text-primary">
                    <i class="fas fa-sign-in-alt me-1"></i>Masuk Sekarang
                </a>
            </div>
            <div class="p-4 text-center pt-0">
                <a href="{{ route('public.home') }}" class="fw-semibold text-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
                </a>
            </div>      
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<script>
    let currentStep = 1;
    const totalSteps = 3;
    
    // Fungsi untuk memformat nomor telepon
    function formatPhoneNumber() {
        const phoneInput = document.getElementById('no_hp_display');
        const hiddenInput = document.getElementById('no_hp');
        
        // Hapus semua karakter non-digit
        let phoneValue = phoneInput.value.replace(/\D/g, '');
        
        // Hapus 0 di depan jika ada
        if (phoneValue.startsWith('0')) {
            phoneValue = phoneValue.substring(1);
        }
        
        // Update nilai display
        phoneInput.value = phoneValue;
        
        // Set nilai lengkap ke hidden input
        hiddenInput.value = '62' + phoneValue;
        
        // Validasi panjang
        if (phoneValue && (phoneValue.length < 9 || phoneValue.length > 13)) {
            phoneInput.classList.add('is-invalid');
            phoneInput.parentElement.nextElementSibling.innerHTML = 'Nomor HP harus 9-13 digit setelah 62';
            return false;
        } else {
            phoneInput.classList.remove('is-invalid');
            phoneInput.parentElement.nextElementSibling.innerHTML = '';
            return true;
        }
    }
    
    // Handle paste event
    function handlePhonePaste(e) {
        e.preventDefault();
        
        const phoneInput = document.getElementById('no_hp_display');
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        
        // Hapus semua karakter non-digit
        const cleaned = pastedText.replace(/\D/g, '');
        
        // Hapus 62 atau 0 di depan
        let finalValue = cleaned;
        if (finalValue.startsWith('62')) {
            finalValue = finalValue.substring(2);
        } else if (finalValue.startsWith('0')) {
            finalValue = finalValue.substring(1);
        }
        
        // Set nilai ke input
        phoneInput.value = finalValue;
        formatPhoneNumber();
    }
    
    function nextStep() {
        if (currentStep < totalSteps) {
            // Validate current step
            if (validateStep(currentStep)) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
                
                currentStep++;
                
                document.getElementById(`step${currentStep}`).classList.add('active');
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
            }
        }
    }
    
    function prevStep() {
        if (currentStep > 1) {
            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
            
            currentStep--;
            
            document.getElementById(`step${currentStep}`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
        }
    }
    
    function validateStep(step) {
        let isValid = true;
        const stepElement = document.getElementById(`step${step}`);
        
        console.log(`=== Validating Step ${step} ===`);
        
        if (step === 1) {
            // Validasi field di step 1
            const fields = ['nama', 'email', 'no_hp_display', 'tanggal_lahir', 'alamat'];
            
            // Validasi nama
            const nama = document.getElementById('nama');
            if (!nama || !nama.value.trim()) {
                nama.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Nama kosong');
            } else {
                nama.classList.remove('is-invalid');
                console.log('✅ Nama valid');
            }
            
            // Validasi email
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !email.value.trim() || !emailRegex.test(email.value)) {
                email.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Email tidak valid');
            } else {
                email.classList.remove('is-invalid');
                console.log('✅ Email valid');
            }
            
            // Validasi nomor HP
            const noHpDisplay = document.getElementById('no_hp_display');
            if (!noHpDisplay || !noHpDisplay.value.trim()) {
                noHpDisplay.classList.add('is-invalid');
                if (noHpDisplay.parentElement.nextElementSibling) {
                    noHpDisplay.parentElement.nextElementSibling.innerHTML = 'Nomor HP wajib diisi';
                }
                isValid = false;
                console.log('❌ Nomor HP kosong');
            } else if (noHpDisplay.value.length < 9 || noHpDisplay.value.length > 13) {
                noHpDisplay.classList.add('is-invalid');
                if (noHpDisplay.parentElement.nextElementSibling) {
                    noHpDisplay.parentElement.nextElementSibling.innerHTML = 'Nomor HP harus 9-13 digit setelah 62';
                }
                isValid = false;
                console.log('❌ Nomor HP tidak valid');
            } else {
                noHpDisplay.classList.remove('is-invalid');
                if (noHpDisplay.parentElement.nextElementSibling) {
                    noHpDisplay.parentElement.nextElementSibling.innerHTML = '';
                }
                document.getElementById('no_hp').value = '62' + noHpDisplay.value;
                console.log('✅ Nomor HP valid');
            }
            
            // Validasi tanggal lahir
            const tanggalLahir = document.getElementById('tanggal_lahir');
            if (!tanggalLahir || !tanggalLahir.value) {
                tanggalLahir.classList.add('is-invalid');
                const errorDiv = document.getElementById('tanggal_lahir_error');
                if (errorDiv) {
                    errorDiv.innerHTML = 'Tanggal lahir wajib diisi';
                }
                isValid = false;
                console.log('❌ Tanggal lahir kosong');
            } else {
                const birthDate = new Date(tanggalLahir.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                if (age < 17) {
                    tanggalLahir.classList.add('is-invalid');
                    const errorDiv = document.getElementById('tanggal_lahir_error');
                    if (errorDiv) {
                        errorDiv.innerHTML = 'Umur tidak boleh kurang dari 17 tahun';
                    }
                    isValid = false;
                    console.log('❌ Umur kurang dari 17 tahun');
                } else {
                    tanggalLahir.classList.remove('is-invalid');
                    console.log('✅ Tanggal lahir valid');
                }
            }
            
            // Validasi alamat
            const alamat = document.getElementById('alamat');
            if (!alamat || !alamat.value.trim()) {
                alamat.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Alamat kosong');
            } else {
                alamat.classList.remove('is-invalid');
                console.log('✅ Alamat valid');
            }
            
            // Validasi jenis kelamin
            const jenisKelaminChecked = document.querySelector('input[name="jenis_kelamin"]:checked');
            if (!jenisKelaminChecked) {
                const container = document.querySelector('.mb-4:has(input[name="jenis_kelamin"])');
                let errorDiv = container.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    container.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Jenis kelamin wajib dipilih';
                isValid = false;
                console.log('❌ Jenis kelamin belum dipilih');
            } else {
                const container = document.querySelector('.mb-4:has(input[name="jenis_kelamin"])');
                const errorDiv = container.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
                console.log('✅ Jenis kelamin valid');
            }
            
        } else if (step === 2) {
            // Validasi field di step 2
            const username = document.getElementById('username');
            if (!username || !username.value.trim()) {
                username.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Username kosong');
            } else {
                username.classList.remove('is-invalid');
                console.log('✅ Username valid');
            }
            
            const password = document.getElementById('password');
            if (!password || !password.value.trim()) {
                password.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Password kosong');
            } else if (password.value.length < 8) {
                password.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Password kurang dari 8 karakter');
            } else {
                password.classList.remove('is-invalid');
                console.log('✅ Password valid');
            }
            
            const passwordConfirm = document.getElementById('password_confirmation');
            if (!passwordConfirm || !passwordConfirm.value.trim()) {
                passwordConfirm.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Konfirmasi password kosong');
            } else if (password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Password tidak sama');
            } else {
                passwordConfirm.classList.remove('is-invalid');
                console.log('✅ Konfirmasi password valid');
            }
            
        } else if (step === 3) {
            // Validasi field di step 3
            const roleChecked = document.querySelector('input[name="role"]:checked');
            if (!roleChecked) {
                const roleSelection = document.querySelector('.role-selection');
                let errorDiv = roleSelection.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    roleSelection.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Pilih peran anda';
                isValid = false;
                console.log('❌ Role belum dipilih');
            } else {
                const roleSelection = document.querySelector('.role-selection');
                const errorDiv = roleSelection.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
                console.log('✅ Role valid');
            }
        }
        
        console.log(`Step ${step} validation result: ${isValid ? 'VALID' : 'INVALID'}`);
        return isValid;
    }
    
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const icon = input.nextElementSibling.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
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
    
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const label = document.getElementById('fileUploadLabel');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                label.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Inisialisasi saat halaman load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize toasts
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toastEl => {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        });
        
        // Initialize role selection if has old value
        const oldRole = '{{ old("role") }}';
        if (oldRole) {
            selectRole(oldRole);
        }
        
        // Inisialisasi nomor HP
        const hiddenInput = document.getElementById('no_hp');
        const displayInput = document.getElementById('no_hp_display');
        
        // Jika ada old value, format untuk display
        if (hiddenInput.value) {
            // Jika mengandung 62, hapus untuk tampilan
            if (hiddenInput.value.startsWith('62')) {
                displayInput.value = hiddenInput.value.substring(2);
            } else {
                displayInput.value = hiddenInput.value;
            }
        }
        
        // Format nomor HP
        formatPhoneNumber();
        
                // Tambahkan event listener untuk paste
        displayInput.addEventListener('paste', handlePhonePaste);
        
        // Tambahkan event listener untuk input real-time
        displayInput.addEventListener('input', formatPhoneNumber);
        
        // Tambahkan event listener untuk validasi real-time
        ['nama', 'email', 'alamat'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            }
        });
        
        // Validasi real-time untuk email
        const emailField = document.getElementById('email');
        if (emailField) {
            emailField.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.value.trim() || !emailRegex.test(this.value)) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }
        
        // Validasi real-time untuk tanggal lahir
        const tanggalLahirField = document.getElementById('tanggal_lahir');
        if (tanggalLahirField) {
            tanggalLahirField.addEventListener('change', function() {
                if (!this.value) {
                    this.classList.add('is-invalid');
                    const errorDiv = document.getElementById('tanggal_lahir_error');
                    if (errorDiv) {
                        errorDiv.innerHTML = 'Tanggal lahir wajib diisi';
                    }
                } else {
                    const birthDate = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    
                    if (age < 17) {
                        this.classList.add('is-invalid');
                        const errorDiv = document.getElementById('tanggal_lahir_error');
                        if (errorDiv) {
                            errorDiv.innerHTML = 'Umur tidak boleh kurang dari 17 tahun';
                        }
                    } else {
                        this.classList.remove('is-invalid');
                    }
                }
            });
        }
        
        // Validasi real-time untuk jenis kelamin
        document.querySelectorAll('input[name="jenis_kelamin"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const container = this.closest('.mb-4');
                const errorDiv = container.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
            });
        });
        
        // Validasi real-time untuk username
        const usernameField = document.getElementById('username');
        if (usernameField) {
            usernameField.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('is-invalid');
                    let error = this.parentElement.querySelector('.invalid-feedback');
                    if (!error) {
                        error = document.createElement('div');
                        error.className = 'invalid-feedback';
                        this.parentElement.appendChild(error);
                    }
                    error.textContent = 'Username wajib diisi';
                } else {
                    this.classList.remove('is-invalid');
                    const error = this.parentElement.querySelector('.invalid-feedback');
                    if (error) {
                        error.remove();
                    }
                }
            });
        }
        
        // Validasi sebelum submit form
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            // Validasi semua step sebelum submit
            let allValid = true;
            
            // Validasi step 1
            // Validasi step 1
            const step1Valid = validateStep(1);
            if (!step1Valid) allValid = false;
            
            // Validasi step 2
            const step2Valid = validateStep(2);
            if (!step2Valid) allValid = false;
            
            // Manual validation untuk step 2 (username & password)
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            
            if (!username.value.trim()) {
                username.classList.add('is-invalid');
                // Tambahkan error message untuk username
                let usernameError = username.parentElement.querySelector('.invalid-feedback');
                if (!usernameError) {
                    usernameError = document.createElement('div');
                    usernameError.className = 'invalid-feedback';
                    username.parentElement.appendChild(usernameError);
                }
                usernameError.textContent = 'Username wajib diisi';
                allValid = false;
            } else {
                // Hapus error message jika sudah valid
                const usernameError = username.parentElement.querySelector('.invalid-feedback');
                if (usernameError) {
                    usernameError.remove();
                }
            }
            
            if (!password.value.trim()) {
                password.classList.add('is-invalid');
                // Tambahkan error message untuk password
                let passwordError = password.parentElement.querySelector('.invalid-feedback');
                if (!passwordError) {
                    passwordError = document.createElement('div');
                    passwordError.className = 'invalid-feedback';
                    password.parentElement.appendChild(passwordError);
                }
                passwordError.textContent = 'Password wajib diisi';
                allValid = false;
            } else if (password.value.length < 8) {
                password.classList.add('is-invalid');
                // Tambahkan error message untuk password
                let passwordError = password.parentElement.querySelector('.invalid-feedback');
                if (!passwordError) {
                    passwordError = document.createElement('div');
                    passwordError.className = 'invalid-feedback';
                    password.parentElement.appendChild(passwordError);
                }
                passwordError.textContent = 'Password minimal 8 karakter';
                allValid = false;
            } else {
                // Hapus error message jika sudah valid
                const passwordError = password.parentElement.querySelector('.invalid-feedback');
                if (passwordError) {
                    passwordError.remove();
                }
            }
            
            if (!passwordConfirm.value.trim()) {
                passwordConfirm.classList.add('is-invalid');
                // Tambahkan error message untuk konfirmasi password
                let confirmError = passwordConfirm.parentElement.querySelector('.invalid-feedback');
                if (!confirmError) {
                    confirmError = document.createElement('div');
                    confirmError.className = 'invalid-feedback';
                    passwordConfirm.parentElement.appendChild(confirmError);
                }
                confirmError.textContent = 'Konfirmasi password wajib diisi';
                allValid = false;
            } else if (password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('is-invalid');
                // Tambahkan error message untuk konfirmasi password
                let confirmError = passwordConfirm.parentElement.querySelector('.invalid-feedback');
                if (!confirmError) {
                    confirmError = document.createElement('div');
                    confirmError.className = 'invalid-feedback';
                    passwordConfirm.parentElement.appendChild(confirmError);
                }
                confirmError.textContent = 'Password tidak sama';
                allValid = false;
            } else {
                // Hapus error message jika sudah valid
                const confirmError = passwordConfirm.parentElement.querySelector('.invalid-feedback');
                if (confirmError) {
                    confirmError.remove();
                }
            }
            
            // Validasi step 3
            const roleChecked = document.querySelector('input[name="role"]:checked');
            
            if (!roleChecked) {
                const roleError = document.createElement('div');
                roleError.className = 'invalid-feedback d-block';
                roleError.textContent = 'Pilih peran anda';
                document.querySelector('.role-selection').appendChild(roleError);
                allValid = false;
            }
            
            // Debug: Log validation result
            console.log('Form validation result:', allValid);
            console.log('Invalid elements:', document.querySelectorAll('.is-invalid'));
            
            // Jika ada yang tidak valid, prevent submit
            if (!allValid) {
                e.preventDefault();
                
                // Scroll ke error pertama
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Tampilkan toast error dengan pesan yang lebih jelas
                const errorToastContainer = document.querySelector('.custom-toast');
                errorToastContainer.innerHTML = `
                    <div class="toast align-items-center text-white bg-danger border-0 mb-3" role="alert" 
                         aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    <strong>Form belum lengkap!</strong>
                                    <div>Mohon lengkapi semua field yang wajib diisi.</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                                    data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                
                // Tampilkan toast
                const errorToastElement = errorToastContainer.querySelector('.toast');
                const errorToast = new bootstrap.Toast(errorToastElement);
                errorToast.show();
                
                // Alert fallback jika toast tidak muncul
                setTimeout(() => {
                    if (!errorToastElement.classList.contains('show')) {
                        alert('Mohon lengkapi semua field yang wajib diisi!');
                    }
                }, 100);
            } else {
                console.log('Form valid, submitting...');
            }
        });
    });
</script>
</body>
</html>