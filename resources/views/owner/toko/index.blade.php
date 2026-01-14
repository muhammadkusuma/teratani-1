@extends('layouts.owner')

@section('title', 'Daftar Toko')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">🏪 DAFTAR TOKO / CABANG</h2>
    <a href="{{ route('owner.toko.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        + TAMBAH TOKO
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

@if(session('warning'))
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-2 py-1 mb-2 text-xs">
        {{ session('warning') }}
    </div>
@endif

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Kode</th>
                <th class="border border-gray-400 p-2">Nama Toko</th>
                <th class="border border-gray-400 p-2">Alamat</th>
                <th class="border border-gray-400 p-2">Telepon</th>
                <th class="border border-gray-400 p-2 text-center w-20">Status</th>
                <th class="border border-gray-400 p-2 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($toko as $key => $row)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $key + 1 }}</td>
                <td class="border border-gray-300 p-2 font-mono">{{ $row->kode_toko }}</td>
                <td class="border border-gray-300 p-2">
                    <span class="font-bold">{{ $row->nama_toko }}</span>
                    @if($row->is_pusat)
                        <span class="bg-blue-600 text-white px-1 py-0.5 text-[9px] font-bold ml-1">PUSAT</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2">{{ $row->alamat ?? '-' }}, {{ $row->kota ?? '-' }}</td>
                <td class="border border-gray-300 p-2">{{ $row->no_telp ?? '-' }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    @if($row->is_active)
                        <span class="px-2 py-0.5 rounded bg-green-200 text-green-800 text-[10px] font-bold">AKTIF</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-red-200 text-red-800 text-[10px] font-bold">TUTUP</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.toko.select', $row->id_toko) }}" class="bg-blue-500 text-white border border-blue-700 px-2 py-0.5 text-[10px] hover:bg-blue-400">PILIH</a>
                        <a href="{{ route('owner.toko.edit', $row->id_toko) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada toko</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection