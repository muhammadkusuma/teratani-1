@extends('layouts.owner')

@section('title', 'Daftar Distributor')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-truck text-blue-700"></i> Distributor & Supplier
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.distributor.hutang.index') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-emerald-600 text-white border border-emerald-800 shadow-md hover:bg-emerald-500 text-xs font-bold transition-all rounded-sm uppercase">
            <i class="fa fa-money-bill-wave"></i> Kelola Hutang
        </a>
        <a href="{{ route('owner.distributor.create') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase">
            <i class="fa fa-plus"></i> Tambah Baru
        </a>
    </div>
</div>

<div class="bg-white border border-gray-300 p-4 mb-4 shadow-sm rounded-sm">
    <form method="GET" action="{{ route('owner.distributor.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Cari Distributor</label>
            <div class="relative">
                <i class="fa fa-search absolute left-2 top-2 text-gray-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Masukkan nama, kode, atau perusahaan..." class="w-full border border-gray-300 pl-8 pr-2 py-1.5 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all">
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Pilih Toko</label>
            <select name="id_toko" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all bg-gray-50">
                <option value="">-- Semua Toko --</option>
                @foreach($userStores as $store)
                    <option value="{{ $store->id_toko }}" {{ request('id_toko') == $store->id_toko ? 'selected' : '' }}>
                        {{ $store->nama_toko }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 md:flex-none bg-blue-600 text-white border border-blue-800 px-6 py-1.5 text-xs font-bold hover:bg-blue-500 transition-colors shadow-sm uppercase">
                <i class="fa fa-filter"></i> Filter
            </button>
            <a href="{{ route('owner.distributor.index') }}" class="flex-1 md:flex-none bg-gray-100 text-gray-700 border border-gray-300 px-6 py-1.5 text-xs font-bold hover:bg-gray-200 transition-colors text-center shadow-sm uppercase">
                <i class="fa fa-sync-alt"></i> Reset
            </a>
        </div>
    </form>
</div>

<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-4">
    <div class="bg-gradient-to-br from-blue-50 to-white border-l-4 border-blue-600 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-blue-800 font-black uppercase tracking-widest mb-1">Total (Filter)</div>
        <div class="text-2xl font-black text-blue-900 leading-none">{{ $summary['total'] }}</div>
    </div>
    <div class="bg-gradient-to-br from-emerald-50 to-white border-l-4 border-emerald-600 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-emerald-800 font-black uppercase tracking-widest mb-1">Aktif (Filter)</div>
        <div class="text-2xl font-black text-emerald-900 leading-none">{{ $summary['aktif'] }}</div>
    </div>
    <div class="bg-gradient-to-br from-rose-50 to-white border-l-4 border-rose-600 p-4 shadow-sm rounded-r-md text-nowrap">
        <div class="text-[10px] text-rose-800 font-black uppercase tracking-widest mb-1">Non-Aktif</div>
        <div class="text-2xl font-black text-rose-900 leading-none">{{ $summary['non_aktif'] }}</div>
    </div>
    <div class="bg-gradient-to-br from-amber-50 to-white border-l-4 border-amber-500 p-4 shadow-sm rounded-r-md">
        <div class="text-[10px] text-amber-800 font-black uppercase tracking-widest mb-1">Jumlah Toko</div>
        <div class="text-2xl font-black text-amber-900 leading-none">{{ $summary['toko'] }}</div>
    </div>
    <div class="bg-gradient-to-br from-indigo-50 to-white border-l-4 border-indigo-600 p-4 shadow-sm rounded-r-md col-span-2 lg:col-span-1">
        <div class="text-[10px] text-indigo-800 font-black uppercase tracking-widest mb-1">Baru (Bulan Ini)</div>
        <div class="text-2xl font-black text-indigo-900 leading-none">{{ $summary['baru'] }}</div>
    </div>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-4">
    @forelse($distributors as $row)
    <div class="bg-white border-t-4 border-blue-600 p-4 shadow-lg rounded-sm relative group active:scale-[0.98] transition-all">
        <div class="flex justify-between items-start mb-3">
            <div>
                <h3 class="font-black text-sm text-blue-900 tracking-tight leading-tight mb-1">{{ $row->nama_distributor }}</h3>
                <div class="inline-block bg-gray-100 px-2 py-0.5 rounded font-mono text-[9px] text-gray-600">ID: {{ $row->kode_distributor }}</div>
                <div class="text-[10px] text-gray-400 font-bold mt-2 uppercase flex items-center gap-1">
                    <i class="fa fa-store text-blue-300"></i> {{ $row->toko->nama_toko }}
                </div>
            </div>
            <div>
                @if($row->is_active)
                    <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm">Aktif</span>
                @else
                    <span class="bg-rose-100 text-rose-800 border border-rose-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm">Non-Aktif</span>
                @endif
            </div>
        </div>
        
        <div class="mb-4 text-xs text-gray-700 bg-gray-50 border border-gray-100 p-3 rounded-sm space-y-2">
            @if($row->nama_perusahaan)
            <div class="font-black text-gray-800 text-[10px] uppercase border-b border-gray-200 pb-1 mb-1">{{ $row->nama_perusahaan }}</div>
            @endif
            
            <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                @if($row->nama_kontak)
                <div class="flex items-center gap-2 text-[10px]"><i class="fa fa-user text-blue-400 w-3"></i> {{ $row->nama_kontak }}</div>
                @endif
                
                @if($row->no_hp_kontak)
                <div class="flex items-center gap-2 text-[10px]"><i class="fa fa-phone text-emerald-400 w-3"></i> {{ $row->no_hp_kontak }}</div>
                @endif

                @if($row->alamat)
                <div class="col-span-2 flex items-center gap-2 text-[10px] border-t border-gray-100 pt-1 mt-1"><i class="fa fa-map-marker-alt text-rose-400 w-3"></i> {{ $row->kota ?? Str::limit($row->alamat, 40) }}</div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 pt-2">
            <a href="{{ route('owner.distributor.show', $row->id_distributor) }}" class="bg-white border border-blue-600 text-blue-700 py-2 px-1 text-center text-[10px] font-black hover:bg-blue-50 transition-colors rounded-sm shadow-sm uppercase">Lihat</a>
            <a href="{{ route('owner.distributor.edit', $row->id_distributor) }}" class="bg-amber-400 border border-amber-600 text-amber-900 py-2 px-1 text-center text-[10px] font-black hover:bg-amber-300 transition-colors rounded-sm shadow-sm uppercase">Edit</a>
            <form action="{{ route('owner.distributor.destroy', $row->id_distributor) }}" method="POST" class="w-full" onsubmit="return confirm('Hapus distributor ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-rose-600 border border-rose-800 text-white py-2 px-1 text-[10px] font-black hover:bg-rose-500 transition-colors rounded-sm shadow-sm uppercase tracking-tighter">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-300 p-8 text-center rounded-sm">
        <i class="fa fa-search text-gray-200 text-4xl mb-3 block"></i>
        <div class="text-gray-400 italic text-sm">Tidak ditemukan distributor yang cocok</div>
    </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white shadow-sm rounded-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-blue-900 p-3 text-center w-12">No</th>
                <th class="border border-blue-900 p-3">Kode</th>
                <th class="border border-blue-900 p-3">Nama Distributor</th>
                <th class="border border-blue-900 p-3">Perusahaan</th>
                <th class="border border-blue-900 p-3">Toko</th>
                <th class="border border-blue-900 p-3">Kontak</th>
                <th class="border border-blue-900 p-3 text-center w-24">Status</th>
                <th class="border border-blue-900 p-3 text-center w-40">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distributors as $key => $row)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $distributors->firstItem() + $key }}</td>
                <td class="p-3 font-mono text-xs text-blue-700 font-bold tracking-tighter">{{ $row->kode_distributor }}</td>
                <td class="p-3">
                    <div class="font-black text-gray-800 leading-tight mb-0.5">{{ $row->nama_distributor }}</div>
                    @if($row->alamat)
                        <div class="text-[9px] text-gray-500 font-bold uppercase truncate max-w-[150px]">
                            <i class="fa fa-map-marker-alt text-rose-400"></i> {{ $row->kota ?? $row->alamat }}
                        </div>
                    @endif
                </td>
                <td class="p-3 text-gray-600 font-semibold">{{ $row->nama_perusahaan ?? '-' }}</td>
                <td class="p-3">
                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter border border-blue-100 shadow-sm">{{ $row->toko->nama_toko }}</span>
                </td>
                <td class="p-3">
                    @if($row->nama_kontak)
                        <div class="font-black text-gray-700 text-[10px] uppercase mb-0.5">{{ $row->nama_kontak }}</div>
                    @endif
                    @if($row->no_hp_kontak)
                        <div class="text-[10px] text-emerald-600 font-bold"><i class="fa fa-phone text-emerald-400"></i> {{ $row->no_hp_kontak }}</div>
                    @endif
                </td>
                <td class="p-3 text-center">
                    @if($row->is_active)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase tracking-tighter border border-emerald-200">Aktif</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[9px] font-black uppercase tracking-tighter border border-rose-200">Non-Aktif</span>
                    @endif
                </td>
                <td class="p-3">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.distributor.show', $row->id_distributor) }}" class="bg-blue-600 text-white px-2.5 py-1 text-[10px] font-black hover:bg-blue-500 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Lihat</a>
                        <a href="{{ route('owner.distributor.edit', $row->id_distributor) }}" class="bg-amber-400 border border-amber-500 text-amber-900 px-2.5 py-1 text-[10px] font-black hover:bg-amber-300 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Edit</a>
                        <form action="{{ route('owner.distributor.destroy', $row->id_distributor) }}" method="POST" class="inline" onsubmit="return confirm('Hapus distributor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-rose-600 text-white px-2.5 py-1 text-[10px] font-black hover:bg-rose-500 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-12 text-center text-gray-400 italic border border-gray-200 bg-gray-50">
                    <i class="fa fa-truck text-gray-100 text-6xl block mb-2"></i>
                    Belum ada data distributor
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($distributors->hasPages())
<div class="mt-4 flex justify-end">
    {{ $distributors->links() }}
</div>
@endif
@endsection
