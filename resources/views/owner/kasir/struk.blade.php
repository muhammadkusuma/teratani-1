<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $penjualan->no_faktur }}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm auto; /* Default to 58mm, can be overridden or auto-detected by printer driver usually but explicit size helps */
        }
        body {
            font-family: 'Courier New', Courier, monospace; /* Monospace is better for receipts */
            font-size: 10px;
            margin: 0;
            padding: 2mm;
            color: #000;
            width: 58mm; /* Target 58mm width */
            max-width: 58mm;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .logo-placeholder {
            width: 40px;
            height: 40px;
            background-color: #eee;
            margin: 0 auto 5px;
            border: 1px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #888;
        }
        .logo-img {
            max-width: 40mm;
            max-height: 20mm;
            margin-bottom: 5px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .header h2 {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 9px;
            line-height: 1.2;
        }
        .metadata {
            margin-bottom: 5px;
            font-size: 9px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .metadata table {
            width: 100%;
        }
        .metadata td {
            padding: 0;
            vertical-align: top;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 9px;
        }
        .items td {
            padding: 2px 0;
            vertical-align: top;
        }
        .item-name {
            font-weight: bold;
            display: block;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
        }
        .totals {
            width: 100%;
            margin-bottom: 10px;
            font-size: 9px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .totals td {
            padding: 1px 0;
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        /* Utility for dashed lines if needed explicitly */
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 5px 0;
            display: none; /* Hide explicit dividers if borders are used */
        }
        
        @media print {
            body { 
                width: 100%; /* Full width of page */
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <!-- Logo Placeholder -->
        <img src="{{ asset('images/logo-struk.png') }}" alt="LOGO" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="logo-placeholder" style="display: none;">
            LOGO
        </div>
        
        <h2>{{ $toko->nama_toko ?? 'TOKO TANI' }}</h2>
        <p>{{ $toko->alamat ?? 'Alamat Toko' }}</p>
        <p>{{ $toko->kota ?? '' }}</p>
        <p>WA/HP : {{ $toko->no_telepon ?? '-' }}</p>
    </div>

    <!-- Metadata -->
    <div class="metadata">
        <table>
            <tr>
                <td style="width: 50%;">Faktur: {{ $penjualan->no_faktur }}</td>
                <td style="text-align: right;">{{ date('d/m/y H:i', strtotime($penjualan->tgl_transaksi)) }}</td>
            </tr>
            <tr>
                <td>Ksr: {{ $penjualan->user->username ?? 'Admin' }}</td>
                <td style="text-align: right;">Plg: {{ $penjualan->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
            </tr>
        </table>
    </div>

    <table class="items">
        @foreach ($penjualan->details as $item)
            <tr>
                <td>
                    <span class="item-name">{{ $item->produk->nama_produk }}</span>
                    <div class="item-details">
                        <span>{{ $item->qty }}x {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</span>
                        <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>

    <table class="totals">
        @if ($penjualan->diskon_nota > 0)
            <tr>
                <td>Diskon :</td>
                <td>{{ number_format($penjualan->diskon_nota, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td style="font-weight: bold;">Total :</td>
            <td style="font-weight: bold;">{{ number_format($penjualan->total_netto, 0, ',', '.') }}</td>
        </tr>
         <tr>
            <td>Bayar :</td>
            <td>{{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ $penjualan->metode_bayar == 'Hutang' ? 'Sisa' : 'Kembali' }} :</td>
            <td>{{ number_format($penjualan->metode_bayar == 'Hutang' ? $penjualan->total_netto - $penjualan->jumlah_bayar : $penjualan->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>TERIMA KASIH</p>
        <p>SEMOGA BERKAH & JADI LANGGANAN</p>
    </div>

</body>
</html>
