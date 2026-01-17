@extends('layouts.owner')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">INPUT DATA PRODUK BARU</h2>
        <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo; Kembali</a>
    </div>

    <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST" enctype="multipart/form-data" class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            
            <div class="space-y-3">
                <h3 class="font-bold text-blue-800 border-b border-gray-300 pb-1 text-xs">A. IDENTITAS BARANG</h3>
                
                <div>
                    <label class="block font-bold text-xs mb-1">Nama Produk <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_produk" class="w-full border border-gray-400 p-1 text-sm uppercase" required placeholder="Contoh: BERAS RAJA LELE 5KG">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-xs mb-1">Kode SKU</label>
                        <input type="text" name="sku" class="w-full border border-gray-400 p-1 text-sm bg-white" placeholder="Opsional">
                    </div>
                    <div>
                        <label class="block font-bold text-xs mb-1">Barcode Scan</label>
                        <input type="text" name="barcode" class="w-full border border-gray-400 p-1 text-sm bg-white" placeholder="Scan...">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-xs mb-1">Kategori</label>
                    <select name="id_kategori" class="w-full border border-gray-400 p-1 text-sm bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            
            <div class="space-y-3">
                <h3 class="font-bold text-blue-800 border-b border-gray-300 pb-1 text-xs">B. SATUAN & HARGA</h3>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-xs mb-1">Satuan Kecil (Ecer) <span class="text-red-600">*</span></label>
                        <select name="id_satuan_kecil" required class="w-full border border-gray-400 p-1 text-sm bg-white">
                            @foreach ($satuans as $sat)
                                <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-xs mb-1">Harga Beli (HPP)</label>
                        <input type="number" name="harga_beli" value="0" step="1" class="w-full border border-gray-400 p-1 text-sm text-right">
                    </div>
                </div>

                
                <div class="bg-green-50 p-3 border border-green-200 rounded">
                    <div class="text-xs font-bold text-green-800 mb-2">💰 HARGA JUAL BERTINGKAT</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs mb-1 font-bold text-blue-700">Eceran/Umum <span class="text-red-600">*</span></label>
                            <input type="number" name="harga_jual_umum" value="0" step="1" class="w-full border border-gray-400 p-1 text-sm text-right font-bold bg-white" required>
                        </div>
                        <div>
                            <label class="block text-xs mb-1">Grosir</label>
                            <input type="number" name="harga_jual_grosir" value="" step="1" placeholder="Optional" class="w-full border border-gray-400 p-1 text-sm text-right bg-white">
                        </div>
                        <div>
                            <label class="block text-xs mb-1">Harga R1 (Langganan)</label>
                            <input type="number" name="harga_r1" value="" step="1" placeholder="Optional" class="w-full border border-gray-400 p-1 text-sm text-right bg-white">
                        </div>
                        <div>
                            <label class="block text-xs mb-1">Harga R2 (Langganan)</label>
                            <input type="number" name="harga_r2" value="" step="1" placeholder="Optional" class="w-full border border-gray-400 p-1 text-sm text-right bg-white">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 bg-blue-50 p-2 border border-blue-100">
                    <div class="col-span-3 text-[10px] font-bold text-blue-800 mb-1">OPSIONAL (SATUAN BESAR)</div>
                    <div>
                        <label class="block text-[10px] mb-1">Sat. Besar</label>
                        <select name="id_satuan_besar" class="w-full border border-gray-400 p-1 text-xs">
                            <option value="">- Nihil -</option>
                            @foreach ($satuans as $sat)
                                <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] mb-1">Nilai Konversi (1 Besar = ... Kecil)</label>
                        <input type="number" name="nilai_konversi" value="1" min="1" class="w-full border border-gray-400 p-1 text-xs text-right">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-xs mb-1 bg-yellow-200 px-1 w-fit">STOK AWAL</label>
                    <input type="number" name="stok_awal" value="0" min="0" class="w-full border border-gray-400 p-1 text-sm text-right font-bold bg-yellow-50">
                </div>
            </div>

            
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-300 pt-3 mt-1">
                <div>
                    <label class="block font-bold text-xs mb-1">Foto Produk</label>
                    <input type="file" name="gambar_produk" class="w-full text-xs border border-gray-400 bg-white p-1">
                </div>
                <div class="flex items-center pt-4">
                    <input type="checkbox" name="is_active" id="is_active" checked class="mr-2">
                    <label for="is_active" class="text-sm font-bold text-gray-700 cursor-pointer select-none">Produk Aktif (Dijual)</label>
                </div>
            </div>

        </div>

        <div class="mt-4 border-t border-gray-300 pt-3 text-right">
            <button type="submit" class="bg-blue-800 text-white px-4 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs">
                SIMPAN DATA
            </button>
        </div>
    </form>
</div>
@endsection