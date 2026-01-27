@extends('layouts.owner')

@section('title', 'Edit Distributor')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-edit"></i> EDIT DISTRIBUTOR
    </h2>
    <a href="{{ route('owner.distributor.index') }}" class="w-full md:w-auto text-center px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs font-bold">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Toko <span class="text-red-600">*</span></label>
                <select name="id_toko" required class="w-full border border-gray-400 p-1 text-xs shadow-inner bg-gray-50 focus:bg-white transition-colors">
                    @foreach($userStores as $store)
                        <option value="{{ $store->id_toko }}" {{ old('id_toko', $distributor->id_toko) == $store->id_toko ? 'selected' : '' }}>
                            {{ $store->nama_toko }}
                        </option>
                    @endforeach
                </select>
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Kode Distributor <span class="text-red-600">*</span></label>
                <input type="text" name="kode_distributor" value="{{ old('kode_distributor', $distributor->kode_distributor) }}" required
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono bg-gray-50 focus:bg-white transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Nama Distributor <span class="text-red-600">*</span></label>
                <input type="text" name="nama_distributor" value="{{ old('nama_distributor', $distributor->nama_distributor) }}" required
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $distributor->nama_perusahaan) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>
        </div>

        
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">{{ old('alamat', $distributor->alamat) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Kota</label>
                <input type="text" name="kota" value="{{ old('kota', $distributor->kota) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', $distributor->provinsi) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Kode Pos</label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos', $distributor->kode_pos) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">No. Telepon</label>
                <input type="text" name="no_telp" value="{{ old('no_telp', $distributor->no_telp) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Email</label>
                <input type="email" name="email" value="{{ old('email', $distributor->email) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Nama Kontak Person</label>
                <input type="text" name="nama_kontak" value="{{ old('nama_kontak', $distributor->nama_kontak) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>

            
            <div>
                <label class="block text-xs font-bold mb-1 uppercase text-gray-600">No. HP Kontak Person</label>
                <input type="text" name="no_hp_kontak" value="{{ old('no_hp_kontak', $distributor->no_hp_kontak) }}"
                       class="w-full border border-gray-400 p-1 text-xs shadow-inner focus:bg-white transition-colors">
            </div>
        </div>

        
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1 uppercase text-gray-600">NPWP</label>
            <input type="text" name="npwp" value="{{ old('npwp', $distributor->npwp) }}"
                   class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono bg-gray-50 focus:bg-white transition-colors">
        </div>

        
        <div class="mt-4 border-t border-gray-300 pt-3">
            <label class="block text-xs font-bold mb-1 uppercase text-gray-600 bg-orange-50 p-1 rounded inline-block">
                <i class="fa fa-money-bill-wave text-orange-600"></i> Tambah Hutang Awal (Opsional)
            </label>
            <input type="number" step="0.01" name="hutang_awal" value="{{ old('hutang_awal') }}"
                   class="w-full border border-gray-400 p-2 text-xs shadow-inner focus:bg-white transition-colors mt-1" placeholder="Masukkan nominal (Isi jika ingin menambah hutang baru)">
            <div class="bg-gray-100 p-2 mt-2 border-l-4 border-orange-500">
                <small class="text-gray-600 text-[10px] block">
                    <i class="fa fa-info-circle"></i> Saldo hutang saat ini: <strong class="text-red-600 text-sm">Rp {{ number_format($distributor->saldo_utang, 0, ',', '.') }}</strong>
                </small>
            </div>
        </div>

        
        <div class="mt-3">
            <label class="block text-xs font-bold mb-1 uppercase text-gray-600">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full border border-gray-400 p-2 text-xs shadow-inner focus:bg-white transition-colors">{{ old('keterangan', $distributor->keterangan) }}</textarea>
        </div>

        
        <div class="mt-4 border-t border-gray-300 pt-3 flex flex-wrap items-center gap-3">
            <label class="flex items-center gap-2 text-xs cursor-pointer group">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $distributor->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 rounded border-gray-400 focus:ring-blue-500">
                <span class="font-bold group-hover:text-blue-700 transition-colors uppercase"><i class="fa fa-check-circle"></i> Status Aktif</span>
            </label>
            <span class="text-gray-500 text-[10px] italic">(Centang jika distributor masih menjalin kerjasama aktif)</span>
        </div>

        <div class="flex flex-col md:flex-row gap-2 mt-6">
            <button type="submit" class="w-full md:w-auto bg-blue-700 text-white border border-blue-900 px-6 py-2 text-xs font-bold shadow hover:bg-blue-600 transition-all uppercase tracking-wider">
                <i class="fa fa-save"></i> UPDATE DISTRIBUTOR
            </button>
            <a href="{{ route('owner.distributor.index') }}" class="w-full md:w-auto text-center bg-gray-200 border border-gray-400 px-6 py-2 text-xs font-bold hover:bg-gray-300 transition-all uppercase tracking-wider">
                BATAL
            </a>
        </div>
    </form>
</div>
@endsection
