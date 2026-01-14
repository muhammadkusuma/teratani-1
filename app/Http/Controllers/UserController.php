<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user manual.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan user baru dari admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|unique:users,username|max:50',
            'email'        => 'nullable|email|unique:users,email|max:100',
            'password'     => 'required|string|min:6',
            'no_hp'        => 'nullable|string|max:20',
        ]);

        User::create([
            'nama_lengkap'  => $request->nama_lengkap,
            'username'      => $request->username,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'password'      => Hash::make($request->password),
            'is_superadmin' => $request->has('is_superadmin'),
            'is_active'     => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:50|unique:users,username,' . $id . ',id_user',
            'email'        => 'nullable|email|max:100|unique:users,email,' . $id . ',id_user',
        ]);

        $dataToUpdate = [
            'nama_lengkap'  => $request->nama_lengkap,
            'username'      => $request->username,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'is_superadmin' => $request->has('is_superadmin'),
            'is_active'     => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui');
    }

    /**
     * Hapus user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
