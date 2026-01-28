@extends('layouts.owner')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-users text-blue-700"></i> Daftar Karyawan
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.karyawan.create') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase">
            <i class="fa fa-plus"></i> Tambah Karyawan
        </a>
    </div>
</div>

<div class="bg-white border border-gray-300 p-4 mb-4 shadow-sm rounded-sm">
    <form method="GET" action="{{ route('owner.karyawan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Filter Toko</label>
            <select name="id_toko" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all bg-gray-50">
                <option value="">-- Semua Toko --</option>
                @foreach($userStores as $store)
                    <option value="{{ $store->id_toko }}" {{ request('id_toko') == $store->id_toko ? 'selected' : '' }}>
                        {{ $store->nama_toko }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Filter Status</label>
            <select name="status_karyawan" class="w-full border border-gray-300 p-1.5 text-xs shadow-inner focus:border-blue-500 outline-none transition-all bg-gray-50">
                <option value="">-- Semua Status --</option>
                <option value="Aktif" {{ request('status_karyawan') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Cuti" {{ request('status_karyawan') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="Resign" {{ request('status_karyawan') == 'Resign' ? 'selected' : '' }}>Resign</option>
            </select>
        </div>
        <div class="md:col-span-2 flex items-end gap-2">
            <button type="submit" class="flex-1 md:flex-none bg-blue-600 text-white border border-blue-800 px-6 py-1.5 text-xs font-bold hover:bg-blue-500 transition-colors shadow-sm uppercase">
                <i class="fa fa-filter"></i> Filter
            </button>
            @if(request('id_toko') || request('status_karyawan'))
            <a href="{{ route('owner.karyawan.index') }}" class="flex-1 md:flex-none bg-gray-100 text-gray-700 border border-gray-300 px-6 py-1.5 text-xs font-bold hover:bg-gray-200 transition-colors text-center shadow-sm uppercase">
                <i class="fa fa-sync-alt"></i> Reset
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-4">
    @forelse($karyawans as $row)
    <div class="bg-white border-t-4 border-blue-600 p-4 shadow-lg rounded-sm relative group active:scale-[0.98] transition-all">
        <div class="flex gap-3 mb-3">
            <div class="flex-shrink-0">
                @if($row->foto)
                    <img src="{{ asset('storage/' . $row->foto) }}" alt="{{ $row->nama_lengkap }}" class="w-16 h-16 object-cover border-2 border-blue-200 rounded-sm shadow">
                @else
                    <div class="w-16 h-16 bg-gray-200 border-2 border-gray-300 flex items-center justify-center rounded-sm">
                        <i class="fa fa-user text-gray-400 text-2xl"></i>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="font-black text-sm text-blue-900 tracking-tight leading-tight mb-1">{{ $row->nama_lengkap }}</h3>
                <div class="inline-block bg-gray-100 px-2 py-0.5 rounded font-mono text-[9px] text-gray-600">{{ $row->kode_karyawan }}</div>
                <div class="mt-2">
                    @if($row->status_karyawan == 'Aktif')
                        <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm">Aktif</span>
                    @elseif($row->status_karyawan == 'Cuti')
                        <span class="bg-amber-100 text-amber-800 border border-amber-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm">Cuti</span>
                    @else
                        <span class="bg-rose-100 text-rose-800 border border-rose-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm">Resign</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="mb-4 text-xs text-gray-700 bg-gray-50 border border-gray-100 p-3 rounded-sm space-y-2">
            <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                <div class="col-span-2 flex items-center gap-2 text-[10px] font-bold">
                    <i class="fa fa-briefcase text-blue-400 w-3"></i>
                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-100">{{ $row->jabatan }}</span>
                </div>
                
                <div class="col-span-2 flex items-center gap-2 text-[10px]">
                    <i class="fa fa-store text-blue-400 w-3"></i> 
                    <span class="font-bold text-blue-700">{{ $row->toko->nama_toko }}</span>
                </div>
                
                <div class="flex items-center gap-2 text-[10px]">
                    <i class="fa fa-{{ $row->jenis_kelamin == 'L' ? 'mars' : 'venus' }} text-{{ $row->jenis_kelamin == 'L' ? 'blue' : 'pink' }}-400 w-3"></i> 
                    <span>{{ $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                
                @if($row->no_hp)
                <div class="flex items-center gap-2 text-[10px]">
                    <i class="fa fa-phone text-emerald-400 w-3"></i> 
                    <span>{{ $row->no_hp }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 pt-2">
            <a href="{{ route('owner.karyawan.show', $row->id_karyawan) }}" class="bg-white border border-blue-600 text-blue-700 py-2 px-1 text-center text-[10px] font-black hover:bg-blue-50 transition-colors rounded-sm shadow-sm uppercase">Lihat</a>
            <a href="{{ route('owner.karyawan.edit', $row->id_karyawan) }}" class="bg-amber-400 border border-amber-600 text-amber-900 py-2 px-1 text-center text-[10px] font-black hover:bg-amber-300 transition-colors rounded-sm shadow-sm uppercase">Edit</a>
            <form action="{{ route('owner.karyawan.destroy', $row->id_karyawan) }}" method="POST" class="w-full" onsubmit="return confirm('Hapus karyawan ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-rose-600 border border-rose-800 text-white py-2 px-1 text-[10px] font-black hover:bg-rose-500 transition-colors rounded-sm shadow-sm uppercase tracking-tighter">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-300 p-8 text-center rounded-sm">
        <i class="fa fa-users text-gray-200 text-4xl mb-3 block"></i>
        <div class="text-gray-400 italic text-sm">Belum ada data karyawan</div>
    </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white shadow-sm rounded-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-blue-900 p-3 text-center w-12">No</th>
                <th class="border border-blue-900 p-3 text-center w-16">Foto</th>
                <th class="border border-blue-900 p-3">Kode</th>
                <th class="border border-blue-900 p-3">Nama</th>
                <th class="border border-blue-900 p-3">Jabatan</th>
                <th class="border border-blue-900 p-3">Toko</th>
                <th class="border border-blue-900 p-3">No. HP</th>
                <th class="border border-blue-900 p-3 text-center w-24">Status</th>
                <th class="border border-blue-900 p-3 text-center w-40">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($karyawans as $key => $row)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $karyawans->firstItem() + $key }}</td>
                <td class="p-3 text-center">
                    @if($row->foto)
                        <img src="{{ asset('storage/' . $row->foto) }}" alt="{{ $row->nama_lengkap }}" class="w-12 h-12 object-cover border border-gray-400 mx-auto rounded-sm">
                    @else
                        <div class="w-12 h-12 bg-gray-200 border border-gray-400 flex items-center justify-center mx-auto rounded-sm">
                            <i class="fa fa-user text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="p-3 font-mono text-xs text-blue-700 font-bold tracking-tighter">{{ $row->kode_karyawan }}</td>
                <td class="p-3">
                    <div class="font-black text-gray-800 leading-tight mb-0.5">{{ $row->nama_lengkap }}</div>
                    <div class="text-[9px] text-gray-500 font-bold uppercase">
                        <i class="fa fa-{{ $row->jenis_kelamin == 'L' ? 'mars' : 'venus' }} text-{{ $row->jenis_kelamin == 'L' ? 'blue' : 'pink' }}-400"></i>
                        {{ $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </div>
                </td>
                <td class="p-3">
                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter border border-blue-100 shadow-sm">{{ $row->jabatan }}</span>
                </td>
                <td class="p-3">
                    <span class="text-blue-700 font-semibold">{{ $row->toko->nama_toko }}</span>
                </td>
                <td class="p-3 text-gray-600">{{ $row->no_hp }}</td>
                <td class="p-3 text-center">
                    @if($row->status_karyawan == 'Aktif')
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase tracking-tighter border border-emerald-200">Aktif</span>
                    @elseif($row->status_karyawan == 'Cuti')
                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[9px] font-black uppercase tracking-tighter border border-amber-200">Cuti</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[9px] font-black uppercase tracking-tighter border border-rose-200">Resign</span>
                    @endif
                </td>
                <td class="p-3">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.karyawan.show', $row->id_karyawan) }}" class="bg-blue-600 text-white px-2.5 py-1 text-[10px] font-black hover:bg-blue-500 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Lihat</a>
                        <a href="{{ route('owner.karyawan.edit', $row->id_karyawan) }}" class="bg-amber-400 border border-amber-500 text-amber-900 px-2.5 py-1 text-[10px] font-black hover:bg-amber-300 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Edit</a>
                        <form action="{{ route('owner.karyawan.destroy', $row->id_karyawan) }}" method="POST" class="inline" onsubmit="return confirm('Hapus karyawan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-rose-600 text-white px-2.5 py-1 text-[10px] font-black hover:bg-rose-500 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="p-12 text-center text-gray-400 italic border border-gray-200 bg-gray-50">
                    <i class="fa fa-users text-gray-100 text-6xl block mb-2"></i>
                    Belum ada data karyawan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($karyawans->hasPages())
<div class="mt-4 flex justify-end">
    {{ $karyawans->links() }}
</div>
@endif
@endsection
