@extends('layouts.owner')

@section('title', 'Retur Pembelian')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="font-bold text-xl">Daftar Retur Pembelian</h2>
    <a href="{{ route('owner.retur-pembelian.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        + Buat Retur ke Distributor
    </a>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distributor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gudang Asal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Retur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($returs as $index => $retur)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $returs->firstItem() + $index }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $retur->tgl_retur->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $retur->distributor->nama_distributor }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $retur->gudang->nama_gudang ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($retur->total_retur, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('owner.retur-pembelian.show', $retur->id_retur_pembelian) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">Tidak ada data retur pembelian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $returs->links() }}
        </div>
    </div>
</div>
@endsection
