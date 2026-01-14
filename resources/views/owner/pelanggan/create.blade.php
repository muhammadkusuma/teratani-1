@extends('layouts.owner')

@section('title', 'Tambah Pelanggan')

@section('content')
    <div class="max-w-3xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">INPUT DATA PELANGGAN BARU</h2>
            <a href="{{ route('owner.pelanggan.index') }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo;
                Kembali</a>
        </div>

        <form action="{{ route('owner.pelanggan.store') }}" method="POST"
            class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                
                <div>
                    <label class="block font-bold text-xs mb-1">Kode Pelanggan (Opsional)</label>
                    <input type="text" name="kode_pelanggan" class="w-full border border-gray-400 p-1 text-sm bg-gray-50"
                        placeholder="Kosongkan untuk auto-generate">
                </div>

                
                <div>
                    <label class="block font-bold text-xs mb-1">Nama Pelanggan <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_pelanggan" class="w-full border border-gray-400 p-1 text-sm" required
                        placeholder="Contoh: Toko Sejahtera">
                </div>

                
                <div>
                    <label class="block font-bold text-xs mb-1">No. Handphone / WA</label>
                    <input type="text" name="no_hp" class="w-full border border-gray-400 p-1 text-sm"
                        placeholder="Contoh: 0812...">
                </div>

                
                <div>
                    <label class="block font-bold text-xs mb-1">Wilayah / Area</label>
                    <input type="text" name="wilayah" class="w-full border border-gray-400 p-1 text-sm"
                        placeholder="Contoh: Pasar Induk">
                </div>

                
                <div>
                    <label class="block font-bold text-xs mb-1">Limit Piutang (Rp)</label>
                    <input type="number" name="limit_piutang" value="0"
                        class="w-full border border-gray-400 p-1 text-sm text-right" placeholder="0">
                    <small class="text-[10px] text-gray-500">Batas maksimal hutang yang diperbolehkan.</small>
                </div>

                
                <div class="md:col-span-2">
                    <label class="block font-bold text-xs mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full border border-gray-400 p-1 text-sm"></textarea>
                </div>

            </div>

            <div class="mt-4 border-t border-gray-300 pt-3 text-right">
                <button type="submit"
                    class="bg-blue-800 text-white px-4 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs">
                    SIMPAN DATA
                </button>
            </div>
        </form>
    </div>
@endsection
