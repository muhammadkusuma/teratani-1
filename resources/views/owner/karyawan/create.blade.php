@extends('layouts.owner')
@section('title', 'Tambah Karyawan')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-plus-circle"></i> TAMBAH KARYAWAN BARU</h2>
    <a href="{{ route('owner.karyawan.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs"><i class="fa fa-arrow-left"></i> KEMBALI</a>
</div>
@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs"><ul class="list-disc ml-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="bg-white border border-gray-400 p-4">
    <form action="{{ route('owner.karyawan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-bold mb-1">Toko <span class="text-red-600">*</span></label>
                <select name="id_toko" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="">-- Pilih Toko --</option>
                    @foreach($userStores as $store)<option value="{{ $store->id_toko }}" {{ old('id_toko') == $store->id_toko ? 'selected' : '' }}>{{ $store->nama_toko }}</option>@endforeach
                </select></div>
            <div><label class="block text-xs font-bold mb-1">Kode Karyawan <span class="text-red-600">*</span></label>
                <input type="text" name="kode_karyawan" value="{{ old('kode_karyawan', $kodeKaryawan) }}" required class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono"></div>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">NIK</label><input type="text" name="nik" value="{{ old('nik') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
            <div class="col-span-2"><label class="block text-xs font-bold mb-1">Nama Lengkap <span class="text-red-600">*</span></label><input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">Tempat Lahir</label><input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
            <div><label class="block text-xs font-bold mb-1">Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
            <div><label class="block text-xs font-bold mb-1">Jenis Kelamin <span class="text-red-600">*</span></label>
                <select name="jenis_kelamin" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select></div>
        </div>
        <div class="mt-3"><label class="block text-xs font-bold mb-1">Alamat</label><textarea name="alamat" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('alamat') }}</textarea></div>
        <div class="grid grid-cols-2 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">No. HP <span class="text-red-600">*</span></label><input type="text" name="no_hp" value="{{ old('no_hp') }}" required class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
            <div><label class="block text-xs font-bold mb-1">Email</label><input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">Jabatan <span class="text-red-600">*</span></label>
                <select name="jabatan" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="Kasir" {{ old('jabatan') == 'Kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="Supervisor" {{ old('jabatan') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="Manager" {{ old('jabatan') == 'Manager' ? 'selected' : '' }}>Manager</option>
                    <option value="Admin" {{ old('jabatan') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Staff Gudang" {{ old('jabatan') == 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
                    <option value="Sales" {{ old('jabatan') == 'Sales' ? 'selected' : '' }}>Sales</option>
                    <option value="Lainnya" {{ old('jabatan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select></div>
            <div><label class="block text-xs font-bold mb-1">Tanggal Masuk <span class="text-red-600">*</span></label><input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
            <div><label class="block text-xs font-bold mb-1">Status <span class="text-red-600">*</span></label>
                <select name="status_karyawan" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="Aktif" {{ old('status_karyawan', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Cuti" {{ old('status_karyawan') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="Resign" {{ old('status_karyawan') == 'Resign' ? 'selected' : '' }}>Resign</option>
                </select></div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">Gaji Pokok (Rp)</label><input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', 0) }}" min="0" step="1000" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
            <div><label class="block text-xs font-bold mb-1">Foto Karyawan</label><input type="file" name="foto" accept="image/*" class="w-full border border-gray-400 p-1 text-xs"></div>
        </div>
        <div class="mt-3"><label class="block text-xs font-bold mb-1">Keterangan</label><textarea name="keterangan" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('keterangan') }}</textarea></div>
        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-blue-700 text-white border border-blue-900 px-4 py-2 text-xs hover:bg-blue-600"><i class="fa fa-save"></i> SIMPAN KARYAWAN</button>
            <a href="{{ route('owner.karyawan.index') }}" class="bg-gray-200 border border-gray-400 px-4 py-2 text-xs hover:bg-gray-300">BATAL</a>
        </div>
    </form>
</div>
@endsection
