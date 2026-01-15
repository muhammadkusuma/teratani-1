@extends('layouts.owner')

@section('title', 'Edit Detail Perusahaan')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-edit"></i> EDIT DETAIL PERUSAHAAN
    </h2>
    <a href="{{ route('owner.perusahaan.index') }}" class="px-3 py-1 bg-gray-500 text-white border border-gray-700 shadow hover:bg-gray-400 text-xs">
        <i class="fa fa-arrow-left"></i> KEMBALI
    </a>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="win98-panel">
    <form action="{{ route('owner.perusahaan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block font-bold mb-1 text-base">Nama Perusahaan <span class="text-red-600">*</span></label>
                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" 
                       class="win98-input w-full" required>
            </div>

            <div>
                <label class="block font-bold mb-1 text-base">Alamat</label>
                <textarea name="alamat" rows="3" class="win98-input w-full">{{ old('alamat', $perusahaan->alamat) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1 text-base">Kota</label>
                    <input type="text" name="kota" value="{{ old('kota', $perusahaan->kota) }}" 
                           class="win98-input w-full">
                </div>

                <div>
                    <label class="block font-bold mb-1 text-base">Provinsi</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $perusahaan->provinsi) }}" 
                           class="win98-input w-full">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1 text-base">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos', $perusahaan->kode_pos) }}" 
                           class="win98-input w-full">
                </div>

                <div>
                    <label class="block font-bold mb-1 text-base">No. Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $perusahaan->no_telp) }}" 
                           class="win98-input w-full">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1 text-base">Email</label>
                    <input type="email" name="email" value="{{ old('email', $perusahaan->email) }}" 
                           class="win98-input w-full">
                </div>

                <div>
                    <label class="block font-bold mb-1 text-base">Website</label>
                    <input type="text" name="website" value="{{ old('website', $perusahaan->website) }}" 
                           class="win98-input w-full" placeholder="www.example.com">
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1 text-base">NPWP</label>
                <input type="text" name="npwp" value="{{ old('npwp', $perusahaan->npwp) }}" 
                       class="win98-input w-full" placeholder="00.000.000.0-000.000">
            </div>

            <div>
                <label class="block font-bold mb-1 text-base">Logo Perusahaan</label>
                @if($perusahaan->logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan" 
                             class="max-w-xs border-2 border-gray-400">
                        <p class="text-xs text-gray-600 mt-1">Logo saat ini</p>
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*" class="win98-input w-full">
                <p class="text-xs text-gray-600 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
            </div>
        </div>

        <div class="flex gap-2 mt-6 pt-4 border-t-2 border-gray-400">
            <button type="submit" class="win98-button bg-blue-600 text-white border-blue-800 hover:bg-blue-500">
                <i class="fa fa-save"></i> SIMPAN PERUBAHAN
            </button>
            <a href="{{ route('owner.perusahaan.index') }}" class="win98-button">
                <i class="fa fa-times"></i> BATAL
            </a>
        </div>
    </form>
</div>
@endsection
