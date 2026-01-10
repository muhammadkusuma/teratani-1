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
                    class="inline-flex items-center px-3 py-1 bg-[#d4d0c8] text-black border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black hover:bg-gray-300 font-bold text-[11px] gap-1 no-underline">
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
                    <div class="flex items-center gap-1">
                        <label class="font-bold text-gray-600">Filter:</label>
                        <div class="relative">
                            <input type="text"
                                class="w-48 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black focus:outline-none focus:bg-yellow-50"
                                placeholder="Cari SKU / Nama...">
                            <button
                                class="absolute right-0 top-0 h-full px-2 bg-gray-300 border-l border-gray-400 text-gray-600 hover:text-black">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- TABLE CONTENT (DataGrid Style) --}}
                <div class="overflow-x-auto bg-white border-2 border-gray-400 border-l-black border-t-black m-1">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-[#d4d0c8] text-black border-b border-black">
                                <th class="border-r border-gray-400 px-2 py-1 text-center w-10">NO</th>
                                <th class="border-r border-gray-400 px-2 py-1 text-center w-16">IMG</th>
                                <th class="border-r border-gray-400 px-2 py-1 text-left">DESKRIPSI PRODUK</th>
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
                                            <a href="{{ route('owner.toko.produk.edit', [$toko->id_toko, $item->id_produk]) }}"
                                                class="bg-gray-100 border border-gray-500 hover:bg-white hover:border-blue-500 text-blue-700 px-1.5 py-0.5 shadow-sm"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form
                                                action="{{ route('owner.toko.produk.destroy', [$toko->id_toko, $item->id_produk]) }}"
                                                method="POST" onsubmit="return confirm('Yakin hapus produk ini?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-gray-100 border border-gray-500 hover:bg-red-600 hover:text-white hover:border-red-800 text-red-600 px-1.5 py-0.5 shadow-sm"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center bg-gray-100 border-b border-gray-300">
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
