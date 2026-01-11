@extends('layouts.owner')

@section('title', 'Dashboard Ringkasan')

@section('content')
<div class="h-full flex flex-col p-2 gap-2 overflow-hidden bg-slate-100">

    {{-- BARIS 1: KARTU STATISTIK --}}
    <div class="grid grid-cols-4 gap-2 h-24 flex-shrink-0">
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-3 shadow-sm flex flex-col justify-between border-l-4 border-yellow-400">
            <div class="text-[10px] uppercase tracking-wider opacity-80">Omset Hari Ini</div>
            <div class="text-2xl font-bold">Rp {{ number_format($omset_hari_ini, 0, ',', '.') }}</div>
            <div class="text-[9px] flex items-center gap-1">
                <i class="fas fa-chart-line"></i> 
                <span>{{ $transaksi_hari_ini }} Transaksi terjadi</span>
            </div>
        </div>

        <div class="bg-white p-3 shadow-sm border border-slate-200 flex flex-col justify-between border-b-2 border-blue-500">
            <div class="flex justify-between items-start">
                <div class="text-[10px] text-slate-500 uppercase font-bold">Unit Bisnis</div>
                <div class="bg-blue-100 text-blue-700 p-1 rounded-sm"><i class="fas fa-store text-xs"></i></div>
            </div>
            <div class="text-2xl font-bold text-slate-700">{{ $total_toko }} <span class="text-[10px] font-normal text-slate-400">Unit</span></div>
        </div>

        <div class="bg-white p-3 shadow-sm border border-slate-200 flex flex-col justify-between border-b-2 border-teal-500">
            <div class="flex justify-between items-start">
                <div class="text-[10px] text-slate-500 uppercase font-bold">Total Produk</div>
                <div class="bg-teal-100 text-teal-700 p-1 rounded-sm"><i class="fas fa-box text-xs"></i></div>
            </div>
            <div class="text-2xl font-bold text-slate-700">{{ $total_produk }} <span class="text-[10px] font-normal text-slate-400">SKU</span></div>
        </div>

        <div class="bg-slate-800 text-slate-200 p-3 shadow-sm flex flex-col justify-center gap-2">
            <a href="{{ route('owner.toko.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white py-1 px-2 text-center text-[10px] font-bold uppercase transition">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Toko
            </a>
            <div class="text-[9px] text-center text-slate-400">Shortcut Owner</div>
        </div>
    </div>

    {{-- BARIS 2: KONTEN UTAMA --}}
    <div class="flex-1 grid grid-cols-3 gap-2 overflow-hidden min-h-0">
        
        {{-- KOLOM KIRI: Grafik & Top Produk --}}
        <div class="col-span-2 flex flex-col gap-2 h-full">
            
            {{-- Grafik --}}
            <div class="flex-1 bg-white border border-slate-300 shadow-sm p-3 flex flex-col">
                <h3 class="text-[11px] font-bold text-slate-700 mb-3 border-l-4 border-blue-600 pl-2 uppercase">Tren Penjualan (7 Hari)</h3>
                <div class="flex-1 flex items-end gap-2 border-b border-l border-slate-200 p-2 pb-0">
                    @php $maxChart = collect($chart_data)->max('total') ?: 1; @endphp
                    @foreach($chart_data as $d)
                        <div class="flex-1 flex flex-col items-center group">
                            <div class="relative w-full bg-blue-100 hover:bg-blue-200 transition-all duration-300 flex items-end justify-center rounded-t-sm" 
                                 style="height: {{ ($d['total'] / $maxChart) * 100 }}%;">
                                <div class="opacity-0 group-hover:opacity-100 absolute -top-6 bg-black text-white text-[9px] px-1 py-0.5 rounded transition">
                                    {{ number_format($d['total']/1000, 0) }}k
                                </div>
                                <div class="bg-blue-500 w-full h-1"></div>
                            </div>
                            <span class="text-[9px] mt-1 text-slate-500 font-bold">{{ $d['hari'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Produk --}}
            <div class="h-40 bg-white border border-slate-300 shadow-sm p-2 overflow-auto">
                <h3 class="text-[11px] font-bold text-slate-700 mb-2 border-l-4 border-orange-500 pl-2 uppercase">
                    <i class="fas fa-crown text-yellow-500 mr-1"></i> Top 5 Produk Bulan Ini
                </h3>
                <table class="w-full text-[10px] text-left">
                    <thead class="bg-slate-100 text-slate-600 border-b border-slate-300">
                        <tr>
                            <th class="p-1">Produk</th>
                            <th class="p-1 text-right">Terjual</th>
                            <th class="p-1 w-1/3">Popularitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($produk_terlaris as $p)
                            <tr>
                                <td class="p-1 font-semibold text-slate-700 truncate max-w-[150px]">{{ $p->nama_produk }}</td>
                                <td class="p-1 text-right font-bold text-blue-700">{{ $p->total_terjual }}</td>
                                <td class="p-1">
                                    <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-orange-400" style="width: {{ min(100, $p->total_terjual * 2) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-2 text-center text-slate-400 italic">Belum ada data penjualan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- KOLOM KANAN: Alert Stok --}}
        <div class="col-span-1 bg-white border border-red-200 shadow-sm flex flex-col h-full">
            <div class="bg-red-50 p-2 border-b border-red-200 flex justify-between items-center">
                <h3 class="text-[11px] font-bold text-red-800 uppercase flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Stok Menipis
                </h3>
                <span class="bg-red-200 text-red-800 text-[9px] px-1 font-bold rounded">{{ count($stok_menipis) }}</span>
            </div>
            
            <div class="flex-1 overflow-auto p-0 bg-white">
                <table class="w-full text-[10px]">
                    <thead class="bg-slate-50 text-slate-500 border-b">
                        <tr>
                            <th class="p-2 text-left">Produk / Toko</th>
                            <th class="p-2 text-right">Sisa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50">
                        @forelse($stok_menipis as $s)
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-2">
                                    <div class="font-bold text-slate-700">{{ $s->nama_produk }}</div>
                                    <div class="text-[9px] text-slate-500 uppercase"><i class="fas fa-store mr-1"></i> {{ $s->nama_toko }}</div>
                                </td>
                                <td class="p-2 text-right">
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-bold border border-red-200">
                                        {{ $s->sisa_stok }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-4 text-center text-green-600">
                                    <i class="fas fa-check-circle text-2xl mb-1 block"></i>
                                    Stok Aman
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-2 bg-slate-50 border-t border-slate-200 text-center">
                <a href="#" class="text-[10px] text-blue-600 hover:underline">Lihat Inventory &rarr;</a>
            </div>
        </div>

    </div>

</div>
@endsection