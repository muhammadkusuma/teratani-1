@extends('layouts.owner')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard Owner</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Selamat Datang, {{ Auth::user()->name }}</li>
        </ol>

        {{-- Alert Success Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Status Toko Aktif --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Toko Aktif Saat Ini</h5>
                                @if (session('toko_active_nama'))
                                    <h2 class="display-6 fw-bold">{{ session('toko_active_nama') }}</h2>
                                @else
                                    <p class="mb-0">Belum ada toko yang dipilih.</p>
                                    <small>Silakan pilih toko untuk mulai mengelola transaksi.</small>
                                @endif
                            </div>
                            <i class="fas fa-store fa-4x opacity-50"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('owner.toko.index') }}">
                            @if (session('toko_active_id'))
                                Ganti Toko
                            @else
                                Pilih Toko
                            @endif
                        </a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Cepat (Hanya muncul jika toko sudah dipilih) --}}
        @if (session('toko_active_id'))
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Penjualan Kasir</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">Buka POS</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">Stok Produk</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">Kelola Stok</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">Laporan Harian</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">Lihat Laporan</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">Pengaturan Toko</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link"
                                href="{{ route('owner.toko.edit', session('toko_active_id')) }}">Edit Info</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
