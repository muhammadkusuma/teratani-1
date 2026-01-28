<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Penjualan</title>
    <style>
        @page {
            /* Default to standard continuous form size but allow driver override if set to auto */
            /* size: 241mm 140mm; */ 
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000080; /* Dark Blue */
            margin: 0;
            padding: 5mm; /* Padding inside the paper */
            width: 100%; /* Fluid width */
            /* height: 100%; Remove fixed height to allow content to flow */
            box-sizing: border-box;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
            width: 100%;
        }
        .header-left {
            width: 35%;
        }
        .header-center {
            width: 20%; /* Increased for better logo centering spacing */
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .header-right {
            width: 45%;
            text-align: right;
        }
        
        .logo-placeholder {
            width: 50px;
            height: 50px;
            border: 1px dashed #000080;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        .logo-img {
            max-width: 100%;
            max-height: 50px;
        }

        h2 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .shop-info, .cust-info {
            font-size: 10px;
            line-height: 1.3;
            word-wrap: break-word;
        }
        
        .invoice-title {
            font-size: 9px;
            margin-bottom: 5px;
        }
        .invoice-no {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 5px;
            table-layout: fixed; /* Helps with responsiveness */
        }
        th {
            border-top: 1px solid #000080;
            border-bottom: 1px solid #000080;
            padding: 3px;
            text-align: left;
            color: #000080;
        }
        td {
            padding: 2px 3px;
            vertical-align: top;
            color: #000080;
            word-wrap: break-word;
        }
        tr.item-row td {
            border-bottom: 1px dashed #ccc; 
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .footer-section {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            width: 100%;
        }
        .footer-left {
            width: 30%;
            text-align: center;
        }
        .footer-center {
            width: 30%;
            text-align: center;
        }
        .footer-right {
            width: 40%;
        }
        
        .total-table {
            width: 100%;
        }
        .total-table td {
            padding: 1px 3px;
            border: none;
        }
        
        .signature-space {
            margin-top: 30px;
            border-top: 1px solid #000080;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        @media print {
            body { 
                width: 100%;
                margin: 0;
                padding: 5mm; 
            }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- HEADER -->
    <div class="header-section">
        <!-- Left: Shop Info -->
        <div class="header-left">
            <div class="shop-info">
                <h2>{{ $transaksi->toko->nama_toko ?? 'KERINCI TANI AGRO' }}</h2>
                {{ $transaksi->toko->alamat ?? 'Jl. Yos Sudarso' }}<br>
                {{ $transaksi->toko->kota ?? 'Kota Sungai Penuh' }}<br>
                HP / WA : {{ $transaksi->toko->telepon ?? '-' }}
            </div>
        </div>

        <!-- Center: Logo -->
        <div class="header-center">
             <img src="{{ asset('images/logo-faktur.png') }}" alt="LOGO" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
            <div class="logo-placeholder" style="display: none;">
                LOGO
            </div>
        </div>

        <!-- Right: Cust & Invoice Info -->
        <div class="header-right">
            <div class="invoice-title">FAKTUR PENJUALAN dibuat oleh ({{ auth()->user()->nama_lengkap ?? '-' }})</div>
            <div class="cust-info">
                <strong>Pelanggan : {{ $transaksi->pelanggan->nama_pelanggan ?? '-' }} - {{ $transaksi->pelanggan->toko ?? '' }}</strong><br>
                HP / WA : ({{ $transaksi->pelanggan->no_hp ?? '-' }})<br>
                Alamat : {{ $transaksi->pelanggan->alamat ?? 'Tanah Kampung' }}
            </div>
            <div class="invoice-no">Nomor Faktur, {{ $transaksi->no_faktur }}</div>
        </div>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th width="30">No.</th>
                <th>Nama Barang</th>
                <th width="60" class="text-right">Qty</th>
                <th width="80" class="text-right">Harga</th>
                <th width="90" class="text-right">Sub Total</th>
                <th width="30" class="text-center">Cek</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->details as $index => $item)
            <tr class="item-row">
                <td>{{ $index + 1 }}.</td>
                <td>{{ $item->produk->nama_produk }}</td>
                <td class="text-right">
                    {{ $item->qty }} {{ $item->satuan_jual }}
                   {{--  @if($item->qty > 1 && $item->satuan_jual != 'Karton')
                        / {{ $item->qty }} Botol
                    @endif --}}
                    <!-- Simplify to just qty unit per image logic if known, otherwise existing data -->
                </td>
                <td class="text-right">{{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                <td class="text-center">_</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer-section">
        <div class="footer-left">
            Hormat Kami,
            <div class="signature-space"></div>
        </div>
        <div class="footer-center">
            Penerima,
            <div class="signature-space"></div>
        </div>
        <div class="footer-right">
            <table class="total-table">
                <tr>
                    <td class="text-right">Jumlah =</td>
                    <td class="text-right">Rp.{{ number_format($transaksi->total_bruto, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-right">Disc</td>
                    <td class="text-right">Rp.{{ number_format($transaksi->diskon_nota, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-right">Total Pembayaran</td>
                    <td class="text-right">-----------------------</td>
                </tr>
                <tr>
                    <td class="text-right" style="font-weight: bold;">Jumlah =</td>
                    <td class="text-right" style="font-weight: bold;">Rp.{{ number_format($transaksi->total_netto, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
