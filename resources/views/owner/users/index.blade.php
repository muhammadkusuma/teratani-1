@extends('layouts.owner')

@section('title', 'Daftar Akun Pengguna')

@section('content')
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block">DAFTAR AKUN PENGGUNA</h2>
        </div>
        <a href="{{ route('owner.users.create') }}"
            class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
            + TAMBAH USER
        </a>
    </div>

    <div class="overflow-x-auto border border-gray-400 bg-white">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <th class="border border-gray-400 p-2 text-center w-10">No</th>
                    <th class="border border-gray-400 p-2">Username</th>
                    <th class="border border-gray-400 p-2">Nama Karyawan</th>
                    <th class="border border-gray-400 p-2">Jabatan</th>
                    <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr class="hover:bg-yellow-50 text-xs">
                        <td class="border border-gray-300 p-2 text-center">{{ $users->firstItem() + $index }}</td>
                        <td class="border border-gray-300 p-2 font-bold">{{ $user->username }}</td>
                        <td class="border border-gray-300 p-2">{{ $user->karyawan->nama_lengkap ?? '-' }}</td>
                        <td class="border border-gray-300 p-2">{{ $user->karyawan->jabatan ?? '-' }}</td>
                        <td class="border border-gray-300 p-2 text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('owner.users.edit', $user->id_user) }}"
                                    class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300 text-black no-underline">EDIT</a>
                                <form action="{{ route('owner.users.destroy', $user->id_user) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white border border-red-800 px-2 py-0.5 text-[10px] hover:bg-red-500">HAPUS</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada akun
                            pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 text-xs">
        {{ $users->links() }}
    </div>
@endsection
