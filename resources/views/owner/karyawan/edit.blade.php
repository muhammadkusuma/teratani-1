@extends('layouts.owner')
@section('title', 'Edit Karyawan')
@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-user-edit text-blue-700"></i> Edit Karyawan
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.karyawan.index') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
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
    <form action="{{ route('owner.karyawan.update', $karyawan->id_karyawan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Toko <span class="text-red-600">*</span>
                </label>
                <select name="id_toko" required class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm bg-gray-50">
                    <option value="">-- Pilih Toko --</option>
                    @foreach($userStores as $store)
                    <option value="{{ $store->id_toko }}" {{ old('id_toko', $karyawan->id_toko) == $store->id_toko ? 'selected' : '' }}>{{ $store->nama_toko }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Kode Karyawan <span class="text-red-600">*</span>
                </label>
                <input type="text" name="kode_karyawan" value="{{ old('kode_karyawan', $karyawan->kode_karyawan) }}" required class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $karyawan->nik) }}" class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Nama Lengkap <span class="text-red-600">*</span>
                </label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir?->format('Y-m-d')) }}" class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Jenis Kelamin <span class="text-red-600">*</span>
                </label>
                <select name="jenis_kelamin" required class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm bg-gray-50">
                    <option value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">{{ old('alamat', $karyawan->alamat) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    No. HP <span class="text-red-600">*</span>
                </label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $karyawan->no_hp) }}" required class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ old('email', $karyawan->email) }}" class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Jabatan <span class="text-red-600">*</span>
                </label>
                <select name="jabatan" required class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm bg-gray-50">
                    <option value="Kasir" {{ old('jabatan', $karyawan->jabatan) == 'Kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="Supervisor" {{ old('jabatan', $karyawan->jabatan) == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="Manager" {{ old('jabatan', $karyawan->jabatan) == 'Manager' ? 'selected' : '' }}>Manager</option>
                    <option value="Admin" {{ old('jabatan', $karyawan->jabatan) == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Staff Gudang" {{ old('jabatan', $karyawan->jabatan) == 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
                    <option value="Sales" {{ old('jabatan', $karyawan->jabatan) == 'Sales' ? 'selected' : '' }}>Sales</option>
                    <option value="Lainnya" {{ old('jabatan', $karyawan->jabatan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Tanggal Masuk <span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk?->format('Y-m-d')) }}" required class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    Status <span class="text-red-600">*</span>
                </label>
                <select name="status_karyawan" required class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm bg-gray-50">
                    <option value="Aktif" {{ old('status_karyawan', $karyawan->status_karyawan) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Cuti" {{ old('status_karyawan', $karyawan->status_karyawan) == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="Resign" {{ old('status_karyawan', $karyawan->status_karyawan) == 'Resign' ? 'selected' : '' }}>Resign</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Gaji Pokok (Rp)</label>
                <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $karyawan->gaji_pokok) }}" min="0" step="1000" class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Foto Karyawan</label>
                <input type="file" name="foto" accept="image/*" class="w-full border border-gray-300 p-2 md:p-1 text-xs rounded-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all">
                @if($karyawan->foto)
                <p class="text-[10px] text-gray-500 mt-1"><i class="fa fa-info-circle text-blue-400"></i> Foto saat ini: {{ basename($karyawan->foto) }}</p>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">Keterangan</label>
            <textarea name="keterangan" rows="2" class="w-full border border-gray-300 p-2.5 md:p-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">{{ old('keterangan', $karyawan->keterangan) }}</textarea>
        </div>

        <div class="flex flex-col md:flex-row justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.karyawan.index') }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 text-xs font-bold hover:bg-blue-600 transition-colors shadow-sm rounded-sm uppercase">
                <i class="fa fa-save"></i> Update Karyawan
            </button>
        </div>
    </form>
</div>
@endsection
