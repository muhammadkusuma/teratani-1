@extends('layouts.owner')
@section('title', 'Detail & Pembayaran Piutang')

@section('content')
    {{-- Header Page --}}
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block uppercase">Rincian Piutang</h2>
            <span class="text-xs text-gray-500 ml-2">Transaksi #{{ $piutang->penjualan->no_faktur ?? '-' }}</span>
        </div>
        <a href="{{ route('owner.piutang.index') }}"
            class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300 text-gray-700 uppercase shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 mb-3 text-xs">
            <i class="fas fa-check mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 mb-3 text-xs">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- KOlom Kiri: Informasi & Riwayat --}}
        <div class="md:col-span-2 space-y-4">

            {{-- Informasi Piutang --}}
            <div class="border border-gray-400 bg-white">
                <div class="bg-gray-200 p-2 border-b border-gray-400 text-xs font-bold text-gray-700 uppercase">
                    Informasi Pelanggan & Hutang
                </div>
                <table class="w-full text-xs text-left border-collapse">
                    <tbody>
                        <tr class="border-b border-gray-300">
                            <td class="p-2 w-1/3 bg-gray-50 border-r border-gray-300 font-semibold text-gray-600 uppercase">
                                Nama Pelanggan</td>
                            <td class="p-2 font-bold text-blue-800 uppercase">
                                {{ $piutang->pelanggan->nama_pelanggan ?? 'UMUM' }}</td>
                        </tr>
                        <tr class="border-b border-gray-300">
                            <td class="p-2 bg-gray-50 border-r border-gray-300 font-semibold text-gray-600 uppercase">Jatuh
                                Tempo</td>
                            <td
                                class="p-2 {{ $piutang->tgl_jatuh_tempo < date('Y-m-d') && $piutang->sisa_piutang > 0 ? 'text-red-600 font-bold' : '' }}">
                                {{ $piutang->tgl_jatuh_tempo ? date('d F Y', strtotime($piutang->tgl_jatuh_tempo)) : '-' }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-300">
                            <td class="p-2 bg-gray-50 border-r border-gray-300 font-semibold text-gray-600 uppercase">Total
                                Hutang Awal</td>
                            <td class="p-2 font-mono">Rp {{ number_format($piutang->total_piutang, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td
                                class="p-2 bg-gray-50 border-r border-gray-300 font-semibold text-gray-600 uppercase align-middle">
                                Sisa Tagihan</td>
                            <td class="p-2">
                                <span
                                    class="text-lg font-mono font-bold text-red-600 border border-red-200 bg-red-50 px-2 py-1 inline-block">
                                    Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Riwayat Pembayaran --}}
            <div class="border border-gray-400 bg-white">
                <div
                    class="bg-gray-200 p-2 border-b border-gray-400 text-xs font-bold text-gray-700 uppercase flex justify-between items-center">
                    <span>Riwayat Pembayaran</span>
                    <span class="text-[10px] font-normal text-gray-500">{{ $piutang->pembayarans->count() }}
                        Transaksi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase border-b border-gray-300">
                                <th class="p-2 border-r border-gray-300 w-32">Tanggal</th>
                                <th class="p-2 border-r border-gray-300 w-24">Metode</th>
                                <th class="p-2 border-r border-gray-300">Keterangan</th>
                                <th class="p-2 text-right w-32">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($piutang->pembayarans as $bayar)
                                <tr class="border-b border-gray-300 hover:bg-yellow-50">
                                    <td class="p-2 border-r border-gray-300">
                                        {{ date('d/m/Y', strtotime($bayar->tgl_bayar)) }}
                                        <span
                                            class="text-[10px] text-gray-500 block">{{ date('H:i', strtotime($bayar->tgl_bayar)) }}</span>
                                    </td>
                                    <td class="p-2 border-r border-gray-300 uppercase">{{ $bayar->metode_bayar }}</td>
                                    <td class="p-2 border-r border-gray-300 italic text-gray-500">
                                        {{ $bayar->keterangan ?? '-' }}</td>
                                    <td class="p-2 text-right font-mono font-bold text-green-700">
                                        Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-gray-500 italic">
                                        Belum ada data pembayaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Form --}}
        <div>
            <div class="border border-gray-400 bg-white sticky top-4 shadow-md">
                <div class="bg-blue-800 p-2 text-white text-xs font-bold uppercase tracking-wide border-b border-blue-900">
                    <i class="fas fa-cash-register mr-1"></i> Input Pembayaran
                </div>

                <div class="p-4">
                    @if ($piutang->status == 'Lunas')
                        <div class="bg-green-100 border-2 border-green-500 text-green-800 p-6 text-center">
                            <i class="fas fa-check-circle text-4xl mb-2 block text-green-600"></i>
                            <span class="font-black text-xl uppercase tracking-widest">LUNAS</span>
                            <p class="text-xs mt-2">Seluruh tagihan telah diselesaikan.</p>
                        </div>
                    @else
                        <form action="{{ route('owner.piutang.storePayment', $piutang->id_piutang) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Tanggal
                                    Bayar</label>
                                <input type="date" name="tgl_bayar" value="{{ date('Y-m-d') }}"
                                    class="w-full border border-gray-400 p-1.5 text-xs focus:outline-none focus:border-blue-600 shadow-inner bg-gray-50">
                            </div>

                            <div class="mb-3">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jumlah Bayar
                                    (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-2 top-1.5 text-gray-500 text-xs font-mono">Rp</span>
                                    <input type="number" name="jumlah_bayar" max="{{ $piutang->sisa_piutang }}" required
                                        class="w-full border border-gray-400 p-1.5 pl-8 text-right text-xs font-mono font-bold focus:outline-none focus:border-blue-600 shadow-inner bg-white"
                                        placeholder="0">
                                </div>
                                <div class="text-[10px] text-gray-500 text-right mt-1 font-mono">
                                    Maks: {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Metode Bayar</label>
                                <select name="metode_bayar"
                                    class="w-full border border-gray-400 p-1.5 text-xs bg-white focus:outline-none focus:border-blue-600 shadow-sm">
                                    <option value="Tunai">TUNAI</option>
                                    <option value="Transfer">TRANSFER BANK</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Keterangan</label>
                                <textarea name="keterangan" rows="2"
                                    class="w-full border border-gray-400 p-1.5 text-xs focus:outline-none focus:border-blue-600 shadow-inner bg-gray-50"
                                    placeholder="Opsional..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-700 text-white font-bold py-2 text-xs uppercase border border-blue-900 shadow-sm hover:bg-blue-600 tracking-wider">
                                <i class="fas fa-save mr-1"></i> Simpan Transaksi
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
