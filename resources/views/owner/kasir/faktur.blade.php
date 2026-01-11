<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur - {{ $transaksi->no_faktur }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background: #eee;
        }

        .invoice-box {
            max-width: 210mm;
            /* A4 Width */
            margin: auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            min-height: 297mm;
            /* A4 Height */
            position: relative;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .company-details {
            text-align: right;
        }

        .company-details h2 {
            margin: 0;
            color: #2c3e50;
            text-transform: uppercase;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            margin: 0;
        }

        /* Info Grid */
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .client-details,
        .invoice-meta {
            width: 48%;
        }

        .box-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #777;
            font-weight: bold;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 3px 0;
        }

        .meta-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }

        /* Table Items */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background: #2c3e50;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 13px;
            text-transform: uppercase;
        }

        .items-table td {
            border-bottom: 1px solid #eee;
            padding: 10px;
        }

        .items-table tr:last-child td {
            border-bottom: 2px solid #333;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Totals */
        .totals-container {
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 40%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 5px;
        }

        .totals-table .total-row {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
            color: #2c3e50;
        }

        /* Terbilang & Notes */
        .notes-section {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-left: 4px solid #2c3e50;
            font-size: 13px;
        }

        .terbilang {
            font-style: italic;
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }

        /* Signature */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            text-align: center;
        }

        .sign-box {
            width: 30%;
        }

        .sign-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        /* Status Stamp */
        .stamp {
            position: absolute;
            top: 180px;
            right: 40px;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 15px;
            border: 3px solid;
            transform: rotate(-15deg);
            opacity: 0.8;
        }

        .lunas {
            color: green;
            border-color: green;
        }

        .belum-lunas {
            color: red;
            border-color: red;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-box {
                box-shadow: none;
                border: none;
                padding: 0;
                margin: 0;
                width: 100%;
                max-width: 100%;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="max-width: 210mm; margin: 0 auto 20px auto; text-align: right;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #2c3e50; color: white; border: none; cursor: pointer; border-radius: 4px;">
            🖨️ Cetak Faktur / Simpan PDF
        </button>
    </div>

    <div class="invoice-box">

        {{-- Stamp Status Lunas/Belum --}}
        @if ($transaksi->status_bayar == 'Lunas')
            <div class="stamp lunas">LUNAS</div>
        @else
            <div class="stamp belum-lunas">BELUM LUNAS</div>
        @endif

        <div class="header">
            <div>
                <h1 class="invoice-title">FAKTUR PENJUALAN</h1>
                <small>No. Referensi: {{ $transaksi->no_faktur }}</small>
            </div>
            <div class="company-details">
                <h2>{{ $transaksi->toko->nama_toko ?? 'TERATANI STORE' }}</h2>
                <div>{{ $transaksi->toko->alamat ?? 'Alamat Toko Belum Diisi' }}</div>
                <div>Telp: {{ $transaksi->toko->telepon ?? '-' }}</div>
            </div>
        </div>

        <div class="info-grid">
            <div class="client-details">
                <div class="box-label">Ditagihkan Kepada (Bill To):</div>
                @if ($transaksi->pelanggan)
                    <h3 style="margin: 5px 0;">{{ $transaksi->pelanggan->nama_pelanggan }}</h3>
                    <div>{{ $transaksi->pelanggan->alamat ?? 'Alamat tidak tersedia' }}</div>
                    <div>Telp: {{ $transaksi->pelanggan->telepon ?? '-' }}</div>
                @else
                    <h3 style="margin: 5px 0; color: #777;">Pelanggan Umum</h3>
                    <div>-</div>
                @endif
            </div>
            <div class="invoice-meta">
                <div class="box-label">Rincian Faktur:</div>
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Tanggal Transaksi</td>
                        <td>: {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Jatuh Tempo</td>
                        <td>:
                            @if ($transaksi->metode_bayar == 'Hutang' && $transaksi->tgl_jatuh_tempo)
                                <strong
                                    style="color: red;">{{ \Carbon\Carbon::parse($transaksi->tgl_jatuh_tempo)->format('d F Y') }}</strong>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="meta-label">Metode Bayar</td>
                        <td>: {{ strtoupper($transaksi->metode_bayar) }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Kasir/Sales</td>
                        <td>: {{ $transaksi->user->username ?? 'Admin' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 45%;">Deskripsi Produk</th>
                    <th style="width: 15%;" class="text-right">Harga Satuan</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 25%;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->details as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</strong>
                            @if ($item->produk && $item->produk->kode_produk)
                                <br><small style="color: #777;">SKU: {{ $item->produk->kode_produk }}</small>
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->qty }} {{ $item->satuan_jual ?? 'Pcs' }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-container">
            <table class="totals-table">
                <tr>
                    <td>Total Bruto</td>
                    <td class="text-right">Rp {{ number_format($transaksi->total_bruto, 0, ',', '.') }}</td>
                </tr>
                @if ($transaksi->diskon_nota > 0)
                    <tr>
                        <td>Diskon</td>
                        <td class="text-right text-red">- Rp {{ number_format($transaksi->diskon_nota, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
                @if ($transaksi->pajak_ppn > 0)
                    <tr>
                        <td>PPN</td>
                        <td class="text-right">Rp {{ number_format($transaksi->pajak_ppn, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL TAGIHAN</td>
                    <td class="text-right">Rp {{ number_format($transaksi->total_netto, 0, ',', '.') }}</td>
                </tr>
                @if ($transaksi->metode_bayar != 'Hutang')
                    <tr style="color: #777; font-size: 12px;">
                        <td>Dibayar</td>
                        <td class="text-right">Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="color: #777; font-size: 12px;">
                        <td>Kembali</td>
                        <td class="text-right">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
                    </tr>
                @else
                    <tr style="color: red; font-size: 12px; font-weight: bold;">
                        <td>Sisa Hutang</td>
                        <td class="text-right">Rp
                            {{ number_format($transaksi->total_netto - $transaksi->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="notes-section">
            <div class="terbilang">Terbilang: # {{ $terbilang }} #</div>

            <div style="margin-top: 10px;">
                <strong>Info Pembayaran:</strong><br>
                Silakan transfer ke:
                <strong>BCA 123-456-7890 a.n {{ $transaksi->toko->nama_toko ?? 'Pemilik Toko' }}</strong><br>
                Mohon sertakan No. Faktur saat melakukan pembayaran.
            </div>

            @if ($transaksi->catatan)
                <div style="margin-top: 10px;">
                    <strong>Catatan:</strong><br>
                    {{ $transaksi->catatan }}
                </div>
            @endif
        </div>

        <div class="signature-section">
            <div class="sign-box">
                <p>Penerima,</p>
                <div class="sign-line"></div>
                <p>({{ $transaksi->pelanggan->nama_pelanggan ?? '.....................' }})</p>
            </div>
            <div class="sign-box">
                <p>Hormat Kami,</p>
                <div class="sign-line"></div>
                <p>({{ $transaksi->toko->nama_toko ?? 'Manager' }})</p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px; font-size: 10px; color: #999;">
            Dicetak secara otomatis oleh sistem Teratani pada {{ date('d/m/Y H:i') }}
        </div>

    </div>

</body>

</html>
