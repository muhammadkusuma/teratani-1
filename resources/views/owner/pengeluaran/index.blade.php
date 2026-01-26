@extends('layouts.owner')
@section('title', 'Daftar Pengeluaran')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-money-bill-wave"></i> DAFTAR PENGELUARAN</h2>
    <a href="{{ route('owner.pengeluaran.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs"><i class="fa fa-plus"></i> TAMBAH PENGELUARAN</a>
</div>
<div class="bg-white border border-gray-400 p-3 mb-3">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <div><label class="block text-xs font-bold mb-1">Toko</label>
            <select name="id_toko" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">Semua Toko</option>
                @foreach($tokos as $toko)
                <option value="{{ $toko->id_toko }}" {{ request('id_toko') == $toko->id_toko ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-xs font-bold mb-1">Dari Tanggal</label><input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        <div><label class="block text-xs font-bold mb-1">Sampai Tanggal</label><input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        <div><label class="block text-xs font-bold mb-1">Kategori</label>
            <select name="kategori" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">Semua</option>
                @foreach(['Gaji', 'Listrik', 'Air', 'Sewa', 'ATK', 'Transportasi', 'Pemeliharaan', 'Pajak', 'Lainnya'] as $kat)
                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select></div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-600 text-white border border-blue-800 px-4 py-1 text-xs hover:bg-blue-500"><i class="fa fa-filter"></i> FILTER</button>
            <a href="{{ route('owner.pengeluaran.index') }}" class="bg-gray-400 text-white border border-gray-600 px-4 py-1 text-xs hover:bg-gray-300"><i class="fa fa-times"></i> RESET</a>
        </div>
    </form>
</div>
<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-3">
    <div class="bg-red-50 border border-red-300 p-3">
        <div class="text-xs text-red-700 font-bold">Total (Filter)</div>
        <div class="text-base md:text-xl font-bold text-red-900">Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</div>
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
    @forelse($pengeluarans as $row)
    <div class="bg-white border-2 border-gray-400 p-3 shadow-md relative">
        <div class="flex justify-between items-start mb-2">
            <div>
                <div class="text-xs text-gray-500 font-bold">{{ $row->tanggal_pengeluaran->format('d/m/Y') }}</div>
                <div class="font-bold text-sm">{{ $row->toko->nama_toko ?? 'N/A' }}</div>
                <div class="text-xs font-mono text-gray-600">{{ $row->kode_pengeluaran }}</div>
            </div>
            <div class="text-right">
                <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-[10px] font-bold block mb-1">{{ $row->kategori }}</span>
                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-[10px] font-bold block">{{ $row->metode_bayar }}</span>
            </div>
        </div>
        
        <div class="mb-2 text-sm text-gray-800 italic border-l-2 border-gray-300 pl-2">
            {{ Str::limit($row->deskripsi, 60) }}
        </div>

        <div class="flex justify-between items-end border-t border-gray-300 pt-2 mt-2">
            <div class="font-bold text-red-700 text-lg">Rp {{ number_format($row->jumlah, 0, ',', '.') }}</div>
            <div class="flex gap-1">
                <a href="{{ route('owner.pengeluaran.show', $row->id_pengeluaran) }}" class="bg-blue-500 text-white border border-blue-700 px-2 py-1 text-xs hover:bg-blue-400">LIHAT</a>
                <a href="{{ route('owner.pengeluaran.edit', $row->id_pengeluaran) }}" class="bg-yellow-400 text-black border border-yellow-600 px-2 py-1 text-xs hover:bg-yellow-300">EDIT</a>
                <form action="{{ route('owner.pengeluaran.destroy', $row->id_pengeluaran) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white border border-red-700 px-2 py-1 text-xs hover:bg-red-400">HAPUS</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-400 p-4 text-center text-gray-500 italic">Belum ada pengeluaran</div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 w-10">No</th>
                <th class="border border-gray-400 p-2">Toko</th>
                <th class="border border-gray-400 p-2">Kode</th>
                <th class="border border-gray-400 p-2">Tanggal</th>
                <th class="border border-gray-400 p-2">Kategori</th>
                <th class="border border-gray-400 p-2">Deskripsi</th>
                <th class="border border-gray-400 p-2">Jumlah</th>
                <th class="border border-gray-400 p-2">Pembayaran</th>
                <th class="border border-gray-400 p-2 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluarans as $key => $row)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $pengeluarans->firstItem() + $key }}</td>
                <td class="border border-gray-300 p-2 font-bold">{{ $row->toko->nama_toko ?? 'N/A' }}</td>
                <td class="border border-gray-300 p-2 font-mono">{{ $row->kode_pengeluaran }}</td>
                <td class="border border-gray-300 p-2">{{ $row->tanggal_pengeluaran->format('d/m/Y') }}</td>
                <td class="border border-gray-300 p-2"><span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-[10px] font-bold">{{ $row->kategori }}</span></td>
                <td class="border border-gray-300 p-2">{{ Str::limit($row->deskripsi, 50) }}</td>
                <td class="border border-gray-300 p-2 font-bold text-red-700">Rp {{ number_format($row->jumlah, 0, ',', '.') }}</td>
                <td class="border border-gray-300 p-2"><span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-[10px] font-bold">{{ $row->metode_bayar }}</span></td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.pengeluaran.show', $row->id_pengeluaran) }}" class="bg-blue-500 text-white border border-blue-700 px-2 py-0.5 text-[10px] hover:bg-blue-400">LIHAT</a>
                        <a href="{{ route('owner.pengeluaran.edit', $row->id_pengeluaran) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                        <form action="{{ route('owner.pengeluaran.destroy', $row->id_pengeluaran) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white border border-red-700 px-2 py-0.5 text-[10px] hover:bg-red-400">HAPUS</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada pengeluaran</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($pengeluarans->hasPages())
<div class="mt-3">{{ $pengeluarans->links() }}</div>
@endif
@endsection
