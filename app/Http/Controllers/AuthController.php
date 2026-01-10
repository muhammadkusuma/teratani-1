<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba melakukan login
        // Auth::attempt secara otomatis akan menghash password input dan membandingkannya dengan di DB
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Pengecekan Level Superadmin
            // Mengambil status dari kolom is_superadmin yang ada di tabel users
            if (Auth::user()->is_superadmin) {
                return redirect()->intended('dashboard');
            }

            // Jika user valid tapi bukan superadmin, logout paksa
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'username' => 'Akun Anda tidak memiliki hak akses Superadmin.',
            ])->onlyInput('username');
        }

        // Jika kredensial salah
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
