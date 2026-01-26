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
    // Constants
    const SEARCH_URL = "{{ route('owner.toko.pembelian.search', $toko->id_toko) }}";
    let rowCount = 0;

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    function initSelect2(element) {
        $(element).select2({
            placeholder: 'Ketik Nama / SKU...',
            allowClear: true,
            width: '100%',
            ajax: {
                url: SEARCH_URL,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id_produk,
                                text: item.nama_produk + ' (' + (item.sku || '-') + ')',
                                price: item.harga_beli
                            };
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select', function (e) {
            let data = e.params.data;
            updatePrice(this, data.price);
        });
    }

    function addRow() {
        rowCount++;
        const tableBody = $('#itemsTable tbody');
        const row = `
            <tr class="text-xs">
                <td class="border border-gray-300 p-1">
                    <select name="items[${rowCount}][id_produk]" class="product-select w-full text-xs" required>
                        <option value="">Cari Produk...</option>
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
            </tr>
        `;
        const $row = $(row);
        tableBody.append($row);
        initSelect2($row.find('.product-select'));
    }

    window.updatePrice = function(selectElement, price) {
        const row = $(selectElement).closest('tr');
        const priceInput = row.find('.price-input');
        if (price) {
            priceInput.val(price);
            calculateSubtotal(priceInput[0]);
        }
    }

    window.calculateSubtotal = function(input) {
        const row = $(input).closest('tr');
        const qty = parseFloat(row.find('.qty-input').val()) || 0;
        const price = parseFloat(row.find('.price-input').val()) || 0;
        const subtotal = qty * price;
        
        row.find('.subtotal-display').val(formatRupiah(subtotal));
        row.find('.subtotal-input').val(subtotal);
        
        calculateGrandTotal();
    }

    window.removeRow = function(btn) {
        $(btn).closest('tr').remove();
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        $('.subtotal-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        
        $('#grandTotalDisplay').text(formatRupiah(total));
        $('#grandTotalInput').val(total);
    }

    $('#addItemBtn').on('click', addRow);
    
    // Handle destination change
    $('#destination').on('change', function() {
        const value = this.value;
        if (value === 'toko') {
            $('#destination_type').val('toko');
            $('#destination_id').val('{{ $toko->id_toko }}');
        } else if (value.startsWith('gudang_')) {
            $('#destination_type').val('gudang');
            $('#destination_id').val(value.replace('gudang_', ''));
        }
    });
    
    // Add first row on load
    addRow();
</script>
@endpush
@endsection
