@extends('layouts.owner')

@section('title', 'Retur Pembelian')

@section('content')
<div class="flex justify-between items-center mb-3">
    <div>
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block">RETUR PEMBELIAN</h2>
        <span class="text-xs text-gray-500 ml-2">Daftar Retur ke Distributor</span>
    </div>
    <a href="{{ route('owner.retur-pembelian.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        + BUAT RETUR KE DISTRIBUTOR
    </a>
</div>
<div class="bg-white border border-gray-400 p-3 mb-3">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        <div><label class="block text-xs font-bold mb-1">Toko</label>
            <select name="id_toko" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">Semua Toko</option>
                @foreach($tokos as $toko)
                <option value="{{ $toko->id_toko }}" {{ request('id_toko') == $toko->id_toko ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-xs font-bold mb-1">Distributor</label>
            <select name="id_distributor" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">Semua Distributor</option>
                @foreach($distributors as $distributor)
                <option value="{{ $distributor->id_distributor }}" {{ request('id_distributor') == $distributor->id_distributor ? 'selected' : '' }}>{{ $distributor->nama_distributor }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-xs font-bold mb-1">Gudang</label>
            <select name="id_gudang" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">Semua Gudang</option>
                @foreach($gudangs as $gudang)
                <option value="{{ $gudang->id_gudang }}" {{ request('id_gudang') == $gudang->id_gudang ? 'selected' : '' }}>{{ $gudang->nama_gudang }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-xs font-bold mb-1">Dari Tanggal</label><input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        <div><label class="block text-xs font-bold mb-1">Sampai Tanggal</label><input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-600 text-white border border-blue-800 px-4 py-1 text-xs hover:bg-blue-500"><i class="fa fa-filter"></i> FILTER</button>
            <a href="{{ route('owner.retur-pembelian.index') }}" class="bg-gray-400 text-white border border-gray-600 px-4 py-1 text-xs hover:bg-gray-300"><i class="fa fa-times"></i> RESET</a>
        </div>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-3">
    <div class="bg-red-50 border border-red-300 p-3">
        <div class="text-xs text-red-700 font-bold">Total Retur (Filter)</div>
        <div class="text-base md:text-xl font-bold text-red-900">Rp {{ number_format($summary['total_retur'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-blue-50 border border-blue-300 p-3">
        <div class="text-xs text-blue-700 font-bold">Transaksi (Filter)</div>
        <div class="text-base md:text-xl font-bold text-blue-900">{{ $summary['jumlah_transaksi'] }}</div>
    </div>
    <div class="bg-yellow-50 border border-yellow-300 p-3">
        <div class="text-xs text-yellow-700 font-bold">Hari Ini</div>
        <div class="text-base md:text-xl font-bold text-yellow-900">Rp {{ number_format($summary['hari_ini'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-pink-50 border border-pink-300 p-3">
        <div class="text-xs text-pink-700 font-bold">Bulan Ini</div>
        <div class="text-base md:text-xl font-bold text-pink-900">Rp {{ number_format($summary['bulan_ini'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-orange-50 border border-orange-300 p-3 col-span-2 md:col-span-1">
        <div class="text-xs text-orange-700 font-bold">Tahun Ini</div>
        <div class="text-base md:text-xl font-bold text-orange-900">Rp {{ number_format($summary['tahun_ini'], 0, ',', '.') }}</div>
    </div>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3">
    @forelse($returs as $retur)
    <div class="bg-white border-2 border-gray-400 p-3 shadow-md relative">
        <div class="flex justify-between items-start mb-2">
            <div>
                <div class="text-xs text-gray-500 font-bold">{{ $retur->tgl_retur->format('d M Y') }}</div>
                <div class="font-bold text-sm">{{ $retur->distributor->nama_distributor }}</div>
                <div class="text-xs font-bold text-gray-700">{{ $retur->gudang->toko->nama_toko ?? 'N/A' }}</div>
                <div class="text-xs text-gray-600">Gudang: {{ $retur->gudang->nama_gudang ?? '-' }}</div>
            </div>
            <div class="text-right flex gap-1 justify-end">
                <a href="{{ route('owner.retur-pembelian.show', $retur->id_retur_pembelian) }}" class="bg-blue-600 text-white border border-blue-800 px-2 py-1 text-[10px] hover:bg-blue-500 block mb-1">
                    DETAIL
                </a>
                <form action="{{ route('owner.retur-pembelian.destroy', $retur->id_retur_pembelian) }}" method="POST" onsubmit="return confirm('yakin ingin hapus ini? data tidak bisa di pulihkan')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white border border-red-800 px-2 py-1 text-[10px] hover:bg-red-500 block mb-1">HAPUS</button>
                </form>
            </div>
        </div>
        
        <div class="flex justify-between items-end border-t border-gray-300 pt-2 mt-2">
            <div class="text-xs text-gray-600">Total Retur</div>
            <div class="font-bold text-lg font-mono">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</div>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-400 p-4 text-center text-gray-500 italic">Belum ada data retur pembelian.</div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Toko</th>
                <th class="border border-gray-400 p-2">Tanggal</th>
                <th class="border border-gray-400 p-2">Distributor</th>
                <th class="border border-gray-400 p-2">Gudang Asal</th>
                <th class="border border-gray-400 p-2 text-right">Total Retur</th>
                <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returs as $index => $retur)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $returs->firstItem() + $index }}</td>
                <td class="border border-gray-300 p-2 font-bold">{{ $retur->gudang->toko->nama_toko ?? 'N/A' }}</td>
                <td class="border border-gray-300 p-2">{{ $retur->tgl_retur->format('d M Y') }}</td>
                <td class="border border-gray-300 p-2">{{ $retur->distributor->nama_distributor }}</td>
                <td class="border border-gray-300 p-2">{{ $retur->gudang->nama_gudang ?? '-' }}</td>
                <td class="border border-gray-300 p-2 text-right font-mono font-bold">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.retur-pembelian.show', $retur->id_retur_pembelian) }}" class="bg-blue-600 text-white border border-blue-800 px-2 py-0.5 text-[10px] hover:bg-blue-500">
                            DETAIL
                        </a>
                        <form action="{{ route('owner.retur-pembelian.destroy', $retur->id_retur_pembelian) }}" method="POST" class="inline" onsubmit="return confirm('yakin ingin hapus ini? data tidak bisa di pulihkan')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white border border-red-800 px-2 py-0.5 text-[10px] hover:bg-red-500">HAPUS</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada data retur pembelian.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $returs->links() }}
</div>
@endsection
