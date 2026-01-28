@extends('layouts.owner')

@section('title', 'Riwayat Pembelian')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-shopping-basket text-blue-700"></i> Riwayat Pembelian
    </h2>
    <a href="{{ route('owner.toko.pembelian.create', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-plus"></i> Input Pembelian
    </a>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3 mb-4">
    @forelse ($pembelians as $index => $pembelian)
    <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 border-blue-500 p-3 shadow-sm rounded-sm">
        <div class="flex justify-between items-start mb-2">
            <div class="flex-1">
                <h3 class="font-black text-sm text-gray-800 mb-1">{{ $pembelian->distributor->nama_distributor }}</h3>
                <p class="text-[10px] font-mono text-blue-600">
                    <i class="fa fa-file-invoice"></i> {{ $pembelian->no_faktur }}
                </p>
            </div>
            <span class="text-[10px] text-gray-500 whitespace-nowrap">
                <i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d M Y') }}
            </span>
        </div>
        
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 border border-amber-800 p-2 rounded-sm mb-2">
            <div class="flex justify-between items-center text-white">
                <span class="font-black uppercase text-[10px]"><i class="fa fa-money-bill-wave"></i> Total Pembelian</span>
                <span class="font-black text-lg">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <a href="{{ route('owner.toko.pembelian.show', [$toko->id_toko, $pembelian->id_pembelian]) }}" class="block text-center bg-blue-600 text-white border border-blue-800 px-3 py-2 text-xs font-bold hover:bg-blue-500 transition-colors rounded-sm uppercase shadow-sm">
            <i class="fa fa-eye"></i> Lihat Detail
        </a>
    </div>
    @empty
    <div class="text-center py-12 bg-white border border-gray-300 rounded-sm">
        <i class="fa fa-shopping-basket text-gray-200 text-5xl block mb-3"></i>
        <p class="text-gray-400 italic text-sm">Belum ada data pembelian</p>
    </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-blue-900 p-3 text-center w-12">No</th>
                <th class="border border-blue-900 p-3">Tanggal</th>
                <th class="border border-blue-900 p-3">No. Faktur</th>
                <th class="border border-blue-900 p-3">Distributor</th>
                <th class="border border-blue-900 p-3 text-right">Total</th>
                <th class="border border-blue-900 p-3 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembelians as $index => $pembelian)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $pembelians->firstItem() + $index }}</td>
                <td class="p-3 text-gray-700">
                    <i class="fa fa-calendar text-blue-500"></i> {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d M Y') }}
                </td>
                <td class="p-3 font-mono text-blue-700 font-bold">{{ $pembelian->no_faktur }}</td>
                <td class="p-3 text-gray-800 font-semibold">{{ $pembelian->distributor->nama_distributor }}</td>
                <td class="p-3 text-right font-mono font-black text-amber-600">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                <td class="p-3 text-center">
                    <a href="{{ route('owner.toko.pembelian.show', [$toko->id_toko, $pembelian->id_pembelian]) }}" class="inline-block px-3 py-1 bg-blue-600 text-white border border-blue-800 rounded-sm hover:bg-blue-500 text-[10px] font-bold transition-colors shadow-sm uppercase">
                        <i class="fa fa-eye"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-8 text-center border border-gray-300">
                    <i class="fa fa-shopping-basket text-gray-200 text-5xl block mb-3"></i>
                    <p class="text-gray-400 italic text-sm">Belum ada data pembelian</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 md:mt-4 text-xs">
    {{ $pembelians->links() }}
</div>
@endsection
