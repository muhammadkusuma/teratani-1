@extends('layouts.owner')

@section('title', 'Tambah Stok')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Tambah Stok Produk</h1>
                <p class="text-gray-600 text-sm">Toko: {{ $toko->nama_toko }}</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('owner.stok.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="id_produk" class="block text-gray-700 font-bold mb-2">
                        Pilih Produk <span class="text-red-500">*</span>
                    </label>
                    <select name="id_produk" id="id_produk" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($produk as $item)
                            <option value="{{ $item->id_produk }}" {{ old('id_produk') == $item->id_produk ? 'selected' : '' }}>
                                {{ $item->nama_produk }} ({{ $item->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="qty" class="block text-gray-700 font-bold mb-2">
                        Jumlah Stok yang Ditambahkan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="qty" id="qty" required min="1" value="{{ old('qty') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        placeholder="Masukkan jumlah">
                </div>

                <div class="mb-6">
                    <label for="keterangan" class="block text-gray-700 font-bold mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        placeholder="Contoh: Pembelian dari supplier, Retur, dll.">{{ old('keterangan') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('owner.stok.index') }}"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
