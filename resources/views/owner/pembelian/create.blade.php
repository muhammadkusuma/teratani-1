@extends('layouts.owner')

@section('title', 'Input Pembelian')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-cart-plus"></i> INPUT PEMBELIAN BARU
    </h2>
    <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="px-3 py-1 bg-gray-200 text-gray-700 border border-gray-400 shadow hover:bg-gray-300 text-xs">
        <i class="fa fa-arrow-left"></i> KEMBALI
    </a>
</div>

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 mb-3 text-xs">
        <strong>Error:</strong>
        <ul class="list-disc ml-4 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 mb-3 text-xs">
        {{ session('error') }}
    </div>
@endif

<div class="bg-gray-100 border border-gray-400 p-4">
    <form action="{{ route('owner.toko.pembelian.store', $toko->id_toko) }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-xs font-bold mb-1">TANGGAL PEMBELIAN</label>
                <input type="date" name="tanggal" class="win98-input w-full text-sm" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">DISTRIBUTOR</label>
                <select name="id_distributor" class="win98-input w-full text-sm" required>
                    <option value="">-- Pilih Distributor --</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id_distributor }}">{{ $distributor->nama_distributor }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">NO. FAKTUR</label>
                <input type="text" name="no_faktur" class="win98-input w-full text-sm" placeholder="Nomor Invoice...">
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">TUJUAN STOK</label>
                <select name="destination" id="destination" class="win98-input w-full text-sm" required>
                    <option value="toko">Toko Utama ({{ $toko->nama_toko }})</option>
                    @foreach($gudangs as $gudang)
                        <option value="gudang_{{ $gudang->id_gudang }}">Gudang: {{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="destination_type" id="destination_type" value="toko">
                <input type="hidden" name="destination_id" id="destination_id" value="{{ $toko->id_toko }}">
            </div>
        </div>

        <div class="mb-4">
            <h3 class="font-bold text-sm bg-blue-900 text-white px-2 py-1 mb-2">ITEM PEMBELIAN</h3>
            <div class="overflow-x-auto border border-gray-400 bg-white">
                <table class="w-full text-left border-collapse" id="itemsTable">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                            <th class="border border-gray-400 p-2 w-[35%]">Produk</th>
                            <th class="border border-gray-400 p-2 w-[15%]">Jumlah</th>
                            <th class="border border-gray-400 p-2 w-[20%]">Harga Satuan</th>
                            <th class="border border-gray-400 p-2 w-[20%]">Subtotal</th>
                            <th class="border border-gray-400 p-2 w-[10%] text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows added via JS -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="3" class="border border-gray-400 p-2 text-right">TOTAL PEMBELIAN:</td>
                            <td colspan="2" class="border border-gray-400 p-2">
                                <span id="grandTotalDisplay">Rp 0</span>
                                <input type="hidden" name="total_pembelian" id="grandTotalInput">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="button" class="mt-2 px-3 py-1 bg-green-600 text-white border border-green-800 shadow hover:bg-green-500 text-xs font-bold" id="addItemBtn">
                + TAMBAH BARANG
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-xs font-bold mb-1">KETERANGAN (OPSIONAL)</label>
            <textarea name="keterangan" class="win98-input w-full text-sm" rows="2"></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white border-2 border-blue-900 shadow hover:bg-blue-600 font-bold text-sm">
                <i class="fa fa-save"></i> SIMPAN PEMBELIAN
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const products = @json($produks);
    let rowCount = 0;

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    function addRow() {
        rowCount++;
        const tableBody = document.querySelector('#itemsTable tbody');
        const row = document.createElement('tr');
        row.className = "text-xs";
        row.innerHTML = `
            <td class="border border-gray-300 p-1">
                <select name="items[${rowCount}][id_produk]" class="product-select win98-input w-full text-xs" required onchange="updatePrice(this)">
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p => `
                        <option value="${p.id_produk}" data-price="${p.harga_beli}">
                            ${p.nama_produk} (${p.sku})
                        </option>
                    `).join('')}
                </select>
            </td>
            <td class="border border-gray-300 p-1">
                <input type="number" name="items[${rowCount}][jumlah]" class="qty-input win98-input w-full text-xs text-right" min="1" value="1" required oninput="calculateSubtotal(this)">
            </td>
            <td class="border border-gray-300 p-1">
                <input type="number" name="items[${rowCount}][harga_satuan]" class="price-input win98-input w-full text-xs text-right" min="0" required oninput="calculateSubtotal(this)">
            </td>
            <td class="border border-gray-300 p-1">
                <input type="text" class="subtotal-display win98-input w-full text-xs text-right bg-gray-100" readonly value="0">
                <input type="hidden" name="items[${rowCount}][subtotal]" class="subtotal-input">
            </td>
            <td class="border border-gray-300 p-1 text-center">
                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeRow(this)">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);

        // Re-init Select2 if needed (for this implementation using native select for simplicity & speed matching win98 style)
        // If specific style required, can init select2 here.
    }

    window.updatePrice = function(select) {
        const row = select.closest('tr');
        const price = select.options[select.selectedIndex].getAttribute('data-price');
        const priceInput = row.querySelector('.price-input');
        if (price) {
            priceInput.value = price;
            calculateSubtotal(priceInput);
        }
    }

    window.calculateSubtotal = function(input) {
        const row = input.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;
        
        row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
        row.querySelector('.subtotal-input').value = subtotal;
        
        calculateGrandTotal();
    }

    window.removeRow = function(btn) {
        btn.closest('tr').remove();
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        
        document.getElementById('grandTotalDisplay').innerText = formatRupiah(total);
        document.getElementById('grandTotalInput').value = total;
    }

    document.getElementById('addItemBtn').addEventListener('click', addRow);
    
    // Handle destination change
    document.getElementById('destination').addEventListener('change', function() {
        const value = this.value;
        if (value === 'toko') {
            document.getElementById('destination_type').value = 'toko';
            document.getElementById('destination_id').value = '{{ $toko->id_toko }}';
        } else if (value.startsWith('gudang_')) {
            document.getElementById('destination_type').value = 'gudang';
            document.getElementById('destination_id').value = value.replace('gudang_', '');
        }
    });
    
    // Add first row on load
    addRow();
</script>
@endpush
@endsection
