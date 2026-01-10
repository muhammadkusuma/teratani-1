@extends('layouts.owner')

@section('content')
    {{-- Container Utama: Gaya Windows Klasik --}}
    <div
        style="font-family: 'MS Sans Serif', Arial, sans-serif; font-size: 11px; max-width: 100%; padding-bottom: 50px; background-color: #c0c0c0; min-height: 100vh;">

        <div class="max-w-[800px] mx-auto p-2">

            {{-- 1. HEADER HALAMAN --}}
            <div class="mb-2 flex justify-between items-end border-b border-gray-500 pb-1">
                <div>
                    <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Entri Data Barang</h1>
                    <div class="text-gray-600 mt-1">
                        Input ke Database Toko: <span
                            class="font-bold text-black bg-white px-1 border border-gray-400">{{ $toko->nama_toko }}</span>
                    </div>
                </div>

                {{-- Tombol Kembali (Gaya 3D) --}}
                <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                    class="inline-flex items-center px-3 py-1 bg-[#d4d0c8] text-black border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black hover:bg-gray-300 font-bold text-[11px] gap-1 no-underline">
                    <i class="fas fa-arrow-left text-red-800"></i> BATAL / KEMBALI
                </a>
            </div>

            {{-- 2. WINDOW FORM --}}
            <div
                class="bg-[#d4d0c8] border-2 border-white border-r-black border-b-black shadow-[2px_2px_0_0_rgba(0,0,0,0.5)]">

                {{-- Window Title Bar --}}
                <div
                    class="bg-blue-800 text-white px-2 py-0.5 font-bold flex justify-between items-center text-[11px] bg-gradient-to-r from-blue-800 to-blue-600">
                    <span>FORM INPUT PRODUK BARU [INSERT MODE]</span>
                    <span
                        class="border border-white bg-gray-300 text-black px-1 leading-none cursor-pointer hover:bg-red-500 hover:text-white">x</span>
                </div>

                <div class="p-3">
                    <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Layout 2 Kolom --}}
                        <div class="flex flex-col md:flex-row gap-3">

                            {{-- KOLOM KIRI (Data Utama) --}}
                            <div class="flex-1 space-y-3">

                                {{-- A. IDENTITAS PRODUK --}}
                                <fieldset class="border border-gray-500 p-2 relative pt-3">
                                    <legend
                                        class="px-1 text-blue-800 font-bold absolute -top-2 left-2 text-[10px] bg-[#d4d0c8]">
                                        A. IDENTITAS PRODUK
                                    </legend>

                                    <div class="space-y-2">
                                        {{-- Nama Produk --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Nama Barang <span
                                                    class="text-red-600">*</span></label>
                                            <input type="text" name="nama_produk" required
                                                placeholder="Contoh: Indomie Goreng..."
                                                class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-yellow-50 focus:bg-white focus:outline-none uppercase">
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            {{-- SKU --}}
                                            <div>
                                                <label class="block font-bold text-gray-700 mb-0.5">Kode SKU</label>
                                                <input type="text" name="sku" placeholder="Opsional..."
                                                    class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white focus:outline-none font-mono">
                                            </div>
                                            {{-- Barcode --}}
                                            <div>
                                                <label class="block font-bold text-gray-700 mb-0.5">Barcode Scan</label>
                                                <input type="text" name="barcode" placeholder="Scan here..."
                                                    class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white focus:outline-none font-mono">
                                            </div>
                                        </div>

                                        {{-- Kategori --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Kategori</label>
                                            <select name="id_kategori"
                                                class="w-full px-1 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($kategoris as $kat)
                                                    <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                {{-- B. SATUAN & KONVERSI --}}
                                <fieldset class="border border-gray-500 p-2 relative pt-3 bg-gray-200">
                                    <legend
                                        class="px-1 text-blue-800 font-bold absolute -top-2 left-2 text-[10px] bg-[#d4d0c8] border border-gray-400">
                                        B. SATUAN & KONVERSI
                                    </legend>

                                    <div class="grid grid-cols-3 gap-2">
                                        {{-- Satuan Kecil --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Sat. Kecil (Ecer) <span
                                                    class="text-red-600">*</span></label>
                                            <select name="id_satuan_kecil" required
                                                class="w-full px-1 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white">
                                                @foreach ($satuans as $sat)
                                                    <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Satuan Besar --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Sat. Besar</label>
                                            <select name="id_satuan_besar"
                                                class="w-full px-1 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white">
                                                <option value="">- Nihil -</option>
                                                @foreach ($satuans as $sat)
                                                    <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Konversi --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Nilai Konversi</label>
                                            <input type="number" name="nilai_konversi" value="1" min="1"
                                                class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white text-right">
                                        </div>
                                    </div>
                                    <div class="text-[9px] text-gray-500 mt-1 italic text-center">
                                        * Rumus: 1 Satuan Besar = [Nilai Konversi] Satuan Kecil
                                    </div>
                                </fieldset>

                            </div>

                            {{-- KOLOM KANAN (Harga & Media) --}}
                            <div class="w-full md:w-1/3 space-y-3">

                                {{-- C. HARGA & STOK --}}
                                <fieldset class="border border-gray-500 p-2 relative pt-3">
                                    <legend
                                        class="px-1 text-blue-800 font-bold absolute -top-2 left-2 text-[10px] bg-[#d4d0c8]">
                                        C. HARGA & STOK
                                    </legend>

                                    <div class="space-y-2">
                                        {{-- HPP --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Harga Beli (HPP)</label>
                                            <input type="number" name="harga_beli_rata_rata" value="0"
                                                class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white text-right font-mono">
                                        </div>

                                        {{-- Harga Jual --}}
                                        <div>
                                            <label class="block font-bold text-gray-700 mb-0.5">Harga Jual Umum <span
                                                    class="text-red-600">*</span></label>
                                            <input type="number" name="harga_jual_umum" value="0" required
                                                class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white text-right font-mono font-bold text-blue-800 text-sm">
                                        </div>

                                        <hr class="border-gray-400 my-2">

                                        {{-- Stok Awal --}}
                                        <div class="bg-yellow-100 p-1 border border-orange-300">
                                            <label class="block font-bold text-orange-900 mb-0.5 text-center">STOK FISIK
                                                AWAL</label>
                                            <input type="number" name="stok_awal" value="0" min="0"
                                                class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black bg-white text-right font-bold">
                                        </div>

                                        {{-- Status Aktif --}}
                                        <div class="flex items-center gap-2 mt-2 justify-center">
                                            <input type="checkbox" name="is_active" checked id="activeCheck"
                                                class="h-4 w-4">
                                            <label for="activeCheck"
                                                class="font-bold text-gray-700 select-none cursor-pointer">Barang Aktif
                                                (Dijual)</label>
                                        </div>
                                    </div>
                                </fieldset>

                                {{-- D. MEDIA --}}
                                <fieldset class="border border-gray-500 p-2 relative pt-3">
                                    <legend
                                        class="px-1 text-blue-800 font-bold absolute -top-2 left-2 text-[10px] bg-[#d4d0c8]">
                                        D. FOTO
                                    </legend>
                                    <input type="file" name="gambar_produk"
                                        class="w-full text-[10px] border border-gray-400 bg-white">
                                </fieldset>

                            </div>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="flex items-center justify-end gap-2 pt-3 mt-3 border-t border-gray-400">
                            <button type="reset"
                                class="px-4 py-1 bg-[#d4d0c8] text-black border-2 border-white border-r-black border-b-black hover:bg-gray-300 active:border-gray-600 active:border-r-white active:border-b-white text-[11px] font-bold w-24">
                                RESET (F5)
                            </button>

                            <button type="submit"
                                class="px-4 py-1 bg-[#d4d0c8] text-black border-2 border-white border-r-black border-b-black hover:bg-gray-300 active:border-gray-600 active:border-r-white active:border-b-white text-[11px] font-bold flex items-center justify-center gap-1 min-w-[120px]">
                                <i class="fas fa-save text-blue-800"></i> SIMPAN (F10)
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Status Bar --}}
                <div class="bg-[#d4d0c8] p-1 border-t border-gray-400 text-[10px] text-gray-600 flex justify-between">
                    <span>Ready.</span>
                    <span>Modul: PRD-ADD-01</span>
                </div>
            </div>

        </div>
    </div>
@endsection
