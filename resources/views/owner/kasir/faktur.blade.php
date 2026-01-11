<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Penjualan #{{ $transaksi->no_faktur ?? $transaksi->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            /* A4 width */
            margin: 0 auto;
            background: white;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .company-info h2 {
            margin: 0;
            color: #2c3e50;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
            text-transform: uppercase;
        }

        /* Info Section */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: bold;
            width: 100px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        /* Totals */
        .totals-table {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 5px;
        }

        .grand-total {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
        }

        /* Footer / Signatures */
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            float: right;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
        }

        /* Print Settings */
        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                size: A4;
                margin: 2cm;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container">
        <div class="no-print" style="margin-bottom: 20px; text-align: right;">
            <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Faktur</button>
        </div>

        <table class="header-table">
            <tr>
                <td width="60%" class="company-info">
                    <h2>{{ $transaksi->toko->nama_toko ?? 'Teratani Store' }}</h2>
                    <p>
                        {{ $transaksi->toko->alamat ?? 'Alamat Toko Belum Diisi' }}<br>
                        Telp: {{ $transaksi->toko->telepon ?? '-' }}
                    </p>
                </td>
                <td width="40%" class="invoice-title">
                    <h1>FAKTUR PENJUALAN</h1>
                    <p>No: #{{ $transaksi->no_faktur ?? $transaksi->id }}</p>
                    <p>Tanggal: {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td width="50%" valign="top">
                    <strong>Kepada Yth:</strong><br>
                    {{ $transaksi->pelanggan->nama ?? 'Umum' }}<br>
                    {{ $transaksi->pelanggan->alamat ?? '-' }}<br>
                    Telp: {{ $transaksi->pelanggan->telepon ?? '-' }}
                </td>
                <td width="50%" valign="top">
                    <strong>Keterangan:</strong><br>
                    Kasir: {{ $transaksi->user->name ?? 'Admin' }}<br>
                    Metode: Tunai/Transfer
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Nama Produk</th>
                    <th width="15%">Harga Satuan</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Satuan</th>
                    <th width="20%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->details as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->produk->nama_produk ?? 'Item Terhapus' }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->jumlah }}</td>
                        <td class="text-center">{{ $item->produk->satuan->nama_satuan ?? 'Pcs' }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Total</td>
                <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td>Grand Total</td>
                <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td class="text-right" style="font-style: italic; font-size: 12px;">
                    {{-- Opsi: Gunakan helper terbilang jika ada --}}
                    (Sesuai Total Bayar)
                </td>
            </tr>
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <p>Hormat Kami,</p>
                <div class="signature-line"></div>
                <p>{{ $transaksi->toko->nama_toko ?? 'Admin' }}</p>
            </div>
            <div class="signature-box" style="float: left; text-align: left;">
                <p>Penerima,</p>
                <div class="signature-line" style="width: 150px;"></div>
                <p>{{ $transaksi->pelanggan->nama ?? '................' }}</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

</body>

</html>
