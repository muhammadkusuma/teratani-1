@extends('layouts.admin')

@section('title', 'Tambah Tenant')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Tenant Baru</h2>

        <form action="{{ route('tenants.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">ID Tenant (Kode Unik)</label>
                <input type="text" name="id_tenant" value="{{ old('id_tenant') }}" placeholder="Contoh: mitra-tani-01"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('id_tenant') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">Gunakan huruf, angka, atau tanda strip (-). Tidak boleh ada spasi.</p>
                @error('id_tenant')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tenant</label>
                <input type="text" name="nama_tenant" value="{{ old('nama_tenant') }}"
                    placeholder="Contoh: Toko Tani Makmur"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('nama_tenant')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Domain (Opsional)</label>
                <input type="text" name="domain" value="{{ old('domain') }}" placeholder="toko.teratani.com"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('domain')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('tenants.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">Simpan</button>
            </div>
        </form>
    </div>
@endsection
