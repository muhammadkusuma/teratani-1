<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Faktur Penjualan</title>
<style>
    body {
        font-family: "Courier New", monospace;
        font-size: 12px;
    }
    .page {
        width: 800px;
        margin: auto;
    }
    .center {
        text-align: center;
    }
    .right {
        text-align: right;
    }
    .row {
        display: flex;
        justify-content: space-between;
    }
    .line {
        border-bottom: 1px dashed #000;
        margin: 6px 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }
    th, td {
        padding: 3px;
    }
    th {
        border-bottom: 1px solid #000;
    }
    .total td {
        padding-top: 4px;
    }
</style>
</head>

<body>
<div class="page">

    {{-- HEADER --}}
    <div class="row">
        <div>
            {{ $transaksi->tgl_transaksi->format('d F Y') }}
        </div>
        <div class="right">
            FAKTUR PENJUALAN dibuat oleh ({{ $transaksi->user->name ?? 'Kasir' }})
        </div>
    </div>

    <div class="center" style="margin-top:10px;">
        <strong>{{ strtoupper($transaksi->toko->nama_toko ?? 'KERINCI TANI AGRO') }}</strong><br>
        {{ $transaksi->toko->alamat ?? '' }}<br>
        {{ $transaksi->toko->kota ?? '' }}<br>
        HP / WA : {{ $transaksi->toko->telepon ?? '-' }}
    </div>

    <div class="line"></div>

    {{-- INFO PELANGGAN --}}
    <div class="row">
        <div>
            Pelanggan : {{ $transaksi->pelanggan->nama_pelanggan ?? 'UMUM' }}<br>
            HP / WA   : {{ $transaksi->pelanggan->no_hp ?? '-' }}<br>
            Alamat    : {{ $transaksi->pelanggan->alamat ?? '-' }}
        </div>
        <div class="right">
            Nomor Faktur<br>
            <strong>{{ $transaksi->no_faktur }}</strong>
        </div>
    </div>

    <div class="line"></div>

    {{-- TABEL BARANG --}}
    <table>
        <thead>
            <tr>
                <th width="40">No</th>
                <th>Nama Barang</th>
                <th width="80">Qty</th>
                <th width="100">Harga</th>
                <th width="120">Sub Total</th>
                <th width="50">Cek</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->details as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->produk->nama_produk }}</td>
                <td>{{ $item->qty }} {{ $item->satuan_jual }}</td>
                <td class="right">{{ number_format($item->harga_jual_satuan,0,',','.') }}</td>
                <td class="right">{{ number_format($item->subtotal,0,',','.') }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    {{-- TOTAL --}}
    <table class="total">
        <tr>
            <td class="right" width="80%">Jumlah =</td>
            <td class="right" width="20%">Rp. {{ number_format($transaksi->total_bruto,0,',','.') }}</td>
        </tr>
        <tr>
            <td class="right">Disc</td>
            <td class="right">Rp. {{ number_format($transaksi->diskon_nota,0,',','.') }}</td>
        </tr>
        <tr>
            <td class="right">Total Pembayaran</td>
            <td class="right">Rp. {{ number_format($transaksi->total_netto,0,',','.') }}</td>
        </tr>
        <tr>
            <td class="right"><strong>Jumlah =</strong></td>
            <td class="right"><strong>Rp. {{ number_format($transaksi->total_netto,0,',','.') }}</strong></td>
        </tr>
    </table>

    <div class="line"></div>

    {{-- TANDA TANGAN --}}
    <div class="row" style="margin-top:30px;">
        <div class="center">
            Hormat Kami,<br><br><br>
            ____________________
        </div>
        <div class="center">
            Penerima,<br><br><br>
            ____________________
        </div>
    </div>

</div>
</body>
</html>
