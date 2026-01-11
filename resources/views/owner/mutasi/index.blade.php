@extends('layouts.owner')

@section('title', 'Transfer Stok Antar Toko')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">RIWAYAT TRANSFER STOK</h2>
    <a href="{{ route('owner.mutasi.create') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
        + BUAT TRANSFER BARU
    </a>
</div>

{{-- Filter/Search Placeholder (Jika diperlukan) --}}
<div class="mb-3 flex gap-2">
    <div class="text-xs text-gray-500 italic py-1">Menampilkan data transfer stok terbaru.</div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto border border-gray-400 bg-white">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                <th class="border border-gray-400 p-2 text-center w-10">No</th>
                <th class="border border-gray-400 p-2">No Bukti</th>
                <th class="border border-gray-400 p-2">Tanggal</th>
                <th class="border border-gray-400 p-2 text-red-700">Dari Toko</th>
                <th class="border border-gray-400 p-2 text-green-700">Ke Toko</th>
                <th class="border border-gray-400 p-2">Pengirim</th>
                <th class="border border-gray-400 p-2 text-center">Status</th>
                <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mutasi as $index => $m)
            <tr class="hover:bg-yellow-50 text-xs">
                <td class="border border-gray-300 p-2 text-center">{{ $mutasi->firstItem() + $index }}</td>
                <td class="border border-gray-300 p-2 font-mono font-bold">{{ $m->no_mutasi }}</td>
                <td class="border border-gray-300 p-2">{{ date('d/m/Y H:i', strtotime($m->tgl_kirim)) }}</td>
                <td class="border border-gray-300 p-2 text-red-700">{{ $m->tokoAsal->nama_toko ?? '-' }}</td>
                <td class="border border-gray-300 p-2 text-green-700">{{ $m->tokoTujuan->nama_toko ?? '-' }}</td>
                <td class="border border-gray-300 p-2">{{ $m->pengirim->name ?? '-' }}</td>
                <td class="border border-gray-300 p-2 text-center">
                    @php
                        $badgeColor = match ($m->status) {
                            'Proses' => 'bg-yellow-200 text-yellow-800 border-yellow-400',
                            'Dikirim' => 'bg-blue-200 text-blue-800 border-blue-400',
                            'Diterima' => 'bg-green-200 text-green-800 border-green-400',
                            'Batal' => 'bg-red-200 text-red-800 border-red-400',
                            default => 'bg-gray-200 text-gray-800 border-gray-400',
                        };
                    @endphp
                    <span class="px-2 py-0.5 border text-[10px] font-bold {{ $badgeColor }}">
                        {{ strtoupper($m->status) }}
                    </span>
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    <a href="{{ route('owner.mutasi.show', $m->id_mutasi) }}" class="bg-gray-100 border border-gray-400 px-2 py-0.5 text-[10px] hover:bg-gray-200 text-blue-700">DETAIL</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada riwayat transfer.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $mutasi->links() }}
</div>
@endsection