@extends('layouts.owner')
@section('title', 'Daftar Piutang Pelanggan')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-slate-700">Kartu Piutang (Belum Lunas)</h2>
        </div>

        <div class="bg-white border shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-100 text-slate-600 border-b text-xs uppercase">
                    <tr>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">No Faktur</th>
                        <th class="p-3">Jatuh Tempo</th>
                        <th class="p-3 text-right">Total Hutang</th>
                        <th class="p-3 text-right">Sudah Bayar</th>
                        <th class="p-3 text-right">Sisa</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($piutangs as $p)
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="p-3 font-semibold">{{ $p->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                            <td class="p-3 text-blue-600">
                                <a href="#" class="hover:underline">{{ $p->penjualan->no_faktur ?? '-' }}</a>
                            </td>
                            <td class="p-3">
                                @if ($p->tgl_jatuh_tempo)
                                    <span class="{{ $p->tgl_jatuh_tempo < date('Y-m-d') ? 'text-red-600 font-bold' : '' }}">
                                        {{ date('d/m/Y', strtotime($p->tgl_jatuh_tempo)) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3 text-right">Rp {{ number_format($p->total_piutang, 0, ',', '.') }}</td>
                            <td class="p-3 text-right text-green-600">Rp {{ number_format($p->sudah_dibayar, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-right font-bold text-red-600">Rp
                                {{ number_format($p->sisa_piutang, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('owner.piutang.show', $p->id_piutang) }}"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                    <i class="fas fa-money-bill-wave"></i> Bayar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada data piutang belum lunas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3">
                {{ $piutangs->links() }}
            </div>
        </div>
    </div>
@endsection
