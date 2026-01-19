@extends('layouts.owner')

@section('title', 'Riwayat Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Riwayat Keluar Masuk Barang</h4>
                <a href="{{ route('owner.riwayat-stok.create') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-exchange-alt"></i> Penyesuaian Stok Manual
                </a>
            </div>
            <div class="card-body">
                <form action="" method="GET" class="row mb-3">
                    <div class="col-md-2">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Jenis</label>
                        <select name="jenis" class="form-control">
                            <option value="">Semua</option>
                            <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Lokasi Tipe</label>
                        <select name="location_type" class="form-control" id="locationType">
                            <option value="">Semua</option>
                            <option value="toko" {{ request('location_type') == 'toko' ? 'selected' : '' }}>Toko</option>
                            <option value="gudang" {{ request('location_type') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                        </select>
                    </div>
                    <!-- Select specific location could be added via JS, keeping it simple for now -->
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Produk</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Stok Akhir</th>
                                <th>Keterangan</th>
                                <th>Ref</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayats as $riwayat)
                            <tr>
                                <td>{{ $riwayat->tanggal->format('d/m/Y') }}</td>
                                <td>
                                    @if($riwayat->id_gudang)
                                        <span class="badge bg-secondary">Gudang: {{ $riwayat->gudang->nama_gudang }}</span>
                                    @elseif($riwayat->id_toko)
                                        <span class="badge bg-primary">Toko: {{ $riwayat->toko->nama_toko }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $riwayat->produk->nama_produk }}</td>
                                <td>
                                    @if($riwayat->jenis == 'masuk')
                                        <span class="text-success fw-bold"><i class="fas fa-arrow-down"></i> Masuk</span>
                                    @else
                                        <span class="text-danger fw-bold"><i class="fas fa-arrow-up"></i> Keluar</span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ number_format($riwayat->jumlah, 0, ',', '.') }}</td>
                                <td>{{ number_format($riwayat->stok_akhir, 0, ',', '.') }}</td>
                                <td>{{ $riwayat->keterangan }}</td>
                                <td><small>{{ $riwayat->referensi }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada riwayat stok.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $riwayats->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
