@extends('layouts.owner')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">EDIT DATA PRODUK</h2>
        <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo; Kembali</a>
    </div>

    <form action="{{ route('owner.toko.produk.update', [$toko->id_toko, $produk->id_produk]) }}" method="POST" enctype="multipart/form-data" class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            
            <div class="space-y-3">
                <h3 class="font-bold text-blue-800 border-b border-gray-300 pb-1 text-xs">A. IDENTITAS BARANG</h3>
                
                <div>
                    <label class="block font-bold text-xs mb-1">Nama Produk <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" class="w-full border border-gray-400 p-1 text-sm uppercase" required>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-xs mb-1">Kode SKU</label>
                        <input type="text" name="sku" value="{{ $produk->sku }}" class="w-full border border-gray-400 p-1 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block font-bold text-xs mb-1">Barcode Scan</label>
                        <input type="text" name="barcode" value="{{ $produk->barcode }}" class="w-full border border-gray-400 p-1 text-sm bg-white">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-xs mb-1">Kategori</label>
                    <select name="id_kategori" class="w-full border border-gray-400 p-1 text-sm bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ $produk->id_kategori == $kat->id_kategori ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            
            <div class="space-y-3">
                <h3 class="font-bold text-blue-800 border-b border-gray-300 pb-1 text-xs">B. SATUAN & HARGA</h3>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-xs mb-1">Satuan Kecil <span class="text-red-600">*</span></label>
                        <select name="id_satuan_kecil" required class="w-full border border-gray-400 p-1 text-sm bg-white">
                            @foreach ($satuans as $sat)
                                <option value="{{ $sat->id_satuan }}" {{ $produk->id_satuan_kecil == $sat->id_satuan ? 'selected' : '' }}>
                                    {{ $sat->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-xs mb-1">Harga Jual Umum</label>
                        <input type="number" name="harga_jual_umum" value="{{ $produk->harga_jual_umum }}" class="w-full border border-gray-400 p-1 text-sm text-right font-bold" required>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 bg-blue-50 p-2 border border-blue-100">
                    <div class="col-span-3 text-[10px] font-bold text-blue-800 mb-1">OPSIONAL (SATUAN BESAR)</div>
                    <div>
                        <label class="block text-[10px] mb-1">Sat. Besar</label>
                        <select name="id_satuan_besar" class="w-full border border-gray-400 p-1 text-xs">
                            <option value="">- Nihil -</option>
                            @foreach ($satuans as $sat)
                                <option value="{{ $sat->id_satuan }}" {{ $produk->id_satuan_besar == $sat->id_satuan ? 'selected' : '' }}>
                                    {{ $sat->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] mb-1">Konversi</label>
                        <input type="number" name="nilai_konversi" value="{{ $produk->nilai_konversi }}" min="1" class="w-full border border-gray-400 p-1 text-xs text-right">
                    </div>
                </div>

                
                <div class="bg-yellow-50 p-2 border border-yellow-200 flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-600">Sisa Stok Fisik:</span>
                    <span class="text-lg font-bold font-mono">{{ $stokToko->stok_fisik ?? 0 }}</span>
                </div>
            </div>

            
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-300 pt-3 mt-1">
                <div class="flex gap-2 items-start">
                    @if ($produk->gambar_produk)
                        <img src="{{ asset('storage/' . $produk->gambar_produk) }}" class="w-12 h-12 object-cover border border-gray-400">
                    @endif
                    <div class="flex-1">
                        <label class="block font-bold text-xs mb-1">Ganti Foto</label>
                        <input type="file" name="gambar_produk" class="w-full text-xs border border-gray-400 bg-white p-1">
                    </div>
                </div>
                <div class="flex items-center pt-4">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $produk->is_active ? 'checked' : '' }} class="mr-2">
                    <label for="is_active" class="text-sm font-bold text-gray-700 cursor-pointer select-none">Produk Aktif (Dijual)</label>
                </div>
            </div>

        </div>

        <div class="mt-4 border-t border-gray-300 pt-3 text-right">
            <button type="submit" class="bg-orange-600 text-white px-4 py-2 border border-orange-800 shadow hover:bg-orange-500 font-bold text-xs">
                UPDATE PERUBAHAN
            </button>
        </div>
    </form>
</div>
@endsection