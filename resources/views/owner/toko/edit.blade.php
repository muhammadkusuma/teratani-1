@extends('layouts.owner')

@section('title', 'Edit Toko')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-edit"></i> EDIT TOKO
    </h2>
    <a href="{{ route('owner.toko.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs">
        <i class="fa fa-arrow-left"></i> KEMBALI
    </a>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs">
        <ul class="list-disc ml-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white border border-gray-400 p-4 max-w-2xl">
    <form action="{{ route('owner.toko.update', $toko->id_toko) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold mb-1">Kode Toko <span class="text-red-600">*</span></label>
                <input type="text" name="kode_toko" value="{{ old('kode_toko', $toko->kode_toko) }}" required
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                @error('kode_toko')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-1">Nama Toko <span class="text-red-600">*</span></label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" required
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                @error('nama_toko')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-3">
            <label class="block text-xs font-bold mb-1">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('alamat', $toko->alamat) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            <div>
                <label class="block text-xs font-bold mb-1">Kota</label>
                <input type="text" name="kota" value="{{ old('kota', $toko->kota) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>

            <div>
                <label class="block text-xs font-bold mb-1">No. Telepon</label>
                <input type="text" name="no_telp" value="{{ old('no_telp', $toko->no_telp) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>
        </div>

        <div class="mt-3">
            <label class="block text-xs font-bold mb-1">Info Rekening</label>
            <textarea name="info_rekening" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('info_rekening', $toko->info_rekening) }}</textarea>
        </div>

        <div class="mt-4 border-t border-gray-300 pt-3">
            <div class="flex items-center gap-4 mb-3">
                <label class="flex items-center gap-2 text-xs">
                    <input type="checkbox" name="is_pusat" value="1" {{ old('is_pusat', $toko->is_pusat) ? 'checked' : '' }}
                           class="border border-gray-400">
                    <span class="font-bold"><i class="fa fa-building"></i> Toko Pusat</span>
                    <span class="text-gray-500">(centang jika ini toko pusat/utama)</span>
                </label>
            </div>

            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 text-xs">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $toko->is_active) ? 'checked' : '' }}
                           class="border border-gray-400">
                    <span class="font-bold"><i class="fa fa-check-circle"></i> Status Aktif</span>
                    <span class="text-gray-500">(centang jika toko masih beroperasi)</span>
                </label>
            </div>
        </div>

        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-blue-700 text-white border border-blue-900 px-4 py-2 text-xs hover:bg-blue-600">
                <i class="fa fa-save"></i> UPDATE TOKO
            </button>
            <a href="{{ route('owner.toko.index') }}" class="bg-gray-200 border border-gray-400 px-4 py-2 text-xs hover:bg-gray-300">
                BATAL
            </a>
        </div>
    </form>
</div>
@endsection
