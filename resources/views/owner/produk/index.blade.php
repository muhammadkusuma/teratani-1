@extends('layouts.owner')

@section('content')
    {{-- Container Utama: Background Abu-abu Windows Klasik --}}
    <div
        style="font-family: 'MS Sans Serif', Arial, sans-serif; font-size: 11px; max-width: 100%; padding-bottom: 50px; background-color: #c0c0c0; min-height: 100vh;">

        <div class="max-w-[1024px] mx-auto p-2">

            {{-- 1. HEADER HALAMAN --}}
            <div class="mb-2 flex justify-between items-end border-b border-gray-500 pb-1">
                <div>
                    <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Master Data Produk</h1>
                    <div class="text-gray-600 mt-1">
                        Database Toko: <span
                            class="font-bold text-black bg-white px-1 border border-gray-400">{{ $toko->nama_toko }}</span>
                    </div>
                </div>

                {{-- Tombol Tambah (Gaya 3D) --}}
                <a href="{{ route('owner.toko.produk.create', $toko->id_toko) }}"
                    class="inline-flex items-center px-3 py-1 bg-[#d4d0c8] text-black border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black hover:bg-gray-300 font-bold text-[11px] gap-1 no-underline shadow-sm">
                    <i class="fas fa-plus text-blue-800"></i> TAMBAH PRODUK
                </a>
            </div>

            {{-- 2. NOTIFIKASI --}}
            @if (session('success'))
                <div
                    class="mb-3 bg-white border border-black p-2 flex items-center gap-2 shadow-[2px_2px_0_0_rgba(0,0,0,0.5)]">
                    <div class="bg-green-600 text-white font-bold px-1 text-[10px]">SUKSES</div>
                    <p class="text-black font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- 3. WINDOW DATA GRID --}}
            <div
                class="bg-[#d4d0c8] border-2 border-white border-r-black border-b-black shadow-[2px_2px_0_0_rgba(0,0,0,0.5)]">

                {{-- Toolbar / Search Filter --}}
                <div class="bg-gray-200 p-2 border-b border-gray-400 flex justify-between items-center">
                    <div class="font-bold text-gray-700 flex items-center gap-1">
                        <i class="fas fa-list"></i> LIST INVENTARIS
                    </div>

                    {{-- Search Bar Retro --}}
                    {{-- FORM PENCARIAN DIMULAI DISINI --}}
                    <form action="{{ route('owner.toko.produk.index', $toko->id_toko) }}" method="GET"
                        class="flex items-center gap-1">
                        <label class="font-bold text-gray-600">Filter:</label>
                        <div class="relative">
                            {{-- Input dengan name="search" dan value lama --}}
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-48 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black focus:outline-none focus:bg-yellow-50"
                                placeholder="Cari SKU / Nama...">

                            {{-- Button type="submit" --}}
                            <button type="submit"
                                class="absolute right-0 top-0 h-full px-2 bg-gray-300 border-l border-gray-400 text-gray-600 hover:text-black cursor-pointer">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        {{-- Tombol Reset Search (opsional, muncul jika sedang mencari) --}}
                        @if (request('search'))
                            <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                                class="px-2 py-0.5 bg-red-200 border border-red-400 text-red-800 text-[10px] font-bold no-underline hover:bg-red-300 ml-1"
                                title="Reset Pencarian">
                                X
                            </a>
                        @endif
                    </form>
                </div>

                {{-- TABLE CONTENT (DataGrid Style) --}}
                {{-- TABLE CONTENT (DataGrid Style) --}}
                <div class="overflow-x-auto bg-white border-2 border-gray-400 border-l-black border-t-black m-1">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-[#d4d0c8] text-black border-b border-black">
                                <th class="border-r border-gray-400 px-2 py-1 text-center w-10">NO</th>
                                <th class="border-r border-gray-400 px-2 py-1 text-center w-16">IMG</th>
                                <th class="border-r border-gray-400 px-2 py-1 text-left">DESKRIPSI PRODUK</th>
                                {{-- KOLOM BARU: STOK --}}
                                <th class="border-r border-gray-400 px-2 py-1 text-center w-20">STOK</th>
                                <th class="border-r border-gray-400 px-2 py-1 text-left">KATEGORI</th>
                                <th class="border-r border-gray-400 px-2 py-1 text-left">SATUAN</th>
                                <th class="border-r border-gray-400 px-2 py-1 text-right">HARGA JUAL</th>
                                <th class="px-2 py-1 text-center w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $index => $item)
                                <tr
                                    class="border-b border-gray-300 hover:bg-blue-100 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">

                                    {{-- No --}}
                                    <td class="border-r border-gray-300 px-2 py-1 text-center font-mono">
                                        {{ $produks->firstItem() + $index }}
                                    </td>

                                    {{-- Gambar --}}
                                    <td class="border-r border-gray-300 px-1 py-1 text-center">
                                        @if ($item->gambar_produk)
                                            <img src="{{ asset('storage/' . $item->gambar_produk) }}" alt="img"
                                                class="h-8 w-8 object-cover border border-black mx-auto">
                                        @else
                                            <div
                                                class="h-8 w-8 bg-gray-200 border border-gray-400 mx-auto flex items-center justify-center text-[9px] text-gray-500">
                                                N/A
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Info Produk --}}
                                    <td class="border-r border-gray-300 px-2 py-1 align-middle">
                                        <div class="font-bold text-black leading-tight">{{ $item->nama_produk }}</div>
                                        <div class="text-[9px] text-gray-500 font-mono mt-0.5">
                                            SKU: {{ $item->sku ?? '-' }} | BC: {{ $item->barcode ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- LOGIKA BARU: MENAMPILKAN STOK --}}
                                    @php
                                        // PERBAIKAN: Gunakan stokTokos (plural)
                                        // Karena hasMany, ini adalah Collection. first() pada Collection kosong = null (AMAN).
                                        $stokData = $item->stokTokos->first();

                                        $jumlahStok = $stokData ? $stokData->stok_fisik : 0;
                                        $stokMinimal = $stokData ? $stokData->stok_minimal : 0;

                                        // Warna warning jika stok tipis
                                        $bgStok =
                                            $jumlahStok <= $stokMinimal
                                                ? 'bg-red-200 text-red-700'
                                                : 'bg-green-100 text-green-800';
                                    @endphp
                                    <td class="border-r border-gray-300 px-2 py-1 text-center align-middle">
                                        <span
                                            class="px-2 py-0.5 rounded {{ $bgStok }} font-bold border border-gray-300">
                                            {{ number_format($jumlahStok, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    {{-- Kategori --}}
                                    <td class="border-r border-gray-300 px-2 py-1 align-middle">
                                        {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                    </td>

                                    {{-- Satuan & Konversi --}}
                                    <td class="border-r border-gray-300 px-2 py-1 align-middle">
                                        <span class="font-bold">{{ $item->satuanKecil->nama_satuan ?? '-' }}</span>
                                        @if ($item->id_satuan_besar)
                                            <div
                                                class="text-[9px] text-blue-800 bg-blue-50 px-1 border border-blue-200 inline-block mt-0.5">
                                                1 {{ $item->satuanBesar->nama_satuan }} = {{ $item->nilai_konversi }}
                                                {{ $item->satuanKecil->nama_satuan }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Harga Jual --}}
                                    <td
                                        class="border-r border-gray-300 px-2 py-1 align-middle text-right font-mono font-bold text-black">
                                        Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-1 py-1 align-middle text-center">
                                        <div class="flex justify-center gap-1">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('owner.toko.produk.edit', [$toko->id_toko, $item->id_produk]) }}"
                                                class="w-6 h-6 flex items-center justify-center bg-[#d4d0c8] border-2 border-t-white border-l-white border-b-black border-r-black active:border-t-black active:border-l-black hover:bg-gray-300 text-blue-800"
                                                title="Edit Data">
                                                <i class="fas fa-pencil-alt text-[11px]"></i>
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <form
                                                action="{{ route('owner.toko.produk.destroy', [$toko->id_toko, $item->id_produk]) }}"
                                                method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-6 h-6 flex items-center justify-center bg-[#d4d0c8] border-2 border-t-white border-l-white border-b-black border-r-black active:border-t-black active:border-l-black hover:bg-gray-300 text-red-600"
                                                    title="Hapus Data">
                                                    <i class="fas fa-times text-[12px] font-bold"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Colspan disesuaikan karena ada tambahan kolom Stok --}}
                                    <td colspan="8" class="px-6 py-8 text-center bg-gray-100 border-b border-gray-300">
                                        <div class="text-gray-400 font-bold italic">-- DATA KOSONG --</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Status Bar / Pagination --}}
                <div class="bg-[#d4d0c8] p-1 border-t border-gray-400 text-[10px] flex justify-between items-center px-2">
                    <div class="flex gap-2">
                        <span>Total: <b>{{ $produks->total() }}</b> Items</span>
                        <span>|</span>
                        <span>Hal: <b>{{ $produks->currentPage() }} / {{ $produks->lastPage() }}</b></span>
                    </div>

                    {{-- Manual Pagination Links (Agar sesuai tema) --}}
                    <div class="flex gap-1">
                        @if ($produks->onFirstPage())
                            <span class="px-2 border border-gray-400 text-gray-400 bg-gray-200 cursor-not-allowed"> Prev
                            </span>
                        @else
                            <a href="{{ $produks->previousPageUrl() }}"
                                class="px-2 border border-gray-500 bg-white hover:bg-blue-100 text-black no-underline"> Prev
                            </a>
                        @endif

                        @if ($produks->hasMorePages())
                            <a href="{{ $produks->nextPageUrl() }}"
                                class="px-2 border border-gray-500 bg-white hover:bg-blue-100 text-black no-underline"> Next
                            </a>
                        @else
                            <span class="px-2 border border-gray-400 text-gray-400 bg-gray-200 cursor-not-allowed"> Next
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
