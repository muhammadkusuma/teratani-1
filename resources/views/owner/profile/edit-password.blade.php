@extends('layouts.owner')

@section('title', 'Ubah Password')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-key"></i> UBAH PASSWORD
    </h2>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white border border-gray-400 p-4 max-w-md">
    <form action="{{ route('owner.profile.update-password') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="block text-xs font-bold mb-1">Password Lama <span class="text-red-600">*</span></label>
            <input type="password" name="current_password" required
                   class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            @error('current_password')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label class="block text-xs font-bold mb-1">Password Baru <span class="text-red-600">*</span></label>
            <input type="password" name="new_password" required
                   class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            @error('new_password')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-xs mt-1">Minimal 6 karakter</p>
        </div>

        <div class="mb-4">
            <label class="block text-xs font-bold mb-1">Konfirmasi Password Baru <span class="text-red-600">*</span></label>
            <input type="password" name="new_password_confirmation" required
                   class="w-full border border-gray-400 p-1 text-xs shadow-inner">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-700 text-white border border-blue-900 px-3 py-1 text-xs hover:bg-blue-600">
                SIMPAN PASSWORD
            </button>
            <a href="{{ route('owner.dashboard') }}" class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300">
                BATAL
            </a>
        </div>
    </form>
</div>

<div class="mt-4 bg-yellow-50 border border-yellow-400 p-3 text-xs max-w-md">
    <strong><i class="fa fa-exclamation-triangle"></i> Perhatian:</strong>
    <ul class="list-disc ml-4 mt-1">
        <li>Pastikan Anda mengingat password baru</li>
        <li>Password minimal 6 karakter</li>
        <li>Setelah ubah password, Anda tetap login</li>
    </ul>
</div>
@endsection
