@extends('layouts.owner')

@section('content')
    <div class="container py-4">
        <div class="card col-md-8 mx-auto">
            <div class="card-header">
                <h4>Buka Cabang Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.toko.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Toko / Cabang</label>
                        <input type="text" name="nama_toko" class="form-control" required
                            placeholder="Contoh: Teratani Cabang Rumbai">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Kota / Wilayah</label>
                            <input type="text" name="kota" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Toko</button>
                    <a href="{{ route('owner.toko.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
