@extends('layouts.owner')

@section('title', 'Data Pelanggan')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">DAFTAR PELANGGAN (CRM)</h2>
    <a href="{{ route('owner.pelanggan.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        + TAMBAH PELANGGAN
    </a>
</div>

{{-- Search Bar Sederhana --}}
<form method="GET" action="{{ route('owner.pelanggan.index') }}" class="mb-3 flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / No HP..." class="border border-gray-400 p-1 text-xs w-64 shadow-inner">
    <button type="submit" class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300">CARI</button>
</form>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Kode</th>
                <th class="border border-gray-400 p-2">Nama Pelanggan</th>
                <th class="border border-gray-400 p-2">No HP</th>
                <th class="border border-gray-400 p-2">Wilayah</th>
                <th class="border border-gray-400 p-2 text-right">Limit Piutang</th>
                <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggan as $key => $row)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $pelanggan->firstItem() + $key }}</td>
                <td class="border border-gray-300 p-2 font-mono">{{ $row->kode_pelanggan }}</td>
                <td class="border border-gray-300 p-2 font-bold">{{ $row->nama_pelanggan }}</td>
                <td class="border border-gray-300 p-2">{{ $row->no_hp ?? '-' }}</td>
                <td class="border border-gray-300 p-2">{{ $row->wilayah ?? '-' }}</td>
                <td class="border border-gray-300 p-2 text-right font-mono">
                    {{ number_format($row->limit_piutang, 0, ',', '.') }}
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.pelanggan.edit', $row->id_pelanggan) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                        <form action="{{ route('owner.pelanggan.destroy', $row->id_pelanggan) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white border border-red-800 px-2 py-0.5 text-[10px] hover:bg-red-500">HAPUS</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada data pelanggan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $pelanggan->links() }}
</div>
@endsection