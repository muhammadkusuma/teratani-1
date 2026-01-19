@extends('layouts.owner')

@section('title', 'Input Penyesuaian Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Input Penyesuaian Stok Manual (Opname/Retur)</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.riwayat-stok.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Jenis Penyesuaian</label>
                            <select name="jenis" class="form-control" required>
                                <option value="masuk">Masuk (Penambahan/Retur)</option>
                                <option value="keluar">Keluar (Pengurangan/Rusak)</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Lokasi Tipe</label>
                            <select name="location_type" id="location_type" class="form-control" required>
                                <option value="toko">Toko</option>
                                <option value="gudang">Gudang</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3" id="toko_container">
                            <label>Pilih Toko</label>
                            <select name="location_id_toko" id="location_id_toko" class="form-control">
                                @foreach($tokos as $toko)
                                    <option value="{{ $toko->id_toko }}">{{ $toko->nama_toko }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3" id="gudang_container" style="display: none;">
                            <label>Pilih Gudang</label>
                            <select name="location_id_gudang" id="location_id_gudang" class="form-control">
                                @foreach($gudangs as $gudang)
                                    <option value="{{ $gudang->id_gudang }}">{{ $gudang->nama_gudang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Valid location_id input to be populated via JS -->
                        <input type="hidden" name="location_id" id="location_id">
                    </div>

                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" placeholder="Contoh: Retur dari pelanggan, Barang rusak, Selisih stok..."></textarea>
                    </div>

                    <h5 class="mb-3">Item Produk</h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th width="20%">Jumlah</th>
                                    <th width="10%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- JS Populated -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <button type="button" class="btn btn-success btn-sm" id="addItemBtn">+ Tambah Item</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('owner.riwayat-stok.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Penyesuaian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
            rowCount++;
            
            const cell1 = row.insertCell(0);
            let options = '<option value="">-- Pilih Produk --</option>';
            products.forEach(p => {
                options += `<option value="${p.id_produk}">${p.nama_produk} (${p.sku || '-'})</option>`;
            });
            cell1.innerHTML = `<select name="items[${rowCount}][id_produk]" class="form-control select2" required>${options}</select>`;
            
            const cell2 = row.insertCell(1);
            cell2.innerHTML = `<input type="number" name="items[${rowCount}][jumlah]" class="form-control" min="1" required>`;
            
            const cell3 = row.insertCell(2);
            cell3.innerHTML = `<button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>`;
            
            cell3.querySelector('.remove-row').addEventListener('click', function() {
                row.remove();
            });
        });

        // Add 1 row
        addItemBtn.click();
    });
</script>
@endsection
