@extends('layouts.owner')

@section('title', 'Retur Penjualan')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-undo text-blue-700"></i> Retur Penjualan
    </h2>
    <a href="{{ route('owner.retur-penjualan.create') }}" class="w-full md:w-auto text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase tracking-wider">
        <i class="fa fa-plus"></i> Buat Retur Baru
    </a>
</div>

@if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-3 mb-4 shadow-sm text-xs flex items-center gap-2">
        <i class="fa fa-check-circle text-emerald-600 text-sm"></i> 
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
    <div class="bg-gradient-to-br from-blue-50 to-white border-l-4 border-blue-600 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-blue-800 font-black uppercase tracking-widest mb-1 font-black">Total Nilai Retur</div>
        <div class="text-2xl font-black text-blue-900 leading-none">Rp {{ number_format($summary['total_value'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-gradient-to-br from-emerald-50 to-white border-l-4 border-emerald-600 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-emerald-800 font-black uppercase tracking-widest mb-1 font-black">Total Transaksi</div>
        <div class="text-2xl font-black text-emerald-900 leading-none">{{ number_format($summary['total_count'], 0, ',', '.') }} <span class="text-xs font-normal">Data</span></div>
    </div>
    <div class="bg-gradient-to-br from-amber-50 to-white border-l-4 border-amber-600 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-amber-800 font-black uppercase tracking-widest mb-1 font-black">Retur Hari Ini</div>
        <div class="text-2xl font-black text-amber-900 leading-none">Rp {{ number_format($summary['today_value'], 0, ',', '.') }}</div>
    </div>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-4 mb-4">
    @forelse($returs as $retur)
    <div class="bg-white border-t-4 border-blue-500 p-4 shadow-lg rounded-sm relative active:scale-[0.98] transition-all">
        <div class="flex justify-between items-start mb-2">
            <div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $retur->tgl_retur->format('d M Y') }}</div>
                <h3 class="font-black text-sm text-blue-900 tracking-tight leading-tight">{{ $retur->pelanggan->nama_pelanggan }}</h3>
            </div>
            <div>
                <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm">
                    {{ $retur->status_retur }}
                </span>
            </div>
        </div>
        
        <div class="border-y border-gray-100 py-3 mb-3">
            <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Total Nilai Retur</div>
            <div class="text-lg font-black text-blue-700">
                Rp {{ number_format($retur->total_retur, 0, ',', '.') }}
            </div>
            @if($retur->keterangan)
                <div class="text-[10px] text-gray-500 mt-2 bg-gray-50 p-2 rounded italic border-l-2 border-gray-200">
                    "{{ Str::limit($retur->keterangan, 100) }}"
                </div>
            @endif
        </div>

        <a href="{{ route('owner.retur-penjualan.show', $retur->id_retur_penjualan) }}" class="block w-full text-center bg-gray-100 border border-gray-300 text-gray-700 py-2.5 text-xs font-black rounded-sm uppercase tracking-widest hover:bg-gray-200 transition-all">
            <i class="fa fa-eye"></i> Lihat Detail
        </a>
    </div>
    @empty
    <div class="bg-white border border-gray-300 p-8 text-center rounded-sm shadow-sm">
        <i class="fa fa-history text-gray-200 text-4xl mb-3 block"></i>
        <div class="text-gray-400 italic text-sm font-semibold">Belum ada riwayat retur</div>
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
                <th class="border border-blue-900 p-3">Pelanggan</th>
                <th class="border border-blue-900 p-3 text-right">Total Retur</th>
                <th class="border border-blue-900 p-3 text-center w-32">Status</th>
                <th class="border border-blue-900 p-3 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returs as $index => $retur)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $returs->firstItem() + $index }}</td>
                <td class="p-3 font-mono text-xs font-bold text-gray-600">{{ $retur->tgl_retur->format('d M Y') }}</td>
                <td class="p-3">
                    <div class="font-black text-blue-900 uppercase tracking-tight leading-tight">{{ $retur->pelanggan->nama_pelanggan }}</div>
                    @if($retur->user)
                        <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-0.5">Admin: {{ $retur->user->nama_lengkap }}</div>
                    @endif
                </td>
                <td class="p-3 text-right font-black text-blue-700">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
                <td class="p-3 text-center">
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase tracking-tighter border border-emerald-200 shadow-sm">
                        {{ $retur->status_retur }}
                    </span>
                </td>
                <td class="p-3">
                    <div class="flex justify-center">
                        <a href="{{ route('owner.retur-penjualan.show', $retur->id_retur_penjualan) }}" class="bg-blue-600 text-white px-4 py-1.5 text-[10px] font-black hover:bg-blue-500 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">
                            <i class="fa fa-eye"></i> Detail
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-16 text-center text-gray-400 italic border border-gray-200 bg-gray-50">
                    <i class="fa fa-history text-gray-100 text-6xl block mb-2"></i>
                    Belum ada riwayat retur penjualan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 flex justify-end">
    {{ $returs->links() }}
</div>
@endsection
