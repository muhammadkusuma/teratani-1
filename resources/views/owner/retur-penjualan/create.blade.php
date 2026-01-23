@extends('layouts.owner')

@section('title', 'Buat Retur Penjualan')

@section('content')
<div class="mb-4">
    <h2 class="font-bold text-xl mb-4">Input Retur Penjualan (Dari Pelanggan)</h2>
</div>

<div class="bg-white p-6 rounded shadow">
    <form action="{{ route('owner.retur-penjualan.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Pelanggan</label>
                <select name="id_pelanggan" class="w-full border rounded p-2" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id_pelanggan }}">{{ $pelanggan->nama_pelanggan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Retur</label>
                <input type="date" name="tgl_retur" class="w-full border rounded p-2" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan / Alasan Umum</label>
            <textarea name="keterangan" class="w-full border rounded p-2"></textarea>
        </div>

        <h3 class="font-bold mb-2 text-lg">Item Retur</h3>
        <table class="w-full border mb-4" id="itemTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border text-left">Produk</th>
                    <th class="p-2 border text-right">Qty</th>
                    <th class="p-2 border text-right">Harga Satuan (Saat Retur)</th>
                    <th class="p-2 border text-right">Subtotal</th> // New
                    <th class="p-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be added here -->
            </tbody>
        </table>
        
        <button type="button" onclick="addRow()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded mb-4 text-sm">
            + Tambah Item
        </button>

        <div class="flex items-center justify-between mt-4 border-t pt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Simpan Retur
            </button>
            <a href="{{ route('owner.retur-penjualan.index') }}" class="text-blue-500 hover:text-blue-800">Batal</a>
        </div>
    </form>
</div>

<script>
    const produks = @json($produks);

    function addRow() {
        const tbody = document.querySelector('#itemTable tbody');
        const index = tbody.children.length;
        
        let produkOptions = '<option value="">-- Pilih Produk --</option>';
        produks.forEach(p => {
            produkOptions += `<option value="${p.id_produk}" data-harga="${p.harga_jual_umum}">${p.nama_produk} (${p.sku})</option>`;
        });

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="p-2 border">
                <select name="produk_id[]" class="w-full border p-1" onchange="updateHarga(this)" required>
                    ${produkOptions}
                </select>
            </td>
            <td class="p-2 border">
                <input type="number" name="qty[]" class="w-20 border p-1 text-right" value="1" min="1" onchange="calculateRow(this)" required>
            </td>
            <td class="p-2 border">
                <input type="number" name="harga_satuan[]" class="w-32 border p-1 text-right" value="0" required onchange="calculateRow(this)">
            </td>
            <td class="p-2 border text-right">
                <span class="subtotal-display">0</span>
            </td>
            <td class="p-2 border text-center">
                <button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700 font-bold">X</button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function updateHarga(select) {
        const option = select.options[select.selectedIndex];
        const harga = option.getAttribute('data-harga');
        const row = select.closest('tr');
        const hargaInput = row.querySelector('input[name="harga_satuan[]"]');
        if (harga) {
            hargaInput.value = parseInt(harga); // Assuming integer prices per standard
            calculateRow(select);
        }
    }

    function calculateRow(element) {
        const row = element.closest('tr');
        const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
        const harga = parseFloat(row.querySelector('input[name="harga_satuan[]"]').value) || 0;
        const subtotal = qty * harga;
        
        row.querySelector('.subtotal-display').innerText = new Intl.NumberFormat('id-ID').format(subtotal);
    }
    
    // Add one row by default
    document.addEventListener('DOMContentLoaded', () => {
        addRow();
    });
</script>
@endsection
