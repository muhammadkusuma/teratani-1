@extends('layouts.owner')

@section('title', 'Tambah User Baru')

@section('content')
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block">TAMBAH USER BARU</h2>
        <a href="{{ route('owner.users.index') }}"
            class="px-3 py-1 bg-gray-600 text-white border border-gray-800 shadow hover:bg-gray-500 text-xs no-underline">
            &lt; KEMBALI
        </a>
    </div>

    <div class="bg-white border border-gray-400 p-4 max-w-2xl mx-auto shadow-sm">
        <form action="{{ route('owner.users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block font-bold mb-1 text-xs">PILIH KARYAWAN <span class="text-red-500">*</span></label>
                <select name="id_karyawan" class="border border-gray-400 p-1.5 w-full text-xs bg-white" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach ($karyawans as $kry)
                        <option value="{{ $kry->id_karyawan }}" {{ old('id_karyawan') == $kry->id_karyawan ? 'selected' : '' }}>
                            {{ $kry->nama_lengkap }} ({{ $kry->jabatan }})
                        </option>
                    @endforeach
                </select>
                @error('id_karyawan')
                    <div class="text-red-600 text-[10px] mt-0.5">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block font-bold mb-1 text-xs">USERNAME <span class="text-red-500">*</span></label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="border border-gray-400 p-1.5 w-full text-xs" required placeholder="Masukkan username login">
                @error('username')
                    <div class="text-red-600 text-[10px] mt-0.5">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block font-bold mb-1 text-xs">PASSWORD <span class="text-red-500">*</span></label>
                <input type="password" name="password" class="border border-gray-400 p-1.5 w-full text-xs" required
                    minlength="8" placeholder="Minimal 8 karakter">
                @error('password')
                    <div class="text-red-600 text-[10px] mt-0.5">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1 text-xs">KONFIRMASI PASSWORD <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" class="border border-gray-400 p-1.5 w-full text-xs"
                    required placeholder="Ulangi password">
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-200">
                <button type="reset"
                    class="px-4 py-1.5 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">RESET</button>
                <button type="submit"
                    class="px-4 py-1.5 bg-blue-700 text-white border border-blue-900 text-xs hover:bg-blue-600 shadow-sm">SIMPAN</button>
            </div>
        </form>
    </div>
@endsection
