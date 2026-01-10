@extends('layouts.owner')

@section('content')
    <div
        style="font-family: 'MS Sans Serif', Arial, sans-serif; font-size: 11px; max-width: 100%; padding-bottom: 50px; background-color: #c0c0c0; min-height: 100vh;">

        <div class="max-w-[800px] mx-auto p-2">

            {{-- HEADER --}}
            <div class="mb-2 flex justify-between items-end border-b border-gray-500 pb-1">
                <div>
                    <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Edit Produk</h1>
                    <div class="text-gray-600 mt-1">
                        Toko: <span class="font-bold text-black">{{ $toko->nama_toko }}</span>
                    </div>
                </div>
                <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                    class="inline-flex items-center px-2 py-1 bg-[#d4d0c8] text-black border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black hover:bg-gray-300 font-bold text-[11px] no-underline">
                    <i class="fas fa-arrow-left mr-1"></i> KEMBALI
                </a>
            </div>

            {{-- NOTIFIKASI ERROR --}}
            @if ($errors->any())
                <div class="mb-2 bg-red-100 border border-red-500 p-2 text-red-700">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM WINDOW --}}
            <div
                class="bg-[#d4d0c8] border-2 border-white border-r-black border-b-black shadow-[2px_2px_0_0_rgba(0,0,0,0.5)] p-1">

                {{-- Title Bar --}}
                <div class="bg-blue-900 text-white px-2 py-0.5 font-bold flex justify-between items-center mb-2">
                    <span>Properti Produk: {{ $produk->nama_produk }}</span>
                    <span class="cursor-pointer">X</span>
                </div>

                <form action="{{ route('owner.toko.produk.update', [$toko->id_toko, $produk->id_produk]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="bg-white border-2 border-gray-400 border-t-black border-l-black p-3 mb-2">

                        {{-- IDENTITAS PRODUK --}}
                        <fieldset class="border border-gray-400 p-2 mb-2 relative">
                            <legend class="px-1 text-blue-800 font-bold bg-white -mt-2">Identitas Utama</legend>

                            <div class="grid grid-cols-12 gap-2 mb-2">
                                <label class="col-span-3 text-right pt-1">Nama Produk <span
                                        class="text-red-600">*</span>:</label>
                                <div class="col-span-9">
                                    <input type="text" name="nama_produk"
                                        value="{{ old('nama_produk', $produk->nama_produk) }}"
                                        class="w-full px-1 py-0.5 border border-gray-400 focus:bg-yellow-50" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-2 mb-2">
                                <label class="col-span-3 text-right pt-1">SKU / Kode:</label>
                                <div class="col-span-4">
                                    <input type="text" name="sku" value="{{ old('sku', $produk->sku) }}"
                                        class="w-full px-1 py-0.5 border border-gray-400 focus:bg-yellow-50">
                                </div>
                                <label class="col-span-2 text-right pt-1">Barcode:</label>
                                <div class="col-span-3">
                                    <input type="text" name="barcode" value="{{ old('barcode', $produk->barcode) }}"
                                        class="w-full px-1 py-0.5 border border-gray-400 focus:bg-yellow-50">
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-2 mb-2">
                                <label class="col-span-3 text-right pt-1">Kategori:</label>
                                <div class="col-span-9">
                                    <select name="id_kategori" class="w-full px-1 py-0.5 border border-gray-400 bg-white">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategoris as $kat)
                                            <option value="{{ $kat->id_kategori }}"
                                                {{ old('id_kategori', $produk->id_kategori) == $kat->id_kategori ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        {{-- SATUAN & KONVERSI --}}
                        <fieldset class="border border-gray-400 p-2 mb-2 relative">
                            <legend class="px-1 text-blue-800 font-bold bg-white">Satuan & Harga</legend>

                            {{-- Satuan Kecil --}}
                            <div class="grid grid-cols-12 gap-2 mb-2">
                                <label class="col-span-3 text-right pt-1">Satuan Dasar (Kecil) <span
                                        class="text-red-600">*</span>:</label>
                                <div class="col-span-3">
                                    <select name="id_satuan_kecil"
                                        class="w-full px-1 py-0.5 border border-gray-400 bg-white" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($satuans as $sat)
                                            <option value="{{ $sat->id_satuan }}"
                                                {{ old('id_satuan_kecil', $produk->id_satuan_kecil) == $sat->id_satuan ? 'selected' : '' }}>
                                                {{ $sat->nama_satuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Satuan Besar --}}
                            <div class="grid grid-cols-12 gap-2 mb-2">
                                <label class="col-span-3 text-right pt-1">Satuan Besar (Opsional):</label>
                                <div class="col-span-3">
                                    <select name="id_satuan_besar"
                                        class="w-full px-1 py-0.5 border border-gray-400 bg-white">
                                        <option value="">-- Tidak Ada --</option>
                                        @foreach ($satuans as $sat)
                                            <option value="{{ $sat->id_satuan }}"
                                                {{ old('id_satuan_besar', $produk->id_satuan_besar) == $sat->id_satuan ? 'selected' : '' }}>
                                                {{ $sat->nama_satuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-span-3 text-right pt-1">Nilai Konversi:</label>
                                <div class="col-span-3 flex items-center gap-1">
                                    <input type="number" name="nilai_konversi"
                                        value="{{ old('nilai_konversi', $produk->nilai_konversi) }}" min="1"
                                        class="w-full px-1 py-0.5 border border-gray-400 text-right focus:bg-yellow-50">
                                    <span class="text-[9px] text-gray-500 whitespace-nowrap">(1 Besar = X Kecil)</span>
                                </div>
                            </div>

                            {{-- Harga --}}
                            <div class="grid grid-cols-12 gap-2 mb-2">
                                <label class="col-span-3 text-right pt-1 font-bold">Harga Jual Umum <span
                                        class="text-red-600">*</span>:</label>
                                <div class="col-span-4">
                                    <div class="flex items-center">
                                        <span class="bg-gray-200 border border-gray-400 px-1 py-0.5 text-gray-600">Rp</span>
                                        <input type="number" name="harga_jual_umum"
                                            value="{{ old('harga_jual_umum', $produk->harga_jual_umum) }}"
                                            class="w-full px-1 py-0.5 border border-gray-400 text-right font-bold focus:bg-yellow-50"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        {{-- GAMBAR & STATUS --}}
                        <div class="grid grid-cols-12 gap-4">
                            {{-- Kolom Gambar --}}
                            <div class="col-span-8">
                                <fieldset class="border border-gray-400 p-2 h-full">
                                    <legend class="px-1 text-blue-800 font-bold bg-white">Gambar Produk</legend>
                                    <div class="flex gap-2">
                                        <div
                                            class="w-16 h-16 bg-gray-200 border border-black flex items-center justify-center overflow-hidden">
                                            @if ($produk->gambar_produk)
                                                <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                                                    class="object-cover w-full h-full">
                                            @else
                                                <span class="text-xs text-gray-400">No Img</span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" name="gambar_produk" class="w-full text-[10px] mb-1">
                                            <div class="text-[9px] text-gray-500">* Format JPG/PNG, Max 2MB. Biarkan kosong
                                                jika tidak diganti.</div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            {{-- Kolom Info Stok (Read Only) --}}
                            <div class="col-span-4">
                                <fieldset class="border border-gray-400 p-2 h-full bg-yellow-50">
                                    <legend class="px-1 text-blue-800 font-bold bg-white">Info Stok</legend>
                                    <div class="text-center">
                                        <div class="text-xs text-gray-600">Stok Saat Ini</div>
                                        <div class="text-2xl font-mono font-bold">{{ $stokToko->stok_fisik ?? 0 }}</div>
                                        <div class="text-[9px] text-red-500 italic mt-1">*Edit stok via menu Penyesuaian /
                                            Opname</div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        {{-- Status Checkbox --}}
                        <div class="mt-2 flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', $produk->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="cursor-pointer font-bold select-none">Produk Aktif
                                (Dijual)</label>
                        </div>

                    </div>

                    {{-- FOOTER ACTION --}}
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-400">
                        <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                            class="px-4 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black hover:bg-gray-300 text-black no-underline flex items-center justify-center min-w-[80px]">
                            BATAL
                        </a>
                        <button type="submit"
                            class="px-4 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black hover:bg-gray-300 font-bold text-black flex items-center justify-center min-w-[80px]">
                            <i class="fas fa-save mr-1 text-blue-800"></i> SIMPAN PERUBAHAN
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
