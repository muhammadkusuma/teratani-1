@extends('layouts.owner')

@section('title', 'Utang Piutang Distributor')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-money-bill-wave text-blue-700"></i> Utang Piutang Distributor
    </h2>
    <a href="{{ route('owner.distributor.hutang.create') }}" class="w-full md:w-auto text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase">
        <i class="fa fa-plus"></i> Tambah Transaksi
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
    <div class="bg-gradient-to-br from-rose-50 to-white border-l-4 border-rose-600 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-rose-800 font-black uppercase tracking-widest mb-1">Total Utang (Terfilter)</div>
        <div class="text-2xl font-black text-rose-900 leading-none">Rp {{ number_format($summary['total_utang'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-gradient-to-br from-emerald-50 to-white border-l-4 border-emerald-600 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-emerald-800 font-black uppercase tracking-widest mb-1">Total Bayar (Terfilter)</div>
        <div class="text-2xl font-black text-emerald-900 leading-none">Rp {{ number_format($summary['total_bayar'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-gradient-to-br from-amber-50 to-white border-l-4 border-amber-600 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-amber-800 font-black uppercase tracking-widest mb-1">Sisa Tagihan (Saldo)</div>
        <div class="text-2xl font-black text-amber-900 leading-none">Rp {{ number_format($summary['saldo'], 0, ',', '.') }}</div>
    </div>
</div>

<div class="bg-white border border-gray-300 p-4 mb-4 shadow-sm rounded-sm">
    <form method="GET" action="{{ route('owner.distributor.hutang.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <div class="md:col-span-1">
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Distributor</label>
            <select name="id_distributor" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all bg-gray-50">
                <option value="">-- Semua Distributor --</option>
                @foreach($distributors as $d)
                    <option value="{{ $d->id_distributor }}" {{ request('id_distributor') == $d->id_distributor ? 'selected' : '' }}>
                        {{ $d->nama_distributor }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Jenis</label>
            <select name="jenis_transaksi" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all bg-gray-50">
                <option value="">-- Semua --</option>
                <option value="utang" {{ request('jenis_transaksi') == 'utang' ? 'selected' : '' }}>Utang</option>
                <option value="pembayaran" {{ request('jenis_transaksi') == 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Dari</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Sampai</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white border border-blue-800 px-4 py-1.5 text-xs font-bold hover:bg-blue-500 transition-colors shadow-sm uppercase">
                <i class="fa fa-filter"></i> Filter
            </button>
            <a href="{{ route('owner.distributor.hutang.index') }}" class="bg-gray-100 text-gray-700 border border-gray-300 px-4 py-1.5 text-xs font-bold hover:bg-gray-200 transition-colors text-center shadow-sm uppercase">
                <i class="fa fa-sync-alt"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-4 mb-4">
    @forelse($transaksi as $row)
    <div class="bg-white border-t-4 {{ $row->jenis_transaksi == 'utang' ? 'border-rose-500' : 'border-emerald-500' }} p-4 shadow-lg rounded-sm relative active:scale-[0.98] transition-all">
        <div class="flex justify-between items-start mb-2">
            <div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $row->tanggal->format('d M Y') }}</div>
                <h3 class="font-black text-sm text-blue-900 tracking-tight leading-tight">{{ $row->distributor->nama_distributor }}</h3>
                @if($row->no_referensi)
                    <div class="inline-block bg-gray-100 px-2 py-0.5 rounded font-mono text-[9px] text-gray-600 mt-1">REF: {{ $row->no_referensi }}</div>
                @endif
            </div>
            <div>
                @if($row->jenis_transaksi == 'utang')
                    <span class="bg-rose-100 text-rose-800 border border-rose-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm">Utang</span>
                @else
                    <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm">Bayar</span>
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-3 mb-3 border-y border-gray-100 py-3">
            <div>
                <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Nominal</div>
                <div class="text-xs font-black {{ $row->jenis_transaksi == 'utang' ? 'text-rose-600' : 'text-emerald-600' }}">
                    {{ $row->jenis_transaksi == 'utang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                </div>
            </div>
            <div>
                <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tight font-black">Sisa Saldo</div>
                <div class="text-xs font-black text-gray-800">
                    Rp {{ number_format($row->saldo_utang, 0, ',', '.') }}
                </div>
            </div>
            @if($row->keterangan)
            <div class="col-span-2 text-[10px] text-gray-600 bg-gray-50 p-2 rounded-sm italic border-l-2 border-gray-200">
                "{{ $row->keterangan }}"
            </div>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('owner.distributor.hutang.edit', $row->id_utang_piutang) }}" class="bg-amber-400 border border-amber-600 text-amber-900 py-2 px-1 text-center text-[10px] font-black hover:bg-amber-300 transition-colors rounded-sm shadow-sm uppercase">Edit</a>
            <form action="{{ route('owner.distributor.hutang.destroy', $row->id_utang_piutang) }}" method="POST" class="w-full" onsubmit="return confirm('Hapus transaksi ini? Saldo akan dihitung ulang secara otomatis.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-rose-600 border border-rose-800 text-white py-2 px-1 text-[10px] font-black hover:bg-rose-500 transition-colors rounded-sm shadow-sm uppercase">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-300 p-8 text-center rounded-sm shadow-sm">
        <i class="fa fa-history text-gray-200 text-4xl mb-3 block"></i>
        <div class="text-gray-400 italic text-sm font-semibold">Tidak ditemukan transaksi yang cocok</div>
    </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white shadow-sm rounded-sm mb-4">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-blue-900 p-3 text-center w-12">No</th>
                <th class="border border-blue-900 p-3">Tanggal</th>
                <th class="border border-blue-900 p-3">Distributor</th>
                <th class="border border-blue-900 p-3 text-center w-24">Jenis</th>
                <th class="border border-blue-900 p-3 text-right">Nominal</th>
                <th class="border border-blue-900 p-3 text-right bg-amber-400/20 text-blue-900">Saldo Akhir</th>
                <th class="border border-blue-900 p-3">Keterangan</th>
                <th class="border border-blue-900 p-3 text-center w-36">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $key => $row)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $transaksi->firstItem() + $key }}</td>
                <td class="p-3 font-mono text-xs font-bold text-gray-600">{{ $row->tanggal->format('d/m/Y') }}</td>
                <td class="p-3">
                    <div class="font-black text-blue-900 uppercase tracking-tight leading-tight">{{ $row->distributor->nama_distributor }}</div>
                    @if($row->no_referensi)
                        <div class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter mt-0.5">REF: {{ $row->no_referensi }}</div>
                    @endif
                </td>
                <td class="p-3 text-center">
                    @if($row->jenis_transaksi == 'utang')
                        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[9px] font-black uppercase tracking-tighter border border-rose-200">Utang</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase tracking-tighter border border-emerald-200">Bayar</span>
                    @endif
                </td>
                <td class="p-3 text-right font-black">
                    <span class="{{ $row->jenis_transaksi == 'utang' ? 'text-rose-600' : 'text-emerald-700' }}">
                        {{ $row->jenis_transaksi == 'utang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </span>
                </td>
                <td class="p-3 text-right font-black bg-amber-50/30 text-gray-900 border-x border-gray-100">
                    Rp {{ number_format($row->saldo_utang, 0, ',', '.') }}
                </td>
                <td class="p-3 text-gray-500 italic text-[10px] leading-snug">{{ Str::limit($row->keterangan ?? '-', 50) }}</td>
                <td class="p-3">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.distributor.hutang.edit', $row->id_utang_piutang) }}" class="bg-amber-400 border border-amber-500 text-amber-900 px-3 py-1 text-[10px] font-black hover:bg-amber-300 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Edit</a>
                        <form action="{{ route('owner.distributor.hutang.destroy', $row->id_utang_piutang) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-rose-600 text-white px-3 py-1 text-[10px] font-black hover:bg-rose-500 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-16 text-center text-gray-400 italic border border-gray-200 bg-gray-50">
                    <i class="fa fa-history text-gray-100 text-6xl block mb-2"></i>
                    Belum ada riwayat transaksi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transaksi->hasPages())
<div class="mt-4 flex justify-end">
    {{ $transaksi->links() }}
</div>
@endif

@push('scripts')
<script>
    $(document).ready(function() {
        $('select[name="id_distributor"]').select2({
            placeholder: '-- Cari Distributor --',
            allowClear: true,
            width: '100%',
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
