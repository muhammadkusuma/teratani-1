@extends('layouts.owner')

@section('title', 'Tambah User Baru')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-user-plus text-blue-700"></i> Tambah User
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.users.index') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="bg-white border border-gray-300 p-4 md:p-6 max-w-2xl mx-auto shadow-sm rounded-sm">
    <form action="{{ route('owner.users.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                Pilih Karyawan <span class="text-red-500">*</span>
            </label>
            <select name="id_karyawan" class="border border-gray-300 p-2.5 md:p-2 w-full text-xs bg-gray-50 shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required>
                <option value="">-- Pilih Karyawan --</option>
                @foreach ($karyawans as $kry)
                    <option value="{{ $kry->id_karyawan }}" {{ old('id_karyawan') == $kry->id_karyawan ? 'selected' : '' }}>
                        {{ $kry->nama_lengkap }} ({{ $kry->jabatan }})
                    </option>
                @endforeach
            </select>
            @error('id_karyawan')
                <div class="text-red-600 text-xs mt-1 font-semibold">
                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                Username <span class="text-red-500">*</span>
            </label>
            <input type="text" name="username" value="{{ old('username') }}"
                class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" 
                required 
                placeholder="Masukkan username login">
            @error('username')
                <div class="text-red-600 text-xs mt-1 font-semibold">
                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                Password <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password" 
                class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" 
                required
                minlength="8" 
                placeholder="Minimal 8 karakter">
            <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                <i class="fa fa-info-circle text-blue-400"></i>
                <span>Password minimal 8 karakter</span>
            </p>
            @error('password')
                <div class="text-red-600 text-xs mt-1 font-semibold">
                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                Konfirmasi Password <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password_confirmation" 
                class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm"
                required 
                placeholder="Ulangi password">
        </div>

        <div class="flex flex-col md:flex-row justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
            <button type="reset"
                class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-gray-100 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase text-gray-700">
                <i class="fa fa-redo"></i> Reset
            </button>
            <button type="submit"
                class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 text-xs font-bold hover:bg-blue-600 transition-colors shadow-sm rounded-sm uppercase">
                <i class="fa fa-save"></i> Simpan
            </button>
        </div>
    </form>
</div>
@endsection
