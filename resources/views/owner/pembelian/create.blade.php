@extends('layouts.owner')

@section('title', 'Input Pembelian')

@section('content')
    <div class="flex flex-col h-[calc(100vh-80px)] w-full" x-data="invoiceHandler()">

        {{-- Header Page --}}
        <div class="flex-none flex justify-between items-center mb-3">
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">INPUT PEMBELIAN / STOK MASUK</h2>
            <a href="{{ route('owner.pembelian.index') }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo;
                Kembali</a>
        </div>

        {{-- Menampilkan Error Validasi Laravel jika ada --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mb-2 text-sm" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul class="list-disc pl-4 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 
            PERBAIKAN UTAMA DI SINI:
            Ganti @submit="return validateForm()" 
            Menjadi @submit="if(!validateForm()) $event.preventDefault()"
        --}}
        <form action="{{ route('owner.pembelian.store') }}" method="POST"
            class="flex-grow flex flex-col bg-gray-100 p-3 border border-gray-400 shadow-inner text-sm overflow-hidden"
            @submit="if(!validateForm()) $event.preventDefault()">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 h-full">

                {{-- KOLOM KIRI (INPUT INFO) --}}
                <div class="lg:col-span-4 flex flex-col h-full overflow-y-auto pr-2 custom-scrollbar">

                    {{-- Informasi Faktur --}}
                    <div class="mb-4 bg-white p-3 border border-gray-300 rounded shadow-sm">
                        <h3 class="font-bold text-gray-700 border-b border-gray-200 mb-3 pb-1 text-xs uppercase">
                            Informasi Faktur
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block font-bold text-xs mb-1">Distributor <span
                                        class="text-red-600">*</span></label>
                                <select name="id_distributor"
                                    class="w-full border border-gray-400 p-1.5 text-sm bg-white focus:outline-none focus:border-blue-600 rounded"
                                    required>
                                    <option value="">-- Pilih Distributor --</option>
                                    @foreach ($distributors as $dist)
                                        <option value="{{ $dist->id_distributor }}"
                                            {{ old('id_distributor') == $dist->id_distributor ? 'selected' : '' }}>
                                            {{ $dist->nama_distributor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-xs mb-1">No. Faktur Supplier <span
                                        class="text-red-600">*</span></label>
                                <input type="text" name="no_faktur_supplier" value="{{ old('no_faktur_supplier') }}"
                                    class="w-full border border-gray-400 p-1.5 text-sm focus:outline-none focus:border-blue-600 rounded"
                                    placeholder="Contoh: INV-001/ABC" required>
                            </div>

                            <div>
                                <label class="block font-bold text-xs mb-1">Tanggal Pembelian <span
                                        class="text-red-600">*</span></label>
                                <input type="date" name="tgl_pembelian" value="{{ old('tgl_pembelian', date('Y-m-d')) }}"
                                    class="w-full border border-gray-400 p-1.5 text-sm focus:outline-none focus:border-blue-600 rounded"
                                    required>
                            </div>
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    <div class="bg-white p-3 border border-gray-300 rounded shadow-sm">
                        <h3 class="font-bold text-gray-700 border-b border-gray-200 mb-3 pb-1 text-xs uppercase">
                            Pembayaran
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block font-bold text-xs mb-1">Status Bayar <span
                                        class="text-red-600">*</span></label>
                                <select name="status_bayar" x-model="statusBayar"
                                    class="w-full border border-gray-400 p-1.5 text-sm bg-white focus:outline-none focus:border-blue-600 rounded"
                                    required>
                                    <option value="Lunas">Lunas (Cash Keluar)</option>
                                    <option value="Hutang">Hutang (Tempo)</option>
                                    <option value="Sebagian">Sebagian (DP)</option>
                                </select>
                            </div>

                            <div x-show="statusBayar !== 'Lunas'" x-transition>
                                <label class="block font-bold text-xs mb-1">Jatuh Tempo <span
                                        class="text-red-600">*</span></label>
                                <input type="date" name="tgl_jatuh_tempo" value="{{ old('tgl_jatuh_tempo') }}"
                                    class="w-full border border-gray-400 p-1.5 text-sm focus:outline-none focus:border-blue-600 rounded">
                            </div>

                            <div x-show="statusBayar === 'Sebagian'" x-transition>
                                <label class="block font-bold text-xs mb-1">Nominal Dibayar (DP)</label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500 font-bold">Rp</span>
                                    <input type="number" name="nominal_bayar" value="{{ old('nominal_bayar') }}"
                                        class="w-full pl-8 border border-gray-400 p-1.5 text-sm focus:outline-none focus:border-blue-600 rounded"
                                        placeholder="0">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-xs mb-1">Catatan / Keterangan</label>
                                <textarea name="keterangan" rows="2"
                                    class="w-full border border-gray-400 p-1.5 text-sm focus:outline-none focus:border-blue-600 rounded">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (TABEL PRODUK) --}}
                <div class="lg:col-span-8 flex flex-col h-full overflow-hidden">
                    <div
                        class="bg-white border border-gray-400 shadow-sm flex flex-col flex-grow overflow-hidden relative rounded">

                        <div class="flex-none p-2 bg-gray-200 border-b border-gray-400 flex justify-between items-center">
                            <h3 class="font-bold text-gray-700 text-xs uppercase">Keranjang Belanja</h3>
                            <button type="button" @click="addItem()"
                                class="bg-blue-800 text-white px-3 py-1 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs rounded transition">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>
                        </div>

                        <div class="flex-grow overflow-y-auto custom-scrollbar bg-white">
                            <table class="w-full text-left border-collapse border-b border-gray-300">
                                <thead
                                    class="bg-gray-100 text-xs font-bold text-gray-700 uppercase sticky top-0 z-20 shadow-sm">
                                    <tr>
                                        <th class="border-b-2 border-r border-gray-300 p-2 w-[35%] bg-gray-100">Produk</th>
                                        <th class="border-b-2 border-r border-gray-300 p-2 w-[15%] text-center bg-gray-100">
                                            Qty</th>
                                        <th class="border-b-2 border-r border-gray-300 p-2 w-[20%] text-right bg-gray-100">
                                            Harga Beli (@)</th>
                                        <th class="border-b-2 border-r border-gray-300 p-2 w-[25%] text-right bg-gray-100">
                                            Subtotal</th>
                                        <th class="border-b-2 border-gray-300 p-2 w-[5%] bg-gray-100"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="hover:bg-blue-50 border-b border-gray-200 transition">
                                            <td class="border-r border-gray-300 p-2 align-top">
                                                {{-- Produk Select --}}
                                                <select :name="'produk_id[' + index + ']'" x-model="item.produk_id"
                                                    class="w-full border border-gray-300 p-1.5 text-sm focus:outline-none focus:border-blue-500 rounded"
                                                    required>
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach ($produks as $prod)
                                                        <option value="{{ $prod->id_produk }}">{{ $prod->nama_produk }}
                                                            ({{ $prod->satuan_kecil }})</option>
                                                    @endforeach
                                                </select>
                                                {{-- Expired Date --}}
                                                <div class="mt-1 flex items-center gap-1">
                                                    <span class="text-[10px] text-gray-500 font-bold">Exp:</span>
                                                    <input type="date" :name="'tgl_expired[' + index + ']'"
                                                        class="border border-gray-300 text-[11px] p-0.5 text-gray-600 rounded w-full focus:outline-none focus:border-blue-500">
                                                </div>
                                            </td>
                                            <td class="border-r border-gray-300 p-2 align-top">
                                                <input type="number" :name="'qty[' + index + ']'" x-model="item.qty"
                                                    @input="calculateRow(index)" min="1"
                                                    class="w-full border border-gray-300 p-1.5 text-center focus:outline-none focus:border-blue-500 rounded font-bold"
                                                    required>
                                            </td>
                                            <td class="border-r border-gray-300 p-2 align-top">
                                                <input type="number" :name="'harga_beli[' + index + ']'"
                                                    x-model="item.harga" @input="calculateRow(index)" min="0"
                                                    class="w-full border border-gray-300 p-1.5 text-right focus:outline-none focus:border-blue-500 rounded"
                                                    required>
                                            </td>
                                            <td
                                                class="border-r border-gray-300 p-2 align-top text-right font-mono font-bold text-gray-700 pt-3">
                                                <span x-text="formatRupiah(item.subtotal)"></span>
                                            </td>
                                            <td class="p-2 align-top text-center pt-3">
                                                <button type="button" @click="removeItem(index)"
                                                    class="text-red-500 hover:text-red-700 font-bold text-xs transition"
                                                    title="Hapus Baris">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            {{-- Pesan jika kosong --}}
                            <div x-show="items.length === 0" class="p-8 text-center text-gray-400 italic">
                                Belum ada produk yang ditambahkan. Klik tombol "Tambah Baris".
                            </div>
                        </div>

                        <div
                            class="flex-none bg-gray-50 border-t-2 border-gray-300 p-3 flex justify-between items-center z-20 shadow-[0_-2px_4px_rgba(0,0,0,0.05)]">
                            <span class="font-bold text-gray-700 uppercase text-sm">Grand Total Pembelian</span>
                            <span class="font-black text-2xl text-blue-900" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>

                    <div class="flex-none mt-3 flex justify-end gap-2">
                        <a href="{{ route('owner.pembelian.index') }}"
                            class="bg-gray-300 text-gray-800 px-4 py-2 border border-gray-400 shadow hover:bg-gray-400 font-bold text-sm rounded flex items-center transition">
                            BATAL
                        </a>
                        <button type="submit"
                            class="bg-blue-800 text-white px-6 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-sm rounded flex items-center transition">
                            <i class="fas fa-save mr-2"></i> SIMPAN & TAMBAH STOK
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function invoiceHandler() {
            return {
                statusBayar: '{{ old('status_bayar', 'Lunas') }}',
                items: [
                    @if (old('produk_id'))
                        @foreach (old('produk_id') as $i => $pid)
                            {
                                produk_id: '{{ $pid }}',
                                qty: {{ old('qty')[$i] ?? 1 }},
                                harga: {{ old('harga_beli')[$i] ?? 0 }},
                                subtotal: {{ (old('qty')[$i] ?? 1) * (old('harga_beli')[$i] ?? 0) }}
                            },
                        @endforeach
                    @else
                        {
                            produk_id: '',
                            qty: 1,
                            harga: 0,
                            subtotal: 0
                        }
                    @endif
                ],
                grandTotal: 0,

                init() {
                    this.calculateTotal();
                },

                addItem() {
                    this.items.push({
                        produk_id: '',
                        qty: 1,
                        harga: 0,
                        subtotal: 0
                    });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.calculateTotal();
                    } else {
                        alert("Minimal harus ada 1 barang!");
                    }
                },

                calculateRow(index) {
                    let item = this.items[index];
                    let qty = parseFloat(item.qty) || 0;
                    let harga = parseFloat(item.harga) || 0;

                    item.subtotal = qty * harga;
                    this.calculateTotal();
                },

                calculateTotal() {
                    this.grandTotal = this.items.reduce((sum, item) => sum + (item.subtotal || 0), 0);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(number);
                },

                validateForm() {
                    if (this.items.length === 0) {
                        alert("Mohon masukkan minimal 1 produk.");
                        return false;
                    }
                    if (this.statusBayar === 'Sebagian') {
                        // Tambahkan validasi jika perlu, misal nominal bayar > 0
                        let nominal = document.querySelector('input[name="nominal_bayar"]').value;
                        if (nominal <= 0 || nominal == "") {
                            alert("Jika status sebagian, mohon isi nominal DP.");
                            return false;
                        }
                    }
                    return true;
                }
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection
