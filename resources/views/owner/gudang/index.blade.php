@extends('layouts.owner')

@section('title', 'Manajemen Gudang')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <div>
        <h2 class="font-bold text-xl border-b-4 border-purple-600 pb-1 pr-6 inline-block uppercase tracking-tight">
            <i class="fa fa-warehouse text-purple-700"></i> Manajemen Gudang
        </h2>
        <span class="text-xs text-gray-500 block md:inline md:ml-2 mt-1 md:mt-0">Toko: {{ $toko->nama_toko }}</span>
    </div>
    <a href="{{ route('owner.toko.gudang.create', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-2 bg-purple-700 text-white border border-purple-900 shadow-md hover:bg-purple-600 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-plus"></i> Tambah Gudang
    </a>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-3 mb-4">
    @forelse($gudangs as $index => $gudang)
    <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 border-purple-500 p-3 shadow-sm rounded-sm">
        <div class="flex justify-between items-start mb-2">
            <div class="flex-1">
                <h3 class="font-black text-sm text-gray-800">{{ $gudang->nama_gudang }}</h3>
                <p class="text-xs text-purple-600 mt-1">
                    <i class="fa fa-map-marker-alt"></i> {{ $gudang->lokasi ?? 'Lokasi tidak ditentukan' }}
                </p>
            </div>
            <span class="text-[10px] bg-purple-100 text-purple-800 px-2 py-1 rounded-sm font-bold border border-purple-300">
                #{{ $index + 1 }}
            </span>
        </div>
        
        <div class="bg-gradient-to-r from-blue-100 to-blue-50 border border-blue-200 p-2 rounded-sm mb-2">
            <div class="flex justify-between items-center">
                <span class="font-black uppercase text-[10px] text-blue-800">
                    <i class="fa fa-boxes"></i> Total Jenis Produk
                </span>
                <span class="font-black text-xl text-blue-900">{{ $gudang->stok_gudangs_count }}</span>
            </div>
        </div>
        
        <div class="grid grid-cols-3 gap-1">
            <a href="{{ route('owner.toko.gudang.show', [$toko->id_toko, $gudang->id_gudang]) }}" class="text-center bg-blue-600 text-white border border-blue-800 px-2 py-1.5 text-[10px] font-bold hover:bg-blue-500 transition-colors rounded-sm uppercase shadow-sm">
                <i class="fa fa-eye"></i> Stok
            </a>
            <a href="{{ route('owner.toko.gudang.edit', [$toko->id_toko, $gudang->id_gudang]) }}" class="text-center bg-amber-400 text-black border border-amber-600 px-2 py-1.5 text-[10px] font-bold hover:bg-amber-300 transition-colors rounded-sm uppercase shadow-sm">
                <i class="fa fa-edit"></i> Edit
            </a>
            <form action="{{ route('owner.toko.gudang.destroy', [$toko->id_toko, $gudang->id_gudang]) }}" method="POST" onsubmit="return confirm('Hapus gudang ini?')" class="inline">
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
        <i class="fa fa-warehouse text-gray-200 text-5xl block mb-3"></i>
        <p class="text-gray-400 italic text-sm">Belum ada data gudang</p>
    </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-purple-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-purple-900 p-3 text-center w-12">No</th>
                <th class="border border-purple-900 p-3">Nama Gudang</th>
                <th class="border border-purple-900 p-3">Lokasi</th>
                <th class="border border-purple-900 p-3 text-center w-40">Total Jenis Produk</th>
                <th class="border border-purple-900 p-3 text-center w-48">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gudangs as $index => $gudang)
            <tr class="hover:bg-purple-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                <td class="p-3 font-black text-purple-700">{{ $gudang->nama_gudang }}</td>
                <td class="p-3 text-gray-700">
                    <i class="fa fa-map-marker-alt text-purple-500"></i> {{ $gudang->lokasi ?? '-' }}
                </td>
                <td class="p-3 text-center font-mono font-black text-blue-700 text-lg">{{ $gudang->stok_gudangs_count }}</td>
                <td class="p-3 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.toko.gudang.show', [$toko->id_toko, $gudang->id_gudang]) }}" class="bg-blue-600 text-white border border-blue-800 px-2 py-1 text-[10px] font-bold hover:bg-blue-500 transition-colors rounded-sm uppercase shadow-sm">
                            <i class="fa fa-eye"></i> Lihat Stok
                        </a>
                        <a href="{{ route('owner.toko.gudang.edit', [$toko->id_toko, $gudang->id_gudang]) }}" class="bg-amber-400 text-black border border-amber-600 px-2 py-1 text-[10px] font-bold hover:bg-amber-300 transition-colors rounded-sm uppercase shadow-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('owner.toko.gudang.destroy', [$toko->id_toko, $gudang->id_gudang]) }}" method="POST" onsubmit="return confirm('Hapus gudang ini?')" class="inline">
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
                <td colspan="5" class="p-8 text-center border border-gray-300">
                    <i class="fa fa-warehouse text-gray-200 text-5xl block mb-3"></i>
                    <p class="text-gray-400 italic text-sm">Belum ada data gudang</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
