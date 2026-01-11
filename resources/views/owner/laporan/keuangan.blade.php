@extends('layouts.owner')

@section('title', 'Laporan Keuangan')

@section('content')
    {{-- Header Style --}}
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 uppercase">Laporan Laba Rugi</h2>
    </div>

    {{-- Filter Form Style --}}
    <form method="GET" action="{{ route('owner.laporan.keuangan') }}" class="mb-4">
        <div class="flex flex-wrap gap-2 items-end bg-gray-100 p-3 border border-gray-400">
            <div class="flex flex-col">
                <label class="text-[10px] text-gray-600 font-bold mb-1 uppercase">Dari Tanggal</label>
                <input type="date" name="start_date"
                    class="border border-gray-400 px-2 py-1 text-xs focus:outline-none focus:border-blue-700"
                    value="{{ $startDate }}">
            </div>
            <div class="flex flex-col">
                <label class="text-[10px] text-gray-600 font-bold mb-1 uppercase">Sampai Tanggal</label>
                <input type="date" name="end_date"
                    class="border border-gray-400 px-2 py-1 text-xs focus:outline-none focus:border-blue-700"
                    value="{{ $endDate }}">
            </div>
            <div class="pb-px">
                {{-- Tombol disamakan persis dengan tombol "Buat Transfer Baru" --}}
                <button type="submit"
                    class="px-3 py-1.5 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs font-bold uppercase tracking-wide">
                    Filter Laporan
                </button>
            </div>
        </div>
    </form>

    {{-- Table Container Style --}}
    <div class="overflow-x-auto border border-gray-400 bg-white">
        {{-- Card Header simulation --}}
        <div class="bg-gray-200 px-3 py-2 border-b border-gray-400">
            <h4 class="font-bold text-gray-700 text-xs uppercase">Rincian Laba Rugi</h4>
        </div>

        <table class="w-full text-left border-collapse">
            <tbody>
                {{-- Penjualan --}}
                <tr class="hover:bg-yellow-50 text-xs border-b border-gray-300">
                    <td class="border-r border-gray-300 p-2 font-bold w-1/2">Penjualan Bersih (Omset)</td>
                    <td class="p-2 text-right text-green-700 font-mono">
                        + Rp {{ number_format($totalOmset, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- HPP --}}
                <tr class="hover:bg-yellow-50 text-xs border-b border-gray-300">
                    <td class="border-r border-gray-300 p-2 font-bold">Harga Pokok Penjualan (HPP)</td>
                    <td class="p-2 text-right text-red-700 font-mono">
                        - Rp {{ number_format($totalHPP, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- Laba Kotor (Style abu-abu agar mirip table-secondary) --}}
                <tr class="bg-gray-100 text-xs border-y-2 border-gray-400 font-bold">
                    <td class="border-r border-gray-400 p-2 uppercase">Laba Kotor</td>
                    <td class="p-2 text-right font-mono text-gray-800">
                        Rp {{ number_format($labaKotor, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- Pengeluaran --}}
                <tr class="hover:bg-yellow-50 text-xs border-b border-gray-300">
                    <td class="border-r border-gray-300 p-2 font-bold">Biaya Operasional (Pengeluaran)</td>
                    <td class="p-2 text-right text-red-700 font-mono">
                        - Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- Laba Bersih (Style biru agar mirip table-primary tapi lebih soft sesuai tema) --}}
                <tr class="bg-blue-50 text-sm border-t-2 border-gray-400 font-bold text-blue-900">
                    <td class="border-r border-gray-400 p-3 uppercase tracking-wider">Laba Bersih</td>
                    <td class="p-3 text-right font-mono text-lg">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Footer Note (Optional, mirip style "Menampilkan data...") --}}
    <div class="mt-2 flex gap-2">
        <div class="text-[10px] text-gray-500 italic">
            Laporan periode {{ date('d/m/Y', strtotime($startDate)) }} s/d {{ date('d/m/Y', strtotime($endDate)) }}
        </div>
    </div>
@endsection
