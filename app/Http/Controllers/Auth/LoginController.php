<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penghuni;
use App\Models\Pemilik; 

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:penghuni,pemilik'
        ]);

        $credentials = $request->only('username', 'password');
        $role = $request->role;
        $remember = $request->has('remember'); // TAMBAH REMEMBER ME

        if ($role === 'penghuni') {
            if (Auth::guard('penghuni')->attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->route('penghuni.dashboard')
                    ->with('success', 'Login sebagai penghuni berhasil!');
            }
        } else {
            if (Auth::guard('pemilik')->attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->route('pemilik.dashboard')
                    ->with('success', 'Login sebagai pemilik berhasil!');
            }
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->withInput($request->only('username', 'role'));
    }

    public function logout(Request $request)
    {
        $userType = '';
        
        // Deteksi role dan logout
        if (Auth::guard('penghuni')->check()) {
            $userType = 'Penghuni';
            Auth::guard('penghuni')->logout();
        } elseif (Auth::guard('pemilik')->check()) {
            $userType = 'Pemilik';
            Auth::guard('pemilik')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', "Logout $userType berhasil! Sampai jumpa kembali.");
    }
}