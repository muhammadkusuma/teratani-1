@extends('layouts.owner')

@section('title', 'Edit Transaksi Utang Piutang Distributor')

@section('content')
<div class="mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 inline-block pr-4">
        <i class="fa fa-edit"></i> EDIT TRANSAKSI UTANG PIUTANG DISTRIBUTOR
    </h2>
</div>

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white border border-gray-400 p-4">
    <form action="{{ route('owner.utang-piutang-distributor.update', $transaksi->id_utang_piutang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold mb-1">Distributor <span class="text-red-600">*</span></label>
                <select name="id_distributor" required class="w-full border border-gray-400 p-2 text-xs shadow-inner @error('id_distributor') border-red-500 @enderror">
                    <option value="">-- Pilih Distributor --</option>
                    @foreach($distributors as $d)
                        <option value="{{ $d->id_distributor }}" {{ (old('id_distributor', $transaksi->id_distributor) == $d->id_distributor) ? 'selected' : '' }}>
                            {{ $d->nama_distributor }} ({{ $d->toko->nama_toko }})
                        </option>
                    @endforeach
                </select>
                @error('id_distributor')
                    <span class="text-red-600 text-[10px]">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-1">Tanggal <span class="text-red-600">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}" required class="w-full border border-gray-400 p-2 text-xs shadow-inner @error('tanggal') border-red-500 @enderror">
                @error('tanggal')
                    <span class="text-red-600 text-[10px]">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-1">Jenis Transaksi <span class="text-red-600">*</span></label>
                <select name="jenis_transaksi" required class="w-full border border-gray-400 p-2 text-xs shadow-inner @error('jenis_transaksi') border-red-500 @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="utang" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'utang' ? 'selected' : '' }}>Utang (Tambah Utang)</option>
                    <option value="pembayaran" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'pembayaran' ? 'selected' : '' }}>Pembayaran (Bayar Utang)</option>
                </select>
                @error('jenis_transaksi')
                    <span class="text-red-600 text-[10px]">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-1">Nominal <span class="text-red-600">*</span></label>
                <input type="number" step="0.01" name="nominal" value="{{ old('nominal', $transaksi->nominal) }}" required placeholder="0" class="w-full border border-gray-400 p-2 text-xs shadow-inner @error('nominal') border-red-500 @enderror">
                @error('nominal')
                    <span class="text-red-600 text-[10px]">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-1">No Referensi</label>
                <input type="text" name="no_referensi" value="{{ old('no_referensi', $transaksi->no_referensi) }}" maxlength="50" placeholder="No PO, Invoice, dll" class="w-full border border-gray-400 p-2 text-xs shadow-inner @error('no_referensi') border-red-500 @enderror">
                @error('no_referensi')
                    <span class="text-red-600 text-[10px]">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-2">
                <label class="block text-xs font-bold mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full border border-gray-400 p-2 text-xs shadow-inner @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                @error('keterangan')
                    <span class="text-red-600 text-[10px]">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-400 p-2 mt-4 text-xs">
            <i class="fa fa-info-circle text-yellow-600"></i> 
            <strong>Catatan:</strong> Saldo akan di-recalculate secara otomatis setelah perubahan.
        </div>

        <div class="flex gap-2 mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white border border-blue-800 text-xs hover:bg-blue-500">
                <i class="fa fa-save"></i> UPDATE
            </button>
            <a href="{{ route('owner.utang-piutang-distributor.index') }}" class="px-4 py-2 bg-gray-400 text-white border border-gray-600 text-xs hover:bg-gray-300">
                <i class="fa fa-times"></i> BATAL
            </a>
        </div>
    </form>
</div>
@endsection
