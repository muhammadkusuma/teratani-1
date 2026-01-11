@extends('layouts.owner')

@section('title', 'Daftar Pembelian')

@section('content')
    <div class="p-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Riwayat Pembelian Barang</h2>
            <a href="{{ route('owner.pembelian.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm text-sm font-bold">
                <i class="fas fa-plus mr-1"></i> Input Faktur Baru
            </a>
        </div>

        <div class="bg-white border border-gray-300 shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-600 text-xs uppercase border-b border-gray-300">
                    <tr>
                        <th class="p-3 border-r">Tgl</th>
                        <th class="p-3 border-r">No. Faktur</th>
                        <th class="p-3 border-r">Distributor</th>
                        <th class="p-3 border-r">Total</th>
                        <th class="p-3 border-r">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($pembelians as $beli)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 border-r">{{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('d/m/Y') }}</td>
                            <td class="p-3 border-r font-bold text-blue-800">{{ $beli->no_faktur_supplier }}</td>
                            <td class="p-3 border-r">{{ $beli->distributor->nama_distributor ?? '-' }}</td>
                            <td class="p-3 border-r font-mono">Rp {{ number_format($beli->total_pembelian, 0, ',', '.') }}
                            </td>
                            <td class="p-3 border-r">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $beli->status_bayar == 'Lunas' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $beli->status_bayar }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('owner.pembelian.show', $beli->id_pembelian) }}"
                                    class="text-blue-600 hover:text-blue-800 underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500 italic">Belum ada data pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pembelians->links() }}
        </div>
    </div>
@endsection
