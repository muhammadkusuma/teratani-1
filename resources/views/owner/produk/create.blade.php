@extends('layouts.owner')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-4xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
        <h2 class="font-bold text-lg md:text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
            <i class="fa fa-plus-circle text-blue-700"></i> Tambah Produk
        </h2>
        <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 md:p-6 border border-gray-300 shadow-sm rounded-sm">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            
            {{-- SECTION A: IDENTITAS BARANG --}}
            <div class="space-y-3">
                <h3 class="font-black text-blue-800 border-b-2 border-blue-600 pb-2 text-xs uppercase tracking-wider">
                    <i class="fa fa-tag"></i> A. Identitas Barang
                </h3>
                
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                        Nama Produk <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nama_produk" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs uppercase shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required placeholder="Contoh: BERAS RAJA LELE 5KG">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Kode SKU</label>
                        <input type="text" name="sku" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-gray-50 shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" placeholder="Opsional">
                    </div>
                    <div>
                        <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Barcode Scan</label>
                        <input type="text" name="barcode" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-gray-50 shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" placeholder="Scan...">
                    </div>
                </div>

                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Kategori</label>
                    <select name="id_kategori" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- SECTION B: SATUAN & HARGA --}}
            <div class="space-y-3">
                <h3 class="font-black text-blue-800 border-b-2 border-blue-600 pb-2 text-xs uppercase tracking-wider">
                    <i class="fa fa-dollar-sign"></i> B. Satuan & Harga
                </h3>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                            Satuan Kecil (Ecer) <span class="text-red-600">*</span>
                        </label>
                        <select name="id_satuan_kecil" required class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                            @foreach ($satuans as $sat)
                                <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Harga Beli (HPP)</label>
                        <input type="number" name="harga_beli" value="0" step="1" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs text-right shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                    </div>
                </div>

                {{-- Harga Jual Bertingkat --}}
                <div class="bg-gradient-to-br from-emerald-50 to-white p-3 md:p-4 border border-emerald-200 rounded-sm shadow-sm">
                    <div class="text-xs font-black text-emerald-800 mb-3 uppercase tracking-wider">
                        <i class="fa fa-tags"></i> Harga Jual Bertingkat
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] mb-1 font-black text-blue-700 uppercase tracking-wider">
                                Eceran/Umum <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="harga_jual_umum" value="0" step="1" class="w-full border border-gray-300 p-2 text-xs text-right font-bold bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required>
                        </div>
                        <div>
                            <label class="block text-[10px] mb-1 font-black text-gray-600 uppercase tracking-wider">Grosir</label>
                            <input type="number" name="harga_jual_grosir" value="" step="1" placeholder="Optional" class="w-full border border-gray-300 p-2 text-xs text-right bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] mb-1 font-black text-gray-600 uppercase tracking-wider">Harga R1 (Langganan)</label>
                            <input type="number" name="harga_r1" value="" step="1" placeholder="Optional" class="w-full border border-gray-300 p-2 text-xs text-right bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] mb-1 font-black text-gray-600 uppercase tracking-wider">Harga R2 (Langganan)</label>
                            <input type="number" name="harga_r2" value="" step="1" placeholder="Optional" class="w-full border border-gray-300 p-2 text-xs text-right bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        </div>
                    </div>
                </div>

                {{-- Optional: Satuan Besar --}}
                <div class="grid grid-cols-3 gap-2 bg-blue-50 p-3 border border-blue-200 rounded-sm">
                    <div class="col-span-3 text-[10px] font-black text-blue-800 mb-1 uppercase tracking-wider">
                        <i class="fa fa-cubes"></i> Optional (Satuan Besar)
                    </div>
                    <div>
                        <label class="block text-[10px] mb-1 font-bold text-gray-600">Sat. Besar</label>
                        <select name="id_satuan_besar" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all rounded-sm">
                            <option value="">- Nihil -</option>
                            @foreach ($satuans as $sat)
                                <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] mb-1 font-bold text-gray-600">Nilai Konversi (1 Besar = ... Kecil)</label>
                        <input type="number" name="nilai_konversi" value="1" min="1" class="w-full border border-gray-300 p-1.5 text-xs text-right shadow-inner focus:border-blue-500 outline-none transition-all rounded-sm">
                    </div>
                </div>

                {{-- Stok Awal --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-black mb-2 text-[10px] bg-amber-100 text-amber-800 px-2 py-1 w-fit uppercase tracking-wider border border-amber-300 rounded-sm">
                            <i class="fa fa-warehouse"></i> Lokasi Stok Awal
                        </label>
                        <select name="lokasi_stok_awal" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-amber-50 shadow-inner focus:border-amber-500 focus:ring-1 focus:ring-amber-200 outline-none transition-all rounded-sm">
                            <option value="toko">TOKO (ETALASE DEPAN)</option>
                            @foreach ($gudangs as $g)
                                <option value="{{ $g->id_gudang }}">{{ $g->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-black mb-2 text-[10px] bg-amber-100 text-amber-800 px-2 py-1 w-fit uppercase tracking-wider border border-amber-300 rounded-sm">
                            <i class="fa fa-boxes"></i> Jumlah Stok Awal
                        </label>
                        <input type="number" name="stok_awal" value="0" min="0" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs text-right font-bold bg-amber-50 shadow-inner focus:border-amber-500 focus:ring-1 focus:ring-amber-200 outline-none transition-all rounded-sm">
                    </div>
                </div>
            </div>

            {{-- SECTION C: MEDIA & STATUS --}}
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-t-2 border-gray-200 pt-4 mt-2">
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                        <i class="fa fa-image"></i> Foto Produk
                    </label>
                    <input type="file" name="gambar_produk" class="w-full text-xs border border-gray-300 bg-white p-2 shadow-inner focus:border-blue-500 outline-none transition-all rounded-sm file:mr-2 file:py-1 file:px-3 file:rounded-sm file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="flex items-center pt-4">
                    <input type="checkbox" name="is_active" id="is_active" checked class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="text-sm font-bold text-gray-700 cursor-pointer select-none">
                        <i class="fa fa-check-circle text-emerald-600"></i> Produk Aktif (Dijual)
                    </label>
                </div>
            </div>

        </div>

        <div class="flex flex-col md:flex-row justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 font-bold text-xs transition-all rounded-sm uppercase">
                <i class="fa fa-save"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection