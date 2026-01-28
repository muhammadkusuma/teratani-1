@extends('layouts.owner')
@section('title', 'Detail Karyawan')
@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-id-card text-blue-700"></i> Detail Karyawan
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.karyawan.edit', $karyawan->id_karyawan) }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-amber-400 border border-amber-600 hover:bg-amber-300 text-xs font-bold transition-all rounded-sm uppercase no-underline text-black">
            <i class="fa fa-edit"></i> Edit
        </a>
        <a href="{{ route('owner.karyawan.index') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    {{-- Photo & Basic Info Card --}}
    <div class="md:col-span-1 bg-white border border-gray-300 p-4 md:p-6 text-center shadow-sm rounded-sm">
        @if($karyawan->foto)
        <img src="{{ asset('storage/' . $karyawan->foto) }}" alt="{{ $karyawan->nama_lengkap }}" class="w-full max-w-xs mx-auto md:max-w-full border-2 border-blue-200 mb-4 rounded-sm shadow">
        @else
        <div class="w-full max-w-xs mx-auto md:max-w-full h-48 md:h-64 bg-gray-100 border-2 border-gray-300 flex items-center justify-center mb-4 rounded-sm">
            <i class="fa fa-user text-6xl text-gray-300"></i>
        </div>
        @endif
        <h3 class="font-black text-base md:text-lg text-gray-800 mb-1">{{ $karyawan->nama_lengkap }}</h3>
        <p class="text-xs text-gray-500 font-mono bg-gray-100 inline-block px-3 py-1 rounded mb-3">{{ $karyawan->kode_karyawan }}</p>
        <div class="mt-4">
            @if($karyawan->status_karyawan == 'Aktif')
            <span class="px-4 py-2 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-black uppercase tracking-tighter shadow-sm inline-block">Aktif</span>
            @elseif($karyawan->status_karyawan == 'Cuti')
            <span class="px-4 py-2 rounded-full bg-amber-100 text-amber-800 border border-amber-200 text-xs font-black uppercase tracking-tighter shadow-sm inline-block">Cuti</span>
            @else
            <span class="px-4 py-2 rounded-full bg-rose-100 text-rose-800 border border-rose-200 text-xs font-black uppercase tracking-tighter shadow-sm inline-block">Resign</span>
            @endif
        </div>
    </div>

    {{-- Detailed Info Card --}}
    <div class="md:col-span-2 bg-white border border-gray-300 p-4 md:p-6 shadow-sm rounded-sm">
        <h3 class="font-black text-sm border-b-2 border-blue-600 pb-2 mb-4 text-blue-900 uppercase tracking-wider">
            <i class="fa fa-user"></i> Data Pribadi
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 text-xs mb-6">
            <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">NIK</span>
                <span class="font-semibold text-gray-800">{{ $karyawan->nik ?? '-' }}</span>
            </div>
            <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Jenis Kelamin</span>
                <span class="font-semibold text-gray-800">
                    <i class="fa fa-{{ $karyawan->jenis_kelamin == 'L' ? 'mars' : 'venus' }} text-{{ $karyawan->jenis_kelamin == 'L' ? 'blue' : 'pink' }}-500"></i>
                    {{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </span>
            </div>
            <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Tempat Lahir</span>
                <span class="font-semibold text-gray-800">{{ $karyawan->tempat_lahir ?? '-' }}</span>
            </div>
            <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Tanggal Lahir</span>
                <span class="font-semibold text-gray-800">{{ $karyawan->tanggal_lahir?->format('d/m/Y') ?? '-' }}</span>
            </div>
            <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Umur</span>
                <span class="font-semibold text-gray-800">{{ $karyawan->umur ? $karyawan->umur . ' tahun' : '-' }}</span>
            </div>
            <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">No. HP</span>
                <span class="font-semibold text-gray-800">
                    <i class="fa fa-phone text-emerald-500"></i> {{ $karyawan->no_hp }}
                </span>
            </div>
            <div class="md:col-span-2 bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Email</span>
                <span class="font-semibold text-gray-800">
                    <i class="fa fa-envelope text-blue-500"></i> {{ $karyawan->email ?? '-' }}
                </span>
            </div>
            <div class="md:col-span-2 bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Alamat</span>
                <span class="font-semibold text-gray-800">{{ $karyawan->alamat ?? '-' }}</span>
            </div>
        </div>

        <h3 class="font-black text-sm border-b-2 border-blue-600 pb-2 mb-4 text-blue-900 uppercase tracking-wider">
            <i class="fa fa-briefcase"></i> Data Pekerjaan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 text-xs mb-6">
            <div class="bg-blue-50 p-3 rounded-sm border border-blue-200">
                <span class="text-blue-700 font-bold uppercase text-[10px] tracking-wider block mb-1">Toko</span>
                <span class="font-black text-blue-900">
                    <i class="fa fa-store"></i> {{ $karyawan->toko->nama_toko }}
                </span>
            </div>
            <div class="bg-blue-50 p-3 rounded-sm border border-blue-200">
                <span class="text-blue-700 font-bold uppercase text-[10px] tracking-wider block mb-1">Jabatan</span>
                <span class="bg-blue-600 text-white px-3 py-1 rounded font-black text-xs inline-block shadow-sm">{{ $karyawan->jabatan }}</span>
            </div>
            <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Tanggal Masuk</span>
                <span class="font-semibold text-gray-800">{{ $karyawan->tanggal_masuk?->format('d/m/Y') }}</span>
            </div>
            <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
                <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Masa Kerja</span>
                <span class="font-semibold text-gray-800">
                    <i class="fa fa-calendar-check text-green-500"></i> {{ $karyawan->masa_kerja }}
                </span>
            </div>
            @if($karyawan->tanggal_keluar)
            <div class="bg-rose-50 p-3 rounded-sm border border-rose-200">
                <span class="text-rose-700 font-bold uppercase text-[10px] tracking-wider block mb-1">Tanggal Keluar</span>
                <span class="font-semibold text-rose-700">{{ $karyawan->tanggal_keluar->format('d/m/Y') }}</span>
            </div>
            @endif
            <div class="bg-emerald-50 p-3 rounded-sm border border-emerald-200">
                <span class="text-emerald-700 font-bold uppercase text-[10px] tracking-wider block mb-1">Gaji Pokok</span>
                <span class="font-black text-emerald-900 text-sm">Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($karyawan->keterangan)
        <h3 class="font-black text-sm border-b-2 border-blue-600 pb-2 mb-4 text-blue-900 uppercase tracking-wider">
            <i class="fa fa-sticky-note"></i> Keterangan
        </h3>
        <div class="bg-amber-50 p-4 rounded-sm border border-amber-200 text-xs text-gray-700 leading-relaxed">
            {{ $karyawan->keterangan }}
        </div>
        @endif
    </div>
</div>
@endsection
