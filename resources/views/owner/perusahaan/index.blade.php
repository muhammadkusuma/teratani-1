@extends('layouts.owner')

@section('title', 'Detail Perusahaan')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-building"></i> DETAIL PERUSAHAAN
    </h2>
    <a href="{{ route('owner.perusahaan.edit') }}" class="px-3 py-1 bg-yellow-600 text-white border border-yellow-800 shadow hover:bg-yellow-500 text-xs">
        <i class="fa fa-edit"></i> EDIT DATA
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

@if($perusahaan)
<div class="win98-panel mb-3">
    <table class="w-full text-base">
        <tr>
            <td class="font-bold py-2 w-48">Nama Perusahaan</td>
            <td class="py-2">: {{ $perusahaan->nama_perusahaan }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">Alamat</td>
            <td class="py-2">: {{ $perusahaan->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">Kota</td>
            <td class="py-2">: {{ $perusahaan->kota ?? '-' }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">Provinsi</td>
            <td class="py-2">: {{ $perusahaan->provinsi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">Kode Pos</td>
            <td class="py-2">: {{ $perusahaan->kode_pos ?? '-' }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">No. Telepon</td>
            <td class="py-2">: {{ $perusahaan->no_telp ?? '-' }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">Email</td>
            <td class="py-2">: {{ $perusahaan->email ?? '-' }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">Website</td>
            <td class="py-2">: {{ $perusahaan->website ?? '-' }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">NPWP</td>
            <td class="py-2">: {{ $perusahaan->npwp ?? '-' }}</td>
        </tr>
        <tr>
            <td class="font-bold py-2">Pemilik</td>
            <td class="py-2">: {{ $perusahaan->pemilik ?? '-' }}</td>
        </tr>
        @if($perusahaan->logo)
        <tr>
            <td class="font-bold py-2">Logo</td>
            <td class="py-2">
                <img src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan" class="max-w-xs border-2 border-gray-400 mt-2">
            </td>
        </tr>
        @endif
    </table>
</div>

<div class="win98-panel">
    <h3 class="font-bold text-base mb-3 border-b-2 border-gray-400 pb-1">
        <i class="fa fa-store"></i> DAFTAR TOKO / CABANG
    </h3>
    
    @if($perusahaan->tokos->count() > 0)
    <table class="win98-table">
        <thead>
            <tr>
                <th class="text-center w-12">No</th>
                <th>Kode</th>
                <th>Nama Toko</th>
                <th>Alamat</th>
                <th class="text-center w-24">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perusahaan->tokos as $key => $toko)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="font-mono">{{ $toko->kode_toko }}</td>
                <td>
                    <span class="font-bold">{{ $toko->nama_toko }}</span>
                    @if($toko->is_pusat)
                        <span class="bg-blue-600 text-white px-2 py-0.5 text-xs font-bold ml-1">PUSAT</span>
                    @endif
                </td>
                <td>{{ $toko->alamat ?? '-' }}, {{ $toko->kota ?? '-' }}</td>
                <td class="text-center">
                    @if($toko->is_active)
                        <span class="px-2 py-1 bg-green-200 text-green-800 text-xs font-bold">AKTIF</span>
                    @else
                        <span class="px-2 py-1 bg-red-200 text-red-800 text-xs font-bold">TUTUP</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-gray-600 italic text-center py-4">Belum ada toko terdaftar</p>
    @endif
</div>
@else
<div class="win98-panel">
    <p class="text-red-600 font-bold text-center py-4">Data perusahaan tidak ditemukan</p>
</div>
@endif
@endsection
