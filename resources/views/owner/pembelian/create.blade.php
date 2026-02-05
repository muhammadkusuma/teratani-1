@extends('layouts.owner')

@section('title', 'Input Pembelian')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-3">
    <h2 class="font-bold text-lg border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-cart-plus text-blue-700"></i> Input Pembelian Baru
    </h2>
    <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-1.5 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs font-bold transition-all uppercase">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

@if ($errors->any())
    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-3 mb-4 shadow-sm text-xs">
        <p class="font-black uppercase mb-1 tracking-wider"><i class="fa fa-exclamation-triangle"></i> Terjadi Kesalahan:</p>
        <ul class="list-disc ml-5 space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-3 mb-4 shadow-sm text-xs">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white border border-gray-300 p-6 shadow-sm rounded-sm">
    <form action="{{ route('owner.toko.pembelian.store', $toko->id_toko) }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Tanggal Pembelian <span class="text-rose-600">*</span></label>
                <input type="date" name="tanggal" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" value="{{ date('Y-m-d') }}" required>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Distributor <span class="text-rose-600">*</span></label>
                <select name="id_distributor" required class="w-full border border-gray-300 p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none">
                    <option value="">-- Pilih Distributor --</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id_distributor }}" {{ request('distributor_id') == $distributor->id_distributor ? 'selected' : '' }}>
                            {{ $distributor->nama_distributor }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">No. Faktur</label>
                <input type="text" name="no_faktur" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none font-mono" placeholder="Nomor Invoice...">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Tujuan Stok <span class="text-rose-600">*</span></label>
                <select name="destination" id="destination" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" required>
                    <option value="toko">Toko Utama ({{ $toko->nama_toko }})</option>
                    @foreach($gudangs as $gudang)
                        <option value="gudang_{{ $gudang->id_gudang }}">Gudang: {{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="destination_type" id="destination_type" value="toko">
                <input type="hidden" name="destination_id" id="destination_id" value="{{ $toko->id_toko }}">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Metode Pembayaran <span class="text-rose-600">*</span></label>
                <select name="jenis_bayar" id="jenis_bayar" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" required>
                    <option value="cash">CASH (Tunai)</option>
                    <option value="hutang">HUTANG (Tempo)</option>
                </select>
            </div>

            <div id="jatuh_tempo_container" class="hidden">
                <label class="block text-[10px] font-black text-rose-600 uppercase mb-1 tracking-wider">Tanggal Jatuh Tempo <span class="text-rose-600">*</span></label>
                <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="w-full border border-rose-300 p-2 text-xs shadow-inner focus:border-rose-500 transition-all outline-none bg-rose-50">
            </div>
        </div>

        <div class="mb-6 border-t border-gray-200 pt-5">
            <h3 class="font-black text-sm text-gray-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                <i class="fa fa-list-ol text-blue-500"></i> Item Pembelian
            </h3>
            <div class="overflow-x-auto border border-gray-200 rounded-sm shadow-sm">
                <table class="w-full text-left border-collapse" id="itemsTable">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-[10px] uppercase tracking-wider font-black">
                            <th class="border-b border-gray-200 p-3 w-[35%]">Produk</th>
                            <th class="border-b border-gray-200 p-3 w-[15%]">Jumlah</th>
                            <th class="border-b border-gray-200 p-3 w-[20%] text-right">Harga Satuan</th>
                            <th class="border-b border-gray-200 p-3 w-[20%] text-right">Subtotal</th>
                            <th class="border-b border-gray-200 p-3 w-[10%] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Rows added via JS -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-blue-50 font-bold text-xs">
                            <td colspan="3" class="p-3 text-right uppercase tracking-wider text-blue-800">Total Pembelian:</td>
                            <td colspan="2" class="p-3 text-right">
                                <span id="grandTotalDisplay" class="text-blue-700 text-lg">Rp 0</span>
                                <input type="hidden" name="total_pembelian" id="grandTotalInput">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="button" class="mt-3 px-4 py-2 bg-emerald-600 text-white border border-emerald-700 shadow-sm hover:bg-emerald-500 text-xs font-bold rounded-sm uppercase tracking-wider transition-all" id="addItemBtn">
                + Tambah Barang
            </button>
        </div>

        <div class="mb-6">
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="w-full border border-gray-300 p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none" rows="2" placeholder="Catatan tambahan..."></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="px-6 py-2 bg-gray-100 text-gray-600 border border-gray-300 text-xs font-bold rounded-sm uppercase tracking-widest hover:bg-gray-200 transition-all">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-700 text-white border border-blue-900 shadow-lg hover:bg-blue-600 hover:scale-[1.02] text-xs font-black rounded-sm uppercase tracking-widest transition-all">
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
            dropdownParent: $(element).closest('td'), // Ensure dropdown opens correctly in table
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
            <tr class="text-xs group hover:bg-blue-50/50 transition-colors">
                <td class="p-2 align-middle">
                    <select name="items[${rowCount}][id_produk]" class="product-select w-full text-xs" required>
                        <option value="">Cari Produk...</option>
                    </select>
                </td>
                <td class="p-2 align-middle">
                    <input type="number" name="items[${rowCount}][jumlah]" class="qty-input w-full border border-gray-300 p-1.5 text-xs text-right shadow-inner focus:border-blue-500 outline-none rounded-sm" min="1" value="1" required oninput="calculateSubtotal(this)">
                </td>
                <td class="p-2 align-middle">
                    <input type="number" name="items[${rowCount}][harga_satuan]" class="price-input w-full border border-gray-300 p-1.5 text-xs text-right shadow-inner focus:border-blue-500 outline-none rounded-sm" min="0" required oninput="calculateSubtotal(this)">
                </td>
                <td class="p-2 align-middle">
                    <input type="text" class="subtotal-display w-full border border-transparent bg-transparent p-1.5 text-xs text-right font-bold text-gray-700" readonly value="0">
                    <input type="hidden" name="items[${rowCount}][subtotal]" class="subtotal-input">
                </td>
                <td class="p-2 align-middle text-center">
                    <button type="button" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1 rounded-full transition-all" onclick="removeRow(this)" title="Hapus Item">
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
    
    // Handle payment method change
    $('#jenis_bayar').on('change', function() {
        if (this.value === 'hutang') {
            $('#jatuh_tempo_container').removeClass('hidden');
            $('#jatuh_tempo').prop('required', true);
        } else {
            $('#jatuh_tempo_container').addClass('hidden');
            $('#jatuh_tempo').prop('required', false);
        }
    });

    // Add first row on load
    addRow();
</script>
<style>
    /* Custom override for Select2 to match the theme */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #d1d5db; /* gray-300 */
        border-radius: 0.125rem; /* rounded-sm */
        height: 34px;
        box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05); /* shadow-inner */
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 34px;
        font-size: 0.75rem; /* text-xs */
        padding-left: 0.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 32px;
    }
    /* Error state for select2 if needed */
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6; /* blue-500 */
    }
</style>
@endpush
@endsection
