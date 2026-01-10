@extends('layouts.owner')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Toko</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('owner.toko.index') }}">Daftar Toko</a></li>
            <li class="breadcrumb-item active">Edit Data</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i> Edit Toko: {{ $toko->nama_toko }}
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form Update menggunakan Method PUT --}}
                <form action="{{ route('owner.toko.update', $toko->id_toko) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_toko" class="form-label">Nama Toko <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_toko" name="nama_toko"
                            value="{{ old('nama_toko', $toko->nama_toko) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kota" class="form-label">Kota</label>
                            <input type="text" class="form-control" id="kota" name="kota"
                                value="{{ old('kota', $toko->kota) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp"
                                value="{{ old('no_telp', $toko->no_telp) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $toko->alamat) }}</textarea>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                            {{ $toko->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Status Aktif</label>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('owner.toko.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
