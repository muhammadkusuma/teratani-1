@extends('layouts.owner')
@section('title', 'Edit Pendapatan')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-edit"></i> EDIT PENDAPATAN</h2>
    <a href="{{ route('owner.pendapatan_pasif.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs"><i class="fa fa-arrow-left"></i> KEMBALI</a>
</div>
@if($errors->any())
<div class="bg-green-100 border border-red-400 text-green-700 px-2 py-1 mb-2 text-xs"><ul class="list-disc ml-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="bg-white border border-gray-400 p-4">
    <form action="{{ route('owner.pendapatan_pasif.update', $pendapatanPasif->id_pendapatan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-bold mb-1">Kode Pendapatan <span class="text-green-600">*</span></label><input type="text" name="kode_pendapatan" value="{{ old('kode_pendapatan', $pendapatanPasif->kode_pendapatan) }}" required class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono"></div>
            <div><label class="block text-xs font-bold mb-1">Tanggal <span class="text-green-600">*</span></label><input type="date" name="tanggal_pendapatan" value="{{ old('tanggal_pendapatan', $pendapatanPasif->tanggal_pendapatan->format('Y-m-d')) }}" required class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">Kategori <span class="text-green-600">*</span></label>
                <select name="kategori" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Bunga Bank', 'Investasi Aset', 'Komisi', 'Investasi', 'Lainnya'] as $kat)
                    <option value="{{ $kat }}" {{ old('kategori', $pendapatanPasif->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select></div>
            <div><label class="block text-xs font-bold mb-1">Jumlah (Rp) <span class="text-green-600">*</span></label><input type="number" name="jumlah" value="{{ old('jumlah', (int)$pendapatanPasif->jumlah) }}" required min="0" step="1000" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        </div>
        <div class="mt-3"><label class="block text-xs font-bold mb-1">Sumber <span class="text-green-600">*</span></label><textarea name="sumber" rows="3" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('sumber', $pendapatanPasif->sumber) }}</textarea></div>
        <div class="grid grid-cols-2 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">Metode Penerimaan <span class="text-green-600">*</span></label>
                <select name="metode_terima" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="Tunai" {{ old('metode_terima', $pendapatanPasif->metode_terima) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="Transfer" {{ old('metode_terima', $pendapatanPasif->metode_terima) == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                </select></div>
            <div><label class="block text-xs font-bold mb-1">Bukti Penerimaan (PDF/Gambar)</label><input type="file" name="bukti_penerimaan" accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-gray-400 p-1 text-xs"></div>
        </div>
        <div class="mt-3"><label class="block text-xs font-bold mb-1">Keterangan</label><textarea name="keterangan" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('keterangan', $pendapatanPasif->keterangan) }}</textarea></div>
        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-blue-700 text-white border border-blue-900 px-4 py-2 text-xs hover:bg-blue-600"><i class="fa fa-save"></i> UPDATE PENDAPATAN</button>
            <a href="{{ route('owner.pendapatan_pasif.index') }}" class="bg-gray-200 border border-gray-400 px-4 py-2 text-xs hover:bg-gray-300">BATAL</a>
        </div>
    </form>
</div>
@endsection
