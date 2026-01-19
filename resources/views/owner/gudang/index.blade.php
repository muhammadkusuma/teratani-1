@extends('layouts.owner')

@section('title', 'Manajemen Gudang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Gudang</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Gudang</th>
                                <th>Lokasi</th>
                                <th class="text-center">Total Jenis Produk (Stok Ada)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gudangs as $gudang)
                            <tr>
                                <td>{{ $gudang->nama_gudang }}</td>
                                <td>{{ $gudang->lokasi ?? '-' }}</td>
                                <!-- Count only items with stock > 0 if needed, or total items listed -->
                                <td class="text-center">{{ $gudang->stok_gudangs_count }}</td>
                                <td>
                                    <a href="{{ route('owner.gudang.show', $gudang->id_gudang) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-boxes"></i> Lihat Stok
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data gudang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
