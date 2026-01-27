@extends('layouts.owner')

@section('title', 'Detail Retur Pembelian')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-file-invoice"></i> DETAIL RETUR PEMBELIAN</h2>
    <div class="flex gap-2">
        <a href="{{ route('owner.retur-pembelian.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs"><i class="fa fa-arrow-left"></i> KEMBALI</a>
    </div>
</div>

<div class="bg-white border border-gray-400 p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3"><i class="fa fa-info-circle"></i> INFORMASI RETUR</h3>
            <div class="space-y-2 text-xs">
                <div class="flex"><span class="w-32 text-gray-600">No Retur:</span><span class="font-mono font-bold">#{{ $retur->id_retur_pembelian }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Tanggal:</span><span class="font-semibold">{{ $retur->tgl_retur->format('d/m/Y') }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Distributor:</span><span class="font-bold">{{ $retur->distributor->nama_distributor }}</span></div>
               <div class="flex"><span class="w-32 text-gray-600">Gudang Asal:</span><span class="font-bold">{{ $retur->gudang->nama_gudang ?? '-' }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Total Retur:</span><span class="font-bold text-red-700 text-lg">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</span></div>
            </div>
        </div>
        <div>
            <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3"><i class="fa fa-sticky-note"></i> KETERANGAN</h3>
             <div class="text-xs space-y-3">
                @if($retur->keterangan)
                <div>
                   <div class="bg-gray-50 border border-gray-300 p-2 rounded">{{ $retur->keterangan }}</div>
                </div>
                 @else
                <div class="text-gray-500 italic">Tidak ada keterangan tambahan.</div>
                @endif
             </div>
        </div>
    </div>

    <div class="mt-4 border-t border-gray-300 pt-4">
        <h3 class="font-bold text-sm mb-3"><i class="fa fa-box"></i> ITEM RETUR</h3>
        <div class="block md:hidden space-y-3 mb-3">
            @foreach($retur->details as $detail)
            <div class="bg-gray-50 border border-gray-300 p-3 rounded">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <div class="font-bold text-sm">{{ $detail->produk->nama_produk }}</div>
                        <div class="text-[10px] text-gray-500">{{ $detail->produk->kode_produk ?? $detail->produk->sku }}</div>
                    </div>
                </div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-600">Qty:</span>
                    <span class="font-bold">{{ $detail->qty }}</span>
                </div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-600">Harga:</span>
                    <span>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs border-t border-gray-200 pt-1 mt-1">
                    <span class="font-bold text-gray-700">Subtotal:</span>
                    <span class="font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
            <div class="bg-gray-100 p-3 rounded flex justify-between items-center border border-gray-300">
                <span class="font-bold text-xs uppercase">Total Retur</span>
                <span class="font-bold text-red-700 text-sm">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="hidden md:block overflow-x-auto border border-gray-300">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-xs uppercase">
                        <th class="p-2 border border-gray-300">Produk</th>
                        <th class="p-2 border border-gray-300 text-right w-24">Qty</th>
                        <th class="p-2 border border-gray-300 text-right w-32">Harga Satuan</th>
                        <th class="p-2 border border-gray-300 text-right w-32">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($retur->details as $detail)
                    <tr class="text-xs">
                        <td class="p-2 border border-gray-300">
                            <div class="font-bold">{{ $detail->produk->nama_produk }}</div>
                            <div class="text-[10px] text-gray-500">{{ $detail->produk->kode_produk ?? $detail->produk->sku }}</div>
                        </td>
                        <td class="p-2 border border-gray-300 text-right font-bold">{{ $detail->qty }}</td>
                        <td class="p-2 border border-gray-300 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="p-2 border border-gray-300 text-right border-l-2 border-l-gray-400 font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-bold text-xs">
                    <tr>
                        <td colspan="3" class="p-2 border border-gray-300 text-right uppercase">Total Retur</td>
                        <td class="p-2 border border-gray-300 text-right text-red-700 text-sm">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
