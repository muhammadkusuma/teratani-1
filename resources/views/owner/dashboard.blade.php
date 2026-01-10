@extends('layouts.owner')

@section('title', 'Dashboard Toko')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->nama_lengkap }}! 👋</h1>
            <p class="text-gray-500 mt-1">Berikut adalah ringkasan performa tokomu hari ini.</p>
        </div>
        <div class="hidden sm:block">
            <span class="bg-white border px-4 py-2 rounded-lg text-sm text-gray-600 shadow-sm">
                📅 {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    @if (!$tenant)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Akun kamu belum terhubung dengan data Toko/Tenant manapun. Silakan hubungi admin atau <a
                            href="{{ route('tenants.create') }}" class="font-bold underline">daftarkan tokomu</a>.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 p-3 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">+12%</span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Penjualan Hari Ini</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp 2.450.000</p>
                <p class="text-xs text-gray-400 mt-2">18 Transaksi berhasil</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-red-100 p-3 rounded-lg text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded">Action Needed</span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Stok Menipis</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">5 Barang</p>
                <p class="text-xs text-gray-400 mt-2">Segera lakukan restock</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Piutang</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp 850.000</p>
                <p class="text-xs text-gray-400 mt-2">Dari 3 Pelanggan</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-100 p-3 rounded-lg text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Cabang Toko</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $tenant->toko_count ?? 0 }} Cabang</p>
                <p class="text-xs text-gray-400 mt-2">Bisnis: {{ $tenant->nama_bisnis ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Transaksi Terakhir</h2>
                    <a href="#" class="text-sm text-green-600 hover:text-green-700 font-medium">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 font-medium">No Faktur</th>
                                <th class="p-4 font-medium">Pelanggan</th>
                                <th class="p-4 font-medium">Total</th>
                                <th class="p-4 font-medium">Status</th>
                                <th class="p-4 font-medium">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <tr>
                                <td class="p-4 font-medium text-gray-800">TRX-001</td>
                                <td class="p-4 text-gray-600">Bpk. Budi (Umum)</td>
                                <td class="p-4 font-bold text-gray-800">Rp 150.000</td>
                                <td class="p-4"><span
                                        class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Lunas</span>
                                </td>
                                <td class="p-4 text-gray-500">10:45</td>
                            </tr>
                            <tr>
                                <td class="p-4 font-medium text-gray-800">TRX-002</td>
                                <td class="p-4 text-gray-600">Kelompok Tani Makmur</td>
                                <td class="p-4 font-bold text-gray-800">Rp 2.100.000</td>
                                <td class="p-4"><span
                                        class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">Piutang</span>
                                </td>
                                <td class="p-4 text-gray-500">11:20</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-6 text-white shadow-lg">
                    <h2 class="text-lg font-bold mb-2">Mulai Jualan?</h2>
                    <p class="text-green-100 text-sm mb-4">Buka halaman kasir untuk mencatat transaksi baru dengan cepat.
                    </p>
                    <a href="#"
                        class="block w-full bg-white text-green-700 text-center font-bold py-3 rounded-lg hover:bg-green-50 transition shadow">
                        Buka Kasir
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Shortcut</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#"
                            class="flex flex-col items-center justify-center p-4 border rounded-lg hover:border-green-500 hover:bg-green-50 transition cursor-pointer group">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-green-600 mb-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-xs font-medium text-gray-600 group-hover:text-green-700">Tambah Produk</span>
                        </a>
                        <a href="#"
                            class="flex flex-col items-center justify-center p-4 border rounded-lg hover:border-green-500 hover:bg-green-50 transition cursor-pointer group">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-green-600 mb-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-xs font-medium text-gray-600 group-hover:text-green-700">Pelanggan
                                Baru</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
