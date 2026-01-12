@extends('layouts.owner')

@section('title', 'Input Pembelian')

@section('content')
    {{-- 
        CONTAINER UTAMA: 
        h-[calc(100vh-100px)] -> Mengatur tinggi agar pas 1 layar (dikurangi estimasi tinggi Navbar/Header layout).
        Jika layout anda memiliki navbar yang tinggi, sesuaikan angka 100px tersebut.
    --}}
    <div class="flex flex-col h-[calc(100vh-80px)] w-full" x-data="invoiceHandler()">

        {{-- Header Page --}}
        <div class="flex-none flex justify-between items-center mb-3">
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">INPUT PEMBELIAN / STOK MASUK</h2>
            <a href="{{ route('owner.pembelian.index') }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo;
                Kembali</a>
        </div>

        {{-- 
            FORM CONTAINER:
            flex-grow: Mengisi sisa ruang ke bawah.
            overflow-hidden: Mencegah scrollbar ganda pada body.
        --}}
        <form action="{{ route('owner.pembelian.store') }}" method="POST"
            class="flex-grow flex flex-col bg-gray-100 p-3 border border-gray-400 shadow-inner text-sm overflow-hidden">
            @csrf

            {{-- GRID UTAMA: h-full agar kolom mengisi tinggi form --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 h-full">

                {{-- 
                    KOLOM KIRI (INPUT DATA):
                    overflow-y-auto: Agar bagian input bisa discroll sendiri jika layar kependekan, 
                    tanpa menggeser tombol simpan di kanan.
                --}}
                <div class="lg:col-span-4 flex flex-col h-full overflow-y-auto pr-2 custom-scrollbar">
                    
                    {{-- Informasi Faktur --}}
                    <div class="mb-4">
                        <h3 class="font-bold text-gray-700 border-b border-gray-300 mb-2 pb-1 text-xs uppercase sticky top-0 bg-gray-100 z-10">
                            Informasi Faktur
                        </h3>
                        <div class="space-y-3 px-1">
                            <div>
                                <label class="block font-bold text-xs mb-1">Distributor <span class="text-red-600">*</span></label>
                                <select name="id_distributor"
                                    class="w-full border border-gray-400 p-1 text-sm bg-white focus:outline-none focus:border-blue-600"
                                    required>
                                    <option value="">-- Pilih Distributor --</option>
                                    @foreach ($distributors as $dist)
                                        <option value="{{ $dist->id_distributor }}">{{ $dist->nama_distributor }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-xs mb-1">No. Faktur Supplier</label>
                                <input type="text" name="no_faktur_supplier"
                                    class="w-full border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600"
                                    placeholder="Nomor dari struk fisik" required>
                            </div>

                            <div>
                                <label class="block font-bold text-xs mb-1">Tanggal Pembelian</label>
                                <input type="date" name="tgl_pembelian" value="{{ date('Y-m-d') }}"
                                    class="w-full border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600"
                                    required>
                            </div>
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    <div>
                        <h3 class="font-bold text-gray-700 border-b border-gray-300 mb-2 pb-1 text-xs uppercase sticky top-0 bg-gray-100 z-10">
                            Pembayaran
                        </h3>
                        <div class="space-y-3 px-1">
                            <div>
                                <label class="block font-bold text-xs mb-1">Status Bayar</label>
                                <select name="status_bayar" x-model="statusBayar"
                                    class="w-full border border-gray-400 p-1 text-sm bg-white focus:outline-none focus:border-blue-600"
                                    required>
                                    <option value="Lunas">Lunas (Cash Keluar)</option>
                                    <option value="Hutang">Hutang (Tempo)</option>
                                    <option value="Sebagian">Sebagian (DP)</option>
                                </select>
                            </div>

                            <div x-show="statusBayar !== 'Lunas'">
                                <label class="block font-bold text-xs mb-1">Jatuh Tempo</label>
                                <input type="date" name="tgl_jatuh_tempo"
                                    class="w-full border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600">
                            </div>

                            <div x-show="statusBayar === 'Sebagian'">
                                <label class="block font-bold text-xs mb-1">Nominal Dibayar (DP)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500 font-bold">Rp</span>
                                    <input type="number" name="nominal_bayar"
                                        class="w-full pl-8 border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600"
                                        placeholder="0">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-xs mb-1">Catatan / Keterangan</label>
                                <textarea name="keterangan" rows="2"
                                    class="w-full border border-gray-400 p-1 text-sm focus:outline-none focus:border-blue-600"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 
                    KOLOM KANAN (KERANJANG):
                    flex flex-col h-full: Agar mengisi penuh tinggi kanan.
                --}}
                <div class="lg:col-span-8 flex flex-col h-full overflow-hidden">
                    
                    {{-- CONTAINER TABEL: flex-grow + overflow-hidden --}}
                    <div class="bg-white border border-gray-400 shadow-sm flex flex-col flex-grow overflow-hidden relative">
                        
                        {{-- Toolbar Keranjang (Fixed Height) --}}
                        <div class="flex-none p-2 bg-gray-200 border-b border-gray-400 flex justify-between items-center">
                            <h3 class="font-bold text-gray-700 text-xs uppercase">Rincian Barang</h3>
                            <button type="button" @click="addItem()"
                                class="bg-blue-800 text-white px-3 py-1 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>
                        </div>

                        {{-- Area Tabel (Scrollable) --}}
                        <div class="flex-grow overflow-y-auto custom-scrollbar bg-white">
                            <table class="w-full text-left border-collapse border-b border-gray-300">
                                {{-- Sticky Header: Header diam saat discroll --}}
                                <thead class="bg-gray-100 text-xs font-bold text-gray-700 uppercase sticky top-0 z-20 shadow-sm">
                                    <tr>
                                        <th class="border-b-2 border-r border-gray-300 p-2 w-[40%] bg-gray-100">Produk</th>
                                        <th class="border-b-2 border-r border-gray-300 p-2 w-[15%] text-center bg-gray-100">Qty</th>
                                        <th class="border-b-2 border-r border-gray-300 p-2 w-[20%] text-right bg-gray-100">Harga Beli</th>
                                        <th class="border-b-2 border-r border-gray-300 p-2 w-[20%] text-right bg-gray-100">Subtotal</th>
                                        <th class="border-b-2 border-gray-300 p-2 w-[5%] bg-gray-100"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="hover:bg-blue-50 border-b border-gray-200">
                                            <td class="border-r border-gray-300 p-1 align-top">
                                                <select :name="'produk_id[' + index + ']'" x-model="item.produk_id"
                                                    class="w-full border border-gray-300 p-1 text-sm focus:outline-none focus:border-blue-500"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($produks as $prod)
                                                        <option value="{{ $prod->id_produk }}">{{ $prod->nama_produk }}
                                                            ({{ $prod->satuan_kecil }})</option>
                                                    @endforeach
                                                </select>
                                                <input type="date" :name="'tgl_expired[' + index + ']'"
                                                    class="mt-1 w-full border border-gray-300 text-[10px] p-0.5 text-gray-500"
                                                    title="Tanggal Kadaluarsa">
                                            </td>
                                            <td class="border-r border-gray-300 p-1 align-top">
                                                <input type="number" :name="'qty[' + index + ']'" x-model="item.qty"
                                                    @input="calculateRow(index)" min="1"
                                                    class="w-full border border-gray-300 p-1 text-center focus:outline-none focus:border-blue-500" required>
                                            </td>
                                            <td class="border-r border-gray-300 p-1 align-top">
                                                <input type="number" :name="'harga_beli[' + index + ']'" x-model="item.harga"
                                                    @input="calculateRow(index)"
                                                    class="w-full border border-gray-300 p-1 text-right focus:outline-none focus:border-blue-500" required>
                                            </td>
                                            <td class="border-r border-gray-300 p-1 align-top text-right font-mono font-bold text-gray-700 pt-2">
                                                <span x-text="formatRupiah(item.subtotal)"></span>
                                            </td>
                                            <td class="p-1 align-top text-center pt-2">
                                                <button type="button" @click="removeItem(index)"
                                                    class="text-red-500 hover:text-red-700 font-bold text-xs">
                                                    [X]
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        {{-- Footer Total (Fixed di bawah tabel) --}}
                        <div class="flex-none bg-gray-50 border-t-2 border-gray-300 p-2 flex justify-between items-center z-20">
                            <span class="font-bold text-gray-700 uppercase">Grand Total</span>
                            <span class="font-black text-xl text-blue-900 bg-yellow-100 px-2 border border-yellow-300" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>

                    {{-- Tombol Aksi (Selalu di bawah Kanan) --}}
                    <div class="flex-none mt-3 flex justify-end gap-2">
                        <a href="{{ route('owner.pembelian.index') }}"
                            class="bg-gray-300 text-gray-800 px-4 py-2 border border-gray-400 shadow hover:bg-gray-400 font-bold text-xs flex items-center">
                            BATAL
                        </a>
                        <button type="submit"
                            class="bg-blue-800 text-white px-6 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs flex items-center">
                            <i class="fas fa-save mr-2"></i> SIMPAN TRANSAKSI
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Script tetap sama --}}
    <script>
        function invoiceHandler() {
            return {
                statusBayar: 'Lunas',
                items: [{
                    produk_id: '',
                    qty: 1,
                    harga: 0,
                    subtotal: 0
                }],
                grandTotal: 0,
                // ... (fungsi lainnya sama persis)
                addItem() {
                    this.items.push({ produk_id: '', qty: 1, harga: 0, subtotal: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) { this.items.splice(index, 1); this.calculateTotal(); }
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
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                }
            }
        }
    </script>
    
    {{-- Optional: Style untuk scrollbar agar lebih rapi --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endsection