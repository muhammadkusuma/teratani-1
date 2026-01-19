@extends('layouts.owner')

@section('title', 'Input Penyesuaian Stok')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-edit"></i> INPUT PENYESUAIAN STOK MANUAL
    </h2>
    <a href="{{ route('owner.riwayat-stok.index') }}" class="px-3 py-1 bg-gray-200 text-gray-700 border border-gray-400 shadow hover:bg-gray-300 text-xs">
        <i class="fa fa-arrow-left"></i> KEMBALI
    </a>
</div>

<div class="bg-gray-100 border border-gray-400 p-4">
    <form action="{{ route('owner.riwayat-stok.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-xs font-bold mb-1">TANGGAL</label>
                <input type="date" name="tanggal" class="win98-input w-full text-sm" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">JENIS PENYESUAIAN</label>
                <select name="jenis" class="win98-input w-full text-sm" required>
                    <option value="masuk">MASUK (Penambahan/Retur)</option>
                    <option value="keluar">KELUAR (Pengurangan/Rusak)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">LOKASI TIPE</label>
                <select name="location_type" id="location_type" class="win98-input w-full text-sm" required>
                    <option value="toko">Toko</option>
                    <option value="gudang">Gudang</option>
                </select>
            </div>
            <div id="toko_container">
                <label class="block text-xs font-bold mb-1">PILIH TOKO</label>
                <select name="location_id_toko" id="location_id_toko" class="win98-input w-full text-sm">
                    @foreach($tokos as $toko)
                        <option value="{{ $toko->id_toko }}">{{ $toko->nama_toko }}</option>
                    @endforeach
                </select>
            </div>
            <div id="gudang_container" style="display: none;">
                <label class="block text-xs font-bold mb-1">PILIH GUDANG</label>
                <select name="location_id_gudang" id="location_id_gudang" class="win98-input w-full text-sm">
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id_gudang }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Valid location_id input to be populated via JS -->
            <input type="hidden" name="location_id" id="location_id">
        </div>

        <div class="mb-4">
            <label class="block text-xs font-bold mb-1">KETERANGAN</label>
            <textarea name="keterangan" class="win98-input w-full text-sm" rows="2" placeholder="Contoh: Retur dari pelanggan, Barang rusak, Selisih stok..."></textarea>
        </div>

        <div class="mb-4">
            <h3 class="font-bold text-sm bg-blue-900 text-white px-2 py-1 mb-2">ITEM PRODUK</h3>
            <div class="overflow-x-auto border border-gray-400 bg-white">
                <table class="w-full text-left border-collapse" id="itemsTable">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                            <th class="border border-gray-400 p-2 w-[55%]">Produk</th>
                            <th class="border border-gray-400 p-2 w-[25%]">Jumlah</th>
                            <th class="border border-gray-400 p-2 w-[10%] text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- JS Populated -->
                    </tbody>
                </table>
            </div>
            <button type="button" class="mt-2 px-3 py-1 bg-green-600 text-white border border-green-800 shadow hover:bg-green-500 text-xs font-bold" id="addItemBtn">
                + TAMBAH ITEM
            </button>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white border-2 border-blue-900 shadow hover:bg-blue-600 font-bold text-sm">
                <i class="fa fa-save"></i> SIMPAN PENYESUAIAN
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
        const itemsTable = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
        const addItemBtn = document.getElementById('addItemBtn');
        let rowCount = 0;
        const products = @json($produks);

        addItemBtn.addEventListener('click', function() {
            const row = itemsTable.insertRow();
            row.className = "text-xs";
            rowCount++;
            
            const cell1 = row.insertCell(0);
            cell1.className = "border border-gray-300 p-1";
            let options = '<option value="">-- Pilih Produk --</option>';
            products.forEach(p => {
                options += `<option value="${p.id_produk}">${p.nama_produk} (${p.sku || '-'})</option>`;
            });
            cell1.innerHTML = `<select name="items[${rowCount}][id_produk]" class="win98-input w-full text-xs" required>${options}</select>`;
            
            const cell2 = row.insertCell(1);
            cell2.className = "border border-gray-300 p-1";
            cell2.innerHTML = `<input type="number" name="items[${rowCount}][jumlah]" class="win98-input w-full text-xs text-right" min="1" required>`;
            
            const cell3 = row.insertCell(2);
            cell3.className = "border border-gray-300 p-1 text-center";
            cell3.innerHTML = `<button type="button" class="text-red-600 hover:text-red-800" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button>`;
        });

        // Add 1 row
        addItemBtn.click();
    });
</script>
@endpush
@endsection
