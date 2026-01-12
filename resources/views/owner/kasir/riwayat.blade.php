@extends('layouts.owner')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="w-full flex flex-col font-sans text-[12px] bg-[#c0c0c0]"
        style="font-family: 'MS Sans Serif', Arial, sans-serif; min-height: calc(100vh - 80px);">

        {{-- HEADER --}}
        <div class="shrink-0 p-2 border-b border-gray-500 flex justify-between items-center bg-[#c0c0c0]">
            <div>
                <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Riwayat Transaksi</h1>
                <div class="text-gray-600 mt-1">Lihat & Cetak Ulang Struk</div>
            </div>

            <div class="flex gap-2">
                <form action="{{ route('owner.kasir.riwayat') }}" method="GET" class="flex items-center gap-2">
                    <label class="font-bold">Tanggal:</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="px-2 py-1 border-2 border-gray-400 border-l-black border-t-black">
                </form>

                <a href="{{ route('owner.kasir.index') }}"
                    class="px-4 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black font-bold flex items-center gap-1 hover:bg-red-100 text-red-900">
                    <i class="fas fa-arrow-left"></i> KEMBALI KE POS
                </a>
            </div>
        </div>

        {{-- KONTEN TABEL --}}
        <div class="p-2 flex-1 overflow-auto">
            <div class="bg-white border-2 border-gray-400 border-l-black border-t-black min-h-full p-1">
                <table class="w-full border-collapse text-left">
                    <thead class="bg-blue-800 text-white sticky top-0">
                        <tr>
                            <th class="p-2 border border-gray-400">No Faktur</th>
                            <th class="p-2 border border-gray-400">Waktu</th>
                            <th class="p-2 border border-gray-400">Pelanggan</th>
                            <th class="p-2 border border-gray-400">Metode</th>
                            <th class="p-2 border border-gray-400 text-right">Total</th>
                            <th class="p-2 border border-gray-400 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $trx)
                            <tr class="hover:bg-yellow-50 border-b border-gray-200">
                                <td class="p-2 border border-gray-300 font-mono font-bold">{{ $trx->no_faktur }}</td>
                                <td class="p-2 border border-gray-300">{{ date('H:i:s', strtotime($trx->tgl_transaksi)) }}
                                </td>
                                <td class="p-2 border border-gray-300">
                                    {{ $trx->pelanggan->nama_pelanggan ?? 'Umum' }}
                                </td>
                                <td class="p-2 border border-gray-300">
                                    <span
                                        class="px-2 py-0.5 rounded {{ $trx->metode_bayar == 'Hutang' ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                                        {{ $trx->metode_bayar }}
                                    </span>
                                </td>
                                <td class="p-2 border border-gray-300 text-right font-mono">
                                    Rp {{ number_format($trx->total_netto, 0, ',', '.') }}
                                </td>
                                <td class="p-2 border border-gray-300 text-center">
                                    <div class="flex justify-center gap-1">
                                        {{-- Cetak Struk (Thermal) --}}
                                        <button
                                            onclick="window.open('{{ route('owner.kasir.print', $trx->id_penjualan) }}', 'Struk', 'width=400,height=600')"
                                            class="px-2 py-1 bg-gray-200 border border-gray-400 hover:bg-white text-[10px] font-bold"
                                            title="Cetak Struk">
                                            <i class="fas fa-receipt"></i> STRUK
                                        </button>

                                        {{-- Cetak Faktur (A4) --}}
                                        <button
                                            onclick="window.open('{{ route('owner.kasir.cetak-faktur', $trx->id_penjualan) }}', 'Faktur', 'width=800,height=600')"
                                            class="px-2 py-1 bg-blue-100 border border-blue-400 hover:bg-white text-[10px] font-bold text-blue-800"
                                            title="Cetak Faktur">
                                            <i class="fas fa-file-invoice"></i> FAKTUR
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500 italic">
                                    Tidak ada transaksi pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
