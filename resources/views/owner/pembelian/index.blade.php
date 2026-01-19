@extends('layouts.owner')

@section('title', 'Daftar Pembelian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Riwayat Pembelian Distributor</h4>
                <a href="{{ route('owner.toko.pembelian.create', $toko->id_toko) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Input Pembelian
                </a>
            </div>
            <div class="card-body">
                <form action="" method="GET" class="row mb-3">
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Distributor</th>
                                <th>No Faktur</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembelians as $pembelian)
                            <tr>
                                <td>{{ $pembelian->tanggal->format('d M Y') }}</td>
                                <td>{{ $pembelian->distributor->nama_distributor }}</td>
                                <td>{{ $pembelian->no_faktur ?? '-' }}</td>
                                <td>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                                <td>{{ $pembelian->keterangan }}</td>
                                <td>
                                    <a href="{{ route('owner.toko.pembelian.show', [$toko->id_toko, $pembelian->id_pembelian]) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data pembelian.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $pembelians->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
