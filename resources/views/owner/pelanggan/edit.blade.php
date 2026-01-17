@extends('layouts.owner')

@section('title', 'Edit Pelanggan')

@section('content')
    <div class="max-w-3xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">EDIT DATA PELANGGAN</h2>
            <a href="{{ route('owner.pelanggan.index') }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo;
                Kembali</a>
        </div>

        <form action="{{ route('owner.pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST"
            class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-bold text-xs mb-1">Kode Pelanggan</label>
                    <input type="text" name="kode_pelanggan" value="{{ $pelanggan->kode_pelanggan }}"
                        class="w-full border border-gray-400 p-1 text-sm bg-yellow-50">
                </div>

                <div>
                    <label class="block font-bold text-xs mb-1">Nama Pelanggan <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_pelanggan" value="{{ $pelanggan->nama_pelanggan }}"
                        class="w-full border border-gray-400 p-1 text-sm" required>
                </div>

                <div>
                    <label class="block font-bold text-xs mb-1">No. Handphone / WA</label>
                    <input type="text" name="no_hp" value="{{ $pelanggan->no_hp }}"
                        class="w-full border border-gray-400 p-1 text-sm">
                </div>

                <div>
                    <label class="block font-bold text-xs mb-1">Wilayah / Area</label>
                    <input type="text" name="wilayah" value="{{ $pelanggan->wilayah }}"
                        class="w-full border border-gray-400 p-1 text-sm">
                </div>

                <div>
                    <label class="block font-bold text-xs mb-1">Limit Piutang (Rp)</label>
                    <input type="number" name="limit_piutang" value="{{ $pelanggan->limit_piutang }}"
                        class="w-full border border-gray-400 p-1 text-sm text-right">
                </div>

                
                <div>
                    <label class="block font-bold text-xs mb-1 bg-green-100 px-2 py-1 w-fit">💰 Kategori Harga</label>
                    <select name="kategori_harga" class="w-full border border-gray-400 p-1 text-sm bg-white">
                        <option value="umum" {{ ($pelanggan->kategori_harga ?? 'umum') == 'umum' ? 'selected' : '' }}>Umum / Eceran (Harga Normal)</option>
                        <option value="grosir" {{ ($pelanggan->kategori_harga ?? 'umum') == 'grosir' ? 'selected' : '' }}>Grosir (Harga Grosir)</option>
                        <option value="r1" {{ ($pelanggan->kategori_harga ?? 'umum') == 'r1' ? 'selected' : '' }}>Langganan R1 (Harga Khusus R1)</option>
                        <option value="r2" {{ ($pelanggan->kategori_harga ?? 'umum') == 'r2' ? 'selected' : '' }}>Langganan R2 (Harga Khusus R2)</option>
                    </select>
                    <small class="text-[10px] text-gray-500">Harga yang akan digunakan di kasir untuk pelanggan ini.</small>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-bold text-xs mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full border border-gray-400 p-1 text-sm">{{ $pelanggan->alamat }}</textarea>
                </div>
            </div>

            <div class="mt-4 border-t border-gray-300 pt-3 text-right">
                <button type="submit"
                    class="bg-orange-600 text-white px-4 py-2 border border-orange-800 shadow hover:bg-orange-500 font-bold text-xs">
                    UPDATE PERUBAHAN
                </button>
            </div>
        </form>
    </div>
@endsection
