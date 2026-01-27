@extends('layouts.owner')

@section('title', 'Edit Transaksi Utang Piutang Distributor')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-lg border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-edit text-blue-700"></i> Edit Transaksi Utang Piutang
    </h2>
    <a href="{{ route('owner.distributor.hutang.index') }}" class="w-full md:w-auto text-center px-4 py-1.5 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs font-bold transition-all uppercase">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white border border-gray-300 p-6 shadow-sm rounded-sm">
    <form action="{{ route('owner.distributor.hutang.update', $transaksi->id_utang_piutang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Distributor <span class="text-rose-600">*</span></label>
                <select name="id_distributor" required class="w-full border p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none @error('id_distributor') border-rose-500 @else border-gray-300 @enderror">
                    <option value="">-- Pilih Distributor --</option>
                    @foreach($distributors as $d)
                        <option value="{{ $d->id_distributor }}" {{ (old('id_distributor', $transaksi->id_distributor) == $d->id_distributor) ? 'selected' : '' }}>
                            {{ $d->nama_distributor }} ({{ $d->toko->nama_toko }})
                        </option>
                    @endforeach
                </select>
                @error('id_distributor')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Tanggal Transaksi <span class="text-rose-600">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}" required class="w-full border p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none @error('tanggal') border-rose-500 @else border-gray-300 @enderror">
                @error('tanggal')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Jenis Transaksi <span class="text-rose-600">*</span></label>
                <select name="jenis_transaksi" required class="w-full border p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none @error('jenis_transaksi') border-rose-500 @else border-gray-300 @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="utang" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'utang' ? 'selected' : '' }}>Utang (Tambah Tagihan)</option>
                    <option value="pembayaran" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'pembayaran' ? 'selected' : '' }}>Pembayaran (Kurangi Tagihan)</option>
                </select>
                @error('jenis_transaksi')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Nominal (Rp) <span class="text-rose-600">*</span></label>
                <input type="number" step="0.01" name="nominal" value="{{ old('nominal', $transaksi->nominal) }}" required placeholder="0.00" class="w-full border p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none @error('nominal') border-rose-500 @else border-gray-300 @enderror">
                @error('nominal')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">No Referensi / Bukti</label>
                <input type="text" name="no_referensi" value="{{ old('no_referensi', $transaksi->no_referensi) }}" maxlength="50" placeholder="No Invoice, Kwitansi, dll" class="w-full border p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none @error('no_referensi') border-rose-500 @else border-gray-300 @enderror">
                @error('no_referensi')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2 mt-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="3" class="w-full border p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none @error('keterangan') border-rose-500 @else border-gray-300 @enderror" placeholder="Detail transaksi...">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                @error('keterangan')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="bg-amber-50 border-l-4 border-amber-400 p-3 mt-5 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <i class="fa fa-info-circle text-amber-600 text-xs text-sm"></i> 
                <span class="text-[10px] font-black text-amber-900 uppercase tracking-widest leading-none">Pemberitahuan Sistem</span>
            </div>
            <p class="text-xs text-amber-800 font-medium leading-normal italic">
                Saldo akhir distributor akan dihitung ulang secara otomatis oleh sistem setelah perubahan ini disimpan untuk memastikan keakuratan data.
            </p>
        </div>

        <div class="flex flex-col md:flex-row gap-3 mt-8 border-t border-gray-100 pt-5">
            <button type="submit" class="w-full md:w-auto bg-blue-700 text-white border border-blue-900 px-8 py-3 text-xs font-black shadow-lg hover:bg-blue-600 hover:scale-[1.02] transition-all rounded-sm uppercase tracking-widest">
                <i class="fa fa-save"></i> Perbarui Transaksi
            </button>
            <a href="{{ route('owner.distributor.hutang.index') }}" class="w-full md:w-auto text-center bg-gray-100 text-gray-700 border border-gray-300 px-8 py-3 text-xs font-black hover:bg-gray-200 transition-all rounded-sm uppercase tracking-widest">
                <i class="fa fa-times"></i> Batalkan
            </a>
        </div>
    </form>
</div>
    </form>
</div>
@endsection
