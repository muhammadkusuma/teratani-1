@extends('layouts.owner')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Toko / Cabang</h2>
            <a href="{{ route('owner.toko.create') }}" class="btn btn-primary">+ Tambah Cabang Baru</a>
        </div>

        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <div class="row">
            @foreach ($toko as $item)
                <div class="col-md-4 mb-3">
                    <div class="card h-100 {{ session('toko_active_id') == $item->id_toko ? 'border-primary' : '' }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->nama_toko }}</h5>
                            <p class="card-text text-muted">
                                {{ $item->kota }} <br>
                                <small>{{ $item->alamat }}</small>
                            </p>

                            <hr>

                            @if (session('toko_active_id') == $item->id_toko)
                                <button class="btn btn-success w-100" disabled>Sedang Aktif</button>
                            @else
                                <a href="{{ route('owner.toko.select', $item->id_toko) }}"
                                    class="btn btn-outline-primary w-100">
                                    Kelola Toko Ini
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
