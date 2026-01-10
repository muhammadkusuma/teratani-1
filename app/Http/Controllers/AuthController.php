<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Menampilkan form register.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses register user baru (Calon Manajer Toko).
     */
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email'    => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms'    => ['required'], // Checkbox syarat & ketentuan
        ]);

        // 2. Simpan ke Database
        $user = User::create([
            'nama_lengkap'  => $validated['name'], // Mapping dari input 'name' ke kolom 'nama_lengkap'
            'username'      => $validated['username'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'is_superadmin' => false, // Default user biasa/manajer toko
            'is_active'     => true,
        ]);

        // 3. Login otomatis setelah register
        Auth::login($user);

        // 4. Redirect ke dashboard
        return redirect()->intended('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }
}
