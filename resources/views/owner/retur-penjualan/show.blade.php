@extends('layouts.owner')

@section('title', 'Detail Retur Penjualan')

@section('content')
<div class="mb-4">
    <h2 class="font-bold text-xl">Detail Retur Penjualan #{{ $retur->id_retur_penjualan }}</h2>
</div>

<div class="bg-white p-6 rounded shadow mb-4">
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <p class="text-gray-600">Pelanggan:</p>
            <p class="font-bold">{{ $retur->pelanggan->nama_pelanggan }}</p>
        </div>
        <div>
            <p class="text-gray-600">Tanggal Retur:</p>
            <p class="font-bold">{{ $retur->tgl_retur->format('d/m/Y') }}</p>
        </div>
        <div>
            <p class="text-gray-600">Total Nilai Retur:</p>
            <p class="font-bold text-lg">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</p>
        </div>
        <div>
            <p class="text-gray-600">Keterangan:</p>
            <p>{{ $retur->keterangan ?? '-' }}</p>
        </div>
    </div>

    <h3 class="font-bold mb-2">Item Retur</h3>
    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border text-left">Produk</th>
                <th class="p-2 border text-right">Qty</th>
                <th class="p-2 border text-right">Harga Retur</th>
                <th class="p-2 border text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($retur->details as $detail)
            <tr>
                <td class="p-2 border">
                    <div class="font-bold">{{ $detail->produk->nama_produk }}</div>
                    <div class="text-xs text-gray-500">{{ $detail->produk->sku }}</div>
                </td>
                <td class="p-2 border text-right">{{ $detail->qty }}</td>
                <td class="p-2 border text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="p-2 border text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50 font-bold">
            <tr>
                <td colspan="3" class="p-2 border text-right">Total</td>
                <td class="p-2 border text-right">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-4">
        <a href="{{ route('owner.retur-penjualan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Kembali</a>
    </div>
</div>
@endsection
