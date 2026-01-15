@extends('layouts.owner')
@section('title', 'Detail Pendapatan')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-file-invoice"></i> DETAIL PENDAPATAN</h2>
    <div class="flex gap-2">
        <a href="{{ route('owner.pendapatan_pasif.edit', $pendapatanPasif->id_pendapatan) }}" class="px-3 py-1 bg-yellow-400 border border-yellow-600 hover:bg-yellow-300 text-xs"><i class="fa fa-edit"></i> EDIT</a>
        <a href="{{ route('owner.pendapatan_pasif.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs"><i class="fa fa-arrow-left"></i> KEMBALI</a>
    </div>
</div>
<div class="bg-white border border-gray-400 p-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3"><i class="fa fa-info-circle"></i> INFORMASI PENDAPATAN</h3>
            <div class="space-y-2 text-xs">
                <div class="flex"><span class="w-32 text-gray-600">Kode:</span><span class="font-mono font-bold">{{ $pendapatanPasif->kode_pendapatan }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Tanggal:</span><span class="font-semibold">{{ $pendapatanPasif->tanggal_pendapatan?->format('d/m/Y') }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Kategori:</span><span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded font-bold">{{ $pendapatanPasif->kategori }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Jumlah:</span><span class="font-bold text-green-700 text-lg">Rp {{ number_format($pendapatanPasif->jumlah, 0, ',', '.') }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Metode Bayar:</span><span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold">{{ $pendapatanPasif->metode_terima }}</span></div>
                <div class="flex"><span class="w-32 text-gray-600">Dicatat oleh:</span><span class="font-semibold">{{ $pendapatanPasif->user->name ?? '-' }}</span></div>
            </div>
        </div>
        <div>
            <h3 class="font-bold text-sm border-b border-gray-300 pb-2 mb-3"><i class="fa fa-sticky-note"></i> DESKRIPSI & KETERANGAN</h3>
            <div class="text-xs space-y-3">
                <div>
                    <div class="text-gray-600 mb-1">Sumber:</div>
                    <div class="bg-gray-50 border border-gray-300 p-2 rounded">{{ $pendapatanPasif->sumber }}</div>
                </div>
                @if($pendapatanPasif->keterangan)
                <div>
                    <div class="text-gray-600 mb-1">Keterangan:</div>
                    <div class="bg-gray-50 border border-gray-300 p-2 rounded">{{ $pendapatanPasif->keterangan }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @if($pendapatanPasif->bukti_penerimaan)
    <div class="mt-4 border-t border-gray-300 pt-4">
        <h3 class="font-bold text-sm mb-3"><i class="fa fa-file-alt"></i> BUKTI PEMBAYARAN</h3>
        <div class="bg-gray-50 border border-gray-300 p-3 rounded">
            @if(Str::endsWith($pendapatanPasif->bukti_penerimaan, '.pdf'))
                <a href="{{ asset('storage/' . $pendapatanPasif->bukti_penerimaan) }}" target="_blank" class="text-blue-600 hover:underline text-xs">
                    <i class="fa fa-file-pdf text-green-500 text-2xl"></i> Lihat Bukti Penerimaan (PDF)
                </a>
            @else
                <img src="{{ asset('storage/' . $pendapatanPasif->bukti_penerimaan) }}" alt="Bukti Penerimaan" class="max-w-md border border-gray-400">
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
