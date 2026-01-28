@extends('layouts.owner')

@section('title', 'Detail Retur Penjualan')

@section('content')
<div class="mb-3 md:mb-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <h2 class="font-bold text-lg md:text-xl border-b-4 border-red-600 pb-1 pr-6 uppercase tracking-tight">
            <i class="fa fa-undo text-red-700"></i> Detail Retur #{{ $retur->id_retur_penjualan }}
        </h2>
        <a href="{{ route('owner.retur-penjualan.index') }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="bg-white border border-gray-300 p-4 md:p-6 shadow-sm rounded-sm mb-4">
    {{-- Info Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-4">
        <div class="bg-blue-50 border border-blue-200 p-3 rounded-sm">
            <p class="text-blue-700 text-[10px] font-black uppercase tracking-wider mb-1">Pelanggan</p>
            <p class="font-bold text-sm text-gray-800">{{ $retur->pelanggan->nama_pelanggan }}</p>
        </div>
        <div class="bg-gray-50 border border-gray-200 p-3 rounded-sm">
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-wider mb-1">Tanggal Retur</p>
            <p class="font-bold text-sm text-gray-800">
                <i class="fa fa-calendar text-gray-400"></i> {{ $retur->tgl_retur->format('d/m/Y') }}
            </p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-white border border-red-200 p-3 rounded-sm">
            <p class="text-red-700 text-[10px] font-black uppercase tracking-wider mb-1">Total Nilai Retur</p>
            <p class="font-black text-base md:text-lg text-red-600">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</p>
        </div>
        <div class="bg-amber-50 border border-amber-200 p-3 rounded-sm">
            <p class="text-amber-700 text-[10px] font-black uppercase tracking-wider mb-1">Keterangan</p>
            <p class="text-sm text-gray-700">{{ $retur->keterangan ?? '-' }}</p>
        </div>
    </div>

    {{-- Section Header --}}
    <h3 class="font-black text-sm border-b-2 border-gray-600 pb-2 mb-3 text-gray-900 uppercase tracking-wider">
        <i class="fa fa-list"></i> Item Retur
    </h3>

    {{-- Mobile Card View --}}
    <div class="block md:hidden space-y-3 mb-4">
        @foreach($retur->details as $detail)
        <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 border-red-500 p-3 shadow-sm rounded-sm">
            <div class="mb-2">
                <h4 class="font-black text-sm text-gray-800">{{ $detail->produk->nama_produk }}</h4>
                <p class="text-[10px] font-mono text-gray-500">{{ $detail->produk->sku }}</p>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div>
                    <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block">Qty</span>
                    <span class="font-bold text-gray-800">{{ $detail->qty }}</span>
                </div>
                <div>
                    <span class="text-gray-500 font-bold uppercase text-[10px] tracking-wider block">Harga</span>
                    <span class="font-bold text-gray-800">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
                </div>
                <div class="col-span-2 bg-red-50 border border-red-200 p-2 rounded-sm mt-1">
                    <span class="text-red-700 font-bold uppercase text-[10px] tracking-wider block">Subtotal</span>
                    <span class="font-black text-red-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
        
        {{-- Total Card --}}
        <div class="bg-gradient-to-br from-red-600 to-red-700 border border-red-800 p-3 rounded-sm shadow-md">
            <div class="flex justify-between items-center text-white">
                <span class="font-black uppercase tracking-wider text-xs">Total Retur</span>
                <span class="font-black text-lg">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-[10px] font-black uppercase tracking-widest">
                    <th class="border border-gray-800 p-3">Produk</th>
                    <th class="border border-gray-800 p-3 text-right w-24">Qty</th>
                    <th class="border border-gray-800 p-3 text-right">Harga Retur</th>
                    <th class="border border-gray-800 p-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($retur->details as $detail)
                <tr class="hover:bg-gray-50 transition-colors text-xs border-b border-gray-200">
                    <td class="p-3">
                        <div class="font-bold text-gray-800">{{ $detail->produk->nama_produk }}</div>
                        <div class="text-[10px] font-mono text-gray-500">{{ $detail->produk->sku }}</div>
                    </td>
                    <td class="p-3 text-right font-bold text-gray-700">{{ $detail->qty }}</td>
                    <td class="p-3 text-right text-gray-700">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="p-3 text-right font-bold text-red-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-red-50 font-bold">
                <tr>
                    <td colspan="3" class="p-3 border-t-2 border-red-500 text-right uppercase text-xs tracking-wider text-red-900">Total</td>
                    <td class="p-3 border-t-2 border-red-500 text-right font-black text-red-600 text-sm">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col md:flex-row justify-end gap-2 mt-4 pt-4 border-t border-gray-200">
        <a href="{{ route('owner.retur-penjualan.index') }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <form action="{{ route('owner.retur-penjualan.destroy', $retur->id_retur_penjualan) }}" method="POST" class="w-full md:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menghapus retur ini? Stok produk akan dikurangi kembali.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full px-6 py-2.5 md:py-2 bg-red-600 text-white border border-red-800 text-xs font-bold hover:bg-red-700 transition-colors shadow-sm rounded-sm uppercase">
                <i class="fa fa-trash"></i> Hapus Retur
            </button>
        </form>
    </div>
</div>
@endsection
