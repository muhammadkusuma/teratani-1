@extends('layouts.owner')

@section('title', 'Data Distributor')

@section('content')
    {{-- Header Page --}}
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">DAFTAR DISTRIBUTOR</h2>
        <a href="{{ route('owner.distributor.create') }}"
            class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs font-bold">
            + TAMBAH DISTRIBUTOR
        </a>
    </div>

    {{-- Search Bar --}}
    <form action="{{ route('owner.distributor.index') }}" method="GET" class="mb-3 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kontak / telepon..."
            class="border border-gray-400 p-1 text-xs w-64 shadow-inner focus:outline-none focus:border-blue-600">
        <button type="submit"
            class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300 text-gray-800 font-bold">
            CARI
        </button>
    </form>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Data --}}
    <div class="overflow-x-auto border border-gray-400 bg-white">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <th class="border border-gray-400 p-2 text-center w-10">No</th>
                    <th class="border border-gray-400 p-2">Nama Distributor</th>
                    <th class="border border-gray-400 p-2">Kontak Person</th>
                    <th class="border border-gray-400 p-2">No. Telepon</th>
                    <th class="border border-gray-400 p-2">Alamat</th>
                    <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($distributors as $key => $item)
                    <tr class="hover:bg-yellow-50 text-xs text-gray-800">
                        <td class="border border-gray-300 p-2 text-center">
                            {{ $distributors->firstItem() + $key }}
                        </td>
                        <td class="border border-gray-300 p-2 font-bold text-blue-900">
                            {{ $item->nama_distributor }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ $item->nama_kontak ?? '-' }}
                        </td>
                        <td class="border border-gray-300 p-2 font-mono">
                            {{ $item->no_telp ?? '-' }}
                        </td>
                        <td class="border border-gray-300 p-2 truncate max-w-xs" title="{{ $item->alamat }}">
                            {{ $item->alamat ?? '-' }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('owner.distributor.edit', $item->id_distributor) }}"
                                    class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300 text-black font-medium">
                                    EDIT
                                </a>
                                <form action="{{ route('owner.distributor.destroy', $item->id_distributor) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus distributor ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white border border-red-800 px-2 py-0.5 text-[10px] hover:bg-red-500 font-medium">
                                        HAPUS
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500 italic border border-gray-300">
                            Belum ada data distributor.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 text-xs">
        {{ $distributors->links() }}
    </div>
@endsection
