<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('karyawan')
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->whereNotNull('id_karyawan') // Only show employee users
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('owner.users.index', compact('users'));
    }

    public function create()
    {
        // Get employees from the same company who don't have a user account yet
        $karyawans = Karyawan::whereHas('toko', function ($query) {
                $query->where('id_perusahaan', Auth::user()->id_perusahaan);
            })
            ->doesntHave('user')
            ->orderBy('nama_lengkap')
            ->get();

        return view('owner.users.create', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => [
                'required',
                'exists:karyawan,id_karyawan',
                function ($attribute, $value, $fail) {
                    // Start of custom validation closure
                    $karyawan = Karyawan::find($value);
                    if (!$karyawan) return; // handled by exists
                    
                    // Verify karyawan belongs to owner's company
                    if ($karyawan->toko->id_perusahaan != Auth::user()->id_perusahaan) {
                        $fail('Karyawan tidak valid.');
                    }
                    
                    // Verify karyawan doesn't have an account
                    if ($karyawan->user) {
                        $fail('Karyawan ini sudah memiliki akun pengguna.');
                    }
                },
            ],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $karyawan = Karyawan::find($request->id_karyawan);

        User::create([
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'nama_lengkap'  => $karyawan->nama_lengkap,
            'email'         => $karyawan->email,
            'no_hp'         => $karyawan->no_hp,
            'id_perusahaan' => Auth::user()->id_perusahaan,
            'id_karyawan'   => $karyawan->id_karyawan,
            'is_superadmin' => false,
            'is_active'     => true,
        ]);

        return redirect()->route('owner.users.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if ($user->id_perusahaan != Auth::user()->id_perusahaan) {
            abort(403);
        }

        return view('owner.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id_perusahaan != Auth::user()->id_perusahaan) {
            abort(403);
        }

        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('owner.users.index')
            ->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id_perusahaan != Auth::user()->id_perusahaan) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('owner.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
