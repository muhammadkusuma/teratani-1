@extends('layouts.owner')

@section('title', 'Detail Pembelian')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-lg md:text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-receipt text-blue-700"></i> Detail Pembelian
    </h2>
    <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

{{-- Header Information Card --}}
<div class="bg-white border border-gray-300 p-4 md:p-6 mb-4 shadow-sm rounded-sm">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 text-xs">
        <div class="space-y-2">
            <div class="flex items-start gap-2">
                <span class="font-black text-gray-500 w-28 flex-shrink-0 uppercase text-[10px] tracking-wider">
                    <i class="fa fa-calendar text-blue-500"></i> Tanggal
                </span>
                <span class="text-gray-800">{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d F Y') }}</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="font-black text-gray-500 w-28 flex-shrink-0 uppercase text-[10px] tracking-wider">
                    <i class="fa fa-truck text-blue-500"></i> Distributor
                </span>
                <span class="text-gray-800 font-bold">{{ $pembelian->distributor->nama_distributor }}</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="font-black text-gray-500 w-28 flex-shrink-0 uppercase text-[10px] tracking-wider">
                    <i class="fa fa-file-invoice text-blue-500"></i> No Faktur
                </span>
                <span class="font-mono bg-amber-100 border border-amber-300 px-2 py-0.5 rounded-sm text-amber-900 font-bold">{{ $pembelian->no_faktur }}</span>
            </div>
        </div>
        <div class="space-y-2">
            <div class="flex items-start gap-2">
                <span class="font-black text-gray-500 w-28 flex-shrink-0 uppercase text-[10px] tracking-wider">
                    <i class="fa fa-user text-blue-500"></i> Oleh User
                </span>
                <span class="text-gray-800">{{ $pembelian->user->username ?? '-' }}</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="font-black text-gray-500 w-28 flex-shrink-0 uppercase text-[10px] tracking-wider">
                    <i class="fa fa-sticky-note text-blue-500"></i> Keterangan
                </span>
                <span class="text-gray-800">{{ $pembelian->keterangan ?? '-' }}</span>
            </div>
        </div>
    </div>
    
    {{-- Total Prominent Display --}}
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 border border-amber-800 p-4 rounded-sm mt-4 shadow-md">
        <div class="flex justify-between items-center text-white">
            <span class="font-black uppercase text-xs"><i class="fa fa-money-bill-wave"></i> Total Pembelian</span>
            <span class="font-black text-2xl md:text-3xl">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3 mb-4">
    @foreach ($pembelian->details as $index => $detail)
    <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 border-blue-500 p-3 shadow-sm rounded-sm">
        <div class="flex justify-between items-start mb-2">
            <div class="flex-1">
                <h3 class="font-black text-sm text-gray-800">{{ $detail->produk->nama_produk }}</h3>
                <p class="text-[10px] font-mono text-blue-600">SKU: {{ $detail->produk->sku }}</p>
            </div>
            <span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-1 rounded-sm font-bold border border-blue-300">
                #{{ $index + 1 }}
            </span>
        </div>
        
        <div class="grid grid-cols-2 gap-2 mb-2">
            <div class="bg-blue-50 border border-blue-200 p-2 rounded-sm">
                <span class="text-[10px] text-blue-700 uppercase font-bold block mb-1">Jumlah</span>
                <div class="font-black text-blue-900">{{ $detail->jumlah }}</div>
            </div>
            <div class="bg-purple-50 border border-purple-200 p-2 rounded-sm">
                <span class="text-[10px] text-purple-700 uppercase font-bold block mb-1">Harga Satuan</span>
                <div class="font-mono font-bold text-purple-900 text-xs">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-amber-100 to-amber-50 border border-amber-300 p-2 rounded-sm">
            <div class="flex justify-between items-center">
                <span class="font-black uppercase text-[10px] text-amber-800">Subtotal</span>
                <span class="font-black text-amber-700">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-blue-900 p-3 text-center w-12">No</th>
                <th class="border border-blue-900 p-3">Produk</th>
                <th class="border border-blue-900 p-3 text-center w-20">Qty</th>
                <th class="border border-blue-900 p-3 text-right w-32">Harga Satuan</th>
                <th class="border border-blue-900 p-3 text-right w-32">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian->details as $index => $detail)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                <td class="p-3">
                    <div class="font-bold text-gray-800">{{ $detail->produk->nama_produk }}</div>
                    <div class="text-[10px] font-mono text-blue-600">{{ $detail->produk->sku }}</div>
                </td>
                <td class="p-3 text-center font-black text-blue-700">{{ $detail->jumlah }}</td>
                <td class="p-3 text-right font-mono text-purple-700 font-bold">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="p-3 text-right font-mono font-black text-amber-700">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gradient-to-r from-amber-100 to-amber-50 font-bold text-sm border-t-2 border-amber-600">
                <td colspan="4" class="p-3 text-right font-black uppercase text-amber-900">Total Pembelian</td>
                <td class="p-3 text-right font-black text-amber-700 text-lg">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
