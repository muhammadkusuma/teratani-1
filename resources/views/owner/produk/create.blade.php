@extends('layouts.owner')

@section('content')
    <div class="container-fluid px-6 py-6 bg-gray-50 min-h-screen">

        {{-- Header Navigation --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h1>
                <p class="text-sm text-gray-500">Toko: <span class="font-semibold text-blue-600">{{ $toko->nama_toko }}</span>
                </p>
            </div>
            <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Form Container --}}
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden max-w-4xl mx-auto">

            {{-- Form Header --}}
            <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                    <i class="fas fa-edit mr-2"></i> Form Input Data
                </h2>
            </div>

            <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST"
                enctype="multipart/form-data" class="p-6">
                @csrf

                {{-- SECTION 1: Informasi Dasar --}}
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 border-b pb-2 mb-4">A. Informasi Dasar</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Produk --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Produk <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 @error('nama') border-red-500 @enderror"
                                value="{{ old('nama') }}" placeholder="Contoh: Pupuk NPK" required>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select name="kategori_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoris as $cat)
                                    <option value="{{ $cat->id_kategori }}"
                                        {{ old('kategori_id') == $cat->id_kategori ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Satuan --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Satuan <span
                                    class="text-red-500">*</span></label>
                            <select name="satuan_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3">
                                <option value="">-- Pilih Satuan --</option>
                                @foreach ($satuans as $sat)
                                    <option value="{{ $sat->id_satuan }}"
                                        {{ old('satuan_id') == $sat->id_satuan ? 'selected' : '' }}>
                                        {{ $sat->nama_satuan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Harga & Stok --}}
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 border-b pb-2 mb-4">B. Harga & Inventaris</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Harga Beli --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga Modal (Beli)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="harga_beli"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2"
                                    value="{{ old('harga_beli') }}" placeholder="0">
                            </div>
                        </div>

                        {{-- Harga Jual --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga Jual <span
                                    class="text-red-500">*</span></label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                                </div>
                                <input type="number" name="harga_jual"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 font-semibold"
                                    value="{{ old('harga_jual') }}" placeholder="0" required>
                            </div>
                        </div>

                        {{-- Stok Awal --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Stok Awal <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="stok"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3"
                                value="0" required>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Media --}}
                <div class="mb-8">
                    <h3 class="text-sm font-medium text-gray-900 border-b pb-2 mb-4">C. Media</h3>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Foto Produk</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md bg-gray-50 hover:bg-white transition">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file-upload"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload file</span>
                                        <input id="file-upload" name="foto" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end bg-gray-50 -m-6 mt-0 px-6 py-4 border-t border-gray-200">
                    <button type="reset"
                        class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none">
                        Reset
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
