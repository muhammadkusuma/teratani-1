<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $penjualan->no_faktur }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }

        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }

            /* Ukuran Kertas Thermal 80mm */
        }
    </style>
</head>

<body onload="window.print()">

    <div class="text-center">
        <h3 style="margin:0">{{ $toko->nama_toko ?? 'Toko' }}</h3>
        <p style="margin:0">{{ $toko->alamat ?? '-' }}</p>
        <p class="line"></p>
    </div>

    <table>
        <tr>
            <td>No: {{ $penjualan->no_faktur }}</td>
            <td class="text-end">{{ date('d/m/Y H:i', strtotime($penjualan->tgl_transaksi)) }}</td>
        </tr>
        <tr>
            <td>Kasir: {{ $penjualan->user->name ?? 'Admin' }}</td>
            <td class="text-end">{{ $penjualan->metode_bayar }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        @foreach ($penjualan->details as $item)
            <tr>
                <td colspan="2">{{ $item->produk->nama_produk }}</td>
            </tr>
            <tr>
                <td>{{ $item->qty }} x {{ number_format($item->harga_jual_satuan) }}</td>
                <td class="text-end">{{ number_format($item->subtotal) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Total</td>
            <td class="text-end bold">{{ number_format($penjualan->total_netto) }}</td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="text-end">{{ number_format($penjualan->jumlah_bayar) }}</td>
        </tr>
        <tr>
            <td>Kembali/Sisa</td>
            <td class="text-end">{{ number_format($penjualan->kembalian) }}</td>
        </tr>
    </table>

    <div class="text-center" style="margin-top: 15px;">
        <p>Terima Kasih</p>
    </div>

</body>

</html>
