@extends('layouts.owner')

@section('title', 'Input Pembelian')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-lg md:text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-cart-plus text-blue-700"></i> Input Pembelian Baru
    </h2>
    <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

@if ($errors->any())
    <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <p class="font-bold text-xs mb-2"><i class="fa fa-exclamation-triangle"></i> Terdapat kesalahan:</p>
        <ul class="list-disc list-inside text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="bg-white border border-gray-300 p-4 md:p-6 shadow-sm rounded-sm">
    <form action="{{ route('owner.toko.pembelian.store', $toko->id_toko) }}" method="POST">
        @csrf
        
        {{-- Header Information --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-calendar"></i> Tanggal Pembelian <span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-truck"></i> Distributor <span class="text-red-600">*</span>
                </label>
                <select name="id_distributor" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required>
                    <option value="">-- Pilih Distributor --</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id_distributor }}">{{ $distributor->nama_distributor }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-file-invoice"></i> No. Faktur
                </label>
                <input type="text" name="no_faktur" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" placeholder="Nomor Invoice...">
            </div>
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-warehouse"></i> Tujuan Stok <span class="text-red-600">*</span>
                </label>
                <select name="destination" id="destination" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" required>
                    <option value="toko">Toko Utama ({{ $toko->nama_toko }})</option>
                    @foreach($gudangs as $gudang)
                        <option value="gudang_{{ $gudang->id_gudang }}">Gudang: {{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="destination_type" id="destination_type" value="toko">
                <input type="hidden" name="destination_id" id="destination_id" value="{{ $toko->id_toko }}">
            </div>
        </div>

        {{-- Items Section --}}
        <div class="mb-6">
            <h3 class="font-black text-sm bg-gradient-to-r from-blue-900 to-blue-700 text-white px-3 py-2 mb-3 uppercase tracking-wider rounded-sm shadow-md">
                <i class="fa fa-list"></i> Item Pembelian
            </h3>
            
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
                <table class="w-full text-left border-collapse" id="itemsTable">
                    <thead>
                        <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                            <th class="border border-blue-900 p-3 w-[35%]">Produk</th>
                            <th class="border border-blue-900 p-3 w-[15%]">Jumlah</th>
                            <th class="border border-blue-900 p-3 w-[20%]">Harga Satuan</th>
                            <th class="border border-blue-900 p-3 w-[20%]">Subtotal</th>
                            <th class="border border-blue-900 p-3 w-[10%] text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows added via JS -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-gradient-to-r from-amber-100 to-amber-50 font-bold border-t-2 border-amber-600">
                            <td colspan="3" class="p-3 text-right font-black uppercase text-sm text-amber-900">Total Pembelian:</td>
                            <td colspan="2" class="p-3">
                                <span id="grandTotalDisplay" class="text-xl font-black text-amber-700">Rp 0</span>
                                <input type="hidden" name="total_pembelian" id="grandTotalInput">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Mobile Cards Container --}}
            <div id="mobileItemsContainer" class="md:hidden space-y-3 mb-3">
                <!-- Mobile cards added via JS -->
            </div>
            
            {{-- Mobile Total Display --}}
            <div class="md:hidden bg-gradient-to-r from-amber-600 to-amber-700 border border-amber-800 p-4 rounded-sm shadow-md mb-3">
                <div class="flex justify-between items-center text-white">
                    <span class="font-black uppercase text-xs"><i class="fa fa-money-bill-wave"></i> Total Pembelian</span>
                    <span id="grandTotalDisplayMobile" class="font-black text-2xl">Rp 0</span>
                </div>
            </div>

            <button type="button" class="w-full md:w-auto px-4 py-2.5 md:py-2 bg-emerald-600 text-white border border-emerald-800 shadow-md hover:bg-emerald-500 text-xs font-bold transition-all rounded-sm uppercase" id="addItemBtn">
                <i class="fa fa-plus"></i> Tambah Barang
            </button>
        </div>

        {{-- Keterangan --}}
        <div class="mb-6">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                <i class="fa fa-sticky-note"></i> Keterangan (Opsional)
            </label>
            <textarea name="keterangan" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm" rows="2"></textarea>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col md:flex-row justify-end gap-2 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 font-bold text-xs transition-all rounded-sm uppercase">
                <i class="fa fa-save"></i> Simpan Pembelian
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
                        q: params.term
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
        
        // Desktop Table Row
        const tableBody = $('#itemsTable tbody');
        const tableRow = `
            <tr class="text-xs hover:bg-blue-50 transition-colors border-b border-gray-200" data-row="${rowCount}">
                <td class="border-l border-r border-gray-300 p-2">
                    <select name="items[${rowCount}][id_produk]" class="product-select w-full text-xs border border-gray-300 p-1.5 rounded-sm" required>
                        <option value="">Cari Produk...</option>
                    </select>
                </td>
                <td class="border-r border-gray-300 p-2">
                    <input type="number" name="items[${rowCount}][jumlah]" class="qty-input w-full text-xs text-right border border-gray-300 p-1.5 rounded-sm" min="1" value="1" required oninput="calculateSubtotal(this)">
                </td>
                <td class="border-r border-gray-300 p-2">
                    <input type="number" name="items[${rowCount}][harga_satuan]" class="price-input w-full text-xs text-right border border-gray-300 p-1.5 rounded-sm" min="0" required oninput="calculateSubtotal(this)">
                </td>
                <td class="border-r border-gray-300 p-2">
                    <input type="text" class="subtotal-display w-full text-xs text-right bg-gray-50 border border-gray-300 p-1.5 rounded-sm font-bold text-amber-700" readonly value="Rp 0">
                    <input type="hidden" name="items[${rowCount}][subtotal]" class="subtotal-input">
                </td>
                <td class="border-r border-gray-300 p-2 text-center">
                    <button type="button" class="text-red-600 hover:text-red-800 font-bold" onclick="removeRow(${rowCount})">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        const $tableRow = $(tableRow);
        tableBody.append($tableRow);
        initSelect2($tableRow.find('.product-select'));

        // Mobile Card
        const mobileContainer = $('#mobileItemsContainer');
        const mobileCard = `
            <div class="bg-gradient-to-br from-white to-gray-50 border border-blue-200 p-3 rounded-sm shadow-sm" data-row="${rowCount}">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-black text-xs text-blue-700">Item #${rowCount}</span>
                    <button type="button" class="text-red-600 hover:text-red-800 font-bold" onclick="removeRow(${rowCount})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="space-y-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-600 mb-1">Produk</label>
                        <select name="items[${rowCount}][id_produk]" class="product-select-mobile w-full text-xs border border-gray-300 p-2 rounded-sm bg-white" required>
                            <option value="">Cari Produk...</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 mb-1">Jumlah</label>
                            <input type="number" name="items[${rowCount}][jumlah]" class="qty-input-mobile w-full text-xs text-right border border-gray-300 p-2 rounded-sm" min="1" value="1" required oninput="calculateSubtotalMobile(this)">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 mb-1">Harga Satuan</label>
                            <input type="number" name="items[${rowCount}][harga_satuan]" class="price-input-mobile w-full text-xs text-right border border-gray-300 p-2 rounded-sm" min="0" required oninput="calculateSubtotalMobile(this)">
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 p-2 rounded-sm">
                        <label class="block text-[10px] font-bold text-blue-700 mb-1">Subtotal</label>
                        <div class="subtotal-display-mobile text-sm font-black text-blue-900">Rp 0</div>
                        <input type="hidden" name="items[${rowCount}][subtotal]" class="subtotal-input-mobile">
                    </div>
                </div>
            </div>
        `;
        mobileContainer.append(mobileCard);
    }

    window.updatePrice = function(selectElement, price) {
        const row = $(selectElement).closest('tr, [data-row]');
        const priceInput = row.find('.price-input, .price-input-mobile');
        if (price) {
            priceInput.val(price);
            if ($(selectElement).hasClass('product-select')) {
                calculateSubtotal(priceInput[0]);
            } else {
                calculateSubtotalMobile(priceInput[0]);
            }
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

    window.calculateSubtotalMobile = function(input) {
        const card = $(input).closest('[data-row]');
        const qty = parseFloat(card.find('.qty-input-mobile').val()) || 0;
        const price = parseFloat(card.find('.price-input-mobile').val()) || 0;
        const subtotal = qty * price;
        
        card.find('.subtotal-display-mobile').text(formatRupiah(subtotal));
        card.find('.subtotal-input-mobile').val(subtotal);
        
        calculateGrandTotal();
    }

    window.removeRow = function(rowNum) {
        $(`tr[data-row="${rowNum}"]`).remove();
        $(`div[data-row="${rowNum}"]`).remove();
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        $('.subtotal-input, .subtotal-input-mobile').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        
        const formatted = formatRupiah(total);
        $('#grandTotalDisplay').text(formatted);
        $('#grandTotalDisplayMobile').text(formatted);
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
