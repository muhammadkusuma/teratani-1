@extends('layouts.owner')

@section('title', 'Input Penyesuaian Stok')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-lg md:text-xl border-b-4 border-amber-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-exchange-alt text-amber-700"></i> Penyesuaian Stok Manual
    </h2>
    <a href="{{ route('owner.riwayat-stok.index') }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white border border-gray-300 p-4 md:p-6 shadow-sm rounded-sm">
    <form action="{{ route('owner.riwayat-stok.store') }}" method="POST">
        @csrf
        
        {{-- Header Fields --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-calendar"></i> Tanggal <span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm" value="{{ date('Y-m-d') }}" required>
            </div>
            
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-exchange-alt"></i> Jenis <span class="text-red-600">*</span>
                </label>
                <select name="jenis" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm" required>
                    <option value="masuk">MASUK (Penambahan/Retur)</option>
                    <option value="keluar">KELUAR (Pengurangan/Rusak)</option>
                </select>
            </div>
            
            <div>
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-map-marker-alt"></i> Lokasi Tipe <span class="text-red-600">*</span>
                </label>
                <select name="location_type" id="location_type" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm" required>
                    <option value="toko">Toko</option>
                    <option value="gudang">Gudang</option>
                </select>
            </div>
            
            <div id="toko_container">
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-store"></i> Pilih Toko <span class="text-red-600">*</span>
                </label>
                <select name="location_id_toko" id="location_id_toko" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm">
                    @foreach($tokos as $toko)
                        <option value="{{ $toko->id_toko }}">{{ $toko->nama_toko }}</option>
                    @endforeach
                </select>
            </div>
            
            <div id="gudang_container" style="display: none;">
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-warehouse"></i> Pilih Gudang <span class="text-red-600">*</span>
                </label>
                <select name="location_id_gudang" id="location_id_gudang" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm">
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id_gudang }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>
            
            <input type="hidden" name="location_id" id="location_id">
        </div>

        {{-- Keterangan --}}
        <div class="mb-6">
            <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                <i class="fa fa-sticky-note"></i> Keterangan
            </label>
            <textarea name="keterangan" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none transition-all rounded-sm" rows="2" placeholder="Contoh: Retur dari pelanggan, Barang rusak, Selisih stok..."></textarea>
        </div>

        {{-- Items Section --}}
        <div class="mb-6">
            <h3 class="font-black text-sm bg-gradient-to-r from-indigo-900 to-indigo-700 text-white px-3 py-2 mb-3 uppercase tracking-wider rounded-sm shadow-md">
                <i class="fa fa-list"></i> Item Produk
            </h3>
            
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
                <table class="w-full text-left border-collapse" id="itemsTable">
                    <thead>
                        <tr class="bg-indigo-900 text-white text-[10px] font-black uppercase tracking-widest">
                            <th class="border border-indigo-900 p-3 w-[60%]">Produk</th>
                            <th class="border border-indigo-900 p-3 w-[30%]">Jumlah</th>
                            <th class="border border-indigo-900 p-3 w-[10%] text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- JS Populated -->
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards Container --}}
            <div id="mobileItemsContainer" class="md:hidden space-y-3 mb-3">
                <!-- Mobile cards added via JS -->
            </div>
            
            <button type="button" class="w-full md:w-auto mt-3 px-4 py-2.5 md:py-2 bg-emerald-600 text-white border border-emerald-800 shadow-md hover:bg-emerald-500 text-xs font-bold transition-all rounded-sm uppercase" id="addItemBtn">
                <i class="fa fa-plus"></i> Tambah Item
            </button>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col md:flex-row justify-end gap-2 pt-4 border-t border-gray-200">
            <a href="{{ route('owner.riwayat-stok.index') }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-indigo-700 text-white border border-indigo-900 shadow-md hover:bg-indigo-600 font-bold text-xs transition-all rounded-sm uppercase">
                <i class="fa fa-save"></i> Simpan Penyesuaian
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locType = document.getElementById('location_type');
        const tokoCont = document.getElementById('toko_container');
        const gudangCont = document.getElementById('gudang_container');
        const locIdInput = document.getElementById('location_id');
        const locIdToko = document.getElementById('location_id_toko');
        const locIdGudang = document.getElementById('location_id_gudang');

        function updateLocation() {
            if (locType.value === 'toko') {
                tokoCont.style.display = 'block';
                gudangCont.style.display = 'none';
                locIdInput.value = locIdToko.value;
            } else {
                tokoCont.style.display = 'none';
                gudangCont.style.display = 'block';
                locIdInput.value = locIdGudang.value;
            }
        }

        locType.addEventListener('change', updateLocation);
        locIdToko.addEventListener('change', updateLocation);
        locIdGudang.addEventListener('change', updateLocation);
        updateLocation(); // Init

        // Items Logic
        const itemsTableBody = document.querySelector('#itemsTable tbody');
        const mobileContainer = document.getElementById('mobileItemsContainer');
        const addItemBtn = document.getElementById('addItemBtn');
        let rowCount = 0;
        const products = @json($produks);

        addItemBtn.addEventListener('click', function() {
            rowCount++;
            
            // Desktop Table Row
            const tableRow = document.createElement('tr');
            tableRow.className = 'text-xs hover:bg-indigo-50 transition-colors border-b border-gray-200';
            tableRow.setAttribute('data-row', rowCount);
            
            let prodOptions = '<option value="">-- Pilih Produk --</option>';
            products.forEach(p => {
                prodOptions += `<option value="${p.id_produk}">${p.nama_produk} (${p.sku || '-'})</option>`;
            });
            
            tableRow.innerHTML = `
                <td class="border-l border-r border-gray-300 p-2">
                    <select name="items[${rowCount}][id_produk]" class="w-full text-xs border border-gray-300 p-1.5 rounded-sm" required>${prodOptions}</select>
                </td>
                <td class="border-r border-gray-300 p-2">
                    <input type="number" name="items[${rowCount}][jumlah]" class="w-full text-xs text-right border border-gray-300 p-1.5 rounded-sm" min="1" required>
                </td>
                <td class="border-r border-gray-300 p-2 text-center">
                    <button type="button" class="text-red-600 hover:text-red-800 font-bold" onclick="removeRow(${rowCount})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            itemsTableBody.appendChild(tableRow);

            // Mobile Card
            const mobileCard = document.createElement('div');
            mobileCard.className = 'bg-gradient-to-br from-white to-gray-50 border border-indigo-200 p-3 rounded-sm shadow-sm';
            mobileCard.setAttribute('data-row', rowCount);
            mobileCard.innerHTML = `
                <div class="flex justify-between items-start mb-2">
                    <span class="font-black text-xs text-indigo-700">Item #${rowCount}</span>
                    <button type="button" class="text-red-600 hover:text-red-800 font-bold" onclick="removeRow(${rowCount})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="space-y-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-600 mb-1">Produk</label>
                        <select name="items[${rowCount}][id_produk]" class="w-full text-xs border border-gray-300 p-2 rounded-sm bg-white" required>${prodOptions}</select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-600 mb-1">Jumlah</label>
                        <input type="number" name="items[${rowCount}][jumlah]" class="w-full text-xs text-right border border-gray-300 p-2 rounded-sm" min="1" required>
                    </div>
                </div>
            `;
            mobileContainer.appendChild(mobileCard);
        });

        window.removeRow = function(rowNum) {
            document.querySelector(`tr[data-row="${rowNum}"]`)?.remove();
            document.querySelector(`div[data-row="${rowNum}"]`)?.remove();
        };

        // Add 1 row on init
        addItemBtn.click();
    });
</script>
@endpush
@endsection
