@extends('layouts.owner')

@section('title', 'Manajemen Gudang')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-warehouse"></i> MANAJEMEN GUDANG
    </h2>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Nama Gudang</th>
                <th class="border border-gray-400 p-2">Lokasi</th>
                <th class="border border-gray-400 p-2 text-center">Total Jenis Produk</th>
                <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gudangs as $index => $gudang)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $index + 1 }}</td>
                <td class="border border-gray-300 p-2 font-bold">{{ $gudang->nama_gudang }}</td>
                <td class="border border-gray-300 p-2">{{ $gudang->lokasi ?? '-' }}</td>
                <td class="border border-gray-300 p-2 text-center font-mono font-bold">{{ $gudang->stok_gudangs_count }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    <a href="{{ route('owner.gudang.show', $gudang->id_gudang) }}" class="px-2 py-0.5 bg-blue-600 text-white border border-blue-800 rounded hover:bg-blue-500 text-[10px]">
                        LIHAT STOK
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada data gudang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
