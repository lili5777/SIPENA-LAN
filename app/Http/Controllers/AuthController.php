<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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


    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('admin.akun.index', [
            'user' => $user
        ]);
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
                'new_password_confirmation' => 'required|string',
            ], [
                'current_password.required' => 'Password saat ini harus diisi',
                'new_password.required' => 'Password baru harus diisi',
                'new_password.min' => 'Password baru minimal 8 karakter',
                'new_password.regex' => 'Password harus mengandung huruf besar, kecil, dan angka',
                'new_password.confirmed' => 'Konfirmasi password tidak cocok',
                'new_password_confirmation.required' => 'Konfirmasi password harus diisi',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->route('admin.akun.index')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', $validator->errors()->first());
            }

            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()
                    ->route('admin.akun.index')
                    ->with('error', 'Password saat ini tidak sesuai');
            }

            if (Hash::check($request->new_password, $user->password)) {
                return redirect()
                    ->route('admin.akun.index')
                    ->with('error', 'Password baru tidak boleh sama dengan password lama');
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()
                ->route('admin.akun.index')
                ->with('success', 'Password berhasil diubah');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.akun.index')
                ->with('error', 'Terjadi kesalahan saat mengubah password');
        }
    }
}
