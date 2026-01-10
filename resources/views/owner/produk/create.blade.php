@extends('layouts.owner')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Tambah Produk Baru</h2>

            {{-- PERBAIKAN: Ganti $toko->id menjadi $toko->id_toko --}}
            <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                    <input type="text" name="nama"
                        class="w-full border p-2 rounded @error('nama') border-red-500 @enderror"
                        value="{{ old('nama') }}" required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                        {{-- PERBAIKAN: Name harus sesuai Controller (kategori_id) --}}
                        <select name="kategori_id" class="w-full border p-2 rounded">
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoris as $cat)
                                {{-- PERBAIKAN: Gunakan id_kategori dan nama_kategori --}}
                                <option value="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                        {{-- PERBAIKAN: Name harus sesuai Controller (satuan_id) --}}
                        <select name="satuan_id" class="w-full border p-2 rounded">
                            @foreach ($satuans as $sat)
                                {{-- PERBAIKAN: Gunakan id_satuan dan nama_satuan --}}
                                <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Modal (Beli)</label>
                        <input type="number" name="harga_beli" class="w-full border p-2 rounded"
                            value="{{ old('harga_beli') }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Jual</label>
                        <input type="number" name="harga_jual" class="w-full border p-2 rounded"
                            value="{{ old('harga_jual') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Stok Awal</label>
                    <input type="number" name="stok" class="w-full border p-2 rounded" value="0" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Foto Produk</label>
                    <input type="file" name="foto" class="w-full border p-2 rounded">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                </div>

                <div class="flex justify-end mt-6">
                    {{-- PERBAIKAN: Ganti $toko->id menjadi $toko->id_toko --}}
                    <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                        class="text-gray-600 mr-4 py-2">Batal</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Simpan
                        Produk</button>
                </div>
            </form>
        </div>
    </div>
@endsection
