@extends('layouts.owner')

@section('title', 'Daftar Pembelian')

@section('content')
    {{-- Header Style Klasik --}}
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">RIWAYAT PEMBELIAN BARANG</h2>
        <a href="{{ route('owner.pembelian.create') }}"
            class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs font-bold">
            + INPUT FAKTUR BARU
        </a>
    </div>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('owner.pembelian.index') }}" class="mb-3 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Faktur / Distributor..."
            class="border border-gray-400 p-1 text-xs w-64 shadow-inner focus:outline-none focus:border-blue-600">
        <button type="submit"
            class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300 text-gray-800">CARI</button>
    </form>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Data --}}
    <div class="overflow-x-auto border border-gray-400 bg-white">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <th class="border border-gray-400 p-2 text-center w-10">No</th>
                    <th class="border border-gray-400 p-2">Tanggal</th>
                    <th class="border border-gray-400 p-2">No. Faktur</th>
                    <th class="border border-gray-400 p-2">Distributor</th>
                    <th class="border border-gray-400 p-2 text-right">Total</th>
                    <th class="border border-gray-400 p-2 text-center">Status</th>
                    <th class="border border-gray-400 p-2 text-center w-20">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelians as $key => $beli)
                    <tr class="hover:bg-yellow-50 text-xs text-gray-800">
                        {{-- Nomor Urut --}}
                        <td class="border border-gray-300 p-2 text-center">
                            {{ $pembelians->firstItem() + $key }}
                        </td>

                        {{-- Tanggal --}}
                        <td class="border border-gray-300 p-2">
                            {{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('d/m/Y') }}
                        </td>

                        {{-- No Faktur (Font Mono agar tegas) --}}
                        <td class="border border-gray-300 p-2 font-mono font-bold text-blue-900">
                            {{ $beli->no_faktur_supplier }}
                        </td>

                        {{-- Distributor --}}
                        <td class="border border-gray-300 p-2">
                            {{ $beli->distributor->nama_distributor ?? '-' }}
                        </td>

                        {{-- Total Pembelian --}}
                        <td class="border border-gray-300 p-2 text-right font-mono">
                            Rp {{ number_format($beli->total_pembelian, 0, ',', '.') }}
                        </td>

                        {{-- Status Bayar (Badge Kotak) --}}
                        <td class="border border-gray-300 p-2 text-center">
                            @if ($beli->status_bayar == 'Lunas')
                                <span class="bg-green-100 border border-green-300 text-green-800 px-2 py-0.5 text-[10px]">
                                    LUNAS
                                </span>
                            @else
                                <span class="bg-red-100 border border-red-300 text-red-800 px-2 py-0.5 text-[10px]">
                                    {{ strtoupper($beli->status_bayar) }}
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="border border-gray-300 p-2 text-center">
                            <a href="{{ route('owner.pembelian.show', $beli->id_pembelian) }}"
                                class="inline-block bg-cyan-100 border border-cyan-400 text-cyan-800 px-2 py-0.5 text-[10px] hover:bg-cyan-200">
                                DETAIL
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300">
                            Belum ada data pembelian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 text-xs">
        {{ $pembelians->links() }}
    </div>
@endsection
