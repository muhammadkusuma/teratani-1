@extends('layouts.owner')

@section('title', 'Detail Transfer')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">DETAIL TRANSFER #{{ $mutasi->no_mutasi }}</h2>
        <a href="{{ route('owner.mutasi.index') }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo; Kembali</a>
    </div>

    <div class="bg-gray-100 p-4 border border-gray-400 shadow-inner mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div>
                <p class="mb-1"><span class="font-bold w-24 inline-block">Tanggal:</span> {{ date('d/m/Y H:i', strtotime($mutasi->tgl_kirim)) }}</p>
                <p class="mb-1"><span class="font-bold w-24 inline-block">Pengirim:</span> {{ $mutasi->pengirim->name ?? '-' }}</p>
                <p class="mb-1"><span class="font-bold w-24 inline-block">Status:</span> 
                    <span class="px-2 py-0.5 bg-white border border-gray-400 font-bold">{{ $mutasi->status }}</span>
                </p>
                @if($mutasi->keterangan)
                <p class="mt-2 text-gray-600 italic">"{{ $mutasi->keterangan }}"</p>
                @endif
            </div>
            <div class="border-l border-gray-300 pl-4">
                <div class="mb-2">
                    <div class="font-bold text-red-700">DARI TOKO:</div>
                    <div class="text-lg">{{ $mutasi->tokoAsal->nama_toko ?? '-' }}</div>
                </div>
                <div>
                    <div class="font-bold text-green-700">KE TOKO:</div>
                    <div class="text-lg">{{ $mutasi->tokoTujuan->nama_toko ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto border border-gray-400 bg-white mb-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <th class="border border-gray-400 p-2 text-center w-10">No</th>
                    <th class="border border-gray-400 p-2">Kode / SKU</th>
                    <th class="border border-gray-400 p-2">Nama Produk</th>
                    <th class="border border-gray-400 p-2 text-right">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mutasi->details as $index => $detail)
                <tr class="text-xs border-b border-gray-200">
                    <td class="border-r border-gray-300 p-2 text-center">{{ $index + 1 }}</td>
                    <td class="border-r border-gray-300 p-2 font-mono">{{ $detail->produk->sku ?? '-' }}</td>
                    <td class="border-r border-gray-300 p-2">{{ $detail->produk->nama_produk ?? 'Item Terhapus' }}</td>
                    <td class="border-r border-gray-300 p-2 text-right font-bold">{{ $detail->qty_kirim }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($mutasi->status === 'Proses')
    <div class="bg-yellow-50 border border-yellow-300 p-3 flex justify-between items-center">
        <div class="text-xs text-yellow-800">
            <strong>Konfirmasi Penerimaan:</strong><br>
            Klik tombol di samping jika barang fisik sudah diterima di Toko Tujuan.
        </div>
        <form action="{{ route('owner.mutasi.terima', $mutasi->id_mutasi) }}" method="POST" onsubmit="return confirm('Stok akan bertambah di toko tujuan. Lanjutkan?')">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 border border-green-800 shadow hover:bg-green-500 font-bold text-xs">
                TERIMA BARANG
            </button>
        </form>
    </div>
    @endif
</div>
@endsection