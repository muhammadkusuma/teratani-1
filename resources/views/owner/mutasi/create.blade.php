@extends('layouts.owner')

@section('title', 'Buat Transfer Stok')

@section('content')
    <div x-data="mutasiForm()">
        <form action="{{ route('owner.mutasi.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="p-3 bg-gray-100 border border-gray-300">
                    <label class="block font-bold mb-1">Tanggal Kirim</label>
                    <input type="datetime-local" name="tgl_kirim" class="w-full p-1 border border-gray-400" required
                        value="{{ date('Y-m-d\TH:i') }}">
                </div>

                <div class="p-3 bg-gray-100 border border-gray-300">
                    <label class="block font-bold mb-1">Keterangan / Catatan</label>
                    <input type="text" name="keterangan" class="w-full p-1 border border-gray-400"
                        placeholder="Contoh: Stok tambahan lebaran...">
                </div>

                <div class="p-3 bg-red-50 border border-red-200">
                    <label class="block font-bold mb-1 text-red-800">Dari Toko (Asal)</label>
                    <select name="id_toko_asal" class="w-full p-1 border border-gray-400" required>
                        <option value="">-- Pilih Toko Asal --</option>
                        @foreach ($tokos as $toko)
                            <option value="{{ $toko->id_toko }}">{{ $toko->nama_toko }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="p-3 bg-green-50 border border-green-200">
                    <label class="block font-bold mb-1 text-green-800">Ke Toko (Tujuan)</label>
                    <select name="id_toko_tujuan" class="w-full p-1 border border-gray-400" required>
                        <option value="">-- Pilih Toko Tujuan --</option>
                        @foreach ($tokos as $toko)
                            <option value="{{ $toko->id_toko }}">{{ $toko->nama_toko }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h3 class="font-bold mb-2 border-b border-gray-400 pb-1">Daftar Barang yang Ditransfer</h3>
            <table class="w-full border-collapse border border-gray-400 mb-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-400 p-2 w-10">No</th>
                        <th class="border border-gray-400 p-2">Nama Produk</th>
                        <th class="border border-gray-400 p-2 w-32">Qty Kirim</th>
                        <th class="border border-gray-400 p-2 w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr>
                            <td class="border border-gray-300 p-2 text-center" x-text="index + 1"></td>
                            <td class="border border-gray-300 p-2">
                                <select :name="'items[' + index + '][id_produk]'" class="w-full p-1 border border-gray-300"
                                    required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($produks as $prod)
                                        <option value="{{ $prod->id_produk }}">{{ $prod->nama_produk }}
                                            ({{ $prod->kode_produk }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" :name="'items[' + index + '][qty]'"
                                    class="w-full p-1 border border-gray-300 text-right" min="1" required
                                    placeholder="0">
                            </td>
                            <td class="border border-gray-300 p-2 text-center">
                                <button type="button" @click="removeRow(index)"
                                    class="text-red-600 font-bold hover:text-red-800">X</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="flex gap-2">
                <button type="button" @click="addRow()"
                    class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-sm">
                    + Tambah Baris
                </button>
                <div class="flex-grow"></div>
                <button type="submit"
                    class="px-6 py-2 bg-teal-700 text-white font-bold border-b-4 border-teal-900 hover:bg-teal-600">
                    PROSES TRANSFER
                </button>
            </div>

        </form>
    </div>

    <script>
        function mutasiForm() {
            return {
                rows: [{
                    id_produk: '',
                    qty: 1
                }],
                addRow() {
                    this.rows.push({
                        id_produk: '',
                        qty: 1
                    });
                },
                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    }
                }
            }
        }
    </script>
@endsection
