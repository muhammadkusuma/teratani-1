@extends('layouts.admin')

@section('title', 'Tambah Pengaturan')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Config Baru</h2>

        <form action="{{ route('settings.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Label (Nama Pengaturan)</label>
                <input type="text" name="label" value="{{ old('label') }}" placeholder="Contoh: Nama Aplikasi"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('label')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Key (Kunci Unik)</label>
                <input type="text" name="key" value="{{ old('key') }}" placeholder="Contoh: app_name"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 font-mono">
                <p class="text-xs text-gray-500 mt-1">Gunakan huruf kecil dan underscore (_). Tanpa spasi.</p>
                @error('key')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Data</label>
                <select name="type"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="text">Text Singkat</option>
                    <option value="textarea">Text Panjang</option>
                    <option value="number">Angka</option>
                    <option value="boolean">Switch (ON/OFF)</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Value (Nilai)</label>
                <textarea name="value" rows="3"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('value') }}</textarea>
                @error('value')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">Simpan</button>
            </div>
        </form>
    </div>
@endsection
