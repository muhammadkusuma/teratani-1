@extends('layouts.owner')

@section('title', 'Retur Penjualan')

@section('content')
<div class="flex justify-between items-center mb-3">
    <div>
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block">RETUR PENJUALAN</h2>
        <span class="text-xs text-gray-500 ml-2">Daftar Retur dari Pelanggan</span>
    </div>
    <a href="{{ route('owner.retur-penjualan.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        + BUAT RETUR BARU
    </a>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Tanggal</th>
                <th class="border border-gray-400 p-2">Pelanggan</th>
                <th class="border border-gray-400 p-2 text-right">Total Retur</th>
                <th class="border border-gray-400 p-2 text-center">Status</th>
                <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returs as $index => $retur)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $returs->firstItem() + $index }}</td>
                <td class="border border-gray-300 p-2">{{ $retur->tgl_retur->format('d M Y') }}</td>
                <td class="border border-gray-300 p-2">{{ $retur->pelanggan->nama_pelanggan }}</td>
                <td class="border border-gray-300 p-2 text-right font-mono font-bold">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    <span class="px-2 py-0.5 rounded bg-green-200 text-green-800 text-[10px] font-bold">
                        {{ $retur->status_retur }}
                    </span>
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    <a href="{{ route('owner.retur-penjualan.show', $retur->id_retur_penjualan) }}" class="bg-blue-600 text-white border border-blue-800 px-2 py-0.5 text-[10px] hover:bg-blue-500">
                        DETAIL
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada data retur penjualan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $returs->links() }}
</div>
@endsection
