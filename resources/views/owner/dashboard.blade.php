@extends('layouts.owner')

@section('content')
    <div class="space-y-8">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Owner</h1>
            <p class="text-sm text-gray-500 mt-1">
                Selamat datang, <span class="font-medium">{{ Auth::user()->name }}</span>
            </p>
        </div>

        <!-- Alert Success -->
        @if (session('success'))
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                <svg class="w-5 h-5 mt-0.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1 text-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Card Toko Aktif -->
        <div class="bg-gradient-to-br from-green-600 to-green-500 text-white rounded-2xl shadow-lg">
            <div class="p-6 md:p-8 flex items-center justify-between gap-6">
                <div>
                    <p class="text-sm text-green-100">Toko Aktif Saat Ini</p>

                    @if (session('toko_active_nama'))
                        <h2 class="text-3xl font-bold mt-2">
                            {{ session('toko_active_nama') }}
                        </h2>
                    @else
                        <p class="mt-2 text-lg font-semibold">
                            Belum ada toko dipilih
                        </p>
                        <p class="text-sm text-green-100 mt-1">
                            Silakan pilih toko untuk mulai mengelola transaksi
                        </p>
                    @endif
                </div>

                <div class="hidden md:block opacity-30">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 21h18M3 7l9-4 9 4M4 10h16v11H4z" />
                    </svg>
                </div>
            </div>

            <div class="px-6 pb-6">
                <a href="{{ route('owner.toko.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-white/90 hover:text-white">
                    {{ session('toko_active_id') ? 'Ganti Toko' : 'Pilih Toko' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Quick Menu -->
        @if (session('toko_active_id'))
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Menu Cepat</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

                    <!-- POS -->
                    <a href="#" class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-md border transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Penjualan</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    Kasir (POS)
                                </p>
                            </div>
                            <div class="p-3 rounded-xl bg-green-100 text-green-600 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a5 5 0 00-10 0v2M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Stok -->
                    <a href="#" class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-md border transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Produk</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    Stok Barang
                                </p>
                            </div>
                            <div class="p-3 rounded-xl bg-yellow-100 text-yellow-600 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6M4 13h16v6H4z" />
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Laporan -->
                    <a href="#" class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-md border transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Keuangan</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    Laporan Harian
                                </p>
                            </div>
                            <div class="p-3 rounded-xl bg-red-100 text-red-600 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-6m4 6V7m4 10v-4" />
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Pengaturan -->
                    <a href="{{ route('owner.toko.edit', session('toko_active_id')) }}"
                        class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-md border transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Toko</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    Pengaturan
                                </p>
                            </div>
                            <div class="p-3 rounded-xl bg-blue-100 text-blue-600 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 10a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
        @endif

    </div>
@endsection
