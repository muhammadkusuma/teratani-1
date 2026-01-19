@extends('layouts.owner')

@section('title', 'Stok Gudang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Stok: {{ $gudang->nama_gudang }}</h4>
                <a href="{{ route('owner.gudang.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th class="text-center">Stok Fisik</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stoks as $stok)
                            <tr>
                                <td>{{ $stok->produk->nama_produk }}</td>
                                <td class="text-center fw-bold">{{ number_format($stok->stok_fisik, 0, ',', '.') }}</td>
                                <td>{{ $stok->produk->satuanKecil->nama_satuan ?? 'Pcs' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada stok produk di gudang ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $stoks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
