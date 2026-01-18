@extends('layouts.app')

@section('title', 'Edit Profil - Penghuni')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-900/30 to-emerald-900/30 border border-green-800/30 rounded-2xl p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                    <i class="fas fa-user-edit mr-3 text-green-400"></i>
                    Edit Profil Penghuni
                </h1>
                <p class="text-green-100">Perbarui informasi profil Anda dengan data terbaru</p>
            </div>
            <a href="{{ route('penghuni.profile.show') }}" 
               class="inline-flex items-center px-4 py-2 bg-dark-card/50 border border-dark-border text-white rounded-xl hover:border-green-500 hover:text-green-300 transition mt-4 md:mt-0">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Profil
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
        <form action="{{ route('penghuni.profile.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-white mb-6 pb-4 border-b border-dark-border flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user text-green-400"></i>
                    </div>
                    Data Pribadi
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div class="col-span-2">
                        <label for="nama" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-user-circle text-green-400 mr-2"></i>
                            Nama Lengkap *
                        </label>
                        <div class="relative">
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $penghuni->nama) }}" 
                                   class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition" 
                                   required placeholder="Masukkan nama lengkap">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-signature text-dark-muted"></i>
                            </div>
                        </div>
                        @error('nama')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-at text-green-400 mr-2"></i>
                            Username *
                        </label>
                        <div class="relative">
                            <input type="text" id="username" name="username" value="{{ old('username', $penghuni->username) }}" 
                                   class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition" 
                                   required placeholder="username">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-user text-dark-muted"></i>
                            </div>
                        </div>
                        @error('username')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    

                    
                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-venus-mars text-green-400 mr-2"></i>
                            Jenis Kelamin *
                        </label>
                        <div class="relative">
                            <select id="jenis_kelamin" name="jenis_kelamin" 
                                    class="w-full pl-12 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition appearance-none">
                                <option value="L" {{ old('jenis_kelamin', $penghuni->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $penghuni->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-user text-dark-muted"></i>
                            </div>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted pointer-events-none"></i>
                        </div>
                        @error('jenis_kelamin')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-birthday-cake text-green-400 mr-2"></i>
                            Tanggal Lahir
                        </label>
                        <div class="relative">
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $penghuni->tanggal_lahir) }}" 
                                   class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-calendar text-dark-muted"></i>
                            </div>
                        </div>
                        @error('tanggal_lahir')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-white mb-6 pb-4 border-b border-dark-border flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-address-book text-blue-400"></i>
                    </div>
                    Data Kontak
                </h3>
                
                <div class="space-y-6">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-envelope text-blue-400 mr-2"></i>
                            Email *
                        </label>
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email', $penghuni->email) }}" 
                                   class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition" 
                                   required placeholder="email@contoh.com">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-at text-dark-muted"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Nomor HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-phone text-blue-400 mr-2"></i>
                            Nomor HP *
                        </label>
                        <div class="relative">
                            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', $penghuni->no_hp) }}" 
                                   class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition" 
                                   required placeholder="81234567890">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-mobile-alt text-dark-muted"></i>
                            </div>
                        </div>
                        @error('no_hp')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-map-marker-alt text-blue-400 mr-2"></i>
                            Alamat
                        </label>
                        <div class="relative">
                            <textarea id="alamat" name="alamat" rows="3" 
                                      class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition resize-none" 
                                      placeholder="Alamat lengkap tempat tinggal">{{ old('alamat', $penghuni->alamat) }}</textarea>
                            <div class="absolute left-3 top-3">
                                <i class="fas fa-home text-dark-muted"></i>
                            </div>
                        </div>
                        @error('alamat')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-white mb-6 pb-4 border-b border-dark-border flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500/20 to-orange-500/20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lock text-red-400"></i>
                    </div>
                    Keamanan Akun
                </h3>
                
                <!-- Password Change Note -->
                <div class="bg-red-900/20 border border-red-800/30 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-red-400 text-lg mr-3 mt-0.5"></i>
                        <div>
                            <h4 class="font-semibold text-red-300 mb-1">Ubah Password</h4>
                            <p class="text-red-200/70 text-sm">Kosongkan kolom password jika tidak ingin mengubah password.</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-key text-red-400 mr-2"></i>
                            Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" 
                                   class="w-full pl-12 pr-12 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/30 transition" 
                                   placeholder="Password baru">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-lock text-dark-muted"></i>
                            </div>
                            <button type="button" onclick="togglePassword('password')" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted hover:text-white transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-white mb-3">
                            <i class="fas fa-key text-red-400 mr-2"></i>
                            Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full pl-12 pr-12 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/30 transition" 
                                   placeholder="Ulangi password baru">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-lock text-dark-muted"></i>
                            </div>
                            <button type="button" onclick="togglePassword('password_confirmation')" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-muted hover:text-white transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-dark-border space-y-4 md:space-y-0">
                <div class="w-full md:w-auto">
                    <a href="{{ route('penghuni.profile.show') }}" 
                       class="inline-flex items-center justify-center w-full md:w-auto px-6 py-3 border border-dark-border text-dark-text hover:text-white hover:border-red-500 hover:bg-red-900/20 rounded-xl transition">
                        <i class="fas fa-times mr-2"></i>
                        Batalkan
                    </a>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <button type="button" onclick="resetForm()" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-dark-border text-dark-text hover:text-white hover:border-yellow-500 hover:bg-yellow-900/20 rounded-xl transition">
                        <i class="fas fa-redo mr-2"></i>
                        Reset Form
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

// Format phone number input
document.getElementById('no_hp').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.startsWith('0')) {
        value = value.substring(1);
    }
    e.target.value = value;
});

// Reset form
function resetForm() {
    if (confirm('Anda yakin ingin mengembalikan semua data ke semula?')) {
        window.location.reload();
    }
}



// Input focus effects
const inputs = document.querySelectorAll('input, textarea, select');
inputs.forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('ring-2');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('ring-2');
    });
});
</script>
@endpush

@endsection