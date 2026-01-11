@extends('layouts.owner')

@section('title', 'Input Pembelian')

@section('content')
<div class="p-4 max-w-5xl mx-auto" x-data="invoiceHandler()">
    
    <form action="{{ route('owner.pembelian.store') }}" method="POST">
        @csrf
        
        <div class="bg-white p-4 border border-gray-300 shadow-sm mb-4">
            <h3 class="font-bold text-lg mb-3 text-gray-700 border-b pb-2">Informasi Faktur</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Distributor</label>
                    <select name="id_distributor" class="w-full border border-gray-300 p-2 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500" required>
                        <option value="">-- Pilih Distributor --</option>
                        @foreach($distributors as $dist)
                            <option value="{{ $dist->id_distributor }}">{{ $dist->nama_distributor }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">No. Faktur Supplier</label>
                    <input type="text" name="no_faktur_supplier" class="w-full border border-gray-300 p-2 focus:outline-none focus:border-blue-500" placeholder="Contoh: INV-001/ABC" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Pembelian</label>
                    <input type="date" name="tgl_pembelian" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 p-2 focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Jatuh Tempo (Opsional)</label>
                    <input type="date" name="tgl_jatuh_tempo" class="w-full border border-gray-300 p-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Status Pembayaran</label>
                    <select name="status_bayar" class="w-full border border-gray-300 p-2 bg-gray-50" required>
                        <option value="Lunas">Lunas</option>
                        <option value="Hutang">Hutang</option>
                        <option value="Sebagian">Sebagian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" class="w-full border border-gray-300 p-2 focus:outline-none focus:border-blue-500" placeholder="Catatan tambahan...">
                </div>
            </div>
        </div>

        <div class="bg-white p-4 border border-gray-300 shadow-sm mb-4">
            <h3 class="font-bold text-lg mb-3 text-gray-700 border-b pb-2 flex justify-between items-center">
                <span>Daftar Barang</span>
                <button type="button" @click="addItem()" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded">
                    <i class="fas fa-plus"></i> Tambah Baris
                </button>
            </h3>

            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-600 text-xs uppercase border-b border-gray-300">
                    <tr>
                        <th class="p-2 w-1/3">Produk</th>
                        <th class="p-2 w-24">Expired</th>
                        <th class="p-2 w-20 text-center">Qty</th>
                        <th class="p-2 w-32 text-right">Harga Beli</th>
                        <th class="p-2 w-32 text-right">Subtotal</th>
                        <th class="p-2 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-b">
                            <td class="p-2">
                                <select :name="'produk_id['+index+']'" x-model="item.produk_id" class="w-full border border-gray-300 p-1 text-sm" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($produks as $prod)
                                        <option value="{{ $prod->id_produk }}">{{ $prod->nama_produk }} ({{ $prod->satuan_kecil }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="date" :name="'tgl_expired['+index+']'" class="w-full border border-gray-300 p-1 text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" :name="'qty['+index+']'" x-model="item.qty" @input="calculateRow(index)" min="1" class="w-full border border-gray-300 p-1 text-center text-sm" required>
                            </td>
                            <td class="p-2">
                                <input type="number" :name="'harga_beli['+index+']'" x-model="item.harga" @input="calculateRow(index)" class="w-full border border-gray-300 p-1 text-right text-sm" required>
                            </td>
                            <td class="p-2 text-right font-mono text-sm bg-gray-50">
                                <span x-text="formatRupiah(item.subtotal)"></span>
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="4" class="p-2 text-right text-gray-700">TOTAL FAKTUR:</td>
                        <td class="p-2 text-right text-blue-800 text-lg">
                            <span x-text="formatRupiah(grandTotal)"></span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('owner.pembelian.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded shadow-lg">
                <i class="fas fa-save mr-1"></i> Simpan Faktur
            </button>
        </div>
    </form>
</div>

<script>
    function invoiceHandler() {
        return {
            items: [
                { produk_id: '', qty: 1, harga: 0, subtotal: 0 }
            ],
            grandTotal: 0,
            
            addItem() {
                this.items.push({ produk_id: '', qty: 1, harga: 0, subtotal: 0 });
            },
            
            removeItem(index) {
                if(this.items.length > 1) {
                    this.items.splice(index, 1);
                    this.calculateTotal();
                } else {
                    alert('Minimal harus ada 1 barang.');
                }
            },

            calculateRow(index) {
                let item = this.items[index];
                item.subtotal = (item.qty || 0) * (item.harga || 0);
                this.calculateTotal();
            },

            calculateTotal() {
                this.grandTotal = this.items.reduce((sum, item) => sum + item.subtotal, 0);
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
            }
        }
    }
</script>
@endsection