@extends('layouts.owner')

@section('title', 'Detail Pembelian')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-receipt"></i> DEATIL PEMBELIAN: {{ $pembelian->no_faktur }}
    </h2>
    <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="px-3 py-1 bg-gray-200 text-gray-700 border border-gray-400 shadow hover:bg-gray-300 text-xs">
        <i class="fa fa-arrow-left"></i> KEMBALI
    </a>
</div>

<div class="bg-white border border-gray-400 p-4 mb-4 text-xs">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <table class="w-full">
                <tr>
                    <td class="font-bold w-32 py-1">Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1">Distributor</td>
                    <td>: {{ $pembelian->distributor->nama_distributor }}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1">No Faktur</td>
                    <td>: <span class="font-mono bg-yellow-100 px-1">{{ $pembelian->no_faktur }}</span></td>
                </tr>
            </table>
        </div>
        <div>
            <table class="w-full">
                <tr>
                    <td class="font-bold w-32 py-1">Oleh User</td>
                    <td>: {{ $pembelian->user->username ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1">Total</td>
                    <td>: <span class="font-bold text-lg">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</span></td>
                </tr>
                <tr>
                    <td class="font-bold py-1">Keterangan</td>
                    <td>: {{ $pembelian->keterangan ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Produk</th>
                <th class="border border-gray-400 p-2 text-center">Qty</th>
                <th class="border border-gray-400 p-2 text-right">Harga Satuan</th>
                <th class="border border-gray-400 p-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian->details as $index => $detail)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $index + 1 }}</td>
                <td class="border border-gray-300 p-2">
                    <div class="font-bold">{{ $detail->produk->nama_produk }}</div>
                    <div class="text-[10px] text-gray-500">{{ $detail->produk->sku }}</div>
                </td>
                <td class="border border-gray-300 p-2 text-center font-bold">{{ $detail->jumlah }}</td>
                <td class="border border-gray-300 p-2 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="border border-gray-300 p-2 text-right font-bold">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray-100 font-bold text-xs">
                <td colspan="4" class="border border-gray-400 p-2 text-right uppercase">Total Pembelian</td>
                <td class="border border-gray-400 p-2 text-right">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
