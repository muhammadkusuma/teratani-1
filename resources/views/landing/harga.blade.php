@extends('layouts.landing')

@section('title', 'Harga Paket Toko')

@section('content')
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="text-base font-semibold leading-7 text-green-600">Biaya Langganan</h2>
                <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Investasi Murah untuk Usaha Tani
                </p>
            </div>
            <div
                class="isolate mx-auto mt-16 grid max-w-md grid-cols-1 gap-y-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3 lg:gap-x-8">

                <div class="rounded-3xl p-8 ring-1 ring-gray-200 xl:p-10">
                    <h3 class="text-lg font-semibold leading-8 text-gray-900">Kios Pemula</h3>
                    <p class="mt-4 text-sm leading-6 text-gray-600">Untuk kios pupuk kecil atau startup.</p>
                    <p class="mt-6 flex items-baseline gap-x-1"><span
                            class="text-4xl font-bold tracking-tight text-gray-900">Gratis</span></p>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Maks. 100 Produk</li>
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Kasir Dasar</li>
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Laporan Penualan Harian</li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="mt-8 block rounded-md bg-green-50 px-3 py-2 text-center text-sm font-semibold leading-6 text-green-600 hover:bg-green-100 ring-1 ring-inset ring-green-200">Daftar
                        Sekarang</a>
                </div>

                <div class="rounded-3xl p-8 ring-2 ring-green-600 bg-green-50/20 xl:p-10 relative">
                    <div
                        class="absolute top-0 right-0 -mt-2 -mr-2 bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                        Paling Laris</div>
                    <h3 class="text-lg font-semibold leading-8 text-green-600">Juragan Tani</h3>
                    <p class="mt-4 text-sm leading-6 text-gray-600">Fitur lengkap untuk toko yang sibuk.</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span class="text-4xl font-bold tracking-tight text-gray-900">Rp 50.000</span>
                        <span class="text-sm font-semibold leading-6 text-gray-600">/bulan</span>
                    </p>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Produk Unlimited</li>
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> <strong>Fitur Hutang/Bon
                                Pelanggan</strong></li>
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Manajemen Stok Expired</li>
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Laporan Laba Rugi</li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="mt-8 block rounded-md bg-green-600 px-3 py-2 text-center text-sm font-semibold leading-6 text-white hover:bg-green-500 shadow-sm">Mulai
                        Langganan</a>
                </div>

                <div class="rounded-3xl p-8 ring-1 ring-gray-200 xl:p-10">
                    <h3 class="text-lg font-semibold leading-8 text-gray-900">Distributor</h3>
                    <p class="mt-4 text-sm leading-6 text-gray-600">Untuk agen besar dengan banyak cabang.</p>
                    <p class="mt-6 flex items-baseline gap-x-1"><span
                            class="text-4xl font-bold tracking-tight text-gray-900">Hubungi Kami</span></p>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Semua Fitur Juragan</li>
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Multi-Gudang & Cabang</li>
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Manajemen Karyawan/Kasir</li>
                        <li class="flex gap-x-3"><span class="text-green-600">✓</span> Prioritas Support</li>
                    </ul>
                    <a href="{{ url('/kontak') }}"
                        class="mt-8 block rounded-md bg-white px-3 py-2 text-center text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 ring-1 ring-inset ring-gray-200">Kontak
                        Tim Sales</a>
                </div>

            </div>
        </div>
    </div>
@endsection
