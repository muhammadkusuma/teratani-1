@extends('layouts.owner')

@section('title', 'Detail Perusahaan')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-building text-blue-700"></i> Detail Perusahaan
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.perusahaan.edit') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-amber-400 border border-amber-600 hover:bg-amber-300 text-xs font-bold transition-all rounded-sm uppercase no-underline text-black">
            <i class="fa fa-edit"></i> Edit Data
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($perusahaan)
<div class="bg-white border border-gray-300 p-4 md:p-6 mb-4 shadow-sm rounded-sm">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 text-xs">
        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Nama Perusahaan</span>
            <span class="font-black text-sm text-gray-800">{{ $perusahaan->nama_perusahaan }}</span>
        </div>

        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Pemilik</span>
            <span class="font-semibold text-gray-800">{{ $perusahaan->pemilik ?? '-' }}</span>
        </div>

        <div class="md:col-span-2 bg-blue-50 p-3 rounded-sm border border-blue-200">
            <span class="text-blue-700 font-bold uppercase text-[10px] tracking-wider block mb-1">Alamat Lengkap</span>
            <span class="font-semibold text-gray-800">{{ $perusahaan->alamat ?? '-' }}</span>
        </div>

        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Kota</span>
            <span class="font-semibold text-gray-800">{{ $perusahaan->kota ?? '-' }}</span>
        </div>

        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Provinsi</span>
            <span class="font-semibold text-gray-800">{{ $perusahaan->provinsi ?? '-' }}</span>
        </div>

        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Kode Pos</span>
            <span class="font-semibold text-gray-800">{{ $perusahaan->kode_pos ?? '-' }}</span>
        </div>

        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">No. Telepon</span>
            <span class="font-semibold text-gray-800">
                <i class="fa fa-phone text-emerald-500"></i> {{ $perusahaan->no_telp ?? '-' }}
            </span>
        </div>

        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Email</span>
            <span class="font-semibold text-gray-800">
                <i class="fa fa-envelope text-blue-500"></i> {{ $perusahaan->email ?? '-' }}
            </span>
        </div>

        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Website</span>
            <span class="font-semibold text-gray-800">
                <i class="fa fa-globe text-blue-500"></i> {{ $perusahaan->website ?? '-' }}
            </span>
        </div>

        <div class="bg-gray-50 p-3 rounded-sm border border-gray-200">
            <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block mb-1">NPWP</span>
            <span class="font-mono font-semibold text-gray-800">{{ $perusahaan->npwp ?? '-' }}</span>
        </div>

        @if($perusahaan->logo)
        <div class="md:col-span-2 bg-gradient-to-br from-blue-50 to-white p-4 rounded-sm border border-blue-200 text-center">
            <span class="text-blue-700 font-bold uppercase text-[10px] tracking-wider block mb-3">Logo Perusahaan</span>
            <img src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan" class="max-w-full md:max-w-xs border-2 border-blue-300 mx-auto rounded-sm shadow-md">
        </div>
        @endif
    </div>
</div>

<div class="bg-white border border-gray-300 p-4 md:p-6 shadow-sm rounded-sm">
    <h3 class="font-black text-sm border-b-2 border-blue-600 pb-2 mb-4 text-blue-900 uppercase tracking-wider">
        <i class="fa fa-store"></i> Daftar Toko / Cabang
    </h3>
    
    @if($perusahaan->tokos->count() > 0)
    
    {{-- Mobile Card View --}}
    <div class="block md:hidden space-y-3">
        @foreach($perusahaan->tokos as $key => $toko)
        <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 {{ $toko->is_pusat ? 'border-blue-600' : 'border-gray-300' }} p-3 shadow-sm rounded-sm">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h4 class="font-black text-sm text-gray-800">{{ $toko->nama_toko }}</h4>
                    <p class="text-[10px] font-mono text-gray-500">{{ $toko->kode_toko }}</p>
                </div>
                <div>
                    @if($toko->is_active)
                        <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase border border-emerald-200">Aktif</span>
                    @else
                        <span class="px-2 py-1 rounded-full bg-rose-100 text-rose-800 text-[9px] font-black uppercase border border-rose-200">Tutup</span>
                    @endif
                </div>
            </div>
            @if($toko->is_pusat)
                <span class="inline-block bg-blue-600 text-white px-2 py-1 text-[9px] font-black rounded-sm mb-2 shadow-sm">
                    <i class="fa fa-star"></i> PUSAT
                </span>
            @endif
            <p class="text-xs text-gray-600">
                <i class="fa fa-map-marker-alt text-blue-400"></i>
                {{ $toko->alamat ?? '-' }}, {{ $toko->kota ?? '-' }}
            </p>
        </div>
        @endforeach
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                    <th class="border border-blue-900 p-3 text-center w-12">No</th>
                    <th class="border border-blue-900 p-3">Kode</th>
                    <th class="border border-blue-900 p-3">Nama Toko</th>
                    <th class="border border-blue-900 p-3">Alamat</th>
                    <th class="border border-blue-900 p-3 text-center w-24">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($perusahaan->tokos as $key => $toko)
                <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                    <td class="p-3 text-center font-bold text-gray-400">{{ $key + 1 }}</td>
                    <td class="p-3 font-mono text-xs text-blue-700 font-bold tracking-tighter">{{ $toko->kode_toko }}</td>
                    <td class="p-3">
                        <span class="font-black text-gray-800">{{ $toko->nama_toko }}</span>
                        @if($toko->is_pusat)
                            <span class="bg-blue-600 text-white px-2 py-0.5 text-[9px] font-black ml-1 rounded-sm shadow-sm">
                                <i class="fa fa-star"></i> PUSAT
                            </span>
                        @endif
                    </td>
                    <td class="p-3 text-gray-600">{{ $toko->alamat ?? '-' }}, {{ $toko->kota ?? '-' }}</td>
                    <td class="p-3 text-center">
                        @if($toko->is_active)
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase tracking-tighter border border-emerald-200">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[9px] font-black uppercase tracking-tighter border border-rose-200">Tutup</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <i class="fa fa-store text-gray-200 text-5xl block mb-3"></i>
        <p class="text-gray-400 italic text-sm">Belum ada toko terdaftar</p>
    </div>
    @endif
</div>
@else
<div class="bg-white border border-rose-300 p-8 shadow-sm rounded-sm text-center">
    <i class="fa fa-exclamation-triangle text-rose-400 text-5xl mb-3 block"></i>
    <p class="text-rose-600 font-bold">Data perusahaan tidak ditemukan</p>
</div>
@endif
@endsection
