@extends('layouts.owner')

@section('title', 'Detail Transfer Stok')

@section('content')
    <div class="mb-4">
        <a href="{{ route('owner.mutasi.index') }}" class="text-blue-600 hover:underline">
            &larr; Kembali ke Riwayat
        </a>
    </div>

    {{-- Header Informasi --}}
    <div class="bg-white border border-gray-400 p-4 mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h2 class="text-xl font-bold mb-2">{{ $mutasi->no_mutasi }}</h2>
            <div class="text-sm text-gray-600">
                <p><strong>Tanggal Kirim:</strong> {{ date('d/m/Y H:i', strtotime($mutasi->tgl_kirim)) }}</p>
                <p><strong>Pengirim:</strong> {{ $mutasi->pengirim->name ?? '-' }}</p>
                <p class="mt-2"><strong>Status:</strong>
                    <span class="px-2 py-0.5 bg-gray-200 border border-gray-400 text-gray-800 text-xs font-bold">
                        {{ $mutasi->status }}
                    </span>
                </p>
                @if ($mutasi->status == 'Diterima')
                    <p class="text-green-600 text-xs mt-1">Diterima pada: {{ $mutasi->tgl_terima }}</p>
                @endif
            </div>
        </div>
        <div class="border-l border-gray-300 pl-4">
            <div class="mb-2">
                <span class="block text-xs font-bold text-red-600 uppercase">Dari Toko</span>
                <span class="text-lg">{{ $mutasi->tokoAsal->nama_toko ?? '-' }}</span>
            </div>
            <div class="mb-2">
                <span class="block text-xs font-bold text-green-600 uppercase">Ke Toko</span>
                <span class="text-lg">{{ $mutasi->tokoTujuan->nama_toko ?? '-' }}</span>
            </div>
            @if ($mutasi->keterangan)
                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 text-sm italic">
                    "{{ $mutasi->keterangan }}"
                </div>
            @endif
        </div>
    </div>

    {{-- Tabel Detail Barang --}}
    <h3 class="font-bold text-gray-800 mb-2">Rincian Barang</h3>
    <div class="overflow-x-auto bg-white border border-gray-400 mb-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 border-b border-gray-400 text-sm">
                    <th class="p-2 border-r border-gray-300 w-10 text-center">No</th>
                    <th class="p-2 border-r border-gray-300">Kode Produk (SKU)</th>
                    <th class="p-2 border-r border-gray-300">Nama Produk</th>
                    <th class="p-2 text-right w-32">Qty Kirim</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mutasi->details as $index => $detail)
                    <tr class="border-b border-gray-200">
                        <td class="p-2 border-r border-gray-200 text-center">{{ $index + 1 }}</td>
                        <td class="p-2 border-r border-gray-200 font-mono text-sm">
                            {{ $detail->produk->sku ?? '-' }}
                        </td>
                        <td class="p-2 border-r border-gray-200">
                            {{ $detail->produk->nama_produk ?? 'Produk Terhapus' }}
                        </td>
                        <td class="p-2 text-right font-bold">
                            {{ $detail->qty_kirim }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Aksi --}}
    @if ($mutasi->status === 'Proses')
        <div class="p-4 bg-yellow-50 border border-yellow-300 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-yellow-800">
                <p><strong>Konfirmasi Penerimaan:</strong></p>
                <p>Pastikan fisik barang sudah diterima di Toko Tujuan sebelum menekan tombol ini. Stok toko tujuan akan
                    bertambah otomatis.</p>
            </div>
            <form action="{{ route('owner.mutasi.terima', $mutasi->id_mutasi) }}" method="POST"
                onsubmit="return confirm('Yakin barang sudah diterima? Stok akan ditambahkan ke Toko Tujuan.')">
                @csrf
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white font-bold border-b-4 border-green-800 hover:bg-green-500 shadow-lg">
                    TERIMA BARANG SEKARANG
                </button>
            </form>
        </div>
    @endif

@endsection
