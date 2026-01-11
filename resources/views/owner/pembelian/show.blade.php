@extends('layouts.owner')

@section('title', 'Detail Faktur Pembelian')

@section('content')
<div class="p-4 max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('owner.pembelian.index') }}" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white border border-gray-300 shadow-lg p-6 relative overflow-hidden">
        <div class="absolute top-4 right-4 rotate-12 opacity-80">
            <span class="border-4 border-red-500 text-red-500 px-4 py-1 font-black text-2xl uppercase rounded tracking-widest">
                {{ $pembelian->status_bayar }}
            </span>
        </div>

        <div class="flex justify-between items-start mb-6 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">FAKTUR PEMBELIAN</h1>
                <p class="text-gray-500 text-sm">No. Ref System: #{{ str_pad($pembelian->id_pembelian, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="text-right">
                <h3 class="font-bold text-lg text-blue-800">{{ $pembelian->toko->nama_toko ?? 'TERATANI STORE' }}</h3>
                <p class="text-xs text-gray-500">Tanggal Input: {{ $pembelian->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-6 text-sm">
            <div>
                <p class="text-gray-500 font-bold uppercase text-xs mb-1">Supplier / Distributor</p>
                <p class="font-bold text-lg">{{ $pembelian->distributor->nama_distributor ?? 'Umum' }}</p>
                <p class="text-gray-600">{{ $pembelian->distributor->alamat ?? '-' }}</p>
                <p class="text-gray-600">{{ $pembelian->distributor->no_hp ?? '-' }}</p>
            </div>
            <div class="text-right">
                <table class="ml-auto">
                    <tr>
                        <td class="text-gray-500 pr-4">No. Faktur Supplier:</td>
                        <td class="font-bold">{{ $pembelian->no_faktur_supplier }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 pr-4">Tgl. Faktur:</td>
                        <td class="font-bold">{{ \Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 pr-4">Jatuh Tempo:</td>
                        <td class="font-bold text-red-600">
                            {{ $pembelian->tgl_jatuh_tempo ? \Carbon\Carbon::parse($pembelian->tgl_jatuh_tempo)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="w-full border-collapse mb-6">
            <thead class="bg-gray-800 text-white text-xs uppercase">
                <tr>
                    <th class="p-2 text-left">Produk</th>
                    <th class="p-2 text-center">Qty</th>
                    <th class="p-2 text-right">Harga Satuan</th>
                    <th class="p-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @foreach($pembelian->details as $detail)
                <tr class="border-b border-gray-200">
                    <td class="p-2">
                        <div class="font-bold">{{ $detail->produk->nama_produk ?? 'Item Terhapus' }}</div>
                        <div class="text-xs text-gray-500">Exp: {{ $detail->tgl_expired_item ?? '-' }}</div>
                    </td>
                    <td class="p-2 text-center">{{ $detail->qty }} {{ $detail->satuan_beli }}</td>
                    <td class="p-2 text-right">Rp {{ number_format($detail->harga_beli_satuan, 0, ',', '.') }}</td>
                    <td class="p-2 text-right font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-100">
                <tr>
                    <td colspan="3" class="p-2 text-right font-bold uppercase text-gray-600">Total Pembelian</td>
                    <td class="p-2 text-right font-bold text-xl text-blue-800">
                        Rp {{ number_format($pembelian->total_pembelian, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        @if($pembelian->keterangan)
        <div class="bg-yellow-50 border border-yellow-200 p-3 text-sm text-yellow-800 rounded">
            <strong>Catatan:</strong> {{ $pembelian->keterangan }}
        </div>
        @endif
        
        <div class="mt-8 text-xs text-gray-400 text-center">
            Dicatat oleh: {{ $pembelian->user->name ?? 'System' }}
        </div>
    </div>
</div>
@endsection