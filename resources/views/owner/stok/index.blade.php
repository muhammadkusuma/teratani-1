@extends('layouts.owner')

@section('title', 'Kelola Stok')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-warehouse"></i> KELOLA STOK PRODUK
    </h2>
    <a href="{{ route('owner.stok.tambah') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs text-decoration-none">
        <i class="fa fa-plus"></i> TAMBAH STOK
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">SKU</th>
                <th class="border border-gray-400 p-2">Nama Produk</th>
                <th class="border border-gray-400 p-2">Kategori</th>
                <th class="border border-gray-400 p-2 text-center">Stok Toko</th>
                @foreach($gudangs as $gudang)
                    <th class="border border-gray-400 p-2 text-center">{{ $gudang->nama_gudang }}</th>
                @endforeach
                <th class="border border-gray-400 p-2 text-center bg-blue-100">TOTAL</th>
                <th class="border border-gray-400 p-2 text-center w-24">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produk as $index => $item)
                @php
                    // Stok Toko
                    $stokTokoData = $item->stokTokos->first();
                    $stokToko = $stokTokoData ? $stokTokoData->stok_fisik : 0;
                    $stokMinimal = $stokTokoData ? $stokTokoData->stok_minimal : 5;
                    
                    // Total stok dari semua gudang
                    $totalStokGudang = 0;
                    $gudangStockMap = [];
                    
                    foreach($item->stokGudangs as $stokGudang) {
                        $gudangStockMap[$stokGudang->id_gudang] = $stokGudang->stok_fisik;
                        $totalStokGudang += $stokGudang->stok_fisik;
                    }
                    
                    // Total keseluruhan
                    $totalStok = $stokToko + $totalStokGudang;
                    
                    // Status berdasarkan stok toko
                    if ($stokToko <= 0) {
                        $status = 'habis';
                        $statusClass = 'bg-red-200 text-red-800';
                        $statusText = 'HABIS';
                    } elseif ($stokToko <= $stokMinimal) {
                        $status = 'rendah';
                        $statusClass = 'bg-yellow-200 text-yellow-800';
                        $statusText = 'RENDAH';
                    } else {
                        $status = 'aman';
                        $statusClass = 'bg-green-200 text-green-800';
                        $statusText = 'AMAN';
                    }
                @endphp
                <tr class="hover:bg-yellow-50 text-xs">
                    <td class="border border-gray-300 p-2 text-center">{{ $produk->firstItem() + $index }}</td>
                    <td class="border border-gray-300 p-2 font-mono">{{ $item->sku }}</td>
                    <td class="border border-gray-300 p-2">
                        <div class="font-bold">{{ $item->nama_produk }}</div>
                        <div class="text-[10px] text-gray-500">{{ $item->satuanKecil->nama_satuan ?? '-' }}</div>
                    </td>
                    <td class="border border-gray-300 p-2">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    
                    <!-- Stok Toko -->
                    <td class="border border-gray-300 p-2 text-center font-mono font-bold">
                        {{ number_format($stokToko) }}
                        <div class="text-[9px] text-gray-500">Min: {{ $stokMinimal }}</div>
                    </td>
                    
                    <!-- Stok per Gudang -->
                    @foreach($gudangs as $gudang)
                        <td class="border border-gray-300 p-2 text-center font-mono">
                            {{ number_format($gudangStockMap[$gudang->id_gudang] ?? 0) }}
                        </td>
                    @endforeach
                    
                    <!-- Total Stok -->
                    <td class="border border-gray-300 p-2 text-center font-mono font-bold bg-blue-50">
                        {{ number_format($totalStok) }}
                    </td>
                    
                    <!-- Status -->
                    <td class="border border-gray-300 p-2 text-center">
                        <span class="px-2 py-0.5 rounded {{ $statusClass }} text-[10px] font-bold">{{ $statusText }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 5 + count($gudangs) + 2 }}" class="p-4 text-center text-gray-500 italic border border-gray-300">
                        Belum ada produk
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $produk->links() }}
</div>

@endsection
