@extends('layouts.owner')

@section('title', 'Edit Toko')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-store-alt text-blue-700"></i> Edit Toko
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.toko.index') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if($errors->any())
<div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
    <div class="font-bold text-xs mb-2 flex items-center gap-2">
        <i class="fa fa-exclamation-triangle"></i> Terdapat kesalahan:
    </div>
    <ul class="list-disc ml-6 text-xs space-y-1">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white border border-gray-300 p-4 md:p-6 max-w-2xl shadow-sm rounded-sm">
    <form action="{{ route('owner.toko.update', $toko->id_toko) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Kode Toko <span class="text-red-600">*</span>
                </label>
                <input type="text" name="kode_toko" value="{{ old('kode_toko', $toko->kode_toko) }}" required
                       class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                @error('kode_toko')
                    <p class="text-red-600 text-xs mt-1 font-semibold">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Nama Toko <span class="text-red-600">*</span>
                </label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" required
                       class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                @error('nama_toko')
                    <p class="text-red-600 text-xs mt-1 font-semibold">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">{{ old('alamat', $toko->alamat) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Kota</label>
                <input type="text" name="kota" value="{{ old('kota', $toko->kota) }}"
                       class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>

            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">No. Telepon</label>
                <input type="text" name="no_telp" value="{{ old('no_telp', $toko->no_telp) }}"
                       class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
        </div>

        <div class="mt-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Info Rekening</label>
            <textarea name="info_rekening" rows="2" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">{{ old('info_rekening', $toko->info_rekening) }}</textarea>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <div class="space-y-3">
                <div class="bg-blue-50 border border-blue-200 p-3 rounded-sm">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_pusat" value="1" {{ old('is_pusat', $toko->is_pusat) ? 'checked' : '' }}
                               class="mt-0.5 w-4 h-4 border-blue-300 rounded text-blue-600 focus:ring-1 focus:ring-blue-200">
                        <div class="flex-1">
                            <span class="font-black text-xs text-blue-900 uppercase tracking-wider">
                                <i class="fa fa-building"></i> Toko Pusat
                            </span>
                            <p class="text-[10px] text-blue-700 mt-0.5">Centang jika ini toko pusat/utama</p>
                        </div>
                    </label>
                </div>

                <div class="bg-emerald-50 border border-emerald-200 p-3 rounded-sm">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $toko->is_active) ? 'checked' : '' }}
                               class="mt-0.5 w-4 h-4 border-emerald-300 rounded text-emerald-600 focus:ring-1 focus:ring-emerald-200">
                        <div class="flex-1">
                            <span class="font-black text-xs text-emerald-900 uppercase tracking-wider">
                                <i class="fa fa-check-circle"></i> Status Aktif
                            </span>
                            <p class="text-[10px] text-emerald-700 mt-0.5">Centang jika toko masih beroperasi</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.toko.index') }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 text-xs font-bold hover:bg-blue-600 transition-colors shadow-sm rounded-sm uppercase">
                <i class="fa fa-save"></i> Update Toko
            </button>
        </div>
    </form>
</div>
@endsection
