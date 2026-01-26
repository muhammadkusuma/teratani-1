@extends('layouts.owner')

@section('title', 'Utang Piutang Distributor')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-money-bill-wave"></i>  UTANG PIUTANG DISTRIBUTOR
    </h2>
    <a href="{{ route('owner.distributor.hutang.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
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


<div class="bg-white border border-gray-400 p-3 mb-3">
    <form method="GET" action="{{ route('owner.distributor.hutang.index') }}" class="grid grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-bold mb-1">Distributor</label>
            <select name="id_distributor" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">-- Semua Distributor --</option>
                @foreach($distributors as $d)
                    <option value="{{ $d->id_distributor }}" {{ request('id_distributor') == $d->id_distributor ? 'selected' : '' }}>
                        {{ $d->nama_distributor }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Jenis Transaksi</label>
            <select name="jenis_transaksi" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">-- Semua --</option>
                <option value="utang" {{ request('jenis_transaksi') == 'utang' ? 'selected' : '' }}>Utang</option>
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
            @if(request()->hasAny(['id_distributor', 'jenis_transaksi', 'tanggal_dari', 'tanggal_sampai']))
            <a href="{{ route('owner.distributor.hutang.index') }}" class="bg-gray-400 text-white border border-gray-600 px-4 py-1 text-xs hover:bg-gray-300">
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
                <th class="border border-gray-400 p-2">Distributor</th>
                <th class="border border-gray-400 p-2 text-center w-24">Jenis</th>
                <th class="border border-gray-400 p-2 text-right">Nominal</th>
                <th class="border border-gray-400 p-2 text-right bg-yellow-50">Saldo Utang</th>
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
                    <span class="font-bold">{{ $row->distributor->nama_distributor }}</span>
                    @if($row->no_referensi)
                        <div class="text-[10px] text-gray-600">Ref: {{ $row->no_referensi }}</div>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    @if($row->jenis_transaksi == 'utang')
                        <span class="px-2 py-0.5 rounded bg-red-200 text-red-800 text-[10px] font-bold">UTANG</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-green-200 text-green-800 text-[10px] font-bold">BAYAR</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-right font-mono">
                    <span class="{{ $row->jenis_transaksi == 'utang' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $row->jenis_transaksi == 'utang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </span>
                </td>
                <td class="border border-gray-300 p-2 text-right font-mono bg-yellow-50">
                    <span class="font-bold {{ $row->saldo_utang > 0 ? 'text-red-600' : 'text-gray-600' }}">
                        Rp {{ number_format($row->saldo_utang, 0, ',', '.') }}
                    </span>
                </td>
                <td class="border border-gray-300 p-2">{{ $row->keterangan ?? '-' }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.distributor.hutang.edit', $row->id_utang_piutang) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                        <form action="{{ route('owner.distributor.hutang.destroy', $row->id_utang_piutang) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Saldo akan di-recalculate.')">
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('select[name="id_distributor"]').select2({
            placeholder: '-- Cari Distributor --',
            allowClear: true,
            ajax: {
                url: "{{ route('owner.distributor.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id_distributor,
                                text: item.nama_distributor + ' (' + (item.kode_distributor || '-') + ')'
                            };
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>
@endpush
@endsection
