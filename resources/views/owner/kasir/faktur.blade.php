<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur - {{ $transaksi->no_faktur }}</title>
    <style>
        /* --- STYLE DASAR SAMA SEPERTI SEBELUMNYA --- */
        body {
            font-family: 'Arial', sans-serif;
            color: #000080;
            /* Biru Navy */
            margin: 0;
            padding: 20px;
            background: #e0e0e0;
            font-size: 13px;
        }

        .invoice-box {
            max-width: 210mm;
            margin: auto;
            background: #ffe4e8;
            /* Background Pink Faktur */
            padding: 20px 30px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            min-height: 140mm;
            position: relative;
        }

        /* Header Layout */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .header-left {
            width: 50%;
        }

        .header-right {
            width: 45%;
            text-align: right;
        }

        .company-name {
            font-size: 24px;
            font-weight: 800;
            text-transform: uppercase;
            color: #000080;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 11px;
            line-height: 1.3;
        }

        .customer-info {
            margin-top: 10px;
            border: 1px dashed #000080;
            padding: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 5px;
            width: 80%;
        }

        .invoice-meta {
            margin-top: 15px;
            font-weight: bold;
        }

        /* --- UPDATE PADA TABEL --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 2px solid #000080;
            background: transparent;
        }

        .items-table th {
            border: 1px solid #000080;
            padding: 8px;
            text-align: center;
            background: rgba(0, 0, 128, 0.1);
            color: #000080;
            text-transform: uppercase;
            font-size: 12px;
        }

        .items-table td {
            border: 1px solid #000080;
            padding: 6px 8px;
            vertical-align: middle;
            /* Supaya teks di tengah vertikal */
        }

        /* Kotak Cek Manual */
        .check-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000080;
            background: white;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Footer & Totals */
        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
        }

        .payment-info {
            margin-top: 20px;
            padding: 10px;
            border: 1px dashed #000080;
            background-color: #f0f8ff;
            font-size: 11px;
            width: 55%;
            /* Lebar area info */
        }

        .payment-info h4 {
            margin: 0 0 5px 0;
            text-transform: uppercase;
            font-size: 11px;
        }

        .signatures {
            width: 60%;
            display: flex;
            justify-content: space-between;
            padding-right: 50px;
        }

        .sign-box {
            text-align: center;
            width: 120px;
        }

        .sign-space {
            height: 60px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .totals-area {
            width: 35%;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 4px;
            font-weight: bold;
        }

        .totals-table .label {
            text-align: left;
            width: 40%;
        }

        .totals-table .value {
            text-align: right;
            border-bottom: 1px solid #000080;
        }

        .grand-total {
            font-size: 16px;
            color: #000080;
        }

        .vertical-info {
            position: absolute;
            right: 10px;
            top: 40%;
            transform: rotate(-90deg);
            transform-origin: right top;
            font-size: 10px;
            color: #666;
            white-space: nowrap;
        }

        @media print {
            body {
                background: white;
                -webkit-print-color-adjust: exact;
            }

            .invoice-box {
                box-shadow: none;
                border: none;
                width: 100%;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="max-width: 210mm; margin: 0 auto 10px auto; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 15px; cursor: pointer;">🖨️ Cetak</button>
    </div>

    <div class="invoice-box">

        <div class="header">
            <div class="header-left">
                <div style="font-size: 12px; margin-bottom: 5px;">Kepada Yth,</div>
                <div class="customer-info">
                    <strong
                        style="font-size: 14px;">{{ $transaksi->pelanggan->nama_pelanggan ?? 'Pelanggan Umum' }}</strong><br>
                    {{ $transaksi->pelanggan->alamat ?? 'Alamat: -' }}<br>
                    HP/WA: {{ $transaksi->pelanggan->no_hp ?? '-' }}
                </div>
            </div>

            <div class="header-right">
                <div class="company-name">{{ $transaksi->toko->nama_toko ?? 'KERINCI TANI AGRO' }}</div>
                <div class="company-address">
                    {{ $transaksi->toko->alamat ?? 'Jl. Yos Sudarso' }}<br>
                    {{ $transaksi->toko->kota ?? 'Kota Sungai Penuh' }}<br>
                    HP / WA : {{ $transaksi->toko->no_telp ?? '-' }}
                </div>

                <div class="invoice-meta">
                    <div>NO. FAKTUR : {{ $transaksi->no_faktur }}</div>
                    <div>TANGGAL : {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">NO.</th>
                    <th style="width: 40%; text-align: left;">NAMA BARANG</th>
                    <th style="width: 10%;">QTY</th>
                    <th style="width: 18%; text-align: right;">HARGA</th>
                    <th style="width: 18%; text-align: right;">JUMLAH</th>
                    <th style="width: 9%;">CEK</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->details as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>
                            {{ $item->produk->nama_produk }}
                            @if ($item->produk->kode_produk)
                                <span
                                    style="font-size:10px; font-style:italic;">({{ $item->produk->kode_produk }})</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->qty }} {{ $item->satuan_jual }}</td>
                        <td class="text-right">{{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="check-box"></span>
                        </td>
                    </tr>
                @endforeach

                @for ($i = 0; $i < 8 - count($transaksi->details); $i++)
                    <tr>
                        <td style="color: transparent;">.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center">
                            <span class="check-box" style="border-color: transparent;"></span>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="footer-section">
            <div class="signatures" style="flex-direction: column;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div class="sign-box">
                        <p>Hormat Kami,</p>
                        <div class="sign-space"></div>
                        <p class="sign-name">({{ $transaksi->user->username ?? 'Admin' }})</p>
                    </div>
                    <div class="sign-box">
                        <p>Penerima,</p>
                        <div class="sign-space"></div>
                        <p class="sign-name">({{ $transaksi->pelanggan->nama_pelanggan ?? '..................' }})</p>
                    </div>
                </div>

                @if (!empty($transaksi->toko->info_rekening))
                    <div class="payment-info">
                        <h4>Info Pembayaran Transfer:</h4>
                        {!! nl2br(e($transaksi->toko->info_rekening)) !!}
                    </div>
                @endif
            </div>

            <div class="totals-area">
                <table class="totals-table">
                    <tr>
                        <td class="label">Total</td>
                        <td class="value">Rp {{ number_format($transaksi->total_bruto, 0, ',', '.') }}</td>
                    </tr>
                    @if ($transaksi->diskon_nota > 0)
                        <tr>
                            <td class="label">Disc.</td>
                            <td class="value">- Rp {{ number_format($transaksi->diskon_nota, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="label grand-total">Total Bayar</td>
                        <td class="value grand-total">Rp {{ number_format($transaksi->total_netto, 0, ',', '.') }}</td>
                    </tr>

                    @if ($transaksi->metode_bayar == 'Hutang')
                        <tr>
                            <td class="label" style="color:red;">Sisa Hutang</td>
                            <td class="value" style="color:red;">Rp
                                {{ number_format($transaksi->total_netto - $transaksi->jumlah_bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="label">Bayar</td>
                            <td class="value">Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Kembali</td>
                            <td class="value">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="vertical-info">
            FAKTUR PENJUALAN dibuat oleh ({{ $transaksi->user->name ?? 'System' }}) pada {{ date('d/m/Y H:i') }}
        </div>

    </div>

</body>

</html>
