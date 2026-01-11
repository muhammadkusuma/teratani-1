@extends('layouts.owner')

@section('title', 'Buat Transfer Stok')

@section('content')
<div class="max-w-4xl" x-data="mutasiForm()">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">INPUT TRANSFER STOK BARU</h2>
        <a href="{{ route('owner.mutasi.index') }}" class="text-blue-700 underline text-xs hover:text-blue-500">&laquo; Kembali</a>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mb-4 text-xs">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.mutasi.store') }}" method="POST" class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-bold text-xs mb-1">Tanggal Kirim</label>
                <input type="datetime-local" name="tgl_kirim" class="w-full border border-gray-400 p-1 text-sm bg-white" required value="{{ date('Y-m-d\TH:i') }}">
            </div>
            <div>
                <label class="block font-bold text-xs mb-1">Keterangan</label>
                <input type="text" name="keterangan" class="w-full border border-gray-400 p-1 text-sm bg-white" placeholder="Contoh: Stok Tambahan...">
            </div>
            
            <div class="bg-red-50 p-2 border border-red-200">
                <label class="block font-bold text-xs mb-1 text-red-800">Dari Toko (Asal)</label>
                <select name="id_toko_asal" class="w-full border border-gray-400 p-1 text-sm" required @change="fetchProduk($event.target.value)">
                    <option value="">-- Pilih Toko Asal --</option>
                    @foreach ($tokos as $toko)
                        <option value="{{ $toko->id_toko }}">{{ $toko->nama_toko }}</option>
                    @endforeach
                </select>
            </div>

            <div class="bg-green-50 p-2 border border-green-200">
                <label class="block font-bold text-xs mb-1 text-green-800">Ke Toko (Tujuan)</label>
                <select name="id_toko_tujuan" class="w-full border border-gray-400 p-1 text-sm" required>
                    <option value="">-- Pilih Toko Tujuan --</option>
                    @foreach ($tokos as $toko)
                        <option value="{{ $toko->id_toko }}">{{ $toko->nama_toko }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="border-t border-gray-300 pt-3">
            <h3 class="font-bold text-blue-800 text-xs mb-2">RINCIAN BARANG</h3>
            
            <div x-show="isLoading" class="text-center py-2 text-xs text-gray-500 italic">Sedang memuat data produk...</div>

            <table class="w-full text-left border-collapse bg-white mb-3" x-show="!isLoading">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                        <th class="border border-gray-400 p-2 w-10 text-center">No</th>
                        <th class="border border-gray-400 p-2">Pilih Produk</th>
                        <th class="border border-gray-400 p-2 w-32 text-center">Qty Kirim</th>
                        <th class="border border-gray-400 p-2 w-16 text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="text-xs">
                            <td class="border border-gray-300 p-2 text-center" x-text="index + 1"></td>
                            <td class="border border-gray-300 p-2">
                                <select :name="'items[' + index + '][id_produk]'" class="w-full border border-gray-300 p-1" required>
                                    <option value="">-- Pilih Produk --</option>
                                    <template x-for="prod in listProduk" :key="prod.id_produk">
                                        <option :value="prod.id_produk" x-text="prod.nama_produk + ' (Stok: ' + prod.stok_fisik + ')'"></option>
                                    </template>
                                </select>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" :name="'items[' + index + '][qty]'" class="w-full border border-gray-300 p-1 text-right" min="1" required placeholder="0">
                            </td>
                            <td class="border border-gray-300 p-2 text-center">
                                <button type="button" @click="removeRow(index)" class="text-red-600 font-bold hover:text-red-800">X</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <button type="button" @click="addRow()" class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300 mb-4">
                + Tambah Baris
            </button>
        </div>

        <div class="border-t border-gray-300 pt-3 text-right">
            <button type="submit" class="bg-blue-800 text-white px-4 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs">
                PROSES TRANSFER
            </button>
        </div>
    </form>
</div>

<script>
    function mutasiForm() {
        return {
            isLoading: false,
            listProduk: [],
            rows: [{ id_produk: '', qty: 1 }],
            
            async fetchProduk(idToko) {
                // Reset data jika toko tidak dipilih
                if (!idToko) { 
                    this.listProduk = []; 
                    return; 
                }

                this.isLoading = true;
                this.rows = [{ id_produk: '', qty: 1 }]; // Reset baris input

                try {
                    // FIX: Gunakan relative path (parameter ke-3 false) untuk mengatasi masalah port mismatch
                    let url = "{{ route('owner.mutasi.get-produk', ['id_toko' => '__ID__'], false) }}".replace('__ID__', idToko);
                    
                    const response = await fetch(url, { 
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest', 
                            'Accept': 'application/json' 
                        } 
                    });

                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    this.listProduk = await response.json();
                } catch (error) {
                    console.error("Error fetching produk:", error);
                    alert('Gagal mengambil data produk. Pastikan server berjalan dan Toko valid.');
                } finally {
                    this.isLoading = false;
                }
            },
            
            addRow() { 
                this.rows.push({ id_produk: '', qty: 1 }); 
            },
            
            removeRow(index) { 
                if (this.rows.length > 1) this.rows.splice(index, 1); 
            }
        }
    }
</script>
@endsection