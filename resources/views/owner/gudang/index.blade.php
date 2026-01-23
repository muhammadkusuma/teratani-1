@extends('layouts.owner')

@section('title', 'Manajemen Gudang')

@section('content')
<div class="flex justify-between items-center mb-3">
    <div>
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block">MANAJEMEN GUDANG</h2>
        <span class="text-xs text-gray-500 ml-2">Toko: {{ $toko->nama_toko }}</span>
    </div>
    <a href="{{ route('owner.toko.gudang.create', $toko->id_toko) }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        + TAMBAH GUDANG
    </a>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Nama Gudang</th>
                <th class="border border-gray-400 p-2">Lokasi</th>
                <th class="border border-gray-400 p-2 text-center">Total Jenis Produk</th>
                <th class="border border-gray-400 p-2 text-center w-32">Aksi</th>
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
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.toko.gudang.show', [$toko->id_toko, $gudang->id_gudang]) }}" class="bg-blue-600 text-white border border-blue-800 px-2 py-0.5 text-[10px] hover:bg-blue-500">
                            LIHAT STOK
                        </a>
                        <a href="{{ route('owner.toko.gudang.edit', [$toko->id_toko, $gudang->id_gudang]) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">
                            EDIT
                        </a>
                        <form action="{{ route('owner.toko.gudang.destroy', [$toko->id_toko, $gudang->id_gudang]) }}" method="POST" onsubmit="return confirm('Hapus gudang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white border border-red-800 px-2 py-0.5 text-[10px] hover:bg-red-500">HAPUS</button>
                        </form>
                    </div>
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
