@extends('layouts.owner')

@section('title', 'Tambah Gudang')

@section('content')
<div class="max-w-2xl">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">TAMBAH GUDANG BARU</h2>
        <a href="{{ route('owner.toko.gudang.index', $toko->id_toko) }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo; Kembali</a>
    </div>

    <form action="{{ route('owner.toko.gudang.store', $toko->id_toko) }}" method="POST" class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
        @csrf
        
        <div class="space-y-3">
            <div>
                <label class="block font-bold text-xs mb-1">Nama Gudang <span class="text-red-600">*</span></label>
                <input type="text" name="nama_gudang" class="w-full border border-gray-400 p-1 text-sm uppercase" required placeholder="Contoh: GUDANG UTAMA">
            </div>

            <div>
                <label class="block font-bold text-xs mb-1">Lokasi / Alamat</label>
                <input type="text" name="lokasi" class="w-full border border-gray-400 p-1 text-sm" placeholder="Contoh: Jl. Raya No. 123">
            </div>
        </div>

        <div class="mt-4 border-t border-gray-300 pt-3 text-right">
            <button type="submit" class="bg-blue-800 text-white px-4 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs">
                SIMPAN GUDANG
            </button>
        </div>
    </form>
</div>
@endsection
