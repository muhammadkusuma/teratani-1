@extends('layouts.owner')

@section('content')
<div class="container-fluid py-4">
    <div class="row h-100">
        <div class="col-md-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">
                    <input type="text" id="searchProduct" class="form-control form-control-lg" placeholder="Cari Produk / Scan Barcode..." autofocus>
                </div>
                <div class="card-body overflow-auto" style="height: 70vh;">
                    <div id="loading" class="text-center d-none py-5"><div class="spinner-border text-primary"></div></div>
                    
                    <div class="row g-3" id="productList">
                        @foreach ($produk as $p)
                        <div class="col-6 col-md-3">
                            <div class="card h-100 cursor-pointer product-card" 
                                 onclick="addToCart({{ $p->id_produk }}, '{{ addslashes($p->nama_produk) }}', {{ $p->harga_jual_umum }}, {{ $p->stokToko->stok_fisik ?? 0 }})">
                                <div class="card-body text-center">
                                    <h6 class="text-truncate">{{ $p->nama_produk }}</h6>
                                    <p class="text-primary fw-bold mb-0">Rp {{ number_format($p->harga_jual_umum) }}</p>
                                    <small class="text-muted">Stok: {{ $p->stokToko->stok_fisik ?? 0 }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Keranjang</h5>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <div class="table-responsive flex-grow-1" style="height: 30vh; overflow-y: auto;">
                        <table class="table table-striped mb-0">
                            <tbody id="cartBody"></tbody>
                        </table>
                    </div>

                    <div class="p-3 bg-light border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <h4>Total</h4>
                            <h4 class="text-primary fw-bold" id="totalDisplay">Rp 0</h4>
                        </div>

                        <div class="mb-2">
                            <select id="pelanggan" class="form-select">
                                <option value="">- Pelanggan Umum -</option>
                                @foreach($pelanggan as $plg)
                                <option value="{{ $plg->id_pelanggan }}">{{ $plg->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <select id="metodeBayar" class="form-select" onchange="hitungKembalian()">
                                <option value="Tunai">Tunai (Cash)</option>
                                <option value="Transfer">Transfer / QRIS</option>
                                <option value="Hutang">Hutang (Kredit)</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="inputBayar" class="form-control text-end fw-bold" placeholder="Nominal Bayar" oninput="hitungKembalian()">
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span id="labelKembalian">Kembalian:</span>
                            <span id="textKembalian" class="fw-bold">Rp 0</span>
                        </div>

                        <button onclick="prosesBayar()" class="btn btn-success w-100 btn-lg">BAYAR SEKARANG</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let cart = [];

// === LOGIC KERANJANG ===
function addToCart(id, nama, harga, stok) {
    let item = cart.find(i => i.id === id);
    if(item) {
        if(item.qty < stok) item.qty++;
        else alert('Stok Habis!');
    } else {
        if(stok > 0) cart.push({id, nama, harga, stok, qty: 1});
        else alert('Stok Kosong!');
    }
    renderCart();
}

function renderCart() {
    let html = '';
    let total = 0;
    cart.forEach((item, idx) => {
        let sub = item.harga * item.qty;
        total += sub;
        html += `<tr>
            <td class="ps-3">${item.nama}<br><small class="text-muted">Rp ${item.harga}</small></td>
            <td width="20%"><input type="number" class="form-control form-control-sm" value="${item.qty}" onchange="updateQty(${idx}, this.value)"></td>
            <td class="text-end">Rp ${sub.toLocaleString()}</td>
            <td><button class="btn btn-sm text-danger" onclick="hapusItem(${idx})">x</button></td>
        </tr>`;
    });
    $('#cartBody').html(html);
    $('#totalDisplay').text('Rp ' + total.toLocaleString());
    hitungKembalian();
}

function updateQty(idx, val) {
    let qty = parseInt(val);
    if(qty > cart[idx].stok) {
        alert('Stok maks: ' + cart[idx].stok);
        cart[idx].qty = cart[idx].stok;
    } else if(qty < 1) {
        cart[idx].qty = 1;
    } else {
        cart[idx].qty = qty;
    }
    renderCart();
}

function hapusItem(idx) {
    cart.splice(idx, 1);
    renderCart();
}

// === LOGIC HITUNG BAYAR ===
function hitungKembalian() {
    let total = cart.reduce((a, b) => a + (b.harga * b.qty), 0);
    let bayar = parseFloat($('#inputBayar').val()) || 0;
    let metode = $('#metodeBayar').val();
    let selisih = bayar - total;

    if(metode === 'Hutang') {
        $('#labelKembalian').text('Sisa Hutang:');
        // Kalau hutang, minus berarti sisa hutang (wajar)
        $('#textKembalian').text('Rp ' + Math.abs(selisih).toLocaleString())
             .removeClass('text-success text-danger').addClass('text-warning');
    } else {
        $('#labelKembalian').text('Kembalian:');
        $('#textKembalian').text('Rp ' + selisih.toLocaleString());
        
        if(selisih < 0) $('#textKembalian').removeClass('text-success').addClass('text-danger');
        else $('#textKembalian').removeClass('text-danger').addClass('text-success');
    }
}

// === LOGIC SEARCH REALTIME ===
let timer;
$('#searchProduct').on('keyup', function() {
    clearTimeout(timer);
    let keyword = $(this).val();
    timer = setTimeout(() => {
        $('#loading').removeClass('d-none');
        $('#productList').addClass('opacity-50');
        
        $.get("{{ route('owner.kasir.search') }}", {keyword: keyword}, function(data) {
            $('#loading').addClass('d-none');
            $('#productList').removeClass('opacity-50').empty();
            
            if(data.length === 0) {
                $('#productList').html('<div class="col-12 text-center text-muted">Produk tidak ditemukan</div>');
            } else {
                data.forEach(p => {
                    let stok = p.stok_toko ? p.stok_toko.stok_fisik : 0;
                    let safeName = p.nama_produk.replace(/'/g, "\\'");
                    let card = `
                        <div class="col-6 col-md-3">
                            <div class="card h-100 cursor-pointer product-card" 
                                 onclick="addToCart(${p.id_produk}, '${safeName}', ${p.harga_jual_umum}, ${stok})">
                                <div class="card-body text-center">
                                    <h6 class="text-truncate">${p.nama_produk}</h6>
                                    <p class="text-primary fw-bold mb-0">Rp ${new Intl.NumberFormat().format(p.harga_jual_umum)}</p>
                                    <small class="text-muted">Stok: ${stok}</small>
                                </div>
                            </div>
                        </div>`;
                    $('#productList').append(card);
                });
            }
        });
    }, 300);
});

// === PROSES TRANSAKSI ===
function prosesBayar() {
    if(cart.length === 0) return alert('Keranjang kosong!');

    $.post("{{ route('owner.kasir.store') }}", {
        _token: "{{ csrf_token() }}",
        items: cart,
        bayar: $('#inputBayar').val(),
        id_pelanggan: $('#pelanggan').val(),
        metode_bayar: $('#metodeBayar').val()
    })
    .done(res => {
        alert(res.message);
        location.reload();
    })
    .fail(xhr => {
        alert('Gagal: ' + xhr.responseJSON.message);
    });
}
</script>
<style>
    .cursor-pointer { cursor: pointer; transition: 0.2s; }
    .cursor-pointer:hover { transform: scale(1.02); border-color: blue; }
</style>
@endsection