@extends('layouts.admin')

@section('title', 'Data Tenant')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Data Tenant</h2>
            <a href="{{ route('tenants.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                + Tambah Tenant
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b">
                <form action="{{ route('tenants.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari nama tenant atau domain..."
                        value="{{ request('search') }}"
                        class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </form>
            </div>

            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ID Tenant</th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Tenant</th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Domain</th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tenants as $tenant)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-mono text-gray-600">
                                {{ $tenant->id_tenant }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold text-gray-800">
                                {{ $tenant->nama_tenant }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-blue-600">
                                {{ $tenant->domain ?? '-' }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @if ($tenant->status === 'active')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('tenants.edit', $tenant->id_tenant) }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                                    <form action="{{ route('tenants.destroy', $tenant->id_tenant) }}" method="POST"
                                        onsubmit="return confirm('Hapus tenant ini? Data terkait mungkin akan hilang.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-5 bg-white border-t">
                {{ $tenants->links() }}
            </div>
        </div>
    </div>
@endsection
