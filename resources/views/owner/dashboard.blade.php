@extends('layouts.owner')

@section('title', 'Dashboard Ringkasan')

@section('content')
<div class="h-full flex flex-col p-2 gap-2 overflow-hidden bg-slate-100">

    
    <div class="flex-shrink-0">
        <div class="bg-blue-50 border border-blue-200 text-blue-800 text-xs px-3 py-2 rounded flex justify-between items-center shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fas fa-store text-blue-600"></i> 
                <span>Menampilkan data untuk: <strong>{{ $nama_toko_aktif }}</strong></span>
            </span>
            @if(session('toko_active_id'))
                <span class="text-[10px] bg-blue-200 px-2 py-0.5 rounded-full text-blue-800">Mode Filter Aktif</span>
            @else
                <div class="flex items-center gap-2">
                    <span class="text-[10px] bg-slate-200 px-2 py-0.5 rounded-full text-slate-600">Mode Global</span>
                    <a href="{{ route('owner.toko.index') }}" class="text-[10px] bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-2 py-0.5 rounded font-bold transition shadow-sm border border-yellow-500 no-underline">
                        <i class="fas fa-exchange-alt mr-1"></i> Pilih Toko
                    </a>
                </div>
            @endif
        </div>
    </div>

    
    <div class="grid grid-cols-4 gap-2 h-24 flex-shrink-0">
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-3 shadow-sm flex flex-col justify-between border-l-4 border-yellow-400 rounded-sm">
            <div class="text-[10px] uppercase tracking-wider opacity-80">Omset Hari Ini</div>
            <div class="text-2xl font-bold">Rp {{ number_format($omset_hari_ini, 0, ',', '.') }}</div>
            <div class="text-[9px] flex items-center gap-1">
                <i class="fas fa-chart-line"></i> 
                <span>{{ $transaksi_hari_ini }} Transaksi terjadi</span>
            </div>
        </div>

        <div class="bg-white p-3 shadow-sm border border-slate-200 flex flex-col justify-between border-b-2 border-blue-500 rounded-sm">
            <div class="flex justify-between items-start">
                <div class="text-[10px] text-slate-500 uppercase font-bold">Unit Bisnis</div>
                <div class="bg-blue-100 text-blue-700 p-1 rounded-sm"><i class="fas fa-store text-xs"></i></div>
            </div>
            <div class="text-2xl font-bold text-slate-700">{{ $total_toko }} <span class="text-[10px] font-normal text-slate-400">Unit</span></div>
        </div>

        <div class="bg-white p-3 shadow-sm border border-slate-200 flex flex-col justify-between border-b-2 border-teal-500 rounded-sm">
            <div class="flex justify-between items-start">
                <div class="text-[10px] text-slate-500 uppercase font-bold">Total Produk</div>
                <div class="bg-teal-100 text-teal-700 p-1 rounded-sm"><i class="fas fa-box text-xs"></i></div>
            </div>
            <div class="text-2xl font-bold text-slate-700">{{ $total_produk }} <span class="text-[10px] font-normal text-slate-400">SKU</span></div>
        </div>

        <div class="bg-slate-800 text-slate-200 p-3 shadow-sm flex flex-col justify-center gap-2 rounded-sm">
            <a href="{{ route('owner.toko.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white py-1 px-2 text-center text-[10px] font-bold uppercase transition rounded">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Toko
            </a>
            <div class="text-[9px] text-center text-slate-400">Shortcut Owner</div>
        </div>
    </div>

    
    <div class="flex-1 grid grid-cols-3 gap-2 overflow-hidden min-h-0">
        
        
        <div class="col-span-2 flex flex-col gap-2 h-full">
            
            
            <div class="flex-1 bg-white border border-slate-300 shadow-sm p-3 flex flex-col rounded-sm">
                <h3 class="text-[11px] font-bold text-slate-700 mb-3 border-l-4 border-blue-600 pl-2 uppercase">Tren Penjualan (7 Hari)</h3>
                <div class="flex-1 flex items-end gap-2 border-b border-l border-slate-200 p-2 pb-0">
                    @php $maxChart = collect($chart_data)->max('total') ?: 1; @endphp
                    @foreach($chart_data as $d)
                        <div class="flex-1 flex flex-col items-center group">
                            <div class="relative w-full bg-blue-100 hover:bg-blue-200 transition-all duration-300 flex items-end justify-center rounded-t-sm" 
                                 style="height: {{ ($d['total'] / $maxChart) * 100 }}%;">
                                <div class="opacity-0 group-hover:opacity-100 absolute -top-6 bg-black text-white text-[9px] px-1 py-0.5 rounded transition whitespace-nowrap z-10">
                                    Rp {{ number_format($d['total']/1000, 0) }}k
                                </div>
                                <div class="bg-blue-500 w-full h-1"></div>
                            </div>
                            <span class="text-[9px] mt-1 text-slate-500 font-bold">{{ $d['hari'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            
            <div class="h-40 bg-white border border-slate-300 shadow-sm p-2 overflow-auto rounded-sm">
                <h3 class="text-[11px] font-bold text-slate-700 mb-2 border-l-4 border-orange-500 pl-2 uppercase">
                    <i class="fas fa-crown text-yellow-500 mr-1"></i> Top 5 Produk Bulan Ini
                </h3>
                <table class="w-full text-[10px] text-left">
                    <thead class="bg-slate-100 text-slate-600 border-b border-slate-300">
                        <tr>
                            <th class="p-1 pl-2">Produk</th>
                            <th class="p-1 text-right">Terjual</th>
                            <th class="p-1 w-1/3 pr-2">Popularitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($produk_terlaris as $p)
                            <tr>
                                <td class="p-1 pl-2 font-semibold text-slate-700 truncate max-w-[150px]">{{ $p->nama_produk }}</td>
                                <td class="p-1 text-right font-bold text-blue-700">{{ $p->total_terjual }}</td>
                                <td class="p-1 pr-2">
                                    <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-orange-400" style="width: {{ min(100, $p->total_terjual * 2) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-2 text-center text-slate-400 italic">Belum ada data penjualan bulan ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        
        <div class="col-span-1 bg-white border border-red-200 shadow-sm flex flex-col h-full rounded-sm">
            <div class="bg-red-50 p-2 border-b border-red-200 flex justify-between items-center rounded-t-sm">
                <h3 class="text-[11px] font-bold text-red-800 uppercase flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Stok Menipis (<=10)
                </h3>
                <span class="bg-red-200 text-red-800 text-[9px] px-2 py-0.5 font-bold rounded-full">{{ count($stok_menipis) }}</span>
            </div>
            
            <div class="flex-1 overflow-auto p-0 bg-white">
                <table class="w-full text-[10px]">
                    <thead class="bg-slate-50 text-slate-500 border-b sticky top-0">
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
                                    <div class="text-[9px] text-slate-500 uppercase flex items-center gap-1">
                                        <i class="fas fa-store text-slate-400"></i> {{ $s->nama_toko }}
                                    </div>
                                </td>
                                <td class="p-2 text-right">
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-bold border border-red-200">
                                        {{ $s->sisa_stok }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-6 text-center text-green-600">
                                    <i class="fas fa-check-circle text-3xl mb-2 block opacity-50"></i>
                                    <span class="font-bold">Stok Aman</span>
                                    <div class="text-[9px] text-slate-400 mt-1">Tidak ada produk dengan stok <= 10</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
        </div>

    </div>

</div>

{{-- Store Selection Popup --}}
@if(!session('toko_active_id'))
<div id="storeSelectionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-300 border-4 border-gray-400 shadow-2xl max-w-2xl w-full mx-4" style="border-style: outset;">
        {{-- Title Bar --}}
        <div class="bg-gradient-to-r from-blue-900 to-blue-600 text-white px-3 py-2 flex justify-between items-center">
            <span class="font-bold text-base flex items-center gap-2">
                <i class="fa fa-store"></i> PILIH TOKO AKTIF
            </span>
        </div>

        {{-- Content --}}
        <div class="p-4">
            <div class="bg-yellow-100 border-2 border-yellow-600 p-3 mb-4">
                <p class="text-sm font-bold text-yellow-900">
                    <i class="fa fa-info-circle mr-2"></i>
                    Silakan pilih toko yang ingin Anda kelola. Anda dapat mengubahnya kapan saja melalui menu Toko.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-3 max-h-96 overflow-y-auto pr-2">
                @foreach($userStores as $store)
                <a href="{{ route('owner.toko.select', $store->id_toko) }}" 
                   class="block bg-white border-2 border-gray-400 p-4 hover:bg-blue-50 hover:border-blue-600 transition-all cursor-pointer no-underline"
                   style="border-style: outset;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-base font-bold text-gray-800 m-0">{{ $store->nama_toko }}</h3>
                                @if($store->is_pusat)
                                    <span class="bg-blue-600 text-white px-2 py-1 text-xs font-bold">PUSAT</span>
                                @endif
                                @if($store->is_active)
                                    <span class="bg-green-500 text-white px-2 py-1 text-xs font-bold">AKTIF</span>
                                @else
                                    <span class="bg-red-500 text-white px-2 py-1 text-xs font-bold">TUTUP</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-700">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fa fa-barcode text-gray-500 w-4"></i>
                                    <span class="font-mono">{{ $store->kode_toko }}</span>
                                </div>
                                @if($store->alamat)
                                <div class="flex items-start gap-2 mb-1">
                                    <i class="fa fa-map-marker-alt text-gray-500 w-4 mt-1"></i>
                                    <span>{{ $store->alamat }}, {{ $store->kota ?? '-' }}</span>
                                </div>
                                @endif
                                @if($store->no_telp)
                                <div class="flex items-center gap-2">
                                    <i class="fa fa-phone text-gray-500 w-4"></i>
                                    <span>{{ $store->no_telp }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            <i class="fa fa-chevron-right text-2xl text-gray-400"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            @if($userStores->isEmpty())
            <div class="bg-red-100 border-2 border-red-600 p-6 text-center">
                <i class="fa fa-exclamation-triangle text-4xl text-red-600 mb-3"></i>
                <p class="text-base font-bold text-red-900 mb-2">Tidak ada toko tersedia</p>
                <p class="text-sm text-red-700">Silakan hubungi administrator untuk menambahkan toko.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

@endsection