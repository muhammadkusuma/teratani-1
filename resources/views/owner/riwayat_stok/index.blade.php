@extends('layouts.owner')

@section('title', 'Riwayat Stok')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-indigo-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-history text-indigo-700"></i> Riwayat Keluar Masuk
    </h2>
    <a href="{{ route('owner.riwayat-stok.create') }}" class="w-full md:w-auto text-center px-4 py-2 bg-amber-600 text-white border border-amber-800 shadow-md hover:bg-amber-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-exchange-alt"></i> Penyesuaian Stok
    </a>
</div>

{{-- Filter Form --}}
<div class="bg-white border border-gray-300 p-4 mb-4 shadow-sm rounded-sm">
    <form action="" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                <i class="fa fa-calendar-alt"></i> Dari Tanggal
            </label>
            <input type="date" name="start_date" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm" value="{{ request('start_date') }}">
        </div>
        <div>
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                <i class="fa fa-calendar-check"></i> Sampai Tanggal
            </label>
            <input type="date" name="end_date" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm" value="{{ request('end_date') }}">
        </div>
        <div>
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                <i class="fa fa-filter"></i> Jenis
            </label>
            <select name="jenis" class="w-full border border-gray-300 p-2 text-xs bg-white shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm">
                <option value="">Semua Jenis</option>
                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div class="flex gap-2 items-end">
            <button type="submit" class="flex-1 px-4 py-2 bg-indigo-700 text-white border border-indigo-900 shadow-md hover:bg-indigo-600 text-xs font-bold transition-all rounded-sm uppercase">
                <i class="fa fa-search"></i> Filter
            </button>
            <a href="{{ route('owner.riwayat-stok.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 border border-gray-400 shadow-sm hover:bg-gray-300 text-xs font-bold transition-all rounded-sm uppercase">
                <i class="fa fa-redo"></i>
            </a>
        </div>
    </form>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3 mb-4">
    @forelse ($riwayats as $index => $riwayat)
    <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 {{ $riwayat->jenis == 'masuk' ? 'border-emerald-500' : 'border-red-500' }} p-3 shadow-sm rounded-sm">
        <div class="flex justify-between items-start mb-2">
            <div class="flex-1">
                <h3 class="font-black text-sm text-gray-800">{{ $riwayat->produk->nama_produk }}</h3>
                <p class="text-[10px] font-mono text-indigo-600">{{ $riwayat->produk->sku }}</p>
            </div>
            @if($riwayat->jenis == 'masuk')
                <span class="px-2 py-1 bg-emerald-200 text-emerald-800 border border-emerald-500 rounded text-[10px] font-bold whitespace-nowrap">
                    <i class="fa fa-arrow-down"></i> MASUK
                </span>
            @else
                <span class="px-2 py-1 bg-red-200 text-red-800 border border-red-500 rounded text-[10px] font-bold whitespace-nowrap">
                    <i class="fa fa-arrow-up"></i> KELUAR
                </span>
            @endif
        </div>
        
        <div class="grid grid-cols-2 gap-2 mb-2">
            <div class="bg-blue-50 border border-blue-200 p-2 rounded-sm">
                <span class="text-[10px] text-blue-700 uppercase font-bold block mb-1">
                    <i class="fa fa-calendar"></i> Tanggal
                </span>
                <div class="text-xs font-bold text-blue-900">{{ \Carbon\Carbon::parse($riwayat->tanggal)->format('d/m/Y H:i') }}</div>
            </div>
            <div class="bg-{{ $riwayat->jenis == 'masuk' ? 'emerald' : 'red' }}-50 border border-{{ $riwayat->jenis == 'masuk' ? 'emerald' : 'red' }}-200 p-2 rounded-sm">
                <span class="text-[10px] text-{{ $riwayat->jenis == 'masuk' ? 'emerald' : 'red' }}-700 uppercase font-bold block mb-1">
                    <i class="fa fa-boxes"></i> Jumlah
                </span>
                <div class="font-black text-xl text-{{ $riwayat->jenis == 'masuk' ? 'emerald' : 'red' }}-900">{{ number_format($riwayat->jumlah) }}</div>
            </div>
        </div>
        
        <div class="space-y-1 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-gray-500 uppercase w-16">Lokasi:</span>
                @if($riwayat->id_gudang)
                    <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-[10px] border border-purple-300 font-bold">
                        <i class="fa fa-warehouse"></i> {{ $riwayat->gudang->nama_gudang }}
                    </span>
                @elseif($riwayat->id_toko)
                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-[10px] border border-blue-300 font-bold">
                        <i class="fa fa-store"></i> {{ $riwayat->toko->nama_toko }}
                    </span>
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </div>
            @if($riwayat->keterangan)
                <div class="flex items-start gap-2">
                    <span class="text-[10px] font-bold text-gray-500 uppercase w-16 flex-shrink-0">Ket:</span>
                    <span class="text-gray-700">{{ $riwayat->keterangan }}</span>
                </div>
            @endif
            @if($riwayat->referensi)
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-500 uppercase w-16">Ref:</span>
                    <span class="font-mono text-[10px] text-gray-600">{{ $riwayat->referensi }}</span>
                </div>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-12 bg-white border border-gray-300 rounded-sm">
        <i class="fa fa-history text-gray-200 text-5xl block mb-3"></i>
        <p class="text-gray-400 italic text-sm">Belum ada data riwayat stok</p>
    </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-indigo-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-indigo-900 p-3 text-center w-12">No</th>
                <th class="border border-indigo-900 p-3">Tanggal</th>
                <th class="border border-indigo-900 p-3">Produk</th>
                <th class="border border-indigo-900 p-3 text-center w-24">Jenis</th>
                <th class="border border-indigo-900 p-3 text-center w-24">Jumlah</th>
                <th class="border border-indigo-900 p-3">Lokasi</th>
                <th class="border border-indigo-900 p-3">Keterangan</th>
                <th class="border border-indigo-900 p-3">Referensi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayats as $index => $riwayat)
            <tr class="hover:bg-indigo-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $riwayats->firstItem() + $index }}</td>
                <td class="p-3 text-gray-700 whitespace-nowrap">
                    <i class="fa fa-calendar text-indigo-500"></i> {{ \Carbon\Carbon::parse($riwayat->tanggal)->format('d/m/Y H:i') }}
                </td>
                <td class="p-3">
                    <div class="font-bold text-gray-800">{{ $riwayat->produk->nama_produk }}</div>
                    <div class="text-[10px] font-mono text-indigo-600">{{ $riwayat->produk->sku }}</div>
                </td>
                <td class="p-3 text-center">
                    @if($riwayat->jenis == 'masuk')
                        <span class="px-2 py-1 bg-emerald-200 text-emerald-800 border border-emerald-400 rounded text-[10px] font-bold">
                            <i class="fa fa-arrow-down"></i> MASUK
                        </span>
                    @else
                        <span class="px-2 py-1 bg-red-200 text-red-800 border border-red-400 rounded text-[10px] font-bold">
                            <i class="fa fa-arrow-up"></i> KELUAR
                        </span>
                    @endif
                </td>
                <td class="p-3 text-center font-black font-mono text-{{ $riwayat->jenis == 'masuk' ? 'emerald' : 'red' }}-700 text-lg">
                    {{ number_format($riwayat->jumlah) }}
                </td>
                <td class="p-3">
                    @if($riwayat->id_gudang)
                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-[10px] border border-purple-300 font-bold inline-block">
                            <i class="fa fa-warehouse"></i> {{ $riwayat->gudang->nama_gudang }}
                        </span>
                    @elseif($riwayat->id_toko)
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-[10px] border border-blue-300 font-bold inline-block">
                            <i class="fa fa-store"></i> {{ $riwayat->toko->nama_toko }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="p-3 text-gray-700">{{ $riwayat->keterangan }}</td>
                <td class="p-3 font-mono text-[10px] text-gray-600">{{ $riwayat->referensi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-8 text-center border border-gray-300">
                    <i class="fa fa-history text-gray-200 text-5xl block mb-3"></i>
                    <p class="text-gray-400 italic text-sm">Belum ada data riwayat stok</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 md:mt-4 text-xs">
    {{ $riwayats->links() }}
</div>
@endsection
