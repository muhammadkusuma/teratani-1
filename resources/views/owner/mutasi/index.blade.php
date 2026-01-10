@extends('layouts.owner')

@section('title', 'Transfer Stok Antar Toko')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Riwayat Transfer Barang</h2>
        <a href="{{ route('owner.mutasi.create') }}"
            class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 font-bold border border-blue-800 shadow-sm">
            + Buat Transfer Baru
        </a>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-400">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 border-b border-gray-400 text-sm">
                    <th class="p-2 border-r border-gray-300">No Mutasi</th>
                    <th class="p-2 border-r border-gray-300">Tanggal</th>
                    <th class="p-2 border-r border-gray-300">Asal Toko</th>
                    <th class="p-2 border-r border-gray-300">Tujuan Toko</th>
                    <th class="p-2 border-r border-gray-300">Pengirim</th>
                    <th class="p-2 border-r border-gray-300">Status</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($mutasi as $m)
                    <tr class="border-b border-gray-200 hover:bg-yellow-50">
                        <td class="p-2 border-r border-gray-200 font-mono">{{ $m->no_mutasi }}</td>
                        <td class="p-2 border-r border-gray-200">{{ date('d/m/Y H:i', strtotime($m->tgl_kirim)) }}</td>
                        <td class="p-2 border-r border-gray-200 text-red-600">{{ $m->tokoAsal->nama_toko ?? '-' }}</td>
                        <td class="p-2 border-r border-gray-200 text-green-600">{{ $m->tokoTujuan->nama_toko ?? '-' }}</td>
                        <td class="p-2 border-r border-gray-200">{{ $m->pengirim->name ?? '-' }}</td>
                        <td class="p-2 border-r border-gray-200">
                            <span class="px-2 py-0.5 bg-yellow-200 text-yellow-800 border border-yellow-400 text-xs">
                                {{ $m->status }}
                            </span>
                        </td>
                        <td class="p-2">
                            <button class="text-blue-600 hover:underline">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500 italic">Belum ada data transfer stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-2">
            {{ $mutasi->links() }}
        </div>
    </div>
@endsection
