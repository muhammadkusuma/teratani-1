@extends('layouts.owner')

@section('title', 'Kelola Stok')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-warehouse text-blue-700"></i> Kelola Stok Produk
    </h2>
    <a href="{{ route('owner.stok.tambah') }}" class="w-full md:w-auto text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-plus"></i> Tambah Stok
    </a>
</div>

@if (session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3 mb-4">
    @forelse ($produk as $index => $item)
        @php
            $stokTokoData = $item->stokTokos->first();
            $stokToko = $stokTokoData ? $stokTokoData->stok_fisik : 0;
            $stokMinimal = $stokTokoData ? $stokTokoData->stok_minimal : 5;
            
            $totalStokGudang = 0;
            $gudangStockMap = [];
            
            foreach($item->stokGudangs as $stokGudang) {
                $gudangStockMap[$stokGudang->id_gudang] = $stokGudang->stok_fisik;
                $totalStokGudang += $stokGudang->stok_fisik;
            }
            
            $totalStok = $stokToko + $totalStokGudang;
            
            if ($stokToko <= 0) {
                $statusClass = 'bg-red-200 text-red-800 border-red-500';
                $statusText = 'HABIS';
                $cardBorder = 'border-red-500';
            } elseif ($stokToko <= $stokMinimal) {
                $statusClass = 'bg-yellow-200 text-yellow-800 border-yellow-500';
                $statusText = 'RENDAH';
                $cardBorder = 'border-yellow-500';
            } else {
                $statusClass = 'bg-emerald-200 text-emerald-800 border-emerald-500';
                $statusText = 'AMAN';
                $cardBorder = 'border-blue-500';
            }
        @endphp
        <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 {{ $cardBorder }} p-3 shadow-sm rounded-sm">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <h3 class="font-black text-sm text-gray-800">{{ $item->nama_produk }}</h3>
                    <p class="text-[10px] font-mono text-gray-500">SKU: {{ $item->sku }}</p>
                    <p class="text-xs text-blue-600"><i class="fa fa-tag"></i> {{ $item->kategori->nama_kategori ?? '-' }}</p>
                </div>
                <span class="px-2 py-1 rounded {{ $statusClass }} text-[10px] font-bold whitespace-nowrap">{{ $statusText }}</span>
            </div>
            
            {{-- Stok Breakdown --}}
            <div class="space-y-2 mb-2 pt-2 border-t border-gray-200">
                <div class="flex justify-between items-center bg-blue-50 border border-blue-200 p-2 rounded-sm">
                    <span class="text-[10px] text-blue-700 uppercase font-bold">
                        <i class="fa fa-store"></i> Stok Toko
                    </span>
                    <div class="text-right">
                        <div class="font-mono font-black text-blue-800">{{ number_format($stokToko) }}</div>
                        <div class="text-[9px] text-gray-500">Min: {{ $stokMinimal }}</div>
                    </div>
                </div>
                
                @foreach($gudangs as $gudang)
                    @php
                        $stokGudang = $gudangStockMap[$gudang->id_gudang] ?? 0;
                    @endphp
                    @if($stokGudang > 0)
                        <div class="flex justify-between items-center bg-purple-50 border border-purple-200 p-2 rounded-sm">
                            <span class="text-[10px] text-purple-700 uppercase font-bold">
                                <i class="fa fa-warehouse"></i> {{ $gudang->nama_gudang }}
                            </span>
                            <div class="font-mono font-bold text-purple-800">{{ number_format($stokGudang) }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            {{-- Total --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 border border-emerald-800 p-2 rounded-sm">
                <div class="flex justify-between items-center text-white">
                    <span class="font-black uppercase text-[10px]"><i class="fa fa-boxes"></i> Total Stok</span>
                    <span class="font-black text-lg">{{ number_format($totalStok) }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 bg-white border border-gray-300 rounded-sm">
            <i class="fa fa-warehouse text-gray-200 text-5xl block mb-3"></i>
            <p class="text-gray-400 italic text-sm">Belum ada produk</p>
        </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-blue-900 p-3 text-center w-12">No</th>
                <th class="border border-blue-900 p-3">SKU</th>
                <th class="border border-blue-900 p-3">Nama Produk</th>
                <th class="border border-blue-900 p-3">Kategori</th>
                <th class="border border-blue-900 p-3 text-center">Stok Toko</th>
                @foreach($gudangs as $gudang)
                    <th class="border border-blue-900 p-3 text-center">{{ $gudang->nama_gudang }}</th>
                @endforeach
                <th class="border border-blue-900 p-3 text-center bg-blue-800">TOTAL</th>
                <th class="border border-blue-900 p-3 text-center w-24">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produk as $index => $item)
                @php
                    $stokTokoData = $item->stokTokos->first();
                    $stokToko = $stokTokoData ? $stokTokoData->stok_fisik : 0;
                    $stokMinimal = $stokTokoData ? $stokTokoData->stok_minimal : 5;
                    
                    $totalStokGudang = 0;
                    $gudangStockMap = [];
                    
                    foreach($item->stokGudangs as $stokGudang) {
                        $gudangStockMap[$stokGudang->id_gudang] = $stokGudang->stok_fisik;
                        $totalStokGudang += $stokGudang->stok_fisik;
                    }
                    
                    $totalStok = $stokToko + $totalStokGudang;
                    
                    if ($stokToko <= 0) {
                        $statusClass = 'bg-red-200 text-red-800';
                        $statusText = 'HABIS';
                        $rowClass = 'bg-red-50';
                    } elseif ($stokToko <= $stokMinimal) {
                        $statusClass = 'bg-yellow-200 text-yellow-800';
                        $statusText = 'RENDAH';
                        $rowClass = 'bg-yellow-50';
                    } else {
                        $statusClass = 'bg-emerald-200 text-emerald-800';
                        $statusText = 'AMAN';
                        $rowClass = 'hover:bg-blue-50';
                    }
                @endphp
                <tr class="{{ $rowClass }} transition-colors text-xs border-b border-gray-200">
                    <td class="p-3 text-center font-bold text-gray-400">{{ $produk->firstItem() + $index }}</td>
                    <td class="p-3 font-mono text-blue-700 font-bold">{{ $item->sku }}</td>
                    <td class="p-3">
                        <div class="font-bold text-gray-800">{{ $item->nama_produk }}</div>
                        <div class="text-[10px] text-gray-500">{{ $item->satuanKecil->nama_satuan ?? '-' }}</div>
                    </td>
                    <td class="p-3 text-gray-700">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    
                    {{-- Stok Toko --}}
                    <td class="p-3 text-center font-mono font-bold text-blue-700">
                        {{ number_format($stokToko) }}
                        <div class="text-[9px] text-gray-500">Min: {{ $stokMinimal }}</div>
                    </td>
                    
                    {{-- Stok per Gudang --}}
                    @foreach($gudangs as $gudang)
                        <td class="p-3 text-center font-mono text-purple-700 font-bold">
                            {{ number_format($gudangStockMap[$gudang->id_gudang] ?? 0) }}
                        </td>
                    @endforeach
                    
                    {{-- Total Stok --}}
                    <td class="p-3 text-center font-mono font-black bg-emerald-50 text-emerald-700">
                        {{ number_format($totalStok) }}
                    </td>
                    
                    {{-- Status --}}
                    <td class="p-3 text-center">
                        <span class="px-2 py-1 rounded {{ $statusClass }} text-[10px] font-bold border">{{ $statusText }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 5 + count($gudangs) + 2 }}" class="p-8 text-center border border-gray-300">
                        <i class="fa fa-warehouse text-gray-200 text-5xl block mb-3"></i>
                        <p class="text-gray-400 italic text-sm">Belum ada produk</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 md:mt-4 text-xs">
    {{ $produk->links() }}
</div>

@endsection
