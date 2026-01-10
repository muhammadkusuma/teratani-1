@extends('layouts.owner')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchProduct" class="form-control border-start-0"
                                placeholder="Cari nama, SKU, atau scan barcode..." autofocus>
                        </div>
                    </div>
                    <div class="card-body overflow-auto" style="height: 75vh;">

                        <div class="row g-3" id="productList">
                            @foreach ($produk as $p)
                                <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                                    <div class="card h-100 cursor-pointer product-card hover-shadow"
                                        onclick="addToCart(
                                    {{ $p->id_produk }}, 
                                    '{{ addslashes($p->nama_produk) }}', 
                                    {{ $p->harga_jual_umum ?? 0 }}, 
                                    {{ $p->stokToko->stok_fisik ?? 0 }}
                                 )">
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                            style="height: 100px;">
                                            <i class="fas fa-box-open fa-2x text-secondary"></i>
                                        </div>
                                        <div class="card-body text-center p-2">
                                            <h6 class="card-title font-weight-bold mb-1 text-truncate"
                                                title="{{ $p->nama_produk }}">{{ $p->nama_produk }}</h6>
                                            <p class="text-primary fw-bold mb-0">Rp
                                                {{ number_format($p->harga_jual_umum ?? 0, 0, ',', '.') }}</p>
                                            <small class="text-muted" style="font-size: 0.8rem;">
                                                Stok: {{ $p->stokToko->stok_fisik ?? 0 }}
                                                {{ $p->satuanKecil->nama_satuan ?? 'Pcs' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="loadingSpinner" class="text-center py-5 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Keranjang</h5>
                    </div>
                    <div class="card-body p-0 d-flex flex-column">
                        <div class="table-responsive flex-grow-1" style="height: 40vh; overflow-y: auto;">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead class="bg-light sticky-top">
                                    <tr>
                                        <th class="ps-3">Item</th>
                                        <th width="20%">Qty</th>
                                        <th class="text-end pe-3">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody"></tbody>
                            </table>
                        </div>
                        <div class="p-3 bg-light border-top">
                            <div class="d-flex justify-content-between mb-2 fs-5">
                                <span>Total</span>
                                <span id="totalBruto" class="fw-bold text-primary">Rp 0</span>
                            </div>
                            <div class="mb-2">
                                <select id="pelangganSelect" class="form-select">
                                    <option value="">- Pilih Pelanggan (Opsional) -</option>
                                    @foreach ($pelanggan as $plg)
                                        <option value="{{ $plg->id_pelanggan }}">{{ $plg->nama_pelanggan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Rp</span>
                                <input type="number" id="inputBayar" class="form-control form-control-lg"
                                    placeholder="Nominal Bayar">
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Kembalian:</span>
                                <span id="textKembalian" class="fw-bold">Rp 0</span>
                            </div>
                            <button onclick="processTransaction()" class="btn btn-success w-100 btn-lg fw-bold">
                                <i class="fas fa-save me-2"></i>PROSES BAYAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // --- LOGIC KERANJANG BELANJA (CART) ---
        let cart = [];

        function addToCart(id, name, price, stock) {
            let existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                if (existingItem.qty < stock) {
                    existingItem.qty++;
                } else {
                    alert('Stok tidak mencukupi!');
                    return;
                }
            } else {
                if (stock > 0) {
                    cart.push({
                        id: id,
                        name: name,
                        price: price,
                        qty: 1,
                        stock: stock
                    });
                } else {
                    alert('Stok Habis!');
                    return;
                }
            }
            renderCart();
        }

        function renderCart() {
            let tbody = $('#cartTableBody');
            tbody.empty();
            let total = 0;

            cart.forEach((item, index) => {
                let subtotal = item.price * item.qty;
                total += subtotal;

                tbody.append(`
                <tr>
                    <td class="ps-3"><div class="fw-bold text-truncate" style="max-width: 120px;">${item.name}</div></td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-center qty-input" 
                            data-index="${index}" value="${item.qty}" min="1" max="${item.stock}">
                    </td>
                    <td class="text-end pe-3">${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                    <td><button class="btn btn-link text-danger btn-sm p-0" onclick="removeItem(${index})"><i class="fas fa-trash"></i></button></td>
                </tr>
            `);
            });

            $('#totalBruto').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
            calculateChange(total);
        }

        $(document).on('change', '.qty-input', function() {
            let index = $(this).data('index');
            let newQty = parseInt($(this).val());
            let item = cart[index];
            if (newQty > item.stock) {
                alert('Maksimal stok: ' + item.stock);
                $(this).val(item.stock);
                item.qty = item.stock;
            } else if (newQty < 1 || isNaN(newQty)) {
                item.qty = 1;
            } else {
                item.qty = newQty;
            }
            renderCart();
        });

        function removeItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        $('#inputBayar').on('input', function() {
            let total = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
            calculateChange(total);
        });

        function calculateChange(total) {
            let bayar = parseFloat($('#inputBayar').val()) || 0;
            let kembalian = bayar - total;
            let textElem = $('#textKembalian');
            textElem.text('Rp ' + new Intl.NumberFormat('id-ID').format(kembalian));

            if (kembalian < 0) textElem.addClass('text-danger').removeClass('text-success');
            else textElem.addClass('text-success').removeClass('text-danger');
        }

        function processTransaction() {
            if (cart.length === 0) return alert('Keranjang kosong!');
            let bayar = parseFloat($('#inputBayar').val()) || 0;
            let total = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);

            if (bayar < total) return alert('Uang pembayaran kurang!');

            $.ajax({
                url: "{{ route('owner.kasir.store') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    items: cart,
                    bayar: bayar,
                    id_pelanggan: $('#pelangganSelect').val(),
                    metode_bayar: 'Tunai'
                },
                success: function(response) {
                    alert('Transaksi Berhasil!');
                    cart = [];
                    renderCart();
                    $('#inputBayar').val('');
                    // location.reload(); // Opsional: reload untuk refresh stok
                    // Karena kita pakai AJAX search, lebih baik reset pencarian atau biarkan saja
                },
                error: function(xhr) {
                    alert('Gagal: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan'));
                }
            });
        }

        // --- LOGIC PENCARIAN AJAX (REALTIME) ---
        let debounceTimer;

        $('#searchProduct').on('keyup', function() {
            clearTimeout(debounceTimer);
            let keyword = $(this).val();

            // Delay 300ms agar tidak spam request setiap ketikan huruf
            debounceTimer = setTimeout(function() {
                fetchProducts(keyword);
            }, 300);
        });

        function fetchProducts(keyword) {
            // Tampilkan loading, sembunyikan list
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: "{{ route('owner.kasir.search') }}",
                type: "GET",
                data: {
                    keyword: keyword
                },
                success: function(data) {
                    $('#loadingSpinner').addClass('d-none');
                    $('#productList').empty();

                    if (data.length > 0) {
                        $.each(data, function(key, item) {
                            // Formatting Data
                            let harga = new Intl.NumberFormat('id-ID').format(item.harga_jual_umum);
                            // Akses stok_toko dengan aman (bisa null jika data kotor)
                            let stok = (item.stok_toko) ? item.stok_toko.stok_fisik : 0;
                            let satuan = (item.satuan_kecil) ? item.satuan_kecil.nama_satuan : 'Pcs';
                            // Escape nama produk untuk keamanan string JS
                            let safeName = item.nama_produk.replace(/'/g, "\\'");

                            // Template HTML Card Produk
                            let cardHtml = `
                        <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                            <div class="card h-100 cursor-pointer product-card hover-shadow" 
                                 onclick="addToCart(${item.id_produk}, '${safeName}', ${item.harga_jual_umum}, ${stok})">
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 100px;">
                                    <i class="fas fa-box-open fa-2x text-secondary"></i>
                                </div>
                                <div class="card-body text-center p-2">
                                    <h6 class="card-title font-weight-bold mb-1 text-truncate" title="${item.nama_produk}">${item.nama_produk}</h6>
                                    <p class="text-primary fw-bold mb-0">Rp ${harga}</p>
                                    <small class="text-muted" style="font-size: 0.8rem;">
                                        Stok: ${stok} ${satuan}
                                    </small>
                                </div>
                            </div>
                        </div>
                        `;
                            $('#productList').append(cardHtml);
                        });
                    } else {
                        $('#productList').html(`
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Produk "${keyword}" tidak ditemukan.</p>
                        </div>
                    `);
                    }
                },
                error: function() {
                    $('#loadingSpinner').addClass('d-none');
                    alert('Gagal mengambil data produk.');
                }
            });
        }
    </script>

    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .hover-shadow:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            transform: translateY(-2px);
            transition: all 0.2s;
        }
    </style>
@endsection
