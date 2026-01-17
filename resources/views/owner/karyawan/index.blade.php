@extends('layouts.owner')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-users"></i> DAFTAR KARYAWAN / KASIR
    </h2>
    <a href="{{ route('owner.karyawan.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        <i class="fa fa-plus"></i> TAMBAH KARYAWAN
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs">
        {{ session('error') }}
    </div>
@endif


<div class="bg-white border border-gray-400 p-3 mb-3">
    <form method="GET" action="{{ route('owner.karyawan.index') }}" class="flex items-end gap-3">
        <div class="flex-1">
            <label class="block text-xs font-bold mb-1">Filter Toko</label>
            <select name="id_toko" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">-- Semua Toko --</option>
                @foreach($userStores as $store)
                    <option value="{{ $store->id_toko }}" {{ request('id_toko') == $store->id_toko ? 'selected' : '' }}>
                        {{ $store->nama_toko }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-xs font-bold mb-1">Filter Status</label>
            <select name="status_karyawan" class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                <option value="">-- Semua Status --</option>
                <option value="Aktif" {{ request('status_karyawan') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Cuti" {{ request('status_karyawan') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="Resign" {{ request('status_karyawan') == 'Resign' ? 'selected' : '' }}>Resign</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white border border-blue-800 px-4 py-1 text-xs hover:bg-blue-500">
            <i class="fa fa-filter"></i> FILTER
        </button>
        @if(request('id_toko') || request('status_karyawan'))
        <a href="{{ route('owner.karyawan.index') }}" class="bg-gray-400 text-white border border-gray-600 px-4 py-1 text-xs hover:bg-gray-300">
            <i class="fa fa-times"></i> RESET
        </a>
        @endif
    </form>
</div>

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2 text-center w-16">Foto</th>
                <th class="border border-gray-400 p-2">Kode</th>
                <th class="border border-gray-400 p-2">Nama</th>
                <th class="border border-gray-400 p-2">Jabatan</th>
                <th class="border border-gray-400 p-2">Toko</th>
                <th class="border border-gray-400 p-2">No. HP</th>
                <th class="border border-gray-400 p-2 text-center w-20">Status</th>
                <th class="border border-gray-400 p-2 text-center w-40">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($karyawans as $key => $row)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $karyawans->firstItem() + $key }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    @if($row->foto)
                        <img src="{{ asset('storage/' . $row->foto) }}" alt="{{ $row->nama_lengkap }}" class="w-12 h-12 object-cover border border-gray-400 mx-auto">
                    @else
                        <div class="w-12 h-12 bg-gray-200 border border-gray-400 flex items-center justify-center mx-auto">
                            <i class="fa fa-user text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 font-mono">{{ $row->kode_karyawan }}</td>
                <td class="border border-gray-300 p-2">
                    <span class="font-bold">{{ $row->nama_lengkap }}</span>
                    <div class="text-[10px] text-gray-600">
                        <i class="fa fa-{{ $row->jenis_kelamin == 'L' ? 'mars' : 'venus' }}"></i>
                        {{ $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </div>
                </td>
                <td class="border border-gray-300 p-2">
                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-[10px] font-bold">{{ $row->jabatan }}</span>
                </td>
                <td class="border border-gray-300 p-2">
                    <span class="text-blue-700 font-semibold">{{ $row->toko->nama_toko }}</span>
                </td>
                <td class="border border-gray-300 p-2">{{ $row->no_hp }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    @if($row->status_karyawan == 'Aktif')
                        <span class="px-2 py-0.5 rounded bg-green-200 text-green-800 text-[10px] font-bold">AKTIF</span>
                    @elseif($row->status_karyawan == 'Cuti')
                        <span class="px-2 py-0.5 rounded bg-yellow-200 text-yellow-800 text-[10px] font-bold">CUTI</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-red-200 text-red-800 text-[10px] font-bold">RESIGN</span>
                    @endif
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.karyawan.show', $row->id_karyawan) }}" class="bg-blue-500 text-white border border-blue-700 px-2 py-0.5 text-[10px] hover:bg-blue-400">LIHAT</a>
                        <a href="{{ route('owner.karyawan.edit', $row->id_karyawan) }}" class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                        <form action="{{ route('owner.karyawan.destroy', $row->id_karyawan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white border border-red-700 px-2 py-0.5 text-[10px] hover:bg-red-400">HAPUS</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada karyawan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($karyawans->hasPages())
<div class="mt-3">
    {{ $karyawans->links() }}
</div>
@endif
@endsection
