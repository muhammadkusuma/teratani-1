@extends('layouts.owner')

@section('title', 'Edit Gudang')

@section('content')
<div class="max-w-2xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
        <h2 class="font-bold text-lg md:text-xl border-b-4 border-purple-600 pb-1 pr-6 uppercase tracking-tight">
            <i class="fa fa-edit text-purple-700"></i> Edit Gudang
        </h2>
        <a href="{{ route('owner.toko.gudang.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('owner.toko.gudang.update', [$toko->id_toko, $gudang->id_gudang]) }}" method="POST" class="bg-white p-4 md:p-6 border border-gray-300 shadow-sm rounded-sm">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-warehouse"></i> Nama Gudang <span class="text-red-600">*</span>
                </label>
                <input type="text" name="nama_gudang" value="{{ $gudang->nama_gudang }}" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs uppercase shadow-inner focus:border-purple-500 focus:ring-1 focus:ring-purple-200 outline-none transition-all rounded-sm" required>
            </div>

            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-map-marker-alt"></i> Lokasi / Alamat
                </label>
                <input type="text" name="lokasi" value="{{ $gudang->lokasi }}" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-purple-500 focus:ring-1 focus:ring-purple-200 outline-none transition-all rounded-sm">
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.toko.gudang.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-purple-700 text-white border border-purple-900 shadow-md hover:bg-purple-600 font-bold text-xs transition-all rounded-sm uppercase">
                <i class="fa fa-save"></i> Update Gudang
            </button>
        </div>
    </form>
</div>
@endsection
