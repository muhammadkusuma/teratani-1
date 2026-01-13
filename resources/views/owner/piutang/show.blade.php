@extends('layouts.owner')
@section('title', 'Detail & Pembayaran Piutang')

@section('content')
    <div class="p-4 max-w-5xl mx-auto">

        {{-- Alert --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 mb-4 rounded border border-green-200">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 mb-4 rounded border border-red-200">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Kolom Kiri: Informasi Hutang --}}
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white border p-4 shadow-sm">
                    <h3 class="font-bold text-lg mb-3 border-b pb-2">Informasi Piutang</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-slate-500 w-1/3">No. Faktur Penjualan</td>
                            <td class="font-bold">{{ $piutang->penjualan->no_faktur ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-slate-500">Nama Pelanggan</td>
                            <td class="font-bold text-blue-700">{{ $piutang->pelanggan->nama_pelanggan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-slate-500">Jatuh Tempo</td>
                            <td>{{ $piutang->tgl_jatuh_tempo ? date('d F Y', strtotime($piutang->tgl_jatuh_tempo)) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1 text-slate-500">Total Hutang Awal</td>
                            <td class="font-bold">Rp {{ number_format($piutang->total_piutang, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    <div class="mt-4 p-3 bg-slate-50 border rounded flex justify-between items-center">
                        <span class="text-slate-600 font-bold">SISA PIUTANG</span>
                        <span class="text-xl font-black text-red-600">Rp
                            {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Tabel Riwayat Pembayaran --}}
                <div class="bg-white border shadow-sm">
                    <div class="p-3 bg-slate-100 border-b font-bold text-slate-700">Riwayat Pembayaran</div>
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="p-2">Tanggal</th>
                                <th class="p-2">Metode</th>
                                <th class="p-2">Ket</th>
                                <th class="p-2 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($piutang->pembayarans as $bayar)
                                <tr class="border-b">
                                    <td class="p-2">{{ date('d/m/Y H:i', strtotime($bayar->tgl_bayar)) }}</td>
                                    <td class="p-2">{{ $bayar->metode_bayar }}</td>
                                    <td class="p-2 text-xs italic">{{ $bayar->keterangan ?? '-' }}</td>
                                    <td class="p-2 text-right font-bold text-green-600">Rp
                                        {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-slate-400">Belum ada riwayat pembayaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kolom Kanan: Form Bayar --}}
            <div>
                <div class="bg-white border shadow-sm p-4 sticky top-4">
                    <h3 class="font-bold text-lg mb-3 border-b pb-2"><i class="fas fa-cash-register"></i> Input Pembayaran
                    </h3>

                    @if ($piutang->status == 'Lunas')
                        <div class="bg-green-100 text-green-700 p-4 text-center font-bold border border-green-300">
                            <i class="fas fa-check-circle text-2xl mb-2"></i><br>
                            LUNAS
                        </div>
                        <a href="{{ route('owner.piutang.index') }}"
                            class="block mt-4 text-center text-blue-600 hover:underline">Kembali ke Daftar</a>
                    @else
                        <form action="{{ route('owner.piutang.storePayment', $piutang->id_piutang) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="block text-xs font-bold text-slate-600 mb-1">Tanggal Bayar</label>
                                <input type="date" name="tgl_bayar" value="{{ date('Y-m-d') }}"
                                    class="w-full border p-2 text-sm focus:outline-none focus:border-blue-500">
                            </div>

                            <div class="mb-3">
                                <label class="block text-xs font-bold text-slate-600 mb-1">Jumlah Bayar (Rp)</label>
                                <input type="number" name="jumlah_bayar" max="{{ $piutang->sisa_piutang }}" required
                                    class="w-full border p-2 text-sm font-bold text-right focus:outline-none focus:border-blue-500"
                                    placeholder="0">
                                <div class="text-[10px] text-slate-500 text-right mt-1">Maks:
                                    {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="block text-xs font-bold text-slate-600 mb-1">Metode Bayar</label>
                                <select name="metode_bayar" class="w-full border p-2 text-sm bg-white">
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-slate-600 mb-1">Keterangan (Opsional)</label>
                                <textarea name="keterangan" rows="2" class="w-full border p-2 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Catatan pembayaran..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded shadow-lg transition transform active:scale-95">
                                SIMPAN PEMBAYARAN
                            </button>

                            <a href="{{ route('owner.piutang.index') }}"
                                class="block mt-3 text-center text-xs text-slate-500 hover:text-slate-800">Batal /
                                Kembali</a>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
