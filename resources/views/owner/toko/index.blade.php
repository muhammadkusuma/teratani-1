@extends('layouts.owner')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Manajemen Toko</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Daftar Toko Cabang</li>
        </ol>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-store me-1"></i>
                    Data Toko
                </div>
                <a href="{{ route('owner.toko.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Toko
                </a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Toko</th>
                            <th>Nama Toko</th>
                            <th>Kota</th>
                            <th>No Telp</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($toko as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->kode_toko }}</td>
                                <td>
                                    <strong>{{ $item->nama_toko }}</strong>
                                    @if ($item->is_pusat)
                                        <span class="badge bg-info text-dark ms-1">Pusat</span>
                                    @endif
                                </td>
                                <td>{{ $item->kota }}</td>
                                <td>{{ $item->no_telp }}</td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Non-Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        {{-- Tombol Pilih Toko (Switch) --}}
                                        <a href="{{ route('owner.toko.select', $item->id_toko) }}"
                                            class="btn btn-success btn-sm" title="Kelola Toko Ini">
                                            <i class="fas fa-sign-in-alt"></i> Masuk
                                        </a>

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('owner.toko.edit', $item->id_toko) }}"
                                            class="btn btn-warning btn-sm text-white" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Tombol Hapus (Modal Trigger atau Form) --}}
                                        @if (!$item->is_pusat)
                                            <form action="{{ route('owner.toko.destroy', $item->id_toko) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus toko ini? Data terkait mungkin akan hilang.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data toko. Silakan tambah toko baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
