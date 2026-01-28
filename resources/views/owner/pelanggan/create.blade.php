@extends('layouts.owner')

@section('title', 'Tambah Pelanggan')

@section('content')
    <div class="max-w-3xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
            <h2 class="font-bold text-lg md:text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
                <i class="fa fa-user-plus text-blue-700"></i> Tambah Pelanggan
            </h2>
            <a href="{{ route('owner.pelanggan.index') }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('owner.pelanggan.store') }}" method="POST"
            class="bg-white p-4 md:p-6 border border-gray-300 shadow-sm rounded-sm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Kode Pelanggan --}}
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                        Kode Pelanggan (Opsional)
                    </label>
                    <input type="text" name="kode_pelanggan" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-gray-50 shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm"
                        placeholder="Kosongkan untuk auto-generate">
                </div>

                {{-- Nama Pelanggan --}}
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                        Nama Pelanggan <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nama_pelanggan" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required
                        placeholder="Contoh: Toko Sejahtera">
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                        No. Handphone / WA
                    </label>
                    <input type="text" name="no_hp" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm"
                        placeholder="Contoh: 0812...">
                </div>

                {{-- Wilayah --}}
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                        Wilayah / Area
                    </label>
                    <input type="text" name="wilayah" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm"
                        placeholder="Contoh: Pasar Induk">
                </div>

                {{-- Limit Piutang --}}
                <div>
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                        Limit Piutang (Rp)
                    </label>
                    <input type="number" name="limit_piutang" value="0"
                        class="w-full border border-gray-300 p-2.5 md:p-2 text-xs text-right shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" placeholder="0">
                    <small class="text-[10px] text-gray-500 flex items-center gap-1 mt-1">
                        <i class="fa fa-info-circle text-blue-400"></i> Batas maksimal hutang yang diperbolehkan.
                    </small>
                </div>

                {{-- Kategori Harga --}}
                <div>
                    <label class="block font-black mb-2 text-[10px] text-emerald-700 bg-emerald-50 px-2 py-1 w-fit uppercase tracking-wider rounded-sm border border-emerald-200">
                        <i class="fa fa-tags"></i> Kategori Harga
                    </label>
                    <select name="kategori_harga" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
                        <option value="umum" selected>Umum / Eceran (Harga Normal)</option>
                        <option value="grosir">Grosir (Harga Grosir)</option>
                        <option value="r1">Langganan R1 (Harga Khusus R1)</option>
                        <option value="r2">Langganan R2 (Harga Khusus R2)</option>
                    </select>
                    <small class="text-[10px] text-gray-500 flex items-center gap-1 mt-1">
                        <i class="fa fa-info-circle text-blue-400"></i> Harga yang akan digunakan di kasir untuk pelanggan ini.
                    </small>
                </div>

                {{-- Alamat Lengkap --}}
                <div class="md:col-span-2">
                    <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                        Alamat Lengkap
                    </label>
                    <textarea name="alamat" rows="3" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm"></textarea>
                </div>

            </div>

            <div class="flex flex-col md:flex-row justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('owner.pelanggan.index') }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                    <i class="fa fa-times"></i> Batal
                </a>
                <button type="submit"
                    class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 font-bold text-xs transition-all rounded-sm uppercase">
                    <i class="fa fa-save"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
