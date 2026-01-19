@extends('layouts.owner')

@section('title', 'Detail Pembelian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Detail Pembelian #{{ $pembelian->id_pembelian }}</h4>
                <a href="{{ route('owner.toko.pembelian.index', $toko->id_toko) }}" class="btn btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <table class="table table-borderless">
                            <tr>
                                <th>Tanggal</th>
                                <td>: {{ $pembelian->tanggal->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Distributor</th>
                                <td>: {{ $pembelian->distributor->nama_distributor }}</td>
                            </tr>
                            <tr>
                                <th>No Faktur</th>
                                <td>: {{ $pembelian->no_faktur ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <div class="alert alert-secondary">
                            <strong>Keterangan:</strong><br>
                            {{ $pembelian->keterangan ?? 'Tidak ada keterangan' }}
                        </div>
                    </div>
                </div>

                <h5>Item Barang</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian->details as $detail)
                            <tr>
                                <td>{{ $detail->produk->nama_produk }}</td>
                                <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td class="text-end">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Grand Total</td>
                                <td class="text-end fw-bold">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
