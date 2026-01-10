@extends('layouts.admin')

@section('title', 'Data Tenant')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Tenant</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data tenant dan status aplikasinya
                </p>
            </div>

            <a href="{{ route('tenants.create') }}"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Tenant
            </a>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                <svg class="w-5 h-5 mt-0.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            <!-- Search -->
            <div class="p-4 border-b">
                <form action="{{ route('tenants.index') }}" method="GET" class="flex gap-3">
                    <input type="text" name="search" placeholder="Cari nama tenant atau domain..."
                        value="{{ request('search') }}"
                        class="w-full md:w-80 px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:outline-none">
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                ID Tenant
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Nama Tenant
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Domain
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Status
                            </th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse ($tenants as $tenant)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 font-mono text-gray-600">
                                    {{ $tenant->id_tenant }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-gray-900">
                                    {{ $tenant->nama_bisnis }}
                                </td>
                                <td class="px-5 py-4 text-blue-600">
                                    {{ $tenant->kode_unik_tenant ?? '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($tenant->status === 'active')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            ● Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            ● Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex justify-center gap-4">
                                        <a href="{{ route('tenants.edit', $tenant->id_tenant) }}"
                                            class="text-blue-600 hover:underline font-medium">
                                            Edit
                                        </a>
                                        <form action="{{ route('tenants.destroy', $tenant->id_tenant) }}" method="POST"
                                            onsubmit="return confirm('Hapus tenant ini? Data terkait mungkin akan hilang.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                                    Tidak ada data tenant
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-5 py-4 border-t bg-gray-50">
                {{ $tenants->links() }}
            </div>

        </div>
    </div>
@endsection
