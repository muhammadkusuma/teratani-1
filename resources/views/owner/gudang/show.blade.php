@extends('layouts.owner')

@section('title', 'Stok Gudang')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-boxes"></i> STOK GUDANG: {{ strtoupper($gudang->nama_gudang) }}
    </h2>
    <a href="{{ route('owner.gudang.index') }}" class="px-3 py-1 bg-gray-200 text-gray-700 border border-gray-400 shadow hover:bg-gray-300 text-xs">
        <i class="fa fa-arrow-left"></i> KEMBALI
    </a>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Nama Produk</th>
                <th class="border border-gray-400 p-2 text-right">Stok Fisik</th>
                <th class="border border-gray-400 p-2 text-center">Satuan</th>
                <th class="border border-gray-400 p-2 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stoks as $index => $stok)
                @php
                    $minStok = $stok->produk->stok_minimum ?? 10; // Default 10 if null
                    $isHabis = $stok->stok_fisik <= 0;
                    $isMauHabis = $stok->stok_fisik <= $minStok;
                    
                    if ($isHabis) {
                        $rowClass = 'bg-red-50 hover:bg-red-100';
                        $statusParams = ['bg-red-200', 'text-red-800', 'HABIS'];
                    } elseif ($isMauHabis) {
                        $rowClass = 'bg-yellow-50 hover:bg-yellow-100';
                        $statusParams = ['bg-yellow-200', 'text-yellow-800', 'SEGERA HABIS'];
                    } else {
                        $rowClass = 'hover:bg-gray-50';
                        $statusParams = ['bg-green-200', 'text-green-800', 'AMAN'];
                    }
                @endphp
            <tr class="{{ $rowClass }} text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $stoks->firstItem() + $index }}</td>
                <td class="border border-gray-300 p-2">
                    <div class="font-bold">{{ $stok->produk->nama_produk }}</div>
                    <div class="text-[10px] text-gray-500 font-mono">{{ $stok->produk->sku }}</div>
                </td>
                <td class="border border-gray-300 p-2 text-right font-mono font-bold">{{ number_format($stok->stok_fisik, 0, ',', '.') }}</td>
                <td class="border border-gray-300 p-2 text-center">{{ $stok->produk->satuanKecil->nama_satuan ?? 'Pcs' }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    <span class="px-2 py-0.5 rounded {{ $statusParams[0] }} {{ $statusParams[1] }} text-[10px] font-bold">
                        {{ $statusParams[2] }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500 italic border border-gray-300">Tidak ada stok produk di gudang ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $stoks->links() }}
</div>
@endsection
