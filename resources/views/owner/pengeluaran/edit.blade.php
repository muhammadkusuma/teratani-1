@extends('layouts.owner')
@section('title', 'Edit Pengeluaran')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-edit"></i> EDIT PENGELUARAN</h2>
    <a href="{{ route('owner.pengeluaran.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs"><i class="fa fa-arrow-left"></i> KEMBALI</a>
</div>
@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs"><ul class="list-disc ml-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="bg-white border border-gray-400 p-4">
    <form action="{{ route('owner.pengeluaran.update', $pengeluaran->id_pengeluaran) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-xs font-bold mb-1">Kode Pengeluaran</label><input type="text" name="kode_pengeluaran" value="{{ old('kode_pengeluaran', $pengeluaran->kode_pengeluaran) }}" required class="w-full border border-gray-400 p-1 text-xs shadow-inner font-mono"></div>
            <div><label class="block text-xs font-bold mb-1">Tanggal</label><input type="date" name="tanggal_pengeluaran" value="{{ old('tanggal_pengeluaran', $pengeluaran->tanggal_pengeluaran->format('Y-m-d')) }}" required class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">Kategori <span class="text-red-600">*</span></label>
                <select name="kategori" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Gaji', 'Listrik', 'Air', 'Sewa', 'ATK', 'Transportasi', 'Pemeliharaan', 'Pajak', 'Lainnya'] as $kat)
                    <option value="{{ $kat }}" {{ old('kategori', $pengeluaran->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select></div>
            <div><label class="block text-xs font-bold mb-1">Jumlah (Rp) <span class="text-red-600">*</span></label><input type="number" name="jumlah" value="{{ old('jumlah', (int)$pengeluaran->jumlah) }}" required min="0" step="1000" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></div>
        </div>
        <div class="mt-3"><label class="block text-xs font-bold mb-1">Deskripsi <span class="text-red-600">*</span></label><textarea name="deskripsi" rows="3" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('deskripsi', $pengeluaran->deskripsi) }}</textarea></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
            <div><label class="block text-xs font-bold mb-1">Metode Pembayaran <span class="text-red-600">*</span></label>
                <select name="metode_bayar" required class="w-full border border-gray-400 p-1 text-xs shadow-inner">
                    <option value="Tunai" {{ old('metode_bayar', $pengeluaran->metode_bayar) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="Transfer" {{ old('metode_bayar', $pengeluaran->metode_bayar) == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="Kredit" {{ old('metode_bayar', $pengeluaran->metode_bayar) == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                </select></div>
            <div><label class="block text-xs font-bold mb-1">Bukti Pembayaran (PDF/Gambar)</label><input type="file" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-gray-400 p-1 text-xs"></div>
        </div>
        <div class="mt-3"><label class="block text-xs font-bold mb-1">Keterangan</label><textarea name="keterangan" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea></div>
        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-blue-700 text-white border border-blue-900 px-4 py-2 text-xs hover:bg-blue-600"><i class="fa fa-save"></i> UPDATE PENGELUARAN</button>
            <a href="{{ route('owner.pengeluaran.index') }}" class="bg-gray-200 border border-gray-400 px-4 py-2 text-xs hover:bg-gray-300">BATAL</a>
        </div>
    </form>
</div>
@endsection
