@extends('layouts.owner')

@section('title', 'Piutang Pelanggan')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-hand-holding-usd"></i> PIUTANG PELANGGAN
    </h2>
    <a href="{{ route('owner.utang-piutang-pelanggan.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        <i class="fa fa-plus"></i> TAMBAH TRANSAKSI
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs">
        {{ session('error') }}
    </div>
@endif

{{-- Filter --}}
<div class="bg-white border border-gray-400 p-3 mb-3">
    <form method="GET" action="{{ route('owner.utang-piutang-pelanggan.index') }}" class="grid grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-bold mb-1">Pelanggan</label>
            <select name="id_pelanggan" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">-- Semua Pelanggan --</option>
                @foreach($pelanggans as $p)
                    <option value="{{ $p->id_pelanggan }}" {{ request('id_pelanggan') == $p->id_pelanggan ? 'selected' : '' }}>
                        {{ $p->nama_pelanggan }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Jenis Transaksi</label>
            <select name="jenis_transaksi" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">-- Semua --</option>
                <option value="piutang" {{ request('jenis_transaksi') == 'piutang' ? 'selected' : '' }}>Piutang</option>
                <option value="pembayaran" {{ request('jenis_transaksi') == 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-600 text-white border border-blue-800 px-4 py-1 text-xs hover:bg-blue-500 flex-1">
                <i class="fa fa-filter"></i> FILTER
            </button>
            @if(request()->hasAny(['id_pelanggan', 'jenis_transaksi', 'tanggal_dari', 'tanggal_sampai']))
            <a href="{{ route('owner.utang-piutang-pelanggan.index') }}" class="bg-gray-400 text-white border border-gray-600 px-4 py-1 text-xs hover:bg-gray-300">
                <i class="fa fa-times"></i> RESET
            </a>
            @endif
        </div>
    </form>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">Tanggal</th>
                <th class="border border-gray-400 p-2">Pelanggan</th>
                <th class="border border-gray-400 p-2 text-center w-24">Jenis</th>
                <th class="border border-gray-400 p-2 text-right">Nominal</th>
                <th class="border border-gray-400 p-2 text-right bg-green-50">Saldo Piutang</th>
                <th class="border border-gray-400 p-2">Keterangan</th>
                <th class="border border-gray-400 p-2 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $key => $row)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $transaksi->firstItem() + $key }}</td>
                <td class="border border-gray-300 p-2 font-mono">{{ $row->tanggal->format('d/m/Y') }}</td>
                <td class="border border-gray-300 p-2">
                    <span class="font-bold">{{ $row->pelanggan->nama_pelanggan }}</span>
                    @if($row->no_referensi)
                        <div class="text-[10px] text-gray-600">Ref: {{ $row->no_referensi }}</div>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    @if($row->jenis_transaksi == 'piutang')
                        <span class="px-2 py-0.5 rounded bg-blue-200 text-blue-800 text-[10px] font-bold">PIUTANG</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-green-200 text-green-800 text-[10px] font-bold">BAYAR</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-right font-mono">
                    <span class="{{ $row->jenis_transaksi == 'piutang' ? 'text-blue-600' : 'text-green-600' }}">
                        {{ $row->jenis_transaksi == 'piutang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </span>
                </td>
                <td class="border border-gray-300 p-2 text-right font-mono bg-green-50">
                    <span class="font-bold {{ $row->saldo_piutang > 0 ? 'text-blue-600' : 'text-gray-600' }}">
                        Rp {{ number_format($row->saldo_piutang, 0, ',', '.') }}
                    </span>
                </td>
                <td class="border border-gray-300 p-2">{{ $row->keterangan ?? '-' }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.utang-piutang-pelanggan.edit', $row->id_piutang) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                        <form action="{{ route('owner.utang-piutang-pelanggan.destroy', $row->id_piutang) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Saldo akan di-recalculate.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white border border-red-700 px-2 py-0.5 text-[10px] hover:bg-red-400">HAPUS</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transaksi->hasPages())
<div class="mt-3">
    {{ $transaksi->links() }}
</div>
@endif
@endsection
