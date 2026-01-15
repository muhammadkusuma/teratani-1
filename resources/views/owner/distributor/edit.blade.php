@extends('layouts.owner')

@section('title', 'Edit Distributor')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-edit"></i> EDIT DISTRIBUTOR
    </h2>
    <a href="{{ route('owner.distributor.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs">
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

<div class="bg-white border border-gray-400 p-4">
    <form action="{{ route('owner.distributor.update', $distributor->id_distributor) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            {{-- Toko --}}
            <div>
                <label class="block text-xs font-bold mb-1">Toko <span class="text-red-600">*</span></label>
                <select name="id_toko" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    @foreach($userStores as $store)
                        <option value="{{ $store->id_toko }}" {{ old('id_toko', $distributor->id_toko) == $store->id_toko ? 'selected' : '' }}>
                            {{ $store->nama_toko }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kode Distributor --}}
            <div>
                <label class="block text-xs font-bold mb-1">Kode Distributor <span class="text-red-600">*</span></label>
                <input type="text" name="kode_distributor" value="{{ old('kode_distributor', $distributor->kode_distributor) }}" required
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            {{-- Nama Distributor --}}
            <div>
                <label class="block text-xs font-bold mb-1">Nama Distributor <span class="text-red-600">*</span></label>
                <input type="text" name="nama_distributor" value="{{ old('nama_distributor', $distributor->nama_distributor) }}" required
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>

            {{-- Nama Perusahaan --}}
            <div>
                <label class="block text-xs font-bold mb-1">Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $distributor->nama_perusahaan) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>
        </div>

        {{-- Alamat --}}
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('alamat', $distributor->alamat) }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-3">
            {{-- Kota --}}
            <div>
                <label class="block text-xs font-bold mb-1">Kota</label>
                <input type="text" name="kota" value="{{ old('kota', $distributor->kota) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>

            {{-- Provinsi --}}
            <div>
                <label class="block text-xs font-bold mb-1">Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', $distributor->provinsi) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>

            {{-- Kode Pos --}}
            <div>
                <label class="block text-xs font-bold mb-1">Kode Pos</label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos', $distributor->kode_pos) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            {{-- No Telp --}}
            <div>
                <label class="block text-xs font-bold mb-1">No. Telepon</label>
                <input type="text" name="no_telp" value="{{ old('no_telp', $distributor->no_telp) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-bold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $distributor->email) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            {{-- Nama Kontak --}}
            <div>
                <label class="block text-xs font-bold mb-1">Nama Kontak Person</label>
                <input type="text" name="nama_kontak" value="{{ old('nama_kontak', $distributor->nama_kontak) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>

            {{-- No HP Kontak --}}
            <div>
                <label class="block text-xs font-bold mb-1">No. HP Kontak Person</label>
                <input type="text" name="no_hp_kontak" value="{{ old('no_hp_kontak', $distributor->no_hp_kontak) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner">
            </div>
        </div>

        {{-- NPWP --}}
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1">NPWP</label>
            <input type="text" name="npwp" value="{{ old('npwp', $distributor->npwp) }}"
                   class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono">
        </div>

        {{-- Keterangan --}}
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('keterangan', $distributor->keterangan) }}</textarea>
        </div>

        {{-- Status Aktif --}}
        <div class="mt-4 border-t border-gray-300 pt-3">
            <label class="flex items-center gap-2 text-xs">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $distributor->is_active) ? 'checked' : '' }}
                       class="border border-gray-400">
                <span class="font-bold"><i class="fa fa-check-circle"></i> Status Aktif</span>
                <span class="text-gray-500">(centang jika distributor masih aktif)</span>
            </label>
        </div>

        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-blue-700 text-white border border-blue-900 px-4 py-2 text-xs hover:bg-blue-600">
                <i class="fa fa-save"></i> UPDATE DISTRIBUTOR
            </button>
            <a href="{{ route('owner.distributor.index') }}" class="bg-gray-200 border border-gray-400 px-4 py-2 text-xs hover:bg-gray-300">
                BATAL
            </a>
        </div>
    </form>
</div>
@endsection
