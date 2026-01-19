@extends('layouts.owner')

@section('title', 'Riwayat Pembelian')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-shopping-basket"></i> RIWAYAT PEMBELIAN DISTRIBUTOR
    </h2>
    <a href="{{ route('owner.toko.pembelian.create', $toko->id_toko) }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        <i class="fa fa-plus"></i> INPUT PEMBELIAN
    </a>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Tanggal</th>
                <th class="border border-gray-400 p-2">No. Faktur</th>
                <th class="border border-gray-400 p-2">Distributor</th>
                <th class="border border-gray-400 p-2 text-right">Total</th>
                <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembelians as $index => $pembelian)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $pembelians->firstItem() + $index }}</td>
                <td class="border border-gray-300 p-2">{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d M Y') }}</td>
                <td class="border border-gray-300 p-2 font-mono">{{ $pembelian->no_faktur }}</td>
                <td class="border border-gray-300 p-2">{{ $pembelian->distributor->nama_distributor }}</td>
                <td class="border border-gray-300 p-2 text-right font-bold">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    <a href="{{ route('owner.toko.pembelian.show', [$toko->id_toko, $pembelian->id_pembelian]) }}" class="px-2 py-0.5 bg-blue-600 text-white border border-blue-800 rounded hover:bg-blue-500 text-[10px]">
                        DETAIL
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada data pembelian.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $pembelians->links() }}
</div>
@endsection
