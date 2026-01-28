@extends('layouts.owner')

@section('title', 'Daftar Toko')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-store text-blue-700"></i> Daftar Toko
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.toko.create') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-plus"></i> Tambah Toko
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('warning'))
    <div class="bg-amber-100 border border-amber-400 text-amber-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <i class="fa fa-exclamation-triangle"></i> {{ session('warning') }}
    </div>
@endif

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3">
    @forelse($toko as $key => $row)
    <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 {{ $row->is_pusat ? 'border-blue-600' : 'border-gray-300' }} p-3 shadow-sm rounded-sm">
        <div class="flex justify-between items-start mb-2">
            <div class="flex-1">
                <h3 class="font-black text-sm text-gray-800 mb-1">{{ $row->nama_toko }}</h3>
                <p class="text-[10px] font-mono text-gray-500">{{ $row->kode_toko }}</p>
            </div>
            <div>
                @if($row->is_active)
                    <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase border border-emerald-200">Aktif</span>
                @else
                    <span class="px-2 py-1 rounded-full bg-rose-100 text-rose-800 text-[9px] font-black uppercase border border-rose-200">Tutup</span>
                @endif
            </div>
        </div>
        
        @if($row->is_pusat)
        <div class="mb-2">
            <span class="inline-block bg-blue-600 text-white px-2 py-1 text-[9px] font-black rounded-sm shadow-sm">
                <i class="fa fa-star"></i> PUSAT
            </span>
        </div>
        @endif
        
        <div class="space-y-1 text-xs text-gray-600 mb-3">
            <p><i class="fa fa-map-marker-alt text-blue-400 w-4"></i> {{ $row->alamat ?? '-' }}, {{ $row->kota ?? '-' }}</p>
            <p><i class="fa fa-phone text-emerald-500 w-4"></i> {{ $row->no_telp ?? '-' }}</p>
        </div>
        
        <div class="flex gap-2 pt-2 border-t border-gray-200">
            <a href="{{ route('owner.toko.select', $row->id_toko) }}" class="flex-1 text-center bg-blue-600 text-white border border-blue-800 px-3 py-2 text-xs font-bold hover:bg-blue-500 transition-colors rounded-sm uppercase shadow-sm">
                <i class="fa fa-check-circle"></i> Pilih
            </a>
            <a href="{{ route('owner.toko.edit', $row->id_toko) }}" class="flex-1 text-center bg-amber-400 text-black border border-amber-600 px-3 py-2 text-xs font-bold hover:bg-amber-300 transition-colors rounded-sm uppercase shadow-sm">
                <i class="fa fa-edit"></i> Edit
            </a>
        </div>
    </div>
    @empty
    <div class="text-center py-12 bg-white border border-gray-300 rounded-sm">
        <i class="fa fa-store text-gray-200 text-5xl block mb-3"></i>
        <p class="text-gray-400 italic text-sm">Belum ada toko</p>
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
                <th class="border border-blue-900 p-3">Nama Toko</th>
                <th class="border border-blue-900 p-3">Alamat</th>
                <th class="border border-blue-900 p-3">Telepon</th>
                <th class="border border-blue-900 p-3 text-center w-20">Status</th>
                <th class="border border-blue-900 p-3 text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($toko as $key => $row)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $key + 1 }}</td>
                <td class="p-3 font-mono text-xs text-blue-700 font-bold tracking-tighter">{{ $row->kode_toko }}</td>
                <td class="p-3">
                    <span class="font-black text-gray-800">{{ $row->nama_toko }}</span>
                    @if($row->is_pusat)
                        <span class="bg-blue-600 text-white px-2 py-0.5 text-[9px] font-black ml-1 rounded-sm shadow-sm">
                            <i class="fa fa-star"></i> PUSAT
                        </span>
                    @endif
                </td>
                <td class="p-3 text-gray-600">{{ $row->alamat ?? '-' }}, {{ $row->kota ?? '-' }}</td>
                <td class="p-3 text-gray-600">
                    <i class="fa fa-phone text-emerald-500"></i> {{ $row->no_telp ?? '-' }}
                </td>
                <td class="p-3 text-center">
                    @if($row->is_active)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase tracking-tighter border border-emerald-200">Aktif</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[9px] font-black uppercase tracking-tighter border border-rose-200">Tutup</span>
                    @endif
                </td>
                <td class="p-3 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.toko.select', $row->id_toko) }}" class="bg-blue-600 text-white border border-blue-800 px-2 py-1 text-[10px] font-bold hover:bg-blue-500 transition-colors rounded-sm uppercase shadow-sm">
                            <i class="fa fa-check"></i> Pilih
                        </a>
                        <a href="{{ route('owner.toko.edit', $row->id_toko) }}" class="bg-amber-400 text-black border border-amber-600 px-2 py-1 text-[10px] font-bold hover:bg-amber-300 transition-colors rounded-sm uppercase shadow-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center border border-gray-300">
                    <i class="fa fa-store text-gray-200 text-5xl block mb-3"></i>
                    <p class="text-gray-400 italic text-sm">Belum ada toko</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection