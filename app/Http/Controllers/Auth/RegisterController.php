<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Penghuni;
use App\Models\Pemilik;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input (dasar) - semua field yang harus diisi untuk kedua role
        $rules = [
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:100',
            'username' => 'required|string|max:50',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_hp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'role' => 'required|in:penghuni,pemilik',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'foto_profil' => 'nullable|image|max:2048'
        ];

        $request->validate($rules);

        // Cek unique constraints manual untuk email/username di kedua tabel
        $emailExists = Penghuni::where('email', $request->email)->exists() || 
                      Pemilik::where('email', $request->email)->exists();
        $usernameExists = Penghuni::where('username', $request->username)->exists() || 
                         Pemilik::where('username', $request->username)->exists();

        if ($emailExists) {
            return back()->withErrors(['email' => 'Email sudah digunakan.'])->withInput();
        }

        if ($usernameExists) {
            return back()->withErrors(['username' => 'Username sudah digunakan.'])->withInput();
        }

        try {
            if ($request->role === 'penghuni') {
                $fotoProfilPath = null;
                if ($request->hasFile('foto_profil')) {
                    $fotoProfilPath = $request->file('foto_profil')->store('profiles', 'public');
                }

                $user = Penghuni::create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'no_hp' => $request->no_hp,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'alamat' => $request->alamat,
                    'foto_profil' => $fotoProfilPath,
                    'status_penghuni' => 'calon',
                    'role' => 'penghuni'
                ]);
                
                Auth::guard('penghuni')->login($user);
                return redirect()->route('penghuni.dashboard')
                    ->with('success', 'Registrasi penghuni berhasil!');
                    
            } else {
                $fotoProfilPath = null;
                if ($request->hasFile('foto_profil')) {
                    $fotoProfilPath = $request->file('foto_profil')->store('profiles', 'public');
                }

                $user = Pemilik::create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'no_hp' => $request->no_hp,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'alamat' => $request->alamat,
                    'foto_profil' => $fotoProfilPath,
                    'status_pemilik' => 'pending',
                    'role' => 'pemilik'
                ]);
                
                Auth::guard('pemilik')->login($user);
                return redirect()->route('pemilik.dashboard')
                    ->with('success', 'Registrasi pemilik berhasil!');
            }
        } catch (\Exception $e) {
            return back()->withErrors([
                'register' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage(),
            ])->withInput();
        }
    }
}