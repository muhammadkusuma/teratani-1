@extends('layouts.owner')

@section('title', 'Edit Gudang')

@section('content')
<div class="max-w-2xl">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">EDIT GUDANG</h2>
        <a href="{{ route('owner.toko.gudang.index', $toko->id_toko) }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo; Kembali</a>
    </div>

    <form action="{{ route('owner.toko.gudang.update', [$toko->id_toko, $gudang->id_gudang]) }}" method="POST" class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
        @csrf
        @method('PUT')
        
        <div class="space-y-3">
            <div>
                <label class="block font-bold text-xs mb-1">Nama Gudang <span class="text-red-600">*</span></label>
                <input type="text" name="nama_gudang" value="{{ $gudang->nama_gudang }}" class="w-full border border-gray-400 p-1 text-sm uppercase" required>
            </div>

            <div>
                <label class="block font-bold text-xs mb-1">Lokasi / Alamat</label>
                <input type="text" name="lokasi" value="{{ $gudang->lokasi }}" class="w-full border border-gray-400 p-1 text-sm">
            </div>
        </div>

        <div class="mt-4 border-t border-gray-300 pt-3 text-right">
            <button type="submit" class="bg-blue-800 text-white px-4 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs">
                UPDATE GUDANG
            </button>
        </div>
    </form>
</div>
@endsection
