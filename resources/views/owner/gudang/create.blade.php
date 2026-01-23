@extends('layouts.owner')

@section('title', 'Tambah Gudang')

@section('content')
<div class="mb-4">
    <h2 class="font-bold text-xl mb-4">Tambah Gudang Baru</h2>
</div>

<div class="bg-white p-6 rounded shadow max-w-lg">
    <form action="{{ route('owner.gudang.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Gudang</label>
            <input type="text" name="nama_gudang" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi / Alamat</label>
            <textarea name="lokasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Simpan
            </button>
            <a href="{{ route('owner.gudang.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
