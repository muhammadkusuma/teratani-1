<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user.
     */
    public function index(Request $request)
    {
        // Fitur pencarian sederhana
        $query = User::query();
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Pagination 10 item per halaman
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'      => 'required|string|max:50|unique:users,username',
            'password'      => 'required|string|min:6',
            'nama_lengkap'  => 'nullable|string|max:100',
            'email'         => 'nullable|email|max:100|unique:users,email',
            'no_hp'         => 'nullable|string|max:20',
            'role'          => 'required|in:admin,user', // Mapping is_superadmin
            'status'        => 'required|in:active,inactive', // Mapping is_active
        ]);

        User::create([
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'is_superadmin' => $request->role === 'admin',
            'is_active'     => $request->status === 'active',
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username'      => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'nama_lengkap'  => 'nullable|string|max:100',
            'email'         => ['nullable', 'email', 'max:100', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'no_hp'         => 'nullable|string|max:20',
            'role'          => 'required|in:admin,user',
            'status'        => 'required|in:active,inactive',
            'password'      => 'nullable|string|min:6', // Password opsional saat update
        ]);

        $data = [
            'username'      => $request->username,
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'is_superadmin' => $request->role === 'admin',
            'is_active'     => $request->status === 'active',
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Menghapus user.
     */
    public function destroy(User $user)
    {
        // Mencegah menghapus diri sendiri
        if (auth()->id() == $user->id_user) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}