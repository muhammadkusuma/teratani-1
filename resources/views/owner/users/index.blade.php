@extends('layouts.owner')

@section('title', 'Daftar Akun Pengguna')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-users text-blue-700"></i> Daftar Akun Pengguna
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.users.create') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase">
            <i class="fa fa-plus"></i> Tambah User
        </a>
    </div>
</div>

{{-- Mobile Card View --}}
<div class="block md:hidden space-y-4">
    @forelse($users as $user)
    <div class="bg-white border-t-4 border-blue-600 p-4 shadow-lg rounded-sm relative group active:scale-[0.98] transition-all">
        <div class="flex justify-between items-start mb-3">
            <div>
                <h3 class="font-black text-sm text-blue-900 tracking-tight leading-tight">{{ $user->username }}</h3>
            </div>
        </div>
        
        <div class="mb-4 text-xs text-gray-700 bg-gray-50 border border-gray-100 p-3 rounded-sm space-y-2">
            <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                @if($user->karyawan)
                <div class="col-span-2 flex items-center gap-2 text-[10px]">
                    <i class="fa fa-user text-blue-400 w-3"></i> 
                    <span class="font-bold">{{ $user->karyawan->nama_lengkap }}</span>
                </div>
                @endif
                
                @if($user->karyawan && $user->karyawan->jabatan)
                <div class="col-span-2 flex items-center gap-2 text-[10px]">
                    <i class="fa fa-briefcase text-emerald-400 w-3"></i> 
                    <span>{{ $user->karyawan->jabatan }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 pt-2">
            <a href="{{ route('owner.users.edit', $user->id_user) }}" class="bg-amber-400 border border-amber-600 text-amber-900 py-2 px-1 text-center text-[10px] font-black hover:bg-amber-300 transition-colors rounded-sm shadow-sm uppercase">Edit</a>
            <form action="{{ route('owner.users.destroy', $user->id_user) }}" method="POST" class="w-full" onsubmit="return confirm('Hapus user ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-rose-600 border border-rose-800 text-white py-2 px-1 text-[10px] font-black hover:bg-rose-500 transition-colors rounded-sm shadow-sm uppercase tracking-tighter">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-300 p-8 text-center rounded-sm">
        <i class="fa fa-search text-gray-200 text-4xl mb-3 block"></i>
        <div class="text-gray-400 italic text-sm">Belum ada data akun pengguna</div>
    </div>
    @endforelse
</div>

{{-- Desktop Table View --}}
<div class="hidden md:block overflow-x-auto border border-gray-300 bg-white shadow-sm rounded-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                <th class="border border-blue-900 p-3 text-center w-12">No</th>
                <th class="border border-blue-900 p-3">Username</th>
                <th class="border border-blue-900 p-3">Nama Karyawan</th>
                <th class="border border-blue-900 p-3">Jabatan</th>
                <th class="border border-blue-900 p-3 text-center w-40">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $key => $user)
            <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                <td class="p-3 text-center font-bold text-gray-400">{{ $users->firstItem() + $key }}</td>
                <td class="p-3 font-mono text-xs text-blue-700 font-bold tracking-tighter">{{ $user->username }}</td>
                <td class="p-3">
                    <div class="font-black text-gray-800 leading-tight mb-0.5">{{ $user->karyawan->nama_lengkap ?? '-' }}</div>
                </td>
                <td class="p-3 text-gray-600 font-semibold">{{ $user->karyawan->jabatan ?? '-' }}</td>
                <td class="p-3">
                    <div class="flex justify-center gap-1">
                        <a href="{{ route('owner.users.edit', $user->id_user) }}" class="bg-amber-400 border border-amber-500 text-amber-900 px-2.5 py-1 text-[10px] font-black hover:bg-amber-300 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Edit</a>
                        <form action="{{ route('owner.users.destroy', $user->id_user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-rose-600 text-white px-2.5 py-1 text-[10px] font-black hover:bg-rose-500 transition-colors shadow-sm rounded-sm uppercase tracking-tighter">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-12 text-center text-gray-400 italic border border-gray-200 bg-gray-50">
                    <i class="fa fa-users text-gray-100 text-6xl block mb-2"></i>
                    Belum ada data akun pengguna
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages())
<div class="mt-4 flex justify-end">
    {{ $users->links() }}
</div>
@endif
@endsection
