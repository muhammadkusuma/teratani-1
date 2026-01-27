@extends('layouts.owner')

@section('title', 'Tambah Distributor')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-3">
    <h2 class="font-bold text-lg border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-plus-circle text-blue-700"></i> Tambah Distributor Baru
    </h2>
    <a href="{{ route('owner.distributor.index') }}" class="w-full md:w-auto text-center px-4 py-1.5 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs font-bold transition-all uppercase">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

@if($errors->any())
    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-3 mb-4 shadow-sm text-xs">
        <p class="font-black uppercase mb-1 tracking-wider"><i class="fa fa-exclamation-triangle"></i> Terjadi Kesalahan:</p>
        <ul class="list-disc ml-5 space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white border border-gray-300 p-6 shadow-sm rounded-sm">
    <form action="{{ route('owner.distributor.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Toko <span class="text-rose-600">*</span></label>
                <select name="id_toko" required class="w-full border border-gray-300 p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none">
                    <option value="">-- Pilih Toko --</option>
                    @foreach($userStores as $store)
                        <option value="{{ $store->id_toko }}" {{ old('id_toko') == $store->id_toko ? 'selected' : '' }}>
                            {{ $store->nama_toko }}
                        </option>
                    @endforeach
                </select>
            </div>

            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Kode Distributor <span class="text-rose-600">*</span></label>
                <input type="text" name="kode_distributor" value="{{ old('kode_distributor', $kodeDistributor) }}" required
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner font-mono bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none" placeholder="Contoh: DIST001">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Nama Distributor <span class="text-rose-600">*</span></label>
                <input type="text" name="nama_distributor" value="{{ old('nama_distributor') }}" required
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="Contoh: CV Maju Jaya">
            </div>

            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}"
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="Contoh: PT Maju Jaya Abadi">
            </div>
        </div>

        
        <div class="mt-4">
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Alamat Lengkap</label>
            <textarea name="alamat" rows="2" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="Jl. Raya Utama No. 123...">{{ old('alamat') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-4">
            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Kota</label>
                <input type="text" name="kota" value="{{ old('kota') }}"
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="Malang">
            </div>

            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi') }}"
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="Jawa Timur">
            </div>

            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Kode Pos</label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos') }}"
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="65141">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">No. Telepon Kantor</label>
                <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="0341-123456">
            </div>

            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Email Korespondensi</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="distributor@example.com">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Nama Kontak Person (Sales/Admin)</label>
                <input type="text" name="nama_kontak" value="{{ old('nama_kontak') }}"
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="Budi Santoso">
            </div>

            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">No. Handphone Kontak</label>
                <input type="text" name="no_hp_kontak" value="{{ old('no_hp_kontak') }}"
                       class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" placeholder="0812XXXXXXXX">
            </div>
        </div>

        
        <div class="mt-4">
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">NPWP / Identitas Pajak</label>
            <input type="text" name="npwp" value="{{ old('npwp') }}"
                   class="w-full border border-gray-300 p-2 text-xs shadow-inner font-mono bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none" placeholder="00.000.000.0-000.000">
        </div>

        
        <div class="mt-5 border-t border-gray-200 pt-4">
            <label class="block text-[10px] font-black text-gray-500 uppercase bg-amber-50 p-1 rounded inline-block mb-1 tracking-wider">
                <i class="fa fa-money-bill-wave text-amber-600"></i> Hutang Awal (Saldo Awal)
            </label>
            <input type="number" step="0.01" name="hutang_awal" value="{{ old('hutang_awal') }}"
                   class="w-full border border-gray-300 p-3 text-sm shadow-inner focus:border-blue-500 transition-all outline-none bg-amber-50/20" placeholder="0.00">
            <div class="flex items-start gap-2 bg-gray-50 p-2 mt-2 border-l-4 border-amber-400">
                <i class="fa fa-info-circle text-amber-600 text-xs mt-0.5"></i>
                <small class="text-gray-500 text-[10px] leading-tight font-semibold uppercase">
                    Isi jika distributor ini memiliki saldo hutang yang sudah ada sebelumnya. Biarkan 0 jika tidak ada saldo awal.
                </small>
            </div>
        </div>

        
        <div class="mt-4">
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Catatan / Keterangan Tambahan</label>
            <textarea name="keterangan" rows="3" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none">{{ old('keterangan') }}</textarea>
        </div>

        
        <div class="mt-5 border-t border-gray-200 pt-4 flex flex-wrap items-center gap-3">
            <label class="flex items-center gap-2 text-xs cursor-pointer group">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="font-black group-hover:text-blue-700 transition-colors uppercase tracking-tight"><i class="fa fa-check-circle"></i> Status Aktif Secara Default</span>
            </label>
            <span class="text-gray-400 text-[10px] italic font-semibold">(Menandakan distributor siap digunakan dalam transaksi)</span>
        </div>

        <div class="flex flex-col md:flex-row gap-3 mt-8">
            <button type="submit" class="w-full md:w-auto bg-blue-700 text-white border border-blue-900 px-8 py-3 text-xs font-black shadow-lg hover:bg-blue-600 hover:scale-[1.02] transition-all rounded-sm uppercase tracking-widest">
                <i class="fa fa-save"></i> Simpan Distributor
            </button>
            <a href="{{ route('owner.distributor.index') }}" class="w-full md:w-auto text-center bg-gray-100 text-gray-700 border border-gray-300 px-8 py-3 text-xs font-black hover:bg-gray-200 transition-all rounded-sm uppercase tracking-widest">
                Batalkan
            </a>
        </div>
    </form>
</div>
@endsection
