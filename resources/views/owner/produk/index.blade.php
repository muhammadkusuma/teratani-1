@extends('layouts.owner')

@section('content')
    <div class="container-fluid px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="mt-4 text-2xl font-bold">Produk Toko: {{ $toko->nama }}</h1>
            <a href="{{ route('owner.toko.produk.create', $toko->id) }}"
                class="btn btn-primary bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah Produk
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-gray-50 p-3 border-b">
                <i class="fas fa-box me-1"></i> Daftar Produk
            </div>
            <div class="card-body p-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3">Foto</th>
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Harga Jual</th>
                            <th class="p-3">Stok</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produks as $produk)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">
                                    @if ($produk->foto)
                                        <img src="{{ asset('storage/' . $produk->foto) }}"
                                            class="w-12 h-12 object-cover rounded" alt="Foto">
                                    @else
                                        <span class="text-gray-400">No Img</span>
                                    @endif
                                </td>
                                <td class="p-3 font-medium">{{ $produk->nama }}</td>
                                <td class="p-3">{{ $produk->kategori->nama ?? '-' }}</td>
                                <td class="p-3">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                <td class="p-3">
                                    <span
                                        class="px-2 py-1 rounded text-xs {{ $produk->stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $produk->stok }} {{ $produk->satuan->nama ?? 'Unit' }}
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <a href="{{ route('owner.toko.produk.edit', [$toko->id, $produk->id]) }}"
                                        class="text-yellow-600 hover:text-yellow-800 mr-2">Edit</a>
                                    <form action="{{ route('owner.toko.produk.destroy', [$toko->id, $produk->id]) }}"
                                        method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">Belum ada produk di toko ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-3">
                    {{ $produks->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
