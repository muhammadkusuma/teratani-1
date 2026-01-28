@extends('layouts.owner')

@section('title', 'Data Pelanggan')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-users text-blue-700"></i> Daftar Pelanggan
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.pelanggan.create') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-plus"></i> Tambah Pelanggan
        </a>
    </div>
</div>

<form method="GET" action="{{ route('owner.pelanggan.index') }}" class="mb-3 md:mb-4 flex flex-col sm:flex-row gap-2">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / No HP..." class="flex-1 border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
    <button type="submit" class="px-4 py-2.5 md:py-2 bg-gray-600 text-white border border-gray-800 text-xs font-bold hover:bg-gray-500 transition-all shadow-md rounded-sm uppercase">
        <i class="fa fa-search"></i> Cari
    </button>
</form>

@if(session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3 mb-4">
    @forelse($pelanggan as $key => $row)
    <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 border-blue-500 p-3 shadow-sm rounded-sm">
        <div class="flex justify-between items-start mb-2">
            <div class="flex-1">
                <h3 class="font-black text-sm text-gray-800">{{ $row->nama_pelanggan }}</h3>
                <p class="text-[10px] font-mono text-gray-500">{{ $row->kode_pelanggan }}</p>
            </div>
            <span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-bold">#{{ $pelanggan->firstItem() + $key }}</span>
        </div>
        
        <div class="space-y-1 text-xs text-gray-600 mb-3">
            <p><i class="fa fa-phone text-emerald-500 w-4"></i> {{ $row->no_hp ?? '-' }}</p>
            <p><i class="fa fa-map-marker-alt text-blue-400 w-4"></i> {{ $row->wilayah ?? '-' }}</p>
            <p><i class="fa fa-credit-card text-amber-500 w-4"></i> Limit: <span class="font-bold font-mono">Rp {{ number_format($row->limit_piutang, 0, ',', '.') }}</span></p>
        </div>
        
        <div class="grid grid-cols-3 gap-1 pt-2 border-t border-gray-200">
            <a href="{{ route('owner.pelanggan.show', $row->id_pelanggan) }}" class="text-center bg-blue-600 text-white border border-blue-800 px-2 py-1.5 text-[10px] font-bold hover:bg-blue-500 transition-colors rounded-sm uppercase shadow-sm">
                <i class="fa fa-eye"></i> Lihat
            </a>
            <a href="{{ route('owner.pelanggan.edit', $row->id_pelanggan) }}" class="text-center bg-amber-400 text-black border border-amber-600 px-2 py-1.5 text-[10px] font-bold hover:bg-amber-300 transition-colors rounded-sm uppercase shadow-sm">
                <i class="fa fa-edit"></i> Edit
            </a>
            <form action="{{ route('owner.pelanggan.destroy', $row->id_pelanggan) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 text-white border border-red-800 px-2 py-1.5 text-[10px] font-bold hover:bg-red-500 transition-colors rounded-sm uppercase shadow-sm">
                    <i class="fa fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-12 bg-white border border-gray-300 rounded-sm">
        <i class="fa fa-users text-gray-200 text-5xl block mb-3"></i>
        <p class="text-gray-400 italic text-sm">Belum ada data pelanggan</p>
    </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-blue-900 p-3 text-center w-12">No</th>
                <th class="border border-blue-900 p-3">Kode</th>
                <th class="border border-blue-900 p-3">Nama Pelanggan</th>
                <th class="border border-blue-900 p-3">No HP</th>
                <th class="border border-blue-900 p-3">Wilayah</th>
                <th class="border border-blue-900 p-3 text-right">Limit Piutang</th>
                <th class="border border-blue-900 p-3 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggan as $key => $row)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $pelanggan->firstItem() + $key }}</td>
                <td class="p-3 font-mono text-xs text-blue-700 font-bold tracking-tighter">{{ $row->kode_pelanggan }}</td>
                <td class="p-3 font-black text-gray-800">{{ $row->nama_pelanggan }}</td>
                <td class="p-3 text-gray-600">
                    <i class="fa fa-phone text-emerald-500"></i> {{ $row->no_hp ?? '-' }}
                </td>
                <td class="p-3 text-gray-600">{{ $row->wilayah ?? '-' }}</td>
                <td class="p-3 text-right font-mono text-amber-600 font-bold">
                    Rp {{ number_format($row->limit_piutang, 0, ',', '.') }}
                </td>
                <td class="p-3 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.pelanggan.show', $row->id_pelanggan) }}" class="bg-blue-600 text-white border border-blue-800 px-2 py-1 text-[10px] font-bold hover:bg-blue-500 transition-colors rounded-sm uppercase shadow-sm">
                            <i class="fa fa-eye"></i> Lihat
                        </a>
                        <a href="{{ route('owner.pelanggan.edit', $row->id_pelanggan) }}" class="bg-amber-400 text-black border border-amber-600 px-2 py-1 text-[10px] font-bold hover:bg-amber-300 transition-colors rounded-sm uppercase shadow-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('owner.pelanggan.destroy', $row->id_pelanggan) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white border border-red-800 px-2 py-1 text-[10px] font-bold hover:bg-red-500 transition-colors rounded-sm uppercase shadow-sm">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center border border-gray-300">
                    <i class="fa fa-users text-gray-200 text-5xl block mb-3"></i>
                    <p class="text-gray-400 italic text-sm">Belum ada data pelanggan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 md:mt-4 text-xs">
    {{ $pelanggan->links() }}
</div>
@endsection