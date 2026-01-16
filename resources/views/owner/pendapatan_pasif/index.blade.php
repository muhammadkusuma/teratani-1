@extends('layouts.owner')
@section('title', 'Daftar Pendapatan')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-coins"></i> DAFTAR PENDAPATAN</h2>
    <a href="{{ route('owner.pendapatan_pasif.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs"><i class="fa fa-plus"></i> TAMBAH PENDAPATAN</a>
</div>
<div class="bg-white border border-gray-400 p-3 mb-3">
    <form method="GET" class="grid grid-cols-5 gap-3">
        <div><label class="block text-xs font-bold mb-1">Dari Tanggal</label><input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        <div><label class="block text-xs font-bold mb-1">Sampai Tanggal</label><input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        <div><label class="block text-xs font-bold mb-1">Kategori</label>
            <select name="kategori" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">Semua</option>
                @foreach(['Penjualan', 'Bunga Bank', 'Sewa Aset', 'Komisi', 'Investasi', 'Lainnya'] as $kat)
                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select></div>
        <div><label class="block text-xs font-bold mb-1">Tipe</label>
            <select name="tipe" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">Semua</option>
                <option value="otomatis" {{ request('tipe') == 'otomatis' ? 'selected' : '' }}>Otomatis</option>
                <option value="manual" {{ request('tipe') == 'manual' ? 'selected' : '' }}>Manual</option>
            </select></div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-600 text-white border border-blue-800 px-4 py-1 text-xs hover:bg-blue-500"><i class="fa fa-filter"></i> FILTER</button>
            <a href="{{ route('owner.pendapatan_pasif.index') }}" class="bg-gray-400 text-white border border-gray-600 px-4 py-1 text-xs hover:bg-gray-300"><i class="fa fa-times"></i> RESET</a>
        </div>
    </form>
</div>
<div class="grid grid-cols-5 gap-3 mb-3">
    <div class="bg-green-50 border border-green-300 p-3">
        <div class="text-xs text-green-700 font-bold">Total (Filter)</div>
        <div class="text-xl font-bold text-green-900">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-blue-50 border border-blue-300 p-3">
        <div class="text-xs text-blue-700 font-bold">Transaksi (Filter)</div>
        <div class="text-xl font-bold text-blue-900">{{ $summary['jumlah_transaksi'] }}</div>
    </div>
    <div class="bg-yellow-50 border border-yellow-300 p-3">
        <div class="text-xs text-yellow-700 font-bold">Hari Ini</div>
        <div class="text-xl font-bold text-yellow-900">Rp {{ number_format($summary['hari_ini'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-purple-50 border border-purple-300 p-3">
        <div class="text-xs text-purple-700 font-bold">Bulan Ini</div>
        <div class="text-xl font-bold text-purple-900">Rp {{ number_format($summary['bulan_ini'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-indigo-50 border border-indigo-300 p-3">
        <div class="text-xs text-indigo-700 font-bold">Tahun Ini</div>
        <div class="text-xl font-bold text-indigo-900">Rp {{ number_format($summary['tahun_ini'], 0, ',', '.') }}</div>
    </div>
</div>
<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 w-10">No</th>
                <th class="border border-gray-400 p-2">Kode</th>
                <th class="border border-gray-400 p-2">Tanggal</th>
                <th class="border border-gray-400 p-2">Kategori</th>
                <th class="border border-gray-400 p-2">Sumber</th>
                <th class="border border-gray-400 p-2">Jumlah</th>
                <th class="border border-gray-400 p-2">Tipe</th>
                <th class="border border-gray-400 p-2 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendapatanPasifs as $key => $row)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $pendapatanPasifs->firstItem() + $key }}</td>
                <td class="border border-gray-300 p-2 font-mono">{{ $row->kode_pendapatan }}</td>
                <td class="border border-gray-300 p-2">{{ $row->tanggal_pendapatan->format('d/m/Y') }}</td>
                <td class="border border-gray-300 p-2"><span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-[10px] font-bold">{{ $row->kategori }}</span></td>
                <td class="border border-gray-300 p-2">{{ Str::limit($row->sumber, 50) }}</td>
                <td class="border border-gray-300 p-2 font-bold text-green-700">Rp {{ number_format($row->jumlah, 0, ',', '.') }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    @if($row->is_otomatis)
                    <span class="px-2 py-0.5 rounded bg-blue-200 text-blue-800 text-[10px] font-bold"><i class="fa fa-robot"></i> OTOMATIS</span>
                    @else
                    <span class="px-2 py-0.5 rounded bg-gray-200 text-gray-800 text-[10px] font-bold"><i class="fa fa-hand-paper"></i> MANUAL</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.pendapatan_pasif.show', $row->id_pendapatan) }}" class="bg-blue-500 text-white border border-blue-700 px-2 py-0.5 text-[10px] hover:bg-blue-400">LIHAT</a>
                        @if(!$row->is_otomatis)
                        <a href="{{ route('owner.pendapatan_pasif.edit', $row->id_pendapatan) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                        <form action="{{ route('owner.pendapatan_pasif.destroy', $row->id_pendapatan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pendapatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white border border-red-700 px-2 py-0.5 text-[10px] hover:bg-red-400">HAPUS</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada pendapatan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($pendapatanPasifs->hasPages())
<div class="mt-3">{{ $pendapatanPasifs->links() }}</div>
@endif
@endsection
