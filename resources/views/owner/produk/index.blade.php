@extends('layouts.owner')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Produk</h1>
            <a href="{{ route('owner.toko.produk.create', $toko->id_toko) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Gambar</th>
                                <th>Info Produk</th>
                                <th>Kategori</th>
                                <th>Satuan & Konversi</th>
                                <th>Harga Jual</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        @if ($item->gambar_produk)
                                            <img src="{{ asset('storage/' . $item->gambar_produk) }}" alt="img"
                                                class="img-thumbnail" style="max-height: 50px;">
                                        @else
                                            <span class="text-muted text-xs">No Img</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->nama_produk }}</strong><br>
                                        <small class="text-muted">SKU: {{ $item->sku ?? '-' }}</small><br>
                                        <small class="text-muted">Barcode: {{ $item->barcode ?? '-' }}</small>
                                    </td>
                                    <td>{{ $item->kategori->nama_kategori ?? 'Umum' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->satuanKecil->nama_satuan ?? '-' }}</span>
                                        @if ($item->id_satuan_besar)
                                            <br><small>1 {{ $item->satuanBesar->nama_satuan }} = {{ $item->nilai_konversi }}
                                                {{ $item->satuanKecil->nama_satuan }}</small>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('owner.toko.produk.edit', [$toko->id_toko, $produk->id_produk]) }}"
                                            class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <form
                                            action="{{ route('owner.toko.produk.destroy', [$toko->id_toko, $produk->id_produk]) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $produks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
