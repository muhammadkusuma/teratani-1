@extends('layouts.owner')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Produk - {{ $toko->nama_toko }}</h1>
            <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                {{-- PERHATIKAN ACTION ROUTE INI --}}
                <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-secondary mb-3">Data Barang</h5>

                            <div class="form-group mb-3">
                                <label>Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama_produk" class="form-control" required
                                    placeholder="Nama barang...">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>SKU / Kode Stok</label>
                                    <input type="text" name="sku" class="form-control" placeholder="Kode unik...">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Barcode</label>
                                    <input type="text" name="barcode" class="form-control" placeholder="Scan barcode...">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Kategori</label>
                                <select name="id_kategori" class="form-control">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kat)
                                        <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h5 class="text-secondary mb-3">Media & Stok Awal</h5>
                            <div class="form-group mb-3">
                                <label>Gambar</label>
                                <input type="file" name="gambar_produk" class="form-control" accept="image/*">
                            </div>

                            {{-- Fitur Stok Awal untuk Toko Ini --}}
                            <div class="form-group mb-3">
                                <label>Stok Awal di {{ $toko->nama_toko }}</label>
                                <input type="number" name="stok_awal" class="form-control" value="0" min="0">
                                <small class="text-muted">Langsung mengisi stok fisik di toko ini.</small>
                            </div>

                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" checked id="activeCheck">
                                <label class="form-check-label" for="activeCheck">Status Aktif</label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-secondary mb-3">Satuan & Harga</h5>
                    <div class="row bg-light p-3 rounded">
                        <div class="col-md-4 mb-3">
                            <label>Satuan Kecil (Ecer) <span class="text-danger">*</span></label>
                            <select name="id_satuan_kecil" class="form-control" required>
                                @foreach ($satuans as $sat)
                                    <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Satuan Besar (Grosir)</label>
                            <select name="id_satuan_besar" class="form-control">
                                <option value="">-- Tidak Ada --</option>
                                @foreach ($satuans as $sat)
                                    <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Konversi (1 Besar = ... Kecil)</label>
                            <input type="number" name="nilai_konversi" class="form-control" value="1" min="1">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Harga Beli (HPP)</label>
                            <input type="number" name="harga_beli_rata_rata" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Harga Jual Umum</label>
                            <input type="number" name="harga_jual_umum" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
