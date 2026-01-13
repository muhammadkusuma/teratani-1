@extends('layouts.owner')
@section('title', 'Daftar Piutang Pelanggan')

@section('content')
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block uppercase">Kartu Piutang</h2>
            <span class="text-xs text-gray-500 ml-2">Status: Belum Lunas</span>
        </div>
        {{-- Tombol opsional jika ingin ada aksi di atas, bisa dikosongkan jika tidak butuh --}}
    </div>

    {{-- Search Bar (Disesuaikan strukturnya agar konsisten) --}}
    <form action="{{ route('owner.piutang.index') }}" method="GET" class="mb-3 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pelanggan / No Faktur..."
            class="border border-gray-400 p-1 text-xs w-64 shadow-inner">
        <button type="submit" class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300">CARI</button>
        @if (request('search'))
            <a href="{{ route('owner.piutang.index') }}"
                class="bg-red-200 border border-red-400 px-3 py-1 text-xs hover:bg-red-300 flex items-center text-red-800">RESET</a>
        @endif
    </form>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto border border-gray-400 bg-white">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <th class="border border-gray-400 p-2">Pelanggan</th>
                    <th class="border border-gray-400 p-2 text-center w-32">No Faktur</th>
                    <th class="border border-gray-400 p-2 text-center w-32">Jatuh Tempo</th>
                    <th class="border border-gray-400 p-2 text-right">Total Hutang</th>
                    <th class="border border-gray-400 p-2 text-right">Sudah Bayar</th>
                    <th class="border border-gray-400 p-2 text-right">Sisa</th>
                    <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($piutangs as $p)
                    <tr class="hover:bg-yellow-50 text-xs">
                        <td class="border border-gray-300 p-2 font-semibold text-gray-800">
                            {{ $p->pelanggan->nama_pelanggan ?? 'Umum' }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <a href="#" class="text-blue-700 hover:underline decoration-blue-700">
                                {{ $p->penjualan->no_faktur ?? '-' }}
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            @if ($p->tgl_jatuh_tempo)
                                <span
                                    class="{{ $p->tgl_jatuh_tempo < date('Y-m-d') ? 'text-red-600 font-bold bg-red-100 px-1 border border-red-200' : '' }}">
                                    {{ date('d/m/Y', strtotime($p->tgl_jatuh_tempo)) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2 text-right font-mono">
                            Rp {{ number_format($p->total_piutang, 0, ',', '.') }}
                        </td>
                        <td class="border border-gray-300 p-2 text-right font-mono text-green-700">
                            Rp {{ number_format($p->sudah_dibayar, 0, ',', '.') }}
                        </td>
                        <td class="border border-gray-300 p-2 text-right font-mono font-bold text-red-600">
                            Rp {{ number_format($p->sisa_piutang, 0, ',', '.') }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <a href="{{ route('owner.piutang.show', $p->id_piutang) }}"
                                class="inline-block bg-blue-600 text-white border border-blue-800 px-2 py-1 text-[10px] hover:bg-blue-500 w-full shadow-sm">
                                <i class="fas fa-money-bill-wave mr-1"></i> BAYAR
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300">
                            Tidak ada data piutang belum lunas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 text-xs">
        {{ $piutangs->links() }}
    </div>
@endsection
