@extends('layouts.owner')

@section('title', 'Riwayat Stok')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-history"></i> RIWAYAT KELUAR MASUK BARANG
    </h2>
    <a href="{{ route('owner.riwayat-stok.create') }}" class="px-3 py-1 bg-yellow-600 text-white border border-yellow-800 shadow hover:bg-yellow-500 text-xs">
        <i class="fa fa-exchange-alt"></i> PENYESUAIAN STOK MANUAL
    </a>
</div>

<div class="bg-gray-100 border border-gray-400 p-3 mb-3">
    <form action="" method="GET" class="flex flex-wrap gap-2 items-end">
        <div>
            <label class="block text-xs font-bold mb-1">DARI TANGGAL</label>
            <input type="date" name="start_date" class="win98-input text-xs" value="{{ request('start_date') }}">
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">SAMPAI TANGGAL</label>
            <input type="date" name="end_date" class="win98-input text-xs" value="{{ request('end_date') }}">
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">JENIS</label>
            <select name="jenis" class="win98-input text-xs">
                <option value="">Semua Jenis</option>
                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs font-bold">
                <i class="fa fa-filter"></i> FILTER
            </button>
            <a href="{{ route('owner.riwayat-stok.index') }}" class="px-3 py-1 bg-gray-200 text-gray-700 border border-gray-400 shadow hover:bg-gray-300 text-xs flex items-center">
                RESET
            </a>
        </div>
    </form>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Tanggal</th>
                <th class="border border-gray-400 p-2">Produk</th>
                <th class="border border-gray-400 p-2 text-center">Jenis</th>
                <th class="border border-gray-400 p-2 text-center w-16">Jumlah</th>
                <th class="border border-gray-400 p-2">Lokasi</th>
                <th class="border border-gray-400 p-2">Keterangan</th>
                <th class="border border-gray-400 p-2">Referensi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayats as $index => $riwayat)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $riwayats->firstItem() + $index }}</td>
                <td class="border border-gray-300 p-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($riwayat->tanggal)->format('d/m/Y H:i') }}</td>
                <td class="border border-gray-300 p-2">
                    <div class="font-bold">{{ $riwayat->produk->nama_produk }}</div>
                    <div class="text-[10px] text-gray-500 font-mono">{{ $riwayat->produk->sku }}</div>
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    @if($riwayat->jenis == 'masuk')
                        <span class="text-green-700 font-bold uppercase">Masuk</span>
                    @else
                        <span class="text-red-700 font-bold uppercase">Keluar</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-center font-bold font-mono">
                    {{ number_format($riwayat->jumlah) }}
                </td>
                <td class="border border-gray-300 p-2">
                    @if($riwayat->id_gudang)
                        <span class="bg-purple-100 text-purple-800 px-1 rounded text-[10px] border border-purple-300">GUDANG: {{ $riwayat->gudang->nama_gudang }}</span>
                    @elseif($riwayat->id_toko)
                        <span class="bg-blue-100 text-blue-800 px-1 rounded text-[10px] border border-blue-300">TOKO: {{ $riwayat->toko->kode_toko }}</span>
                    @else
                        -
                    @endif
                </td>
                <td class="border border-gray-300 p-2">{{ $riwayat->keterangan }}</td>
                <td class="border border-gray-300 p-2 font-mono text-[10px]">{{ $riwayat->referensi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada data riwayat stok.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $riwayats->links() }}
</div>
@endsection
