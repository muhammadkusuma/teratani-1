@extends('layouts.owner')

@section('title', 'Edit Detail Perusahaan')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-building-edit text-blue-700"></i> Edit Perusahaan
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.perusahaan.index') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
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

<div class="bg-white border border-gray-300 p-4 md:p-6 shadow-sm rounded-sm">
    <form action="{{ route('owner.perusahaan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Nama Perusahaan <span class="text-red-600">*</span>
                </label>
                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" 
                       class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required>
            </div>

            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Pemilik Perusahaan
                </label>
                <input type="text" name="pemilik" value="{{ old('pemilik', $perusahaan->pemilik) }}" 
                       class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>

            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Alamat</label>
                <textarea name="alamat" rows="3" class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">{{ old('alamat', $perusahaan->alamat) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Kota</label>
                    <input type="text" name="kota" value="{{ old('kota', $perusahaan->kota) }}" 
                           class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                </div>

                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Provinsi</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $perusahaan->provinsi) }}" 
                           class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos', $perusahaan->kode_pos) }}" 
                           class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                </div>

                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">No. Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $perusahaan->no_telp) }}" 
                           class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Email</label>
                    <input type="email" name="email" value="{{ old('email', $perusahaan->email) }}" 
                           class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                </div>

                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Website</label>
                    <input type="text" name="website" value="{{ old('website', $perusahaan->website) }}" 
                           class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" 
                           placeholder="www.example.com">
                </div>
            </div>

            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">NPWP</label>
                <input type="text" name="npwp" value="{{ old('npwp', $perusahaan->npwp) }}" 
                       class="border border-gray-300 p-2.5 md:p-2 w-full text-xs shadow-inner font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" 
                       placeholder="00.000.000.0-000.000">
            </div>

            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Logo Perusahaan</label>
                @if($perusahaan->logo)
                    <div class="mb-3 bg-blue-50 border border-blue-200 p-3 rounded-sm">
                        <p class="text-xs text-blue-700 font-bold mb-2">Logo saat ini:</p>
                        <img src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan" 
                             class="max-w-full md:max-w-xs border-2 border-blue-300 rounded-sm shadow">
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*" 
                       class="border border-gray-300 p-2 md:p-1 w-full text-xs rounded-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all">
                <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                    <i class="fa fa-info-circle text-blue-400"></i>
                    <span>Format: JPG, PNG, GIF. Maksimal 2MB</span>
                </p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.perusahaan.index') }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 text-xs font-bold hover:bg-blue-600 transition-colors shadow-sm rounded-sm uppercase">
                <i class="fa fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
