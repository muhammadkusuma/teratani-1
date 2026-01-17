@extends('layouts.owner')

@section('title', 'Daftar Distributor')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-truck"></i> DAFTAR DISTRIBUTOR / SUPPLIER
    </h2>
    <div class="flex gap-2">
        <a href="{{ route('owner.distributor.hutang.index') }}" class="px-3 py-1 bg-green-700 text-white border border-green-900 shadow hover:bg-green-600 text-xs">
            <i class="fa fa-money-bill-wave"></i> KELOLA HUTANG
        </a>
        <a href="{{ route('owner.distributor.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
            <i class="fa fa-plus"></i> TAMBAH DISTRIBUTOR
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs">
        {{ session('error') }}
    </div>
@endif


<div class="bg-white border border-gray-400 p-3 mb-3">
    <form method="GET" action="{{ route('owner.distributor.index') }}" class="flex items-end gap-3">
        <div class="flex-1">
            <label class="block text-xs font-bold mb-1">Filter Berdasarkan Toko</label>
            <select name="id_toko" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">-- Semua Toko --</option>
                @foreach($userStores as $store)
                    <option value="{{ $store->id_toko }}" {{ request('id_toko') == $store->id_toko ? 'selected' : '' }}>
                        {{ $store->nama_toko }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white border border-blue-800 px-4 py-1 text-xs hover:bg-blue-500">
            <i class="fa fa-filter"></i> FILTER
        </button>
        @if(request('id_toko'))
        <a href="{{ route('owner.distributor.index') }}" class="bg-gray-400 text-white border border-gray-600 px-4 py-1 text-xs hover:bg-gray-300">
            <i class="fa fa-times"></i> RESET
        </a>
        @endif
    </form>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Kode</th>
                <th class="border border-gray-400 p-2">Nama Distributor</th>
                <th class="border border-gray-400 p-2">Perusahaan</th>
                <th class="border border-gray-400 p-2">Toko</th>
                <th class="border border-gray-400 p-2">Kontak</th>
                <th class="border border-gray-400 p-2 text-center w-20">Status</th>
                <th class="border border-gray-400 p-2 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distributors as $key => $row)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $distributors->firstItem() + $key }}</td>
                <td class="border border-gray-300 p-2 font-mono">{{ $row->kode_distributor }}</td>
                <td class="border border-gray-300 p-2">
                    <span class="font-bold">{{ $row->nama_distributor }}</span>
                    @if($row->alamat)
                        <div class="text-[10px] text-gray-600 mt-1">
                            <i class="fa fa-map-marker-alt"></i> {{ $row->kota ?? $row->alamat }}
                        </div>
                    @endif
                </td>
                <td class="border border-gray-300 p-2">{{ $row->nama_perusahaan ?? '-' }}</td>
                <td class="border border-gray-300 p-2">
                    <span class="text-blue-700 font-semibold">{{ $row->toko->nama_toko }}</span>
                </td>
                <td class="border border-gray-300 p-2">
                    @if($row->nama_kontak)
                        <div class="font-semibold">{{ $row->nama_kontak }}</div>
                    @endif
                    @if($row->no_hp_kontak)
                        <div class="text-[10px]"><i class="fa fa-phone"></i> {{ $row->no_hp_kontak }}</div>
                    @endif
                    @if($row->email)
                        <div class="text-[10px]"><i class="fa fa-envelope"></i> {{ $row->email }}</div>
                    @endif
                    @if(!$row->nama_kontak && !$row->no_hp_kontak && !$row->email)
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    @if($row->is_active)
                        <span class="px-2 py-0.5 rounded bg-green-200 text-green-800 text-[10px] font-bold">AKTIF</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-red-200 text-red-800 text-[10px] font-bold">NONAKTIF</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.distributor.show', $row->id_distributor) }}" class="bg-blue-500 text-white border border-blue-700 px-2 py-0.5 text-[10px] hover:bg-blue-400">LIHAT</a>
                        <a href="{{ route('owner.distributor.edit', $row->id_distributor) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                        <form action="{{ route('owner.distributor.destroy', $row->id_distributor) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus distributor ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white border border-red-700 px-2 py-0.5 text-[10px] hover:bg-red-400">HAPUS</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada distributor</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($distributors->hasPages())
<div class="mt-3">
    {{ $distributors->links() }}
</div>
@endif
@endsection
