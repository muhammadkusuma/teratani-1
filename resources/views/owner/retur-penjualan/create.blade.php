@extends('layouts.owner')

@section('title', 'Buat Retur Penjualan')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight text-blue-900">
        <i class="fa fa-undo text-blue-700"></i> Input Retur Penjualan
    </h2>
    <a href="{{ route('owner.retur-penjualan.index') }}" class="w-full md:w-auto text-center px-4 py-1.5 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs font-bold transition-all uppercase tracking-wider">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white border border-gray-300 p-6 shadow-lg rounded-sm">
    <form action="{{ route('owner.retur-penjualan.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">Pelanggan Konsumen <span class="text-rose-600">*</span></label>
                <select id="pelangganSelect" name="id_pelanggan" class="w-full border border-gray-300 p-2.5 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm font-bold" onchange="updatePelangganKategori()" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id_pelanggan }}" data-kategori-harga="{{ $pelanggan->kategori_harga ?? 'umum' }}">{{ $pelanggan->nama_pelanggan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">Tanggal Retur <span class="text-rose-600">*</span></label>
                <input type="date" name="tgl_retur" class="w-full border border-gray-300 p-2.5 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 outline-none transition-all rounded-sm font-mono font-bold" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">Keterangan / Alasan Retur</label>
            <textarea name="keterangan" class="w-full border border-gray-300 p-3 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 outline-none transition-all rounded-sm h-16" placeholder="Masukkan detail alasan retur barang..."></textarea>
        </div>

        <div class="flex items-center gap-2 mb-3 border-b border-gray-100 pb-2">
            <i class="fa fa-list text-blue-700"></i>
            <h3 class="font-black text-xs uppercase tracking-widest text-gray-700">Daftar Item Barang</h3>
        </div>

        {{-- Desktop Headers --}}
        <div class="hidden md:grid md:grid-cols-12 bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest p-3 rounded-t-sm mb-0">
            <div class="col-span-5">Produk / Barang</div>
            <div class="col-span-2 text-center">Qty</div>
            <div class="col-span-2 text-center">Pilih Harga</div>
            <div class="col-span-2 text-right pr-4">Subtotal</div>
            <div class="col-span-1 text-center">Aksi</div>
        </div>

        <div id="itemRows" class="space-y-4 md:space-y-0 border-x border-b border-gray-200 md:border-t-0 rounded-b-sm bg-gray-50/30">
            <!-- Dynamic Rows/Cards Added Here -->
        </div>
        
        <div class="mt-4">
            <button type="button" onclick="addRow()" class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-500 text-white font-black py-2.5 px-6 rounded-sm mb-8 text-xs shadow-sm flex items-center justify-center gap-2 transition-transform active:scale-95 uppercase tracking-tighter">
                <i class="fa fa-plus-circle"></i> Tambah Item Lagi
            </button>
        </div>

        <div class="bg-gray-50 border border-gray-200 p-6 rounded-sm flex flex-col md:flex-row items-center justify-between gap-4 mt-4">
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic leading-none">Status Proses</span>
                <span class="text-xs font-bold text-emerald-700 uppercase flex items-center gap-1 mt-1">
                    <i class="fa fa-check-circle"></i> Selesai (Otomatis Update Stok)
                </span>
            </div>
            <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-blue-700 hover:bg-blue-600 text-white font-black py-3 px-10 rounded-sm shadow-lg hover:shadow-xl transition-all uppercase tracking-widest text-xs active:scale-95">
                    <i class="fa fa-save mr-2"></i> Simpan Retur
                </button>
                <a href="{{ route('owner.retur-penjualan.index') }}" class="w-full md:w-auto text-center bg-white border border-gray-300 text-gray-500 font-black py-3 px-10 rounded-sm hover:bg-gray-100 transition-all uppercase tracking-widest text-xs">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const produks = @json($produks);
    let selectedKategoriHarga = 'umum'; // Default kategori harga

    function updatePelangganKategori() {
        const select = document.getElementById('pelangganSelect');
        const option = select.options[select.selectedIndex];
        selectedKategoriHarga = option.getAttribute('data-kategori-harga') || 'umum';
        
        // Update all existing rows to reflect new customer category
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(row => {
            const produkSelect = row.querySelector('select[name="produk_id[]"]');
            if (produkSelect.value) {
                updateHarga(produkSelect);
            }
        });
    }

    function addRow() {
        const container = document.getElementById('itemRows');
        
        let produkOptions = '<option value="">-- Pilih Produk / Barang --</option>';
        produks.forEach(p => {
            produkOptions += `<option value="${p.id_produk}" 
                data-harga-umum="${p.harga_jual_umum || 0}" 
                data-harga-grosir="${p.harga_jual_grosir || 0}" 
                data-harga-r1="${p.harga_r1 || 0}" 
                data-harga-r2="${p.harga_r2 || 0}">${p.nama_produk} (${p.sku})</option>`;
        });

        const row = document.createElement('div');
        row.className = "item-row p-4 md:p-3 bg-white md:bg-transparent border border-gray-200 md:border-0 md:border-b md:border-gray-100 rounded-sm md:rounded-none md:grid md:grid-cols-12 md:gap-4 md:items-center relative shadow-sm md:shadow-none hover:bg-blue-50/30 transition-colors";
        
        row.innerHTML = `
            <div class="col-span-5 mb-4 md:mb-0">
                <label class="block md:hidden text-[9px] font-black text-gray-400 uppercase mb-1 tracking-widest italic">Produk</label>
                <select name="produk_id[]" class="w-full border border-gray-300 p-2 text-xs shadow-sm bg-gray-50 md:bg-white focus:border-blue-500 outline-none rounded-sm font-bold appearance-none cursor-pointer" onchange="updateHarga(this)" required>
                    ${produkOptions}
                </select>
            </div>
            <div class="col-span-2 mb-4 md:mb-0">
                <label class="block md:hidden text-[9px] font-black text-gray-400 uppercase mb-1 tracking-widest italic text-center">Qty</label>
                <input type="number" name="qty[]" class="w-full border border-gray-300 p-2 text-xs shadow-sm text-center font-black focus:border-blue-500 outline-none rounded-sm bg-gray-50 md:bg-white" value="1" min="1" onchange="calculateRow(this)" required>
            </div>
            <div class="col-span-2 mb-4 md:mb-0">
                <label class="block md:hidden text-[9px] font-black text-gray-400 uppercase mb-1 tracking-widest italic">Pilih Harga</label>
                <select class="price-select w-full border border-gray-300 p-2 text-xs shadow-sm bg-gray-50 md:bg-white focus:border-blue-500 outline-none rounded-sm font-bold" onchange="updateHargaFromSelect(this)" disabled>
                    <option value="">-- Pilih harga --</option>
                </select>
                <input type="hidden" name="harga_satuan[]" class="harga-input" value="0" required>
                <div class="text-right mt-1 text-[9px] font-mono font-bold text-blue-700 price-display">Rp 0</div>
            </div>
            <div class="col-span-2 mb-2 md:mb-0 text-right md:pr-4">
                <label class="block md:hidden text-[9px] font-black text-gray-400 uppercase mb-1 tracking-widest italic">Subtotal</label>
                <span class="subtotal-display font-black text-blue-800 text-xs md:text-sm">0</span>
            </div>
            <div class="col-span-1 flex justify-center mt-2 md:mt-0 pt-3 md:pt-0 border-t border-gray-50 md:border-none">
                <button type="button" onclick="removeRow(this)" class="w-full md:w-auto bg-rose-50 md:bg-transparent text-rose-500 hover:text-rose-700 md:hover:scale-110 transition-all font-bold p-2 md:p-1 rounded-sm border md:border-none border-rose-200">
                    <span class="md:hidden text-[10px] font-black uppercase tracking-tighter mr-2">Hapus Item</span>
                    <i class="fa fa-times-circle text-lg leading-none align-middle"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
    }

    function removeRow(btn) {
        const row = btn.closest('.item-row');
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
        } else {
            alert('Minimal harus ada 1 item barang yang di-retur.');
        }
    }

    function updateHarga(selectElement) {
        const option = selectElement.options[selectElement.selectedIndex];
        const row = selectElement.closest('.item-row');
        const priceSelect = row.querySelector('.price-select');
        const hargaInput = row.querySelector('.harga-input');
        
        if (!option.value) {
            priceSelect.disabled = true;
            priceSelect.innerHTML = '<option value="">-- Pilih harga --</option>';
            hargaInput.value = 0;
            updatePriceDisplay(row);
            calculateRow(selectElement);
            return;
        }

        // Get all prices from data attributes
        const hargaUmum = parseFloat(option.getAttribute('data-harga-umum')) || 0;
        const hargaGrosir = parseFloat(option.getAttribute('data-harga-grosir')) || 0;
        const hargaR1 = parseFloat(option.getAttribute('data-harga-r1')) || 0;
        const hargaR2 = parseFloat(option.getAttribute('data-harga-r2')) || 0;

        // Build price options
        let priceOptions = '';
        const prices = [];

        if (hargaR1 > 0) {
            prices.push({label: 'R1', value: hargaR1, key: 'r1'});
        }
        if (hargaR2 > 0) {
            prices.push({label: 'R2', value: hargaR2, key: 'r2'});
        }
        if (hargaGrosir > 0) {
            prices.push({label: 'Grosir', value: hargaGrosir, key: 'grosir'});
        }
        if (hargaUmum > 0) {
            prices.push({label: 'Umum', value: hargaUmum, key: 'umum'});
        }

        // Generate options
        prices.forEach(price => {
            const formatHarga = new Intl.NumberFormat('id-ID').format(price.value);
            const selected = price.key === selectedKategoriHarga ? 'selected' : '';
            priceOptions += `<option value="${price.value}" ${selected}>Harga ${price.label} - Rp ${formatHarga}</option>`;
        });

        priceSelect.innerHTML = priceOptions;
        priceSelect.disabled = false;

        // Set the selected price value
        const selectedPrice = priceSelect.value || hargaUmum;
        hargaInput.value = selectedPrice;
        
        updatePriceDisplay(row);
        calculateRow(selectElement);
    }

    function updateHargaFromSelect(selectElement) {
        const row = selectElement.closest('.item-row');
        const hargaInput = row.querySelector('.harga-input');
        hargaInput.value = selectElement.value;
        updatePriceDisplay(row);
        calculateRow(selectElement);
    }

    function updatePriceDisplay(row) {
        const hargaInput = row.querySelector('.harga-input');
        const priceDisplay = row.querySelector('.price-display');
        const harga = parseFloat(hargaInput.value) || 0;
        priceDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
    }

    function calculateRow(element) {
        const row = element.closest('.item-row');
        const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
        const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
        const subtotal = qty * harga;
        
        row.querySelector('.subtotal-display').innerText = new Intl.NumberFormat('id-ID').format(subtotal);
    }
    
    // Add one row by default
    document.addEventListener('DOMContentLoaded', () => {
        addRow();
    });
</script>
@endpush
@endsection
