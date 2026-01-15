@extends('layouts.owner')
@section('title', 'Detail Karyawan')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-id-card"></i> DETAIL KARYAWAN</h2>
    <div class="flex gap-2">
        <a href="{{ route('owner.karyawan.edit', $karyawan->id_karyawan) }}" class="px-3 py-1 bg-yellow-400 border border-yellow-600 hover:bg-yellow-300 text-xs"><i class="fa fa-edit"></i> EDIT</a>
        <a href="{{ route('owner.karyawan.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs"><i class="fa fa-arrow-left"></i> KEMBALI</a>
    </div>
</div>
<div class="grid grid-cols-3 gap-4">
    <div class="col-span-1 bg-white border border-gray-400 p-4 text-center">
        @if($karyawan->foto)
        <img src="{{ asset('storage/' . $karyawan->foto) }}" alt="{{ $karyawan->nama_lengkap }}" class="w-full border border-gray-400 mb-3">
        @else
        <div class="w-full h-64 bg-gray-200 border border-gray-400 flex items-center justify-center mb-3"><i class="fa fa-user text-6xl text-gray-400"></i></div>
        @endif
        <h3 class="font-bold text-base">{{ $karyawan->nama_lengkap }}</h3>
        <p class="text-xs text-gray-600 font-mono">{{ $karyawan->kode_karyawan }}</p>
        <div class="mt-3">
            @if($karyawan->status_karyawan == 'Aktif')
            <span class="px-3 py-1 rounded bg-green-200 text-green-800 text-xs font-bold">AKTIF</span>
            @elseif($karyawan->status_karyawan == 'Cuti')
            <span class="px-3 py-1 rounded bg-yellow-200 text-yellow-800 text-xs font-bold">CUTI</span>
            @else
            <span class="px-3 py-1 rounded bg-red-200 text-red-800 text-xs font-bold">RESIGN</span>
            @endif
        </div>
    </div>
    <div class="col-span-2 bg-white border border-gray-400 p-4">
        <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3"><i class="fa fa-user"></i> DATA PRIBADI</h3>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div><span class="text-gray-600">NIK:</span> <span class="font-semibold">{{ $karyawan->nik ?? '-' }}</span></div>
            <div><span class="text-gray-600">Jenis Kelamin:</span> <span class="font-semibold">{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
            <div><span class="text-gray-600">Tempat Lahir:</span> <span class="font-semibold">{{ $karyawan->tempat_lahir ?? '-' }}</span></div>
            <div><span class="text-gray-600">Tanggal Lahir:</span> <span class="font-semibold">{{ $karyawan->tanggal_lahir?->format('d/m/Y') ?? '-' }}</span></div>
            <div><span class="text-gray-600">Umur:</span> <span class="font-semibold">{{ $karyawan->umur ? $karyawan->umur . ' tahun' : '-' }}</span></div>
            <div><span class="text-gray-600">No. HP:</span> <span class="font-semibold">{{ $karyawan->no_hp }}</span></div>
            <div class="col-span-2"><span class="text-gray-600">Email:</span> <span class="font-semibold">{{ $karyawan->email ?? '-' }}</span></div>
            <div class="col-span-2"><span class="text-gray-600">Alamat:</span> <span class="font-semibold">{{ $karyawan->alamat ?? '-' }}</span></div>
        </div>
        <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3 mt-4"><i class="fa fa-briefcase"></i> DATA PEKERJAAN</h3>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div><span class="text-gray-600">Toko:</span> <span class="font-semibold text-blue-700">{{ $karyawan->toko->nama_toko }}</span></div>
            <div><span class="text-gray-600">Jabatan:</span> <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold">{{ $karyawan->jabatan }}</span></div>
            <div><span class="text-gray-600">Tanggal Masuk:</span> <span class="font-semibold">{{ $karyawan->tanggal_masuk?->format('d/m/Y') }}</span></div>
            <div><span class="text-gray-600">Masa Kerja:</span> <span class="font-semibold">{{ $karyawan->masa_kerja }}</span></div>
            @if($karyawan->tanggal_keluar)
            <div><span class="text-gray-600">Tanggal Keluar:</span> <span class="font-semibold text-red-600">{{ $karyawan->tanggal_keluar->format('d/m/Y') }}</span></div>
            @endif
            <div><span class="text-gray-600">Gaji Pokok:</span> <span class="font-semibold">Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}</span></div>
        </div>
        @if($karyawan->keterangan)
        <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3 mt-4"><i class="fa fa-sticky-note"></i> KETERANGAN</h3>
        <p class="text-xs text-gray-700">{{ $karyawan->keterangan }}</p>
        @endif
    </div>
</div>
@endsection
