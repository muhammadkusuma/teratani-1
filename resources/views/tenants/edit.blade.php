@extends('layouts.admin')

@section('title', 'Edit Tenant')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Tenant</h2>
            <span class="text-sm text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded">ID: {{ $tenant->id_tenant }}</span>
        </div>

        <form action="{{ route('tenants.update', $tenant->id_tenant) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tenant</label>
                <input type="text" name="nama_tenant" value="{{ old('nama_tenant', $tenant->nama_bisnis) }}"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('nama_tenant')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Domain (Kode Unik)</label>
                <input type="text" name="domain" value="{{ old('domain', $tenant->kode_unik_tenant) }}"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('domain')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="active"
                        {{ old('status') == 'active' || $tenant->status_langganan == 'Aktif' ? 'selected' : '' }}>Active
                    </option>
                    <option value="inactive"
                        {{ old('status') == 'inactive' || $tenant->status_langganan != 'Aktif' ? 'selected' : '' }}>
                        Inactive</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('tenants.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Perbarui</button>
            </div>
        </form>
    </div>
@endsection
