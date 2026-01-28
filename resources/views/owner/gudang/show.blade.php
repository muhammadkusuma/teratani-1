@extends('layouts.owner')

@section('title', 'Detail Gudang')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <div>
        <h2 class="font-bold text-xl border-b-4 border-purple-600 pb-1 pr-6 inline-block uppercase tracking-tight">
            <i class="fa fa-warehouse text-purple-700"></i> Stok Gudang
        </h2>
        <div class="text-xs text-gray-500 mt-1">
            <span class="font-bold text-purple-700">{{ $gudang->nama_gudang }}</span> • Toko: {{ $toko->nama_toko }}
        </div>
    </div>
    <a href="{{ route('owner.toko.gudang.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3 mb-4">
    @forelse($stoks as $index => $stok)
        @php
            $minStok = $stok->produk->stok_minimum ?? 10;
            $isHabis = $stok->stok_fisik <= 0;
            $isMauHabis = $stok->stok_fisik <= $minStok;
            
            if ($isHabis) {
                $cardBorder = 'border-red-500';
                $statusBadge = 'bg-red-200 text-red-800 border-red-500';
                $statusText = 'HABIS';
                $stokColor = 'text-red-700';
            } elseif ($isMauHabis) {
                $cardBorder = 'border-yellow-500';
                $statusBadge = 'bg-yellow-200 text-yellow-800 border-yellow-500';
                $statusText = 'SEGERA HABIS';
                $stokColor = 'text-yellow-700';
            } else {
                $cardBorder = 'border-emerald-500';
                $statusBadge = 'bg-emerald-200 text-emerald-800 border-emerald-500';
                $statusText = 'AMAN';
                $stokColor = 'text-emerald-700';
            }
        @endphp
        <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 {{ $cardBorder }} p-3 shadow-sm rounded-sm">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <h3 class="font-black text-sm text-gray-800">{{ $stok->produk->nama_produk }}</h3>
                    <p class="text-[10px] font-mono text-purple-600">SKU: {{ $stok->produk->sku }}</p>
                </div>
                <span class="px-2 py-1 rounded {{ $statusBadge }} text-[10px] font-bold border whitespace-nowrap">
                    {{ $statusText }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-gradient-to-br from-purple-50 to-white border border-purple-200 p-2 rounded-sm">
                    <span class="text-[10px] text-purple-700 uppercase font-bold block mb-1">
                        <i class="fa fa-boxes"></i> Stok Fisik
                    </span>
                    <div class="font-black text-xl {{ $stokColor }}">{{ number_format($stok->stok_fisik, 0, ',', '.') }}</div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 p-2 rounded-sm">
                    <span class="text-[10px] text-blue-700 uppercase font-bold block mb-1">
                        <i class="fa fa-balance-scale"></i> Satuan
                    </span>
                    <div class="font-bold text-blue-900">{{ $stok->produk->satuanKecil->nama_satuan ?? 'Pcs' }}</div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 bg-white border border-gray-300 rounded-sm">
            <i class="fa fa-box-open text-gray-200 text-5xl block mb-3"></i>
            <p class="text-gray-400 italic text-sm">Tidak ada stok produk di gudang ini</p>
        </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-purple-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-purple-900 p-3 text-center w-12">No</th>
                <th class="border border-purple-900 p-3">Nama Produk</th>
                <th class="border border-purple-900 p-3 text-right w-32">Stok Fisik</th>
                <th class="border border-purple-900 p-3 text-center w-24">Satuan</th>
                <th class="border border-purple-900 p-3 text-center w-32">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stoks as $index => $stok)
                @php
                    $minStok = $stok->produk->stok_minimum ?? 10;
                    $isHabis = $stok->stok_fisik <= 0;
                    $isMauHabis = $stok->stok_fisik <= $minStok;
                    
                    if ($isHabis) {
                        $rowClass = 'bg-red-50 hover:bg-red-100';
                        $statusBadge = 'bg-red-200 text-red-800 border-red-400';
                        $statusText = 'HABIS';
                        $stokColor = 'text-red-700';
                    } elseif ($isMauHabis) {
                        $rowClass = 'bg-yellow-50 hover:bg-yellow-100';
                        $statusBadge = 'bg-yellow-200 text-yellow-800 border-yellow-400';
                        $statusText = 'SEGERA HABIS';
                        $stokColor = 'text-yellow-700';
                    } else {
                        $rowClass = 'hover:bg-purple-50';
                        $statusBadge = 'bg-emerald-200 text-emerald-800 border-emerald-400';
                        $statusText = 'AMAN';
                        $stokColor = 'text-emerald-700';
                    }
                @endphp
                <tr class="{{ $rowClass }} transition-colors text-xs border-b border-gray-200">
                    <td class="p-3 text-center font-bold text-gray-400">{{ $stoks->firstItem() + $index }}</td>
                    <td class="p-3">
                        <div class="font-bold text-gray-800">{{ $stok->produk->nama_produk }}</div>
                        <div class="text-[10px] font-mono text-purple-600">{{ $stok->produk->sku }}</div>
                    </td>
                    <td class="p-3 text-right font-mono font-black text-xl {{ $stokColor }}">
                        {{ number_format($stok->stok_fisik, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-center text-blue-700 font-bold">{{ $stok->produk->satuanKecil->nama_satuan ?? 'Pcs' }}</td>
                    <td class="p-3 text-center">
                        <span class="px-2 py-1 rounded {{ $statusBadge }} text-[10px] font-bold border">
                            {{ $statusText }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-8 text-center border border-gray-300">
                        <i class="fa fa-box-open text-gray-200 text-5xl block mb-3"></i>
                        <p class="text-gray-400 italic text-sm">Tidak ada stok produk di gudang ini</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 md:mt-4 text-xs">
    {{ $stoks->links() }}
</div>
@endsection
