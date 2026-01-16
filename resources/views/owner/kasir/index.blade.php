@extends('layouts.owner')

@section('title', 'Kasir')

@section('content')
    
    <div class="w-full flex flex-col font-sans text-[11px] bg-[#c0c0c0]"
        style="font-family: 'MS Sans Serif', Arial, sans-serif; height: calc(100vh - 85px); overflow: hidden;">

        
        <div class="shrink-0 p-2 border-b border-gray-500 flex justify-between items-end bg-[#c0c0c0]">
            <div>
                <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Point of Sale (POS)</h1>
                <div class="text-gray-600 mt-1">
                    Kasir: <span
                        class="font-bold text-black bg-white px-1 border border-gray-400">{{ Auth::user()->username }}</span>
                </div>
                <a href="{{ route('owner.kasir.riwayat') }}"
                    class="mb-0.5 px-3 py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black text-xs font-bold hover:bg-blue-100 flex items-center gap-1 text-blue-900 no-underline">
                    <i class="fas fa-history"></i> RIWAYAT / CETAK ULANG
                </a>
            </div>
            <div
                class="text-right font-mono font-bold text-xl bg-black text-green-500 px-3 py-1 border-2 border-gray-500 border-b-white border-r-white">
                <span id="headerTotal">Rp 0</span>
            </div>
        </div>

        
        <div class="flex-1 flex gap-2 p-2 min-h-0 overflow-hidden">

            
            <div class="flex-1 flex flex-col h-full min-h-0">

                
                <div class="shrink-0 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black p-2 mb-2 shadow-sm">
                    <div class="flex gap-2 items-center">
                        <span class="font-bold whitespace-nowrap">CARI [F2]:</span>
                        <div class="relative flex-1">
                            <input type="text" id="searchProduct"
                                class="w-full px-2 py-1 border-2 border-gray-400 border-l-black border-t-black focus:outline-none focus:bg-yellow-50 uppercase font-bold"
                                placeholder="KETIK NAMA / SCAN BARCODE..." autofocus>
                            <div id="loading" class="absolute right-2 top-1 d-none text-red-600 font-bold text-[10px]">
                                LOADING...
                            </div>
                        </div>
                    </div>
                </div>

                
                <div
                    class="flex-1 bg-white border-2 border-gray-400 border-l-black border-t-black overflow-y-auto p-2 custom-scrollbar">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2" id="productList">
                        @foreach ($produk as $p)
                            @php
                                $stok = $p->stokToko->stok_fisik ?? 0;
                                $harga = $p->harga_jual_umum ?? 0;
                            @endphp
                            <div class="cursor-pointer group product-item h-full"
                                onclick="addToCart({{ $p->id_produk }}, '{{ addslashes($p->nama_produk) }}', {{ $harga }}, {{ $stok }})">
                                <div
                                    class="bg-[#d4d0c8] border-2 border-white border-r-black border-b-black p-1 hover:bg-blue-100 active:border-t-black active:border-l-black h-full flex flex-col justify-between min-h-[80px]">
                                    <div class="text-center leading-tight">
                                        <div
                                            class="font-bold text-[10px] text-blue-900 mb-1 line-clamp-2 h-8 overflow-hidden">
                                            {{ $p->nama_produk }}
                                        </div>
                                        <div
                                            class="bg-black text-yellow-300 font-mono text-[11px] px-1 mb-1 border border-gray-500">
                                            Rp {{ number_format($harga, 0, ',', '.') }}
                                        </div>
                                        <div
                                            class="text-[9px] {{ $stok > 0 ? 'text-black' : 'text-red-600 font-bold bg-yellow-200' }}">
                                            Stok: {{ $stok }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            
            <div
                class="w-[380px] flex flex-col h-full bg-[#d4d0c8] border-2 border-white border-r-black border-b-black shadow-lg p-1 shrink-0 min-h-0">

                
                <div
                    class="shrink-0 bg-blue-800 text-white px-2 py-1 font-bold text-center mb-1 text-[11px] bg-gradient-to-r from-blue-800 to-blue-600">
                    KERANJANG BELANJA
                </div>

                
                <div
                    class="flex-1 bg-white border-2 border-gray-400 border-l-black border-t-black overflow-y-auto mb-1 relative custom-scrollbar">
                    <table class="w-full border-collapse">
                        <thead class="sticky top-0 bg-gray-200 text-black text-[10px] border-b border-black z-10">
                            <tr>
                                <th class="px-1 text-left w-5">#</th>
                                <th class="px-1 text-left">ITEM</th>
                                <th class="px-1 text-center w-8">QTY</th>
                                <th class="px-1 text-right w-16">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody" class="text-[11px] font-mono">
                            
                        </tbody>
                    </table>
                </div>

                
                <div class="shrink-0 border-t border-white pt-1 space-y-1">

                    
                    <div
                        class="bg-black text-green-500 p-2 font-mono text-right border-2 border-gray-500 border-b-white border-r-white mb-2">
                        <div class="text-[9px] text-green-700">TOTAL TAGIHAN</div>
                        <div class="text-3xl font-bold leading-none" id="displayTotal">0</div>
                    </div>

                    
                    <div class="grid grid-cols-3 gap-1 items-center mb-1">
                        <label class="font-bold text-right col-span-1">Harga:</label>
                        <select id="kategoriHarga"
                            class="col-span-2 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black bg-white focus:outline-none font-bold text-blue-900">
                            <option value="umum">UMUM (RETAIL)</option>
                            <option value="grosir">GROSIR</option>
                            <option value="r1">HARGA R1</option>
                            <option value="r2">HARGA R2</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-1 items-center mb-1">
                        <label class="font-bold text-right col-span-1">Pelanggan:</label>
                        <select id="pelanggan"
                            class="col-span-2 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black bg-white focus:outline-none">
                            <option value="" data-kategori="umum">- UMUM -</option>
                            @foreach ($pelanggan as $plg)
                                <option value="{{ $plg->id_pelanggan }}" data-kategori="{{ $plg->kategori_harga }}">{{ $plg->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-1 items-center mb-1">
                        <label class="font-bold text-right col-span-1">Metode:</label>
                        <select id="metodeBayar" onchange="hitungKembalian()"
                            class="col-span-2 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black bg-white focus:outline-none">
                            <option value="Tunai">TUNAI (CASH)</option>
                            <option value="Transfer">TRANSFER / QRIS</option>
                            <option value="Hutang">HUTANG (TEMPO)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-1 items-center mb-1">
                        <label class="font-bold text-right col-span-1">BAYAR [F4]:</label>
                        <input type="number" id="inputBayar" oninput="hitungKembalian()"
                            class="col-span-2 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black font-bold text-right focus:bg-yellow-50 focus:outline-none text-blue-900"
                            placeholder="0">
                    </div>

                    <div class="grid grid-cols-3 gap-1 items-center mb-2">
                        <label class="font-bold text-right col-span-1" id="labelKembalian">KEMBALI:</label>
                        <div id="textKembalian"
                            class="col-span-2 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black font-bold text-right bg-gray-200 text-black">
                            Rp 0
                        </div>
                    </div>

                    <button onclick="prosesBayar()"
                        class="w-full py-3 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black active:bg-gray-400 font-bold text-lg flex items-center justify-center gap-2 hover:bg-green-100 text-green-900 transition-colors shadow-sm cursor-pointer">
                        <i class="fas fa-print"></i> PROSES BAYAR [F10]
                    </button>

                </div>
            </div>

        </div>
    </div>

    
    <div id="modalCetak" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-2xl border-2 border-gray-800 w-96 text-center">
            <div class="mb-4 text-green-600">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-2">Transaksi Berhasil!</h2>
            <p class="text-gray-600 mb-6">Pilih jenis struk yang ingin dicetak:</p>

            <div class="flex flex-col gap-3">
                
                <button onclick="printStruk()"
                    class="w-full py-2 px-4 bg-gray-200 border-2 border-gray-400 hover:bg-gray-300 font-bold text-gray-800 flex items-center justify-center gap-2">
                    <i class="fas fa-receipt"></i> CETAK STRUK KECIL (THERMAL)
                </button>

                
                <button onclick="printFaktur()"
                    class="w-full py-2 px-4 bg-blue-600 border-2 border-blue-800 hover:bg-blue-700 text-white font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-file-invoice"></i> CETAK FAKTUR (A4)
                </button>

                
                <button onclick="tutupModal()" class="mt-2 text-sm text-red-600 underline hover:text-red-800">
                    Tutup / Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let cart = [];
        let productsData = {}; // Map to store full product info: id -> product object
        let currentCategory = 'umum'; // Default category

        // Initialize products from server-side loop
        @foreach($produk as $p)
            productsData[{{ $p->id_produk }}] = @json($p);
        @endforeach

        // --- KEYBOARD SHORTCUTS ---
        $(document).keydown(function(e) {
            if (e.key === "F2") {
                e.preventDefault();
                $('#searchProduct').focus();
            }
            if (e.key === "F4") {
                e.preventDefault();
                $('#inputBayar').focus();
            }
            if (e.key === "F10") {
                e.preventDefault();
                prosesBayar();
            }
        });

        // --- CUSTOMER & PRICE CATEGORY HANDLER ---
        $('#pelanggan').on('change', function() {
            let selectedOption = $(this).find(':selected');
            let cat = selectedOption.data('kategori') || 'umum';
            
            // Auto-select the price category based on customer
            // Must trigger 'change' for Select2 to update UI and for the listener below to fire
            $('#kategoriHarga').val(cat).trigger('change'); 
        });

        $('#kategoriHarga').on('change', function() {
            let cat = $(this).val();
            updatePrices(cat);
        });

        function updatePrices(category) {
            currentCategory = category;
            console.log("Price category set to:", currentCategory);
            
            // Update prices in cart
            cart.forEach(item => {
                let product = productsData[item.id];
                if(product) {
                   item.harga = getPriceForCategory(product, currentCategory);
                }
            });
            renderCart();
        }

        function getPriceForCategory(product, category) {
            let price = parseFloat(product.harga_jual_umum);
            if (category === 'grosir' && product.harga_jual_grosir) price = parseFloat(product.harga_jual_grosir);
            if (category === 'r1' && product.harga_r1) price = parseFloat(product.harga_r1);
            if (category === 'r2' && product.harga_r2) price = parseFloat(product.harga_r2);
            return price;
        }

        // --- LOGIC KERANJANG ---
        function addToCart(id, nama, unused_price, stok) {
            // We ignore the passed price and use the one from productsData based on category
            let product = productsData[id];
            if (!product) {
                // Should not happen if data is synced, but fallback safely
                console.error("Product data not found for ID", id);
                return;
            }

            let price = getPriceForCategory(product, currentCategory);
            
            let item = cart.find(i => i.id === id);
            if (item) {
                if (item.qty < stok) {
                    item.qty++;
                    item.harga = price; // Update price just in case category changed
                } else {
                    alert('STOK TIDAK MENCUKUPI! Sisa: ' + stok);
                    return;
                }
            } else {
                if (stok > 0) {
                    cart.push({
                        id,
                        nama,
                        harga: price,
                        stok,
                        qty: 1
                    });
                } else {
                    alert('STOK HABIS!');
                    return;
                }
            }
            renderCart();
        }

        function renderCart() {
            let html = '';
            let total = 0;

            if (cart.length === 0) {
                html = '<tr><td colspan="4" class="text-center py-4 text-gray-400 italic">-- KERANJANG KOSONG --</td></tr>';
            } else {
                cart.forEach((item, idx) => {
                    let sub = item.harga * item.qty;
                    total += sub;
                    html += `
                    <tr class="border-b border-gray-300 hover:bg-blue-50 group">
                        <td class="px-1 py-1 text-center"><button class="text-red-600 font-bold hover:bg-red-200 px-1" onclick="hapusItem(${idx})">x</button></td>
                        
                        <td class="px-1 py-1 truncate max-w-[120px]" title="${item.nama}">
                            ${item.nama}
                            <div class="text-[9px] text-gray-500">@ ${new Intl.NumberFormat('id-ID').format(item.harga)}</div>
                        </td>
                        
                        <td class="px-1 py-1 text-center">
                            <input type="number" class="w-8 text-center border border-gray-400 text-[10px] focus:bg-yellow-100 p-0" 
                                value="${item.qty}" onchange="updateQty(${idx}, this.value)">
                        </td>
                        <td class="px-1 py-1 text-right font-mono">${new Intl.NumberFormat('id-ID').format(sub)}</td>
                    </tr>`;
                });
            }

            $('#cartTableBody').html(html);
            let formattedTotal = new Intl.NumberFormat('id-ID').format(total);
            $('#displayTotal').text(formattedTotal);
            $('#headerTotal').text('Rp ' + formattedTotal);
            hitungKembalian(); // Recalculate change as total might have changed
        }

        function updateQty(idx, val) {
            let qty = parseInt(val);
            if (qty > cart[idx].stok) {
                alert('Melebihi stok tersedia: ' + cart[idx].stok);
                cart[idx].qty = cart[idx].stok;
            } else if (qty < 1 || isNaN(qty)) {
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

        function hitungKembalian() {
            let total = cart.reduce((a, b) => a + (b.harga * b.qty), 0);
            let bayar = parseFloat($('#inputBayar').val()) || 0;
            let metode = $('#metodeBayar').val();
            let selisih = bayar - total;

            let textEl = $('#textKembalian');
            let labelEl = $('#labelKembalian');

            if (metode === 'Hutang') {
                labelEl.text('SISA HUTANG:');
                textEl.text('Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(selisih)))
                    .removeClass('bg-gray-200 bg-red-100').addClass('bg-yellow-100 text-orange-800');
            } else {
                labelEl.text('KEMBALI:');
                textEl.text('Rp ' + new Intl.NumberFormat('id-ID').format(selisih));

                if (selisih < 0) {
                    textEl.removeClass('bg-gray-200 text-black').addClass('bg-red-100 text-red-600');
                } else {
                    textEl.removeClass('bg-red-100 text-red-600').addClass('bg-gray-200 text-black');
                }
            }
        }

        let timer;
        $('#searchProduct').on('keyup', function() {
            clearTimeout(timer);
            let keyword = $(this).val();

            timer = setTimeout(() => {
                $('#loading').removeClass('d-none');
                $('#productList').addClass('opacity-50');

                $.get("{{ route('owner.kasir.search') }}", {
                    keyword: keyword
                }, function(data) {
                    $('#loading').addClass('d-none');
                    $('#productList').removeClass('opacity-50').empty();

                    if (data.length === 0) {
                        $('#productList').html(
                            '<div class="col-span-full text-center text-gray-500 italic py-4">-- PRODUK TIDAK DITEMUKAN --</div>'
                        );
                    } else {
                        data.forEach(p => {
                            // Update our global data map with new results
                            productsData[p.id_produk] = p;

                            let stok = p.stok_toko ? p.stok_toko.stok_fisik : 0;
                            let safeName = p.nama_produk.replace(/'/g, "\\'");
                            let hargaFmt = new Intl.NumberFormat('id-ID').format(p.harga_jual_umum);
                            let stokClass = stok > 0 ? 'text-black' :
                                'text-red-600 font-bold bg-yellow-200';

                            let card = `
                                <div class="cursor-pointer group product-item h-full"
                                     onclick="addToCart(${p.id_produk}, '${safeName}', ${p.harga_jual_umum}, ${stok})">
                                    <div class="bg-[#d4d0c8] border-2 border-white border-r-black border-b-black p-1 hover:bg-blue-100 active:border-t-black active:border-l-black h-full flex flex-col justify-between min-h-[80px]">
                                        <div class="text-center leading-tight">
                                            <div class="font-bold text-[10px] text-blue-900 mb-1 line-clamp-2 h-8 overflow-hidden">${p.nama_produk}</div>
                                            <div class="bg-black text-yellow-300 font-mono text-[11px] px-1 mb-1 border border-gray-500">
                                                Rp ${hargaFmt}
                                            </div>
                                            <div class="text-[9px] ${stokClass}">
                                                Stok: ${stok}
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            $('#productList').append(card);
                        });
                    }
                });
            }, 300);
        });

        let lastTransactionId = null; 

        function prosesBayar() {
            if (cart.length === 0) return alert('KERANJANG KOSONG!');

            let total = cart.reduce((a, b) => a + (b.harga * b.qty), 0);
            let bayar = parseFloat($('#inputBayar').val()) || 0;
            let metode = $('#metodeBayar').val();

            if (metode !== 'Hutang' && bayar < total) {
                alert('PEMBAYARAN KURANG!');
                $('#inputBayar').focus();
                return;
            }

            let btn = $('button[onclick="prosesBayar()"]');
            let oriText = btn.html();
            btn.prop('disabled', true).text('MEMPROSES...');

            $.post("{{ route('owner.kasir.store') }}", {
                    _token: "{{ csrf_token() }}",
                    // We must filter the cart to only send what the backend expects, but the backend likely only needs ID and QTY.
                    // If backend recalculates price, we assume it trusts the client or (better) we should update the backend to respect the applied price or recalculate based on customer category.
                    // For now, let's look at the controller. It fetches the product again.
                    // WAIT: The controller currently uses `harga_jual_umum`. We need to fix the controller too or pass the resolved price?
                    // Best practice: Pass the customer ID to backend, backend looks up category, backend determines price. JS is just for display.
                    // But the controller 'store' method doesn't seem to account for customer category price calculation yet.
                    // See KasirController line 128: $harga = $produk->harga_jual_umum;
                    // I MUST UPDATE THE CONTROLLER STORE METHOD AS WELL.
                    items: cart.map(i => ({ id: i.id, qty: i.qty })), 
                    bayar: bayar,
                    id_pelanggan: $('#pelanggan').val(),
                    metode_bayar: metode
                })
                .done(res => {
                    btn.prop('disabled', false).html(oriText);
                    lastTransactionId = res.id_penjualan;
                    $('#modalCetak').removeClass('hidden').addClass('flex');
                    cart = [];
                    renderCart();
                    $('#inputBayar').val('');
                    $('#pelanggan').val('');
                    currentCategory = 'umum'; // Reset category
                })
                .fail(xhr => {
                    btn.prop('disabled', false).html(oriText);
                    alert('GAGAL: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.'));
                });
        }

        function printStruk() {
            if (!lastTransactionId) return;
            let url = "{{ url('owner/kasir/cetak') }}/" + lastTransactionId;
            window.open(url, 'Struk', 'width=400,height=600');
        }

        function printFaktur() {
            if (!lastTransactionId) return;
            let url = "{{ url('owner/kasir/cetak-faktur') }}/" + lastTransactionId;
            window.open(url, 'Faktur', 'width=800,height=600');
        }

        function tutupModal() {
            $('#modalCetak').addClass('hidden').removeClass('flex');
            location.reload(); 
        }
    </script>

    
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #e0e0e0;
            border-left: 1px solid #808080;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c0c0c0;
            border: 2px solid #fff;
            border-right: 2px solid #404040;
            border-bottom: 2px solid #404040;
        }
    </style>
@endsection
