@extends('layouts.app')

@section('title', 'Edit Profil - Penghuni')

@section('content')
<div class="space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-900/30 to-emerald-900/30 border border-green-800/30 rounded-2xl p-6 mb-6">
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
            <form action="{{ route('penghuni.profile.update') }}" method="POST" class="p-6 md:p-8"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                

                <!-- Personal Information Section -->
                <div class="mb-8">
                    <h3
                        class="text-lg md:text-xl font-semibold text-white mb-6 pb-4 border-b border-dark-border flex items-center">
                        <i class="fas fa-user-circle text-green-400 mr-3"></i>
                        Data Pribadi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div class="col-span-2">
                            <label for="nama" class="block text-sm font-medium text-white mb-2">
                                Nama Lengkap *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-dark-muted"></i>
                                </div>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', $penghuni->nama) }}"
                                    class="w-full pl-10 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                    required placeholder="Masukkan nama lengkap">
                            </div>
                            @error('nama')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-white mb-2">
                                Username *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-at text-dark-muted"></i>
                                </div>
                                <input type="text" id="username" name="username"
                                    value="{{ old('username', $penghuni->username) }}"
                                    class="w-full pl-10 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                    required placeholder="username">
                            </div>
                            @error('username')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-venus-mars mr-2"></i>Jenis Kelamin *
                            </label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition appearance-none">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $penghuni->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $penghuni->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-calendar-alt mr-2"></i>Tanggal Lahir
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-birthday-cake text-dark-muted"></i>
                                </div>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $penghuni->tanggal_lahir ? \Carbon\Carbon::parse($penghuni->tanggal_lahir)->format('Y-m-d') : '') }}"
                                    class="w-full pl-10 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                            </div>
                            @error('tanggal_lahir')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="mb-8">
                    <h3
                        class="text-lg md:text-xl font-semibold text-white mb-6 pb-4 border-b border-dark-border flex items-center">
                        <i class="fas fa-address-book text-blue-400 mr-3"></i>
                        Data Kontak
                    </h3>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-white mb-2">
                                Email *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-dark-muted"></i>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email', $penghuni->email) }}"
                                    class="w-full pl-10 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    required placeholder="email@contoh.com">
                            </div>
                            @error('email')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-white mb-2">
                                Nomor HP *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-dark-muted">+62</span>
                                </div>
                                <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', $penghuni->no_hp) }}"
                                    class="w-full pl-14 pr-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    required placeholder="81234567890">
                            </div>
                            @error('no_hp')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                            </label>
                            <textarea id="alamat" name="alamat" rows="3"
                                class="w-full px-4 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
                                placeholder="Alamat lengkap tempat tinggal">{{ old('alamat', $penghuni->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="mb-8">
                    <h3
                        class="text-lg md:text-xl font-semibold text-white mb-6 pb-4 border-b border-dark-border flex items-center">
                        <i class="fas fa-shield-alt text-red-400 mr-3"></i>
                        Keamanan Akun
                    </h3>

                    <!-- Security Info -->
                    <div class="bg-red-900/20 border border-red-800/30 rounded-xl p-5 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-red-400 text-lg mr-3 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-white mb-1">Ubah Password</h4>
                                <p class="text-red-200 text-sm">Kosongkan kolom password jika tidak ingin mengubah
                                    password Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-white mb-2">
                                Password Baru
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-dark-muted"></i>
                                </div>
                                <input type="password" id="password" name="password"
                                    class="w-full pl-10 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                    placeholder="Password baru">
                                <button type="button" onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-dark-muted hover:text-white transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-dark-muted"></i>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full pl-10 pr-10 py-3 bg-dark-bg border border-dark-border text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                    placeholder="Ulangi password baru">
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-dark-muted hover:text-white transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="flex flex-col-reverse md:flex-row justify-between items-center pt-8 border-t border-dark-border">
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('penghuni.profile.show') }}"
                            class="px-6 py-3 border border-dark-border text-white rounded-xl hover:bg-dark-border/50 transition inline-flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Batalkan
                        </a>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="resetForm()"
                            class="px-6 py-3 border border-dark-border text-white rounded-xl hover:bg-dark-border/50 transition inline-flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Reset Form
                        </button>
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 inline-flex items-center justify-center">
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
                const button = field.nextElementSibling;
                const icon = button.querySelector('i');

                if (field.type === 'password') {
                    field.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    field.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }

            // Format phone number input
            const phoneField = document.getElementById('no_hp');
            if (phoneField) {
                phoneField.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.startsWith('0')) {
                        value = value.substring(1);
                    }
                    if (value.length > 12) {
                        value = value.substring(0, 12);
                    }
                    e.target.value = value;
                });
            }

            // Image preview
            function previewImage(event) {
                const input = event.target;
                const preview = document.getElementById('previewImage');
                const previewContainer = document.getElementById('imagePreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Reset form
            function resetForm() {
                if (confirm('Anda yakin ingin mengembalikan semua perubahan ke nilai awal?')) {
                    window.location.reload();
                }
            }

            // Initialize form validation
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.querySelector('form');
                if (form) {
                    // Add validation for required fields
                    const requiredFields = form.querySelectorAll('[required]');
                    requiredFields.forEach(field => {
                        field.addEventListener('blur', function () {
                            if (!this.value.trim()) {
                                this.classList.add('border-red-500');
                            } else {
                                this.classList.remove('border-red-500');
                            }
                        });
                    });
                }
            });
        </script>
    @endpush

@endsection