@extends('layouts.owner')
@section('title', 'Detail Pengeluaran')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-file-invoice"></i> DETAIL PENGELUARAN</h2>
    <div class="flex gap-2">
        <a href="{{ route('owner.pengeluaran.edit', $pengeluaran->id_pengeluaran) }}" class="px-3 py-1 bg-yellow-400 border border-yellow-600 hover:bg-yellow-300 text-xs"><i class="fa fa-edit"></i> EDIT</a>
        <a href="{{ route('owner.pengeluaran.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs"><i class="fa fa-arrow-left"></i> KEMBALI</a>
    </div>
</div>
<div class="bg-white border border-gray-400 p-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3"><i class="fa fa-info-circle"></i> INFORMASI PENGELUARAN</h3>
            <div class="space-y-2 text-xs">
                <div class="flex"><span class="w-32 text-gray-600">Kode:</span><span class="font-mono font-bold">{{ $pengeluaran->kode_pengeluaran }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Tanggal:</span><span class="font-semibold">{{ $pengeluaran->tanggal_pengeluaran->format('d/m/Y') }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Kategori:</span><span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded font-bold">{{ $pengeluaran->kategori }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Jumlah:</span><span class="font-bold text-red-700 text-lg">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Metode Bayar:</span><span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold">{{ $pengeluaran->metode_bayar }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Dicatat oleh:</span><span class="font-semibold">{{ $pengeluaran->user->name ?? '-' }}</span></div>
            </div>
        </div>
        <div>
            <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3"><i class="fa fa-sticky-note"></i> DESKRIPSI & KETERANGAN</h3>
            <div class="text-xs space-y-3">
                <div>
                    <div class="text-gray-600 mb-1">Deskripsi:</div>
                    <div class="bg-gray-50 border border-gray-300 p-2 rounded">{{ $pengeluaran->deskripsi }}</div>
                </div>
                @if($pengeluaran->keterangan)
                <div>
                    <div class="text-gray-600 mb-1">Keterangan:</div>
                    <div class="bg-gray-50 border border-gray-300 p-2 rounded">{{ $pengeluaran->keterangan }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @if($pengeluaran->bukti_pembayaran)
    <div class="mt-4 border-t border-gray-300 pt-4">
        <h3 class="font-bold text-sm mb-3"><i class="fa fa-file-alt"></i> BUKTI PEMBAYARAN</h3>
        <div class="bg-gray-50 border border-gray-300 p-3 rounded">
            @if(Str::endsWith($pengeluaran->bukti_pembayaran, '.pdf'))
                <a href="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline text-xs">
                    <i class="fa fa-file-pdf text-red-500 text-2xl"></i> Lihat Bukti Pembayaran (PDF)
                </a>
            @else
                <img src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="max-w-md border border-gray-400">
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
