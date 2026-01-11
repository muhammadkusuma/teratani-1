@extends('layouts.owner')

@section('title', 'Daftar Pengeluaran')

@section('content')
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block uppercase">Data Pengeluaran</h2>
            <span class="text-xs text-gray-500 ml-2">Manajemen Operasional</span>
        </div>
        <a href="{{ route('owner.pengeluaran.create') }}"
            class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
            + TAMBAH PENGELUARAN
        </a>
    </div>

    {{-- Search Bar (Disamakan strukturnya, opsional jika ingin digunakan) --}}
    <form action="{{ route('owner.pengeluaran.index') }}" method="GET" class="mb-3 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Keterangan / Kategori..."
            class="border border-gray-400 p-1 text-xs w-64 shadow-inner">
        <button type="submit" class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300">CARI</button>
        @if (request('search'))
            <a href="{{ route('owner.pengeluaran.index') }}"
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
                    <th class="border border-gray-400 p-2 text-center w-10">No</th>
                    <th class="border border-gray-400 p-2 text-center w-32">Tanggal</th>
                    <th class="border border-gray-400 p-2 text-center">Kategori</th>
                    <th class="border border-gray-400 p-2">Keterangan</th>
                    <th class="border border-gray-400 p-2 text-right">Nominal</th>
                    <th class="border border-gray-400 p-2 text-center w-20">Bukti</th>
                    <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluaran as $index => $item)
                    <tr class="hover:bg-yellow-50 text-xs">
                        <td class="border border-gray-300 p-2 text-center">{{ $index + $pengeluaran->firstItem() }}</td>
                        <td class="border border-gray-300 p-2 text-center">
                            {{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->translatedFormat('d F Y') }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <span class="bg-gray-200 px-2 py-0.5 rounded border border-gray-300 text-[10px]">
                                {{ $item->kategori_biaya }}
                            </span>
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                        <td class="border border-gray-300 p-2 text-right font-mono font-bold">
                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            @if ($item->bukti_foto)
                                <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 hover:underline">
                                    LIHAT
                                </a>
                            @else
                                <span class="text-gray-400 italic text-[10px]">-</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');"
                                action="{{ route('owner.pengeluaran.destroy', $item->id_pengeluaran) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 text-white border border-red-800 px-2 py-0.5 text-[10px] hover:bg-red-500 w-full">
                                    HAPUS
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300">
                            Belum ada data pengeluaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 text-xs">
        {{ $pengeluaran->links() }}
    </div>
@endsection