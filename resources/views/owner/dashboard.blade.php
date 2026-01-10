@extends('layouts.owner')

@section('title', 'Dashboard Operasional')

@section('content')
    <div style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; min-height: 100vh; background-color: #e0e0e0;">

        {{-- 1. HEADER UTAMA --}}
        <div
            class="bg-teal-700 text-white px-2 py-2 border-b-2 border-white border-t-2 border-t-teal-500 shadow-md flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-white text-teal-700 font-bold px-1 border border-gray-600">SYS</div>
                <div>
                    <h1 class="text-sm font-bold tracking-wider uppercase">Executive Information System</h1>
                    <p class="text-[9px] text-teal-200">Panel Kontrol Utama - {{ session('toko_active_nama') ?? 'Pusat' }}
                    </p>
                </div>
            </div>
            <div class="text-right">
                <span class="block font-bold text-yellow-300">{{ Auth::user()->name }} [OWNER]</span>
                <span class="block text-[9px]">{{ date('d M Y') }} | <span id="clock">{{ date('H:i') }}</span>
                    WIB</span>
            </div>
        </div>

        {{-- 2. NAVIGASI BAR (Tab Style) --}}
        <div class="bg-gray-200 pt-1 px-1 border-b border-gray-600 flex gap-1 select-none">
            <a href="#"
                class="bg-white border-t border-l border-r border-gray-600 px-4 py-1 font-bold text-black relative top-[1px] z-10">
                DASHBOARD
            </a>
            <a href="#"
                class="bg-gray-300 border-t border-l border-r border-gray-500 px-4 py-1 text-gray-600 hover:bg-gray-100">
                KASIR / POS
            </a>
            @if (session('toko_active_id'))
                <a href="{{ route('owner.toko.produk.index', session('toko_active_id')) }}"
                    class="bg-gray-300 border-t border-l border-r border-gray-500 px-4 py-1 text-gray-600 hover:bg-gray-100">
                    INVENTORY
                </a>
                <a href="#"
                    class="bg-gray-300 border-t border-l border-r border-gray-500 px-4 py-1 text-gray-600 hover:bg-gray-100">
                    LAPORAN KEUANGAN
                </a>
            @endif
            <a href="{{ route('owner.toko.index') }}"
                class="ml-auto bg-red-700 text-white px-3 py-1 font-bold text-[10px] border border-black hover:bg-red-600">
                LOG OUT / GANTI TOKO
            </a>
        </div>

        {{-- 3. KONTEN UTAMA --}}
        <div class="p-2">

            @if (!session('toko_active_id'))
                <div class="bg-yellow-100 border border-red-500 p-4 text-center text-red-600 font-bold mb-4">
                    [!] PERHATIAN: ANDA BELUM MEMILIH UNIT BISNIS. DATA TIDAK DAPAT DITAMPILKAN.
                    <br>
                    <a href="{{ route('owner.toko.index') }}" class="text-blue-700 underline">Klik disini untuk memilih
                        toko</a>
                </div>
            @else
                {{-- A. KPI CARDS (Key Performance Indicators) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-2">

                    {{-- Card 1: Omset --}}
                    <div class="bg-white border-2 border-white border-r-gray-500 border-b-gray-500 p-2 shadow-sm">
                        <p class="text-[9px] text-gray-500 font-bold uppercase">Omset Hari Ini</p>
                        <div class="flex justify-between items-end">
                            <h3 class="text-lg font-bold text-blue-800">Rp 0,-</h3>
                            <span class="text-[9px] text-green-600 font-bold">▲ 0% vs kmrn</span>
                        </div>
                    </div>

                    {{-- Card 2: Profit --}}
                    <div class="bg-white border-2 border-white border-r-gray-500 border-b-gray-500 p-2 shadow-sm">
                        <p class="text-[9px] text-gray-500 font-bold uppercase">Estimasi Laba Kotor</p>
                        <div class="flex justify-between items-end">
                            <h3 class="text-lg font-bold text-green-700">Rp 0,-</h3>
                            <span class="text-[9px] text-gray-400">Margin: 0%</span>
                        </div>
                    </div>

                    {{-- Card 3: Transaksi --}}
                    <div class="bg-white border-2 border-white border-r-gray-500 border-b-gray-500 p-2 shadow-sm">
                        <p class="text-[9px] text-gray-500 font-bold uppercase">Total Transaksi</p>
                        <div class="flex justify-between items-end">
                            <h3 class="text-lg font-bold text-gray-800">0 Nota</h3>
                            <span class="text-[9px] bg-blue-100 text-blue-800 px-1 border border-blue-300">AVG: Rp 0</span>
                        </div>
                    </div>

                    {{-- Card 4: Alert Stok --}}
                    <div
                        class="bg-white border-2 border-white border-r-gray-500 border-b-gray-500 p-2 shadow-sm relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-4 opacity-10">
                            <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm-1-5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h0a1 1 0 110 2H10a1 1 0 01-1-1z" />
                            </svg>
                        </div>
                        <p class="text-[9px] text-red-600 font-bold uppercase">Peringatan Stok</p>
                        <div class="flex justify-between items-end">
                            <h3 class="text-lg font-bold text-red-600">0 Item</h3>
                            <a href="#" class="text-[9px] underline text-blue-600">Lihat Detail >></a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-2">

                    {{-- B. KOLOM KIRI (7 Kolom) - Laporan Keuangan & Grafik --}}
                    <div class="col-span-12 md:col-span-7">
                        <fieldset class="border border-gray-400 p-2 bg-gray-50 h-full">
                            <legend
                                class="px-1 text-[10px] font-bold text-blue-900 border border-gray-400 bg-white shadow-[1px_1px_0_0_rgba(0,0,0,0.2)]">
                                ANALISA PENJUALAN
                            </legend>

                            {{-- Target Bar --}}
                            <div class="mb-3">
                                <div class="flex justify-between text-[10px] mb-1">
                                    <span>Pencapaian Target Harian</span>
                                    <span class="font-bold">0% (Target: Rp 2.000.000)</span>
                                </div>
                                <div class="w-full bg-gray-300 border border-gray-500 h-3 relative">
                                    <div class="bg-blue-600 h-full absolute left-0 top-0" style="width: 0%;"></div>
                                    {{-- Garis-garis grid kecil --}}
                                    <div class="absolute left-1/4 top-0 bottom-0 w-px bg-gray-400"></div>
                                    <div class="absolute left-2/4 top-0 bottom-0 w-px bg-gray-400"></div>
                                    <div class="absolute left-3/4 top-0 bottom-0 w-px bg-gray-400"></div>
                                </div>
                            </div>

                            {{-- Tabel Transaksi Terakhir --}}
                            <div class="border border-gray-400 bg-white">
                                <div
                                    class="bg-gray-200 px-2 py-1 border-b border-gray-400 font-bold text-[10px] flex justify-between">
                                    <span>5 Transaksi Terakhir</span>
                                    <button class="text-blue-700 hover:text-red-600">[Refresh]</button>
                                </div>
                                <table class="w-full text-left">
                                    <thead class="bg-blue-50 text-blue-900 border-b border-gray-300">
                                        <tr>
                                            <th class="px-2 py-1 w-16">Waktu</th>
                                            <th class="px-2 py-1">No. Faktur</th>
                                            <th class="px-2 py-1 text-right">Total</th>
                                            <th class="px-2 py-1 w-20 text-center">Kasir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Data Dummy / Placeholder --}}
                                        <tr class="border-b border-gray-100 hover:bg-yellow-50">
                                            <td class="px-2 py-0.5 text-gray-500 font-mono">-</td>
                                            <td class="px-2 py-0.5 font-mono italic text-gray-400">Belum ada transaksi</td>
                                            <td class="px-2 py-0.5 text-right font-mono text-gray-400">0</td>
                                            <td class="px-2 py-0.5 text-center text-gray-400">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                    </div>

                    {{-- C. KOLOM KANAN (5 Kolom) - Produk & Peringatan --}}
                    <div class="col-span-12 md:col-span-5 flex flex-col gap-2">

                        {{-- Top Produk --}}
                        <fieldset class="border border-gray-400 p-2 bg-white flex-1">
                            <legend
                                class="px-1 text-[10px] font-bold text-green-900 border border-gray-400 bg-white shadow-[1px_1px_0_0_rgba(0,0,0,0.2)]">
                                TOP 5 PRODUK LARIS
                            </legend>
                            <table class="w-full text-[10px]">
                                <tr class="border-b border-gray-300">
                                    <th class="text-left py-1">Nama Barang</th>
                                    <th class="text-right py-1 w-10">Qty</th>
                                </tr>
                                {{-- Placeholder Data --}}
                                <tr>
                                    <td colspan="2" class="py-4 text-center text-gray-400 italic">Data penjualan belum
                                        tersedia hari ini.</td>
                                </tr>
                            </table>
                        </fieldset>

                        {{-- Fast Actions --}}
                        <fieldset class="border border-gray-400 p-2 bg-gray-100">
                            <legend
                                class="px-1 text-[10px] font-bold text-gray-700 border border-gray-400 bg-white shadow-[1px_1px_0_0_rgba(0,0,0,0.2)]">
                                AKSI CEPAT
                            </legend>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="#"
                                    class="border border-gray-400 bg-white px-2 py-2 hover:bg-blue-50 text-center shadow-sm active:translate-y-px">
                                    <span class="block font-bold text-blue-800">+ Produk Baru</span>
                                </a>
                                <a href="#"
                                    class="border border-gray-400 bg-white px-2 py-2 hover:bg-blue-50 text-center shadow-sm active:translate-y-px">
                                    <span class="block font-bold text-blue-800">Stok Opname</span>
                                </a>
                                <a href="#"
                                    class="border border-gray-400 bg-white px-2 py-2 hover:bg-blue-50 text-center shadow-sm active:translate-y-px">
                                    <span class="block font-bold text-blue-800">Laporan Shift</span>
                                </a>
                                <a href="#"
                                    class="border border-gray-400 bg-white px-2 py-2 hover:bg-blue-50 text-center shadow-sm active:translate-y-px">
                                    <span class="block font-bold text-blue-800">Retur Barang</span>
                                </a>
                            </div>
                        </fieldset>

                    </div>
                </div>
            @endif

            {{-- Footer Status Bar --}}
            <div
                class="bg-gray-300 border-t border-gray-500 mt-2 p-1 text-[10px] text-gray-700 flex justify-between select-none shadow-inner">
                <div class="flex gap-4">
                    <span>Status Server: <b class="text-green-700">ONLINE (12ms)</b></span>
                    <span>Database: <b>Connected</b></span>
                    <span>User ID: <b>{{ Auth::id() }}</b></span>
                </div>
                <div>
                    &copy; {{ date('Y') }} Sistem Operasional Toko v1.0
                </div>
            </div>

        </div>
    </div>
@endsection
