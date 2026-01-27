@extends('layouts.owner')

@section('title', 'Buat Retur Pembelian')

@section('content')
@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4"><i class="fa fa-plus-circle"></i> TAMBAH RETUR PEMBELIAN</h2>
    <a href="{{ route('owner.retur-pembelian.index') }}" class="px-3 py-1 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs"><i class="fa fa-arrow-left"></i> KEMBALI</a>
</div>

<div class="bg-white border border-gray-400 p-4">
    <form action="{{ route('owner.retur-pembelian.store') }}" method="POST">
        @csrf
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
            <div>
                <label class="block text-xs font-bold mb-1">Distributor <span class="text-red-600">*</span></label>
                <select name="id_distributor" class="w-full border border-gray-400 p-1 text-xs shadow-inner" required>
                    <option value="">-- Pilih Distributor --</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id_distributor }}">{{ $distributor->nama_distributor }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">Sumber Barang (Gudang / Toko) <span class="text-red-600">*</span></label>
                <select id="source_select" name="source_id" class="w-full border border-gray-400 p-1 text-xs shadow-inner" required>
                    <option value="">-- Pilih Sumber Barang --</option>
                    @foreach($tokos as $toko)
                        <optgroup label="Toko: {{ $toko->nama_toko }}">
                            <option value="toko_{{ $toko->id_toko }}" {{ old('source_id') == 'toko_'.$toko->id_toko ? 'selected' : '' }}> Stok Toko ({{ $toko->nama_toko }})</option>
                            @foreach($gudangs->where('id_toko', $toko->id_toko) as $gudang)
                                <option value="gudang_{{ $gudang->id_gudang }}" {{ old('source_id') == 'gudang_'.$gudang->id_gudang ? 'selected' : '' }}> Gudang: {{ $gudang->nama_gudang }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                 <input type="hidden" name="id_gudang" id="id_gudang" value="{{ old('id_gudang') }}">
                 <input type="hidden" name="id_toko" id="id_toko" value="{{ old('id_toko') }}">
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">Tanggal Retur <span class="text-red-600">*</span></label>
                <input type="date" name="tgl_retur" class="w-full border border-gray-400 p-1 text-xs shadow-inner" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="block text-xs font-bold mb-1">Keterangan</label>
            <textarea name="keterangan" rows="2" class="w-full border border-gray-400 p-1 text-xs shadow-inner"></textarea>
        </div>

        <div class="mb-3">
            <h3 class="font-bold mb-2 text-sm border-b border-gray-300 pb-1 mt-4">ITEM RETUR</h3>
            
            <!-- Items Header (Hidden on Mobile) -->
            <div class="hidden md:grid md:grid-cols-12 gap-2 bg-gray-100 p-2 text-xs font-bold uppercase text-gray-700 border border-gray-300">
                <div class="col-span-4">Produk</div>
                <div class="col-span-2 text-right">Qty</div>
                <div class="col-span-3 text-right">Harga Beli</div>
                <div class="col-span-2 text-right">Subtotal</div>
                <div class="col-span-1 text-center">Aksi</div>
            </div>

            <!-- Items Container -->
            <div id="itemsContainer" class="space-y-3 md:space-y-0">
                <!-- Rows will be added here -->
            </div>
        </div>
        
        <button type="button" onclick="addRow()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 text-xs mb-4 shadow border border-green-800 rounded mt-2">
            <i class="fa fa-plus"></i> Tambah Item
        </button>

        <div class="flex gap-2 mt-4 pt-4 border-t border-gray-300">
            <button type="submit" class="bg-blue-700 text-white border border-blue-900 px-4 py-2 text-xs hover:bg-blue-600 rounded"><i class="fa fa-save"></i> SIMPAN RETUR</button>
            <a href="{{ route('owner.retur-pembelian.index') }}" class="bg-gray-200 border border-gray-400 px-4 py-2 text-xs hover:bg-gray-300 rounded">BATAL</a>
        </div>
    </form>
</div>

<script>
    const stokGudangs = @json($stokGudangs);
    const stokTokos = @json($stokTokos);

    const sourceSelect = document.getElementById('source_select');
    const idGudangInput = document.getElementById('id_gudang');
    const idTokoInput = document.getElementById('id_toko');

    sourceSelect.addEventListener('change', function() {
        const val = this.value;
        document.getElementById('itemsContainer').innerHTML = ''; // Reset items
        
        idGudangInput.value = '';
        idTokoInput.value = '';

        if (val.startsWith('gudang_')) {
            idGudangInput.value = val.replace('gudang_', '');
        } else if (val.startsWith('toko_')) {
            idTokoInput.value = val.replace('toko_', '');
        }
    });

    function addRow() {
        const sourceVal = sourceSelect.value;
        if (!sourceVal) {
            alert('Silakan pilih Sumber Barang terlebih dahulu!');
            return;
        }

        const container = document.getElementById('itemsContainer');
        let produkOptions = '<option value="">-- Pilih Produk --</option>';
        let filteredStoks = [];

        if (sourceVal.startsWith('gudang_')) {
            const gudangId = sourceVal.replace('gudang_', '');
            filteredStoks = stokGudangs.filter(s => s.id_gudang == gudangId);
        } else if (sourceVal.startsWith('toko_')) {
            const tokoId = sourceVal.replace('toko_', '');
            filteredStoks = stokTokos.filter(s => s.id_toko == tokoId);
        }

        if (filteredStoks.length === 0) {
            alert('Tidak ada stok produk di sumber yang dipilih.');
            return;
        }

        filteredStoks.forEach(s => {
            // Label logic
            let label = "";
            if(s.gudang) {
                 label = `${s.produk.nama_produk} - ${s.gudang.nama_gudang}`;
            } else if (s.toko) {
                 label = `${s.produk.nama_produk} - Toko: ${s.toko.nama_toko}`;
            }
            // Use s.produk.harga_beli as default price
            const harga = s.produk.harga_beli; 
            produkOptions += `<option value="${s.id_produk}" data-harga="${harga}">${label}</option>`;
        });

        const rowDiv = document.createElement('div');
        rowDiv.className = "grid grid-cols-1 md:grid-cols-12 gap-2 bg-white md:bg-transparent p-3 md:p-2 border border-gray-300 md:border-0 md:border-b md:border-gray-200 shadow-sm md:shadow-none items-start md:items-center relative";

        rowDiv.innerHTML = `
            <div class="col-span-4">
                <label class="block md:hidden text-xs font-bold text-gray-600 mb-1">Produk</label>
                <select name="produk_id[]" class="w-full border border-gray-400 p-1 text-xs shadow-inner rounded" onchange="updateHarga(this)" required>
                    ${produkOptions}
                </select>
            </div>
            <div class="col-span-2">
                <label class="block md:hidden text-xs font-bold text-gray-600 mb-1">Qty</label>
                <input type="number" name="qty[]" class="w-full border border-gray-400 p-1 text-right text-xs shadow-inner rounded" value="1" min="1" onchange="calculateRow(this)" required>
            </div>
            <div class="col-span-3">
                <label class="block md:hidden text-xs font-bold text-gray-600 mb-1">Harga Beli</label>
                <input type="number" name="harga_satuan[]" class="w-full border border-gray-400 p-1 text-right text-xs shadow-inner rounded" value="0" required onchange="calculateRow(this)">
            </div>
            <div class="col-span-2 text-right">
                <div class="flex justify-between md:block items-center">
                    <label class="block md:hidden text-xs font-bold text-gray-600">Subtotal</label>
                    <span class="subtotal-display font-bold text-sm">0</span>
                </div>
            </div>
            <div class="col-span-1 text-center absolute md:static top-2 right-2">
                 <button type="button" onclick="this.closest('.grid').remove()" class="text-red-600 hover:text-red-800 font-bold p-1">
                    <i class="fa fa-trash"></i>
                 </button>
            </div>
        `;
        container.appendChild(rowDiv);
    }

    function updateHarga(select) {
        const option = select.options[select.selectedIndex];
        const harga = option.getAttribute('data-harga');
        const row = select.closest('.grid');
        const hargaInput = row.querySelector('input[name="harga_satuan[]"]');
        if (harga) {
            hargaInput.value = parseInt(harga);
            calculateRow(select);
        }
    }

    function calculateRow(element) {
        const row = element.closest('.grid');
        const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
        const harga = parseFloat(row.querySelector('input[name="harga_satuan[]"]').value) || 0;
        const subtotal = qty * harga;
        
        row.querySelector('.subtotal-display').innerText = new Intl.NumberFormat('id-ID').format(subtotal);
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        // Handle old input for source_id and trigger change if exists
        const oldSourceId = "{{ old('source_id') }}";
        if (oldSourceId) {
            // We don't need to manually set value here because Blade's 'selected' attribute handled it,
            // but we might need to sync the hidden inputs if they weren't somehow (though Blade handles 'value' too)
            // The itemsContainer will be empty after validation failure, 
            // the user will usually have to re-add items or we could theoretically preserve those too 
            // but preserving items requires more complex JS state management.
            // For now, let's just make sure the source is correctly set.
        }
    });
</script>
@endsection
