@extends('layouts.owner')

@section('title', 'Input Pembelian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Input Pembelian Distributor</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.toko.pembelian.store', $toko->id_toko) }}" method="POST" id="pembelianForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Distributor</label>
                            <select name="id_distributor" class="form-control select2 @error('id_distributor') is-invalid @enderror" required>
                                <option value="">-- Pilih Distributor --</option>
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id_distributor }}" {{ old('id_distributor') == $distributor->id_distributor ? 'selected' : '' }}>
                                        {{ $distributor->nama_distributor }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_distributor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>No Faktur (Opsional)</label>
                            <input type="text" name="no_faktur" class="form-control @error('no_faktur') is-invalid @enderror" value="{{ old('no_faktur') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Masuk ke Stok</label>
                            <select name="destination_type" id="destination_type" class="form-control @error('destination_type') is-invalid @enderror" required>
                                <option value="toko" {{ old('destination_type') == 'toko' ? 'selected' : '' }}>Toko Utama</option>
                                <option value="gudang" {{ old('destination_type') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                            </select>
                            @error('destination_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3" id="destination_id_container" style="display: none;">
                            <label>Pilih Gudang</label>
                            <select name="destination_id" id="destination_id" class="form-control @error('destination_id') is-invalid @enderror">
                                <!-- Populated via JS or server rendered but hidden -->
                                @foreach($gudangs as $gudang)
                                    <option value="{{ $gudang->id_gudang }}">{{ $gudang->nama_gudang }}</option>
                                @endforeach
                            </select>
                            @error('destination_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <!-- Hidden input for Toko ID when Toko is selected -->
                        <input type="hidden" name="toko_id" id="toko_id" value="{{ $toko->id_toko }}">
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <h5 class="mb-3">Item Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 40%">Produk</th>
                                    <th style="width: 20%">Harga Satuan</th>
                                    <th style="width: 15%">Jumlah</th>
                                    <th style="width: 20%">Total</th>
                                    <th style="width: 5%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows added via JS -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-success btn-sm" id="addItemBtn"><i class="fas fa-plus"></i> Tambah Item</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Grand Total</td>
                                    <td colspan="2" class="fw-bold" id="grandTotal">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const destinationType = document.getElementById('destination_type');
        const destinationIdContainer = document.getElementById('destination_id_container');
        const destinationId = document.getElementById('destination_id');
        const tokoId = document.getElementById('toko_id');
        
        // Initial check
        if(destinationType.value === 'gudang') {
            destinationIdContainer.style.display = 'block';
             // We need to ensure the select name is destination_id
        } else {
            destinationIdContainer.style.display = 'none';
        }

        destinationType.addEventListener('change', function() {
            if(this.value === 'gudang') {
                destinationIdContainer.style.display = 'block';
                destinationId.value = "{{ $gudangs->first()->id_gudang ?? '' }}"; // Reset to first gudang
            } else {
                destinationIdContainer.style.display = 'none';
                destinationId.value = tokoId.value; // Set to toko ID
            }
        });
        
        // Handle form submit to ensure correct destination_id
        const form = document.getElementById('pembelianForm');
        form.addEventListener('submit', function() {
             if(destinationType.value === 'toko') {
                 // Clone hidden input or set value into select if simpler?
                 // Actually the server logic handles validation based on type.
                 // But validation rule 'destination_id' => 'required' might fail if the select is hidden/disabled?
                 // No, hidden elements are submitted. But wait.
                 // If I set destination_id select value to toko_id when type is toko, it works.
             }
        });

        // Whenever type changes to toko, force the value of the select to be the toko ID?
        // No, easier way: remove name attribute from select if type is toko, add hidden input with name destination_id.
        // Let's refine the logic.
        // Actually, let's keep it simple. Controller expects `destination_id`.
        // If type is `toko`, use JS to inject a hidden input named `destination_id` with value `toko_id`, and remove name from the select.
        // Or simpler: Just set the value of the select to toko_id? No, the select only has Gudang options.
        
        // BETTER APPROACH:
        // Use two inputs? No.
        // Let's just create a hidden input for destination_id that updates whenever user changes selections.
        // And remove name="destination_id" from the select box, just use it as a UI control.
        
        const realDestinationId = document.createElement('input');
        realDestinationId.type = 'hidden';
        realDestinationId.name = 'destination_id';
        realDestinationId.value = destinationType.value === 'toko' ? tokoId.value : destinationId.value;
        form.appendChild(realDestinationId);
        
        // Remove name from select
        destinationId.removeAttribute('name');
        
        destinationType.addEventListener('change', updateDestination);
        destinationId.addEventListener('change', updateDestination);
        
        function updateDestination() {
            if (destinationType.value === 'toko') {
                realDestinationId.value = tokoId.value;
            } else {
                realDestinationId.value = destinationId.value;
            }
        }

        // --- Items Logic ---
        const itemsTable = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
        const addItemBtn = document.getElementById('addItemBtn');
        const grandTotalEl = document.getElementById('grandTotal');
        
        let rowCount = 0;
        const products = @json($produks); // Passthrough products

        addItemBtn.addEventListener('click', function() {
            addItemRow();
        });

        // Add one empty row on load
        addItemRow();

        function addItemRow() {
            const row = itemsTable.insertRow();
            rowCount++;
            
            // Product Select
            const cell1 = row.insertCell(0);
            let productOptions = '<option value="">-- Pilih Produk --</option>';
            products.forEach(p => {
                productOptions += `<option value="${p.id_produk}" data-price="${p.harga_beli}">${p.nama_produk}</option>`;
            });
            cell1.innerHTML = `<select name="items[${rowCount}][id_produk]" class="form-control product-select" required>${productOptions}</select>`;

            // Price Input
            const cell2 = row.insertCell(1);
            cell2.innerHTML = `<input type="number" name="items[${rowCount}][harga_satuan]" class="form-control price-input" required min="0">`;

            // Qty Input
            const cell3 = row.insertCell(2);
            cell3.innerHTML = `<input type="number" name="items[${rowCount}][jumlah]" class="form-control qty-input" required min="1" value="1">`;

            // Total
            const cell4 = row.insertCell(3);
            cell4.innerHTML = `<input type="text" class="form-control total-display" readonly value="0">`;

            // Remove Btn
            const cell5 = row.insertCell(4);
            cell5.innerHTML = `<button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>`;

            // Attach events
            const productSelect = cell1.querySelector('.product-select');
            const priceInput = cell2.querySelector('.price-input');
            const qtyInput = cell3.querySelector('.qty-input');
            const removeBtn = cell5.querySelector('.remove-row');

            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                priceInput.value = price || 0;
                calculateRow(row);
            });

            priceInput.addEventListener('input', () => calculateRow(row));
            qtyInput.addEventListener('input', () => calculateRow(row));
            
            removeBtn.addEventListener('click', function() {
                if(itemsTable.rows.length > 1) {
                    row.remove();
                    calculateGrandTotal();
                }
            });
        }

        function calculateRow(row) {
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const total = price * qty;
            row.querySelector('.total-display').value = total.toLocaleString('id-ID');
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.total-display').forEach(el => {
                // remove non-numeric chars for calculation if needed, but here value is formatted. 
                // Wait, total-display is for display. We should recalc from inputs to be safe.
            });
            
            // Re-iterate rows provided they exist
            Array.from(itemsTable.rows).forEach(row => {
                 const price = parseFloat(row.querySelector('.price-input').value) || 0;
                 const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                 total += price * qty;
            });
            
            grandTotalEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }
    });
</script>
@endsection
