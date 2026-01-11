@extends('layouts.owner')

@section('title', 'Tambah Pengeluaran')

@section('content')
    <div class="max-w-4xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 uppercase">INPUT PENGELUARAN BARU</h2>
            <a href="{{ route('owner.pengeluaran.index') }}"
                class="text-blue-700 underline text-xs hover:text-blue-500">&laquo; Kembali</a>
        </div>

        <form action="{{ route('owner.pengeluaran.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Kolom Kiri: Detail Utama --}}
                <div class="space-y-3">
                    <h3 class="font-bold text-blue-800 border-b border-gray-300 pb-1 text-xs">A. DETAIL TRANSAKSI</h3>

                    <div>
                        <label class="block font-bold text-xs mb-1">Tanggal Pengeluaran <span
                                class="text-red-600">*</span></label>
                        <input type="date" name="tgl_pengeluaran" value="{{ old('tgl_pengeluaran', date('Y-m-d')) }}"
                            class="w-full border border-gray-400 p-1 text-sm bg-white" required>
                    </div>

                    <div>
                        <label class="block font-bold text-xs mb-1">Kategori Biaya <span
                                class="text-red-600">*</span></label>
                        <select name="kategori_biaya" class="w-full border border-gray-400 p-1 text-sm bg-white" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Gaji Karyawan" {{ old('kategori_biaya') == 'Gaji Karyawan' ? 'selected' : '' }}>
                                Gaji Karyawan</option>
                            <option value="Listrik & Air" {{ old('kategori_biaya') == 'Listrik & Air' ? 'selected' : '' }}>
                                Listrik & Air</option>
                            <option value="Sewa Tempat" {{ old('kategori_biaya') == 'Sewa Tempat' ? 'selected' : '' }}>Sewa
                                Tempat</option>
                            <option value="Perlengkapan Toko"
                                {{ old('kategori_biaya') == 'Perlengkapan Toko' ? 'selected' : '' }}>Perlengkapan Toko
                            </option>
                            <option value="Transportasi" {{ old('kategori_biaya') == 'Transportasi' ? 'selected' : '' }}>
                                Transportasi</option>
                            <option value="Maintenance" {{ old('kategori_biaya') == 'Maintenance' ? 'selected' : '' }}>
                                Perbaikan / Maintenance</option>
                            <option value="Lainnya" {{ old('kategori_biaya') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-xs mb-1">Nominal (Rp) <span class="text-red-600">*</span></label>
                        <input type="number" name="nominal" value="{{ old('nominal') }}" placeholder="0" min="0"
                            class="w-full border border-gray-400 p-1 text-sm text-right font-bold" required>
                    </div>
                </div>

                {{-- Kolom Kanan: Keterangan & Bukti --}}
                <div class="space-y-3">
                    <h3 class="font-bold text-blue-800 border-b border-gray-300 pb-1 text-xs">B. KETERANGAN & BUKTI</h3>

                    <div>
                        <label class="block font-bold text-xs mb-1">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="4" class="w-full border border-gray-400 p-1 text-sm bg-white"
                            placeholder="Tulis detail pengeluaran...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-xs mb-1">Bukti Foto / Struk</label>
                        <input type="file" name="bukti_foto" class="w-full text-xs border border-gray-400 bg-white p-1"
                            accept="image/*">
                        <p class="text-[10px] text-gray-500 mt-1 italic">*Format: JPG, PNG. Maks 2MB.</p>
                    </div>
                </div>

            </div>

            <div class="mt-4 border-t border-gray-300 pt-3 text-right">
                <button type="submit"
                    class="bg-blue-800 text-white px-4 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs">
                    SIMPAN PENGELUARAN
                </button>
            </div>
        </form>
    </div>
@endsection
