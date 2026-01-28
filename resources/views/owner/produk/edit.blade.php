@extends('layouts.owner')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-4xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
        <h2 class="font-bold text-lg md:text-xl border-b-4 border-amber-600 pb-1 pr-6 uppercase tracking-tight">
            <i class="fa fa-edit text-amber-700"></i> Edit Produk
        </h2>
        <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('owner.toko.produk.update', [$toko->id_toko, $produk->id_produk]) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 md:p-6 border border-gray-300 shadow-sm rounded-sm">
        @csrf
        @method('PUT')
        
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
                    <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs uppercase shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Kode SKU</label>
                        <input type="text" name="sku" value="{{ $produk->sku }}" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-gray-50 shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                    </div>
                    <div>
                        <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Barcode Scan</label>
                        <input type="text" name="barcode" value="{{ $produk->barcode }}" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-gray-50 shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Kategori</label>
                    <select name="id_kategori" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ $produk->id_kategori == $kat->id_kategori ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
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
                                <option value="{{ $sat->id_satuan }}" {{ $produk->id_satuan_kecil == $sat->id_satuan ? 'selected' : '' }}>
                                    {{ $sat->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Modal Beli</label>
                        <input type="number" name="harga_beli" value="{{ $produk->harga_beli }}" step="1" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs text-right shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
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
                            <input type="number" name="harga_jual_umum" value="{{ $produk->harga_jual_umum }}" step="1" class="w-full border border-gray-300 p-2 text-xs text-right font-bold bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required>
                        </div>
                        <div>
                            <label class="block text-[10px] mb-1 font-black text-gray-600 uppercase tracking-wider">Grosir</label>
                            <input type="number" name="harga_jual_grosir" value="{{ $produk->harga_jual_grosir }}" step="1" placeholder="Optional" class="w-full border border-gray-300 p-2 text-xs text-right bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] mb-1 font-black text-gray-600 uppercase tracking-wider">Harga R1 (Langganan)</label>
                            <input type="number" name="harga_r1" value="{{ $produk->harga_r1 }}" step="1" placeholder="Optional" class="w-full border border-gray-300 p-2 text-xs text-right bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] mb-1 font-black text-gray-600 uppercase tracking-wider">Harga R2 (Langganan)</label>
                            <input type="number" name="harga_r2" value="{{ $produk->harga_r2 }}" step="1" placeholder="Optional" class="w-full border border-gray-300 p-2 text-xs text-right bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        </div>
                    </div>
                </div>

                {{-- Optional: Satuan Besar --}}
                <div class="grid grid-cols-3 gap-2 bg-blue-50 p-3 border border-blue-200 rounded-sm">
                    <div class="col-span-3 text-[10px] font-black text-blue-800 mb-1 uppercase tracking-wider">
                        <i class="fa fa-cubes"></i> Jual Per Paket/Dus? (Opsional)
                    </div>
                    <div>
                        <label class="block text-[10px] mb-1 font-bold text-gray-600">Nama Paket</label>
                        <select name="id_satuan_besar" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all rounded-sm">
                            <option value="">- Tidak -</option>
                            @foreach ($satuans as $sat)
                                <option value="{{ $sat->id_satuan }}" {{ $produk->id_satuan_besar == $sat->id_satuan ? 'selected' : '' }}>
                                    {{ $sat->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] mb-1 font-bold text-gray-600">1 Paket Isi Berapa?</label>
                        <input type="number" name="nilai_konversi" value="{{ $produk->nilai_konversi }}" min="1" class="w-full border border-gray-300 p-1.5 text-xs text-right shadow-inner focus:border-blue-500 outline-none transition-all rounded-sm">
                    </div>
                </div>

                {{-- Posisi Stok Saat Ini --}}
                <div class="bg-amber-50 p-3 border border-amber-200 rounded-sm">
                    <div class="text-xs font-black text-amber-800 mb-2 border-b border-amber-300 pb-1 uppercase tracking-wider">
                        <i class="fa fa-warehouse"></i> Posisi Stok Fisik Saat Ini
                    </div>
                    <div class="flex justify-between items-center mb-1 border-b border-gray-200 pb-1 text-xs">
                        <span class="text-gray-700">TOKO (ETALASE DEPAN)</span>
                        <span class="font-bold font-mono text-emerald-700">{{ $stokToko->stok_fisik ?? 0 }}</span>
                    </div>
                    @foreach ($stokGudangs as $sg)
                        <div class="flex justify-between items-center mb-1 border-b border-gray-200 pb-1 text-xs">
                            <span class="text-gray-700">GUDANG: {{ $sg->gudang->nama_gudang ?? '-' }}</span>
                            <span class="font-bold font-mono text-blue-700">{{ $sg->stok_fisik }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION C: MEDIA & STATUS --}}
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-t-2 border-gray-200 pt-4 mt-2">
                <div class="flex gap-3 items-start">
                    @if ($produk->gambar_produk)
                        <img src="{{ asset('storage/' . $produk->gambar_produk) }}" class="w-16 h-16 object-cover border border-gray-300 rounded-sm flex-shrink-0">
                    @else
                        <div class="w-16 h-16 bg-gray-100 border border-gray-300 rounded-sm flex items-center justify-center flex-shrink-0">
                            <i class="fa fa-image text-gray-300 text-2xl"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                            <i class="fa fa-image"></i> Ganti Foto
                        </label>
                        <input type="file" name="gambar_produk" class="w-full text-xs border border-gray-300 bg-white p-2 shadow-inner focus:border-blue-500 outline-none transition-all rounded-sm file:mr-2 file:py-1 file:px-3 file:rounded-sm file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
                <div class="flex items-center pt-4">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $produk->is_active ? 'checked' : '' }} class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
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
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-amber-600 text-white border border-amber-800 shadow-md hover:bg-amber-500 font-bold text-xs transition-all rounded-sm uppercase">
                <i class="fa fa-save"></i> Update Perubahan
            </button>
        </div>
    </form>
</div>
@endsection