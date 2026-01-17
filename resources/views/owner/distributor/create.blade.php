@extends('layouts.owner')

@section('title', 'Tambah Distributor')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-plus-circle"></i> TAMBAH DISTRIBUTOR BARU
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
    <form action="{{ route('owner.distributor.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            
            <div>
                <label class="block text-xs font-bold mb-1">Toko <span class="text-red-600">*</span></label>
                <select name="id_toko" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="">-- Pilih Toko --</option>
                    @foreach($userStores as $store)
                        <option value="{{ $store->id_toko }}" {{ old('id_toko') == $store->id_toko ? 'selected' : '' }}>
                            {{ $store->nama_toko }}
                        </option>
                    @endforeach
                </select>
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1">Kode Distributor <span class="text-red-600">*</span></label>
                <input type="text" name="kode_distributor" value="{{ old('kode_distributor', $kodeDistributor) }}" required
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono" placeholder="DIST001">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            
            <div>
                <label class="block text-xs font-bold mb-1">Nama Distributor <span class="text-red-600">*</span></label>
                <input type="text" name="nama_distributor" value="{{ old('nama_distributor') }}" required
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="CV Maju Jaya">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1">Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="PT Maju Jaya Abadi">
            </div>
        </div>

        
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('alamat') }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-3">
            
            <div>
                <label class="block text-xs font-bold mb-1">Kota</label>
                <input type="text" name="kota" value="{{ old('kota') }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="Malang">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1">Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi') }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="Jawa Timur">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1">Kode Pos</label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos') }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="65141">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            
            <div>
                <label class="block text-xs font-bold mb-1">No. Telepon</label>
                <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="0341-123456">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="distributor@example.com">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            
            <div>
                <label class="block text-xs font-bold mb-1">Nama Kontak Person</label>
                <input type="text" name="nama_kontak" value="{{ old('nama_kontak') }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="Budi Santoso">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1">No. HP Kontak Person</label>
                <input type="text" name="no_hp_kontak" value="{{ old('no_hp_kontak') }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="081234567890">
            </div>
        </div>

        
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1">NPWP</label>
            <input type="text" name="npwp" value="{{ old('npwp') }}"
                   class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono" placeholder="00.000.000.0-000.000">
        </div>

        
        <div class="mt-3 border-t border-gray-300 pt-3">
            <label class="block text-xs font-bold mb-1">
                <i class="fa fa-money-bill-wave text-orange-600"></i> Hutang Awal (Opsional)
            </label>
            <input type="number" step="0.01" name="hutang_awal" value="{{ old('hutang_awal') }}"
                   class="w-full border border-gray-400 p-1 text-xs shadow-inner" placeholder="0">
            <small class="text-gray-500 text-[10px]">
                <i class="fa fa-info-circle"></i> Isi jika distributor ini memiliki hutang yang sudah ada sebelumnya. Kosongkan jika tidak ada hutang awal.
            </small>
        </div>

        
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('keterangan') }}</textarea>
        </div>

        
        <div class="mt-4 border-t border-gray-300 pt-3">
            <label class="flex items-center gap-2 text-xs">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="border border-gray-400">
                <span class="font-bold"><i class="fa fa-check-circle"></i> Status Aktif</span>
                <span class="text-gray-500">(centang jika distributor masih aktif)</span>
            </label>
        </div>

        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-blue-700 text-white border border-blue-900 px-4 py-2 text-xs hover:bg-blue-600">
                <i class="fa fa-save"></i> SIMPAN DISTRIBUTOR
            </button>
            <a href="{{ route('owner.distributor.index') }}" class="bg-gray-200 border border-gray-400 px-4 py-2 text-xs hover:bg-gray-300">
                BATAL
            </a>
        </div>
    </form>
</div>
@endsection
