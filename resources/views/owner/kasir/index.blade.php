@extends('layouts.owner')

@section('content')
    <div style="font-family: 'MS Sans Serif', Arial, sans-serif; font-size: 11px; background-color: #c0c0c0; min-height: 100vh; padding-bottom: 50px;">
        
        <div class="max-w-[1200px] mx-auto p-2">

            {{-- HEADER KASIR --}}
            <div class="mb-2 border-b border-gray-500 pb-1 flex justify-between items-end">
                <div>
                    <h1 class="font-bold text-lg text-blue-900 uppercase leading-none">Point of Sale (POS)</h1>
                    <div class="text-gray-600 mt-1">
                        Kasir: <span class="font-bold text-black bg-white px-1 border border-gray-400">{{ Auth::user()->name }}</span> | 
                        Toko: <span class="font-bold text-black bg-white px-1 border border-gray-400">{{ session('toko_active_nama') }}</span>
                    </div>
                </div>
                <div class="text-right font-mono font-bold text-xl bg-black text-green-500 px-3 py-1 border-2 border-gray-500 border-b-white border-r-white">
                    <span id="headerTotal">Rp 0</span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-2 h-[80vh]">
                
                {{-- KOLOM KIRI: DAFTAR PRODUK --}}
                <div class="flex-1 flex flex-col h-full">
                    
                    {{-- Search Bar --}}
                    <div class="bg-[#d4d0c8] border-2 border-white border-r-black border-b-black p-2 mb-2 shadow-sm">
                        <div class="flex gap-2">
                            <span class="font-bold pt-1">CARI BARANG [F2]:</span>
                            <input type="text" id="searchProduct" 
                                class="flex-1 px-2 py-1 border-2 border-gray-400 border-l-black border-t-black focus:outline-none focus:bg-yellow-50 uppercase font-bold"
                                placeholder="KETIK NAMA / SCAN BARCODE..." autofocus>
                        </div>
                    </div>

                    {{-- Product Grid Container --}}
                    <div class="flex-1 bg-white border-2 border-gray-400 border-l-black border-t-black overflow-y-auto p-2" id="productContainer">
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2" id="productList">
                            @foreach ($produk as $p)
                                @php
                                    $stok = $p->stokTokos->first()->stok_fisik ?? 0;
                                    $harga = $p->harga_jual_umum ?? 0;
                                @endphp
                                <div class="product-item cursor-pointer group" 
                                     data-name="{{ strtolower($p->nama_produk) }} {{ $p->barcode ?? '' }} {{ $p->sku ?? '' }}"
                                     onclick="addToCart({{ $p->id_produk }}, '{{ addslashes($p->nama_produk) }}', {{ $harga }}, {{ $stok }})">
                                    
                                    <div class="bg-[#d4d0c8] border-2 border-white border-r-black border-b-black p-1 hover:bg-blue-100 active:border-t-black active:border-l-black active:border-b-white active:border-r-white h-full flex flex-col justify-between">
                                        {{-- Image Placeholder --}}
                                        <div class="bg-white border border-gray-400 h-20 flex items-center justify-center mb-1 overflow-hidden">
                                            @if($p->gambar_produk)
                                                <img src="{{ asset('storage/'.$p->gambar_produk) }}" class="h-full object-cover">
                                            @else
                                                <span class="text-[9px] text-gray-400">NO IMG</span>
                                            @endif
                                        </div>
                                        
                                        <div class="text-center leading-tight">
                                            <div class="font-bold text-[10px] text-blue-900 mb-1 line-clamp-2 h-6 overflow-hidden">{{ $p->nama_produk }}</div>
                                            <div class="bg-black text-yellow-300 font-mono text-[10px] px-1 mb-1">
                                                Rp {{ number_format($harga, 0, ',', '.') }}
                                            </div>
                                            <div class="text-[9px] {{ $stok > 0 ? 'text-black' : 'text-red-600 font-bold bg-yellow-200' }}">
                                                Stok: {{ $stok }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: KERANJANG & PEMBAYARAN --}}
                <div class="w-full md:w-[350px] flex flex-col h-full bg-[#d4d0c8] border-2 border-white border-r-black border-b-black shadow-lg p-1">
                    
                    {{-- Title Bar --}}
                    <div class="bg-blue-800 text-white px-2 py-1 font-bold text-center mb-1 text-[11px] bg-gradient-to-r from-blue-800 to-blue-600">
                        TRANSAKSI PENJUALAN
                    </div>

                    {{-- Tabel Keranjang --}}
                    <div class="flex-1 bg-white border-2 border-gray-400 border-l-black border-t-black overflow-y-auto mb-1 relative">
                        <table class="w-full border-collapse">
                            <thead class="sticky top-0 bg-gray-200 text-black text-[10px] border-b border-black">
                                <tr>
                                    <th class="px-1 text-left w-5">#</th>
                                    <th class="px-1 text-left">ITEM</th>
                                    <th class="px-1 text-center w-10">QTY</th>
                                    <th class="px-1 text-right w-20">TOTAL</th>
                                    <th class="px-1 w-5"></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody" class="text-[11px] font-mono">
                                {{-- Item Row akan dirender JS di sini --}}
                            </tbody>
                        </table>
                        
                        {{-- Empty State --}}
                        <div id="emptyCartMsg" class="absolute inset-0 flex items-center justify-center text-gray-400 italic pointer-events-none">
                            -- KERANJANG KOSONG --
                        </div>
                    </div>

                    {{-- Panel Kalkulasi --}}
                    <div class="border-t border-white pt-1 space-y-1">
                        
                        {{-- Subtotal Box --}}
                        <div class="bg-black text-green-500 p-2 font-mono text-right border-2 border-gray-500 border-b-white border-r-white mb-2">
                            <div class="text-[10px] text-green-700">TOTAL BAYAR</div>
                            <div class="text-2xl font-bold leading-none" id="displayTotal">0</div>
                        </div>

                        {{-- Form Input --}}
                        <div class="grid grid-cols-3 gap-1 items-center mb-1">
                            <label class="font-bold text-right col-span-1">Pelanggan:</label>
                            <select id="pelangganSelect" class="col-span-2 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black">
                                <option value="">- UMUM -</option>
                                @foreach ($pelanggan as $plg)
                                    <option value="{{ $plg->id_pelanggan }}">{{ $plg->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-3 gap-1 items-center mb-1">
                            <label class="font-bold text-right col-span-1">TUNAI [F4]:</label>
                            <input type="number" id="inputBayar" 
                                class="col-span-2 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black font-bold text-right focus:bg-yellow-50 focus:outline-none" 
                                placeholder="0">
                        </div>

                        <div class="grid grid-cols-3 gap-1 items-center mb-2">
                            <label class="font-bold text-right col-span-1">KEMBALI:</label>
                            <input type="text" id="textKembalian" readonly 
                                class="col-span-2 px-1 py-0.5 border-2 border-gray-400 border-l-black border-t-black font-bold text-right bg-gray-200 text-blue-800" 
                                value="0">
                        </div>

                        {{-- Tombol Aksi Besar --}}
                        <button onclick="processTransaction()" 
                            class="w-full py-3 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black active:bg-gray-400 font-bold text-lg flex items-center justify-center gap-2 hover:bg-green-100 text-green-900 transition-colors shadow-sm">
                            <i class="fas fa-print"></i> BAYAR & CETAK [F10]
                        </button>

                         <button onclick="clearCart()" 
                            class="w-full py-1 bg-[#d4d0c8] border-2 border-white border-r-black border-b-black active:border-t-black active:border-l-black active:bg-gray-400 font-bold text-xs text-red-700">
                            BATALKAN TRANSAKSI [ESC]
                        </button>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JQUERY & LOGIC --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let cart = [];

        // Shortcut Keys
        $(document).keydown(function(e) {
            if (e.key === "F2") { e.preventDefault(); $('#searchProduct').focus(); }
            if (e.key === "F4") { e.preventDefault(); $('#inputBayar').focus(); }
            if (e.key === "F10") { e.preventDefault(); processTransaction(); }
            if (e.key === "Escape") { e.preventDefault(); if(confirm('Batalkan transaksi?')) clearCart(); }
        });

        // Search Logic
        $('#searchProduct').on('keyup', function() {
            let val = $(this).val().toLowerCase();
            $('.product-item').filter(function() {
                $(this).toggle($(this).data('name').indexOf(val) > -1)
            });
        });

        function addToCart(id, name, price, stock) {
            if (stock <= 0) {
                alert('STOK HABIS! Tidak dapat menambahkan barang.');
                return;
            }

            let existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                if (existingItem.qty < stock) {
                    existingItem.qty++;
                } else {
                    alert('Stok tidak mencukupi! Sisa: ' + stock);
                }
            } else {
                cart.push({ id: id, name: name, price: price, qty: 1, stock: stock });
            }
            renderCart();
            
            // Play Beep Sound (Optional Retro Feel)
            // let audio = new Audio('beep.mp3'); audio.play(); 
        }

        function renderCart() {
            let tbody = $('#cartTableBody');
            tbody.empty();
            let total = 0;

            if (cart.length > 0) $('#emptyCartMsg').hide();
            else $('#emptyCartMsg').show();

            cart.forEach((item, index) => {
                let subtotal = item.price * item.qty;
                total += subtotal;

                tbody.append(`
                    <tr class="border-b border-gray-300 hover:bg-blue-50 group">
                        <td class="px-1 text-center"><button onclick="removeItem(${index})" class="text-red-600 font-bold hover:bg-red-200 px-1">x</button></td>
                        <td class="px-1 truncate max-w-[120px]" title="${item.name}">${item.name}</td>
                        <td class="px-1 text-center">
                            <input type="number" class="w-8 text-center border border-gray-400 text-[10px] qty-input focus:bg-yellow-100" 
                                data-index="${index}" value="${item.qty}" min="1">
                        </td>
                        <td class="px-1 text-right">${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                        <td></td>
                    </tr>
                `);
            });

            // Update Total Displays
            let formattedTotal = new Intl.NumberFormat('id-ID').format(total);
            $('#displayTotal').text(formattedTotal);
            $('#headerTotal').text('Rp ' + formattedTotal);
            
            calculateChange(total);
        }

        // Qty Change Event
        $(document).on('change', '.qty-input', function() {
            let index = $(this).data('index');
            let newQty = parseInt($(this).val());
            let item = cart[index];

            if (newQty > item.stock) {
                alert('Melebihi stok! Maksimal: ' + item.stock);
                $(this).val(item.stock);
                item.qty = item.stock;
            } else if (newQty < 1 || isNaN(newQty)) {
                item.qty = 1;
                $(this).val(1);
            } else {
                item.qty = newQty;
            }
            renderCart();
        });

        function removeItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function clearCart() {
            cart = [];
            $('#inputBayar').val('');
            renderCart();
        }

        // Hitung Kembalian
        $('#inputBayar').on('input', function() {
            let total = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
            calculateChange(total);
        });

        function calculateChange(total) {
            let bayar = parseFloat($('#inputBayar').val()) || 0;
            let kembalian = bayar - total;
            
            let textKembalian = $('#textKembalian');
            textKembalian.val(new Intl.NumberFormat('id-ID').format(kembalian));

            if (kembalian < 0) {
                textKembalian.addClass('text-red-600 bg-red-100').removeClass('text-blue-800 bg-gray-200');
            } else {
                textKembalian.removeClass('text-red-600 bg-red-100').addClass('text-blue-800 bg-gray-200');
            }
        }

        function processTransaction() {
            if (cart.length === 0) return alert('KERANJANG KOSONG!');

            let bayar = parseFloat($('#inputBayar').val()) || 0;
            let total = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);

            if (bayar < total) {
                alert('PEMBAYARAN KURANG! Harap cek jumlah uang.');
                $('#inputBayar').focus();
                return;
            }

            if(!confirm('Proses Transaksi Senilai Rp ' + new Intl.NumberFormat('id-ID').format(total) + '?')) return;

            // AJAX Process
            $.ajax({
                url: "{{ route('owner.kasir.store') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    items: cart,
                    bayar: bayar,
                    id_pelanggan: $('#pelangganSelect').val(),
                    toko_id: "{{ session('toko_active_id') }}"
                },
                success: function(response) {
                    alert('TRANSAKSI SUKSES! Mencetak Struk...');
                    clearCart();
                    // Reload Page to refresh stock data from server
                    window.location.reload(); 
                },
                error: function(xhr) {
                    alert('ERROR: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem'));
                    console.error(xhr);
                }
            });
        }
    </script>
@endsection