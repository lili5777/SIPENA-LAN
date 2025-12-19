<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        // Cek apakah pengguna sudah login
        if (Auth::check()) {
            return redirect()->route('dashboard'); // Redirect ke dashboard jika sudah login
        }

        return view('auth.login');
    }

    public function proses_login(Request $request)
    {
        // Validasi input email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // Cek kredensial untuk login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();  // Regenerasi session untuk keamanan
            return redirect()->intended('dashboard'); // Redirect ke dashboard jika berhasil login
        }

        // Jika login gagal, kembali dengan error
        return back()->with('error', 'Email atau password salah!')->withInput();
    }

    public function logout(Request $request)
    {
        // Proses logout
        Auth::logout();
        $request->session()->invalidate();  // Hapus session
        $request->session()->regenerateToken();  // Regenerasi token CSRF
        return redirect('/login');  // Arahkan kembali ke halaman login setelah logout
    }
}
