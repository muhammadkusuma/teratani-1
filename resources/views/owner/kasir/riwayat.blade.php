@extends('layouts.owner')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="w-full flex flex-col font-sans text-[12px] bg-[#c0c0c0]"
        style="font-family: 'MS Sans Serif', Arial, sans-serif; min-height: calc(100vh - 80px);">

        {{-- Header Section --}}
        <div class="shrink-0 p-2 border-b border-gray-500 flex flex-col md:flex-row justify-between items-start md:items-center bg-[#c0c0c0] gap-3 md:gap-0">
            <div>
                <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Riwayat Transaksi</h1>
                <div class="text-gray-600 mt-1">Lihat & Cetak Ulang Struk</div>
            </div>

            <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                <form action="{{ route('owner.kasir.riwayat') }}" method="GET" class="flex items-center gap-2">
                    <label class="font-bold whitespace-nowrap">Tanggal:</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="px-2 py-1 border-2 border-gray-400 border-l-black border-t-black w-full md:w-auto">
                </form>

                <a href="{{ route('owner.kasir.index') }}"
                    class="px-4 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black font-bold flex justify-center items-center gap-1 hover:bg-red-100 text-red-900 text-center">
                    <i class="fas fa-arrow-left"></i> KEMBALI KE POS
                </a>
            </div>
        </div>

        
        {{-- Desktop Table View --}}
        <div class="p-2 flex-1 overflow-auto">
            <div class="hidden md:block bg-white border-2 border-gray-400 border-l-black border-t-black min-h-full p-1">
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
                                        
                                        <button
                                            onclick="window.open('{{ route('owner.kasir.print', $trx->id_penjualan) }}', 'Struk', 'width=400,height=600')"
                                            class="px-2 py-1 bg-gray-200 border border-gray-400 hover:bg-white text-[10px] font-bold"
                                            title="Cetak Struk">
                                            <i class="fas fa-receipt"></i> STRUK
                                        </button>

                                        
                                        <button
                                            onclick="window.open('{{ route('owner.kasir.cetak-faktur', $trx->id_penjualan) }}', 'Faktur', 'width=800,height=600')"
                                            class="px-2 py-1 bg-blue-100 border border-blue-400 hover:bg-white text-[10px] font-bold text-blue-800"
                                            title="Cetak Faktur">
                                            <i class="fas fa-file-invoice"></i> FAKTUR
                                        </button>

                                        <button
                                            onclick="window.location.href='{{ route('owner.kasir.salin', $trx->id_penjualan) }}'"
                                            class="px-2 py-1 bg-yellow-100 border border-yellow-400 hover:bg-white text-[10px] font-bold text-yellow-800"
                                            title="Salin / Re-order">
                                            <i class="fas fa-copy"></i> SALIN
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

            {{-- Mobile Card View --}}
            <div class="md:hidden flex flex-col gap-3 pb-20">
                @forelse($transaksi as $trx)
                    <div class="bg-white border-2 border-gray-400 border-l-black border-t-black p-3 shadow-md">
                        <div class="flex justify-between items-start border-b border-gray-300 pb-2 mb-2">
                            <div>
                                <div class="font-bold text-blue-900 text-sm">{{ $trx->no_faktur }}</div>
                                <div class="text-gray-500 text-xs">{{ date('H:i:s', strtotime($trx->tgl_transaksi)) }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-bold text-lg">Rp {{ number_format($trx->total_netto, 0, ',', '.') }}</div>
                                <span class="px-2 py-0.5 rounded text-[10px] {{ $trx->metode_bayar == 'Hutang' ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                                    {{ $trx->metode_bayar }}
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-3">
                            <div class="text-gray-700">
                                <span class="text-gray-500 text-xs block">Pelanggan:</span>
                                <span class="font-semibold">{{ $trx->pelanggan->nama_pelanggan ?? 'Umum' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <button
                                onclick="window.open('{{ route('owner.kasir.print', $trx->id_penjualan) }}', 'Struk', 'width=400,height=600')"
                                class="px-2 py-2 bg-gray-200 border border-gray-400 hover:bg-white text-[10px] font-bold flex flex-col items-center justify-center gap-1 active:bg-gray-300">
                                <i class="fas fa-receipt text-lg"></i> Struk
                            </button>

                            <button
                                onclick="window.open('{{ route('owner.kasir.cetak-faktur', $trx->id_penjualan) }}', 'Faktur', 'width=800,height=600')"
                                class="px-2 py-2 bg-blue-100 border border-blue-400 hover:bg-white text-[10px] font-bold text-blue-800 flex flex-col items-center justify-center gap-1 active:bg-blue-200">
                                <i class="fas fa-file-invoice text-lg"></i> Faktur
                            </button>

                            <button
                                onclick="window.location.href='{{ route('owner.kasir.salin', $trx->id_penjualan) }}'"
                                class="px-2 py-2 bg-yellow-100 border border-yellow-400 hover:bg-white text-[10px] font-bold text-yellow-800 flex flex-col items-center justify-center gap-1 active:bg-yellow-200">
                                <i class="fas fa-copy text-lg"></i> Salin
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border-2 border-gray-400 p-8 text-center text-gray-500 italic">
                        Tidak ada transaksi pada tanggal ini.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $transaksi->appends(['tanggal' => $tanggal])->links() }}
            </div>
        </div>
    </div>
@endsection
