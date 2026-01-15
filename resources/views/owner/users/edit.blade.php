@extends('layouts.owner')

@section('title', 'Edit User')

@section('content')
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block">EDIT USER</h2>
        <a href="{{ route('owner.users.index') }}"
            class="px-3 py-1 bg-gray-600 text-white border border-gray-800 shadow hover:bg-gray-500 text-xs no-underline">
            &lt; KEMBALI
        </a>
    </div>

    <div class="bg-white border border-gray-400 p-4 max-w-2xl mx-auto shadow-sm">
        <form action="{{ route('owner.users.update', $user->id_user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3 bg-gray-100 p-3 border border-gray-300">
                <label class="block font-bold mb-1 text-xs text-gray-600">KARYAWAN</label>
                <div class="font-bold text-sm">{{ $user->karyawan->nama_lengkap }}</div>
                <div class="text-[10px] text-gray-500">{{ $user->karyawan->jabatan }}</div>
            </div>

            <div class="mb-3">
                <label class="block font-bold mb-1 text-xs">USERNAME <span class="text-red-500">*</span></label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                    class="border border-gray-400 p-1.5 w-full text-xs" required>
                @error('username')
                    <div class="text-red-600 text-[10px] mt-0.5">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block font-bold mb-1 text-xs">PASSWORD BARU</label>
                <input type="password" name="password" class="border border-gray-400 p-1.5 w-full text-xs" minlength="8"
                    placeholder="Kosongkan jika tidak ingin mengubah">
                <p class="text-[10px] text-gray-500 mt-0.5">Minimal 8 karakter.</p>
                @error('password')
                    <div class="text-red-600 text-[10px] mt-0.5">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1 text-xs">KONFIRMASI PASSWORD BARU</label>
                <input type="password" name="password_confirmation" class="border border-gray-400 p-1.5 w-full text-xs"
                    placeholder="Ulangi password baru">
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-200">
                <button type="submit"
                    class="px-4 py-1.5 bg-blue-700 text-white border border-blue-900 text-xs hover:bg-blue-600 shadow-sm">UPDATE</button>
            </div>
        </form>
    </div>
@endsection
