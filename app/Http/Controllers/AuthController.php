<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->is_superadmin) {
                return redirect()->intended('dashboard');
            } else {
                // Get Jabatan from Accessor or Relation
                $jabatan = $user->jabatan; // Assumes getJabatanAttribute logic handles logic

                if (in_array($jabatan, ['Kasir', 'Sales'])) {
                    return redirect()->intended(route('owner.kasir.index'));
                }

                if (in_array($jabatan, ['Staff Gudang'])) {
                    return redirect()->intended(route('owner.stok.index'));
                }

                return redirect()->intended('owner/dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email'    => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms'    => ['required'],
        ]);

        $user = User::create([
            'nama_lengkap'  => $validated['name'],
            'username'      => $validated['username'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'is_superadmin' => false,
            'is_active'     => true,
        ]);

        Auth::login($user);

        return redirect()->route('owner.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
