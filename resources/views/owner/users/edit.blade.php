@extends('layouts.owner')

@section('title', 'Edit User')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-user-edit text-blue-700"></i> Edit User
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.users.index') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="bg-white border border-gray-300 p-4 md:p-6 max-w-2xl mx-auto shadow-sm rounded-sm">
    <form action="{{ route('owner.users.update', $user->id_user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4 bg-gradient-to-br from-blue-50 to-white border-l-4 border-blue-600 p-4 rounded-sm shadow-sm">
            <label class="block font-black mb-2 text-[10px] text-blue-800 uppercase tracking-wider">Karyawan</label>
            <div class="font-black text-sm text-gray-800">{{ $user->karyawan->nama_lengkap }}</div>
            <div class="text-xs text-gray-600 mt-1">
                <i class="fa fa-briefcase text-blue-400"></i> {{ $user->karyawan->jabatan }}
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                Username <span class="text-red-500">*</span>
            </label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required>
            @error('username')
                <div class="text-red-600 text-xs mt-1 font-semibold">
                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Password Baru</label>
            <input type="password" name="password" 
                class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" 
                minlength="8"
                placeholder="Kosongkan jika tidak ingin mengubah">
            <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                <i class="fa fa-info-circle text-blue-400"></i>
                <span>Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</span>
            </p>
            @error('password')
                <div class="text-red-600 text-xs mt-1 font-semibold">
                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" 
                class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm"
                placeholder="Ulangi password baru">
        </div>

        <div class="flex flex-col md:flex-row justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.users.index') }}" 
                class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit"
                class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 text-xs font-bold hover:bg-blue-600 transition-colors shadow-sm rounded-sm uppercase">
                <i class="fa fa-save"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
