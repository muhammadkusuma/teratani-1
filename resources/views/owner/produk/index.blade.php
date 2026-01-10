@extends('layouts.owner')

@section('content')
    <div class="container-fluid px-6 py-6 bg-gray-50 min-h-screen">
        {{-- Header Section: Judul & Breadcrumb --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Produk</h1>
                <p class="text-sm text-gray-500">Toko: <span class="font-semibold text-blue-600">{{ $toko->nama_toko }}</span>
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <a href="{{ route('owner.toko.produk.create', $toko->id_toko) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i> Tambah Produk
                </a>
            </div>
        </div>

        {{-- Alert Notification --}}
        @if (session('success'))
            <div class="flex items-center bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6"
                role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">

            {{-- Toolbar / Search Filter (Opsional, untuk nuansa sistem) --}}
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                    <i class="fas fa-list-ul mr-1"></i> Data Inventaris
                </h2>
                {{-- Contoh Search Bar Sederhana --}}
                <div class="relative">
                    <input type="text"
                        class="text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pl-8 py-1 w-64"
                        placeholder="Cari nama produk...">
                    <div class="absolute top-2 left-2.5 text-gray-400 text-xs">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">
                                Foto
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Nama Produk
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Harga Jual
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Stok Fisik
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($produks as $produk)
                            <tr class="hover:bg-blue-50 transition-colors duration-150">
                                {{-- Foto --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($produk->gambar_produk)
                                            <img class="h-10 w-10 rounded-md object-cover border border-gray-200"
                                                src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Nama Produk --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $produk->nama_produk }}</div>
                                    <div class="text-xs text-gray-500">Kode: {{ $produk->kode_produk ?? '-' }}</div>
                                    {{-- Jika ada kode --}}
                                </td>

                                {{-- Kategori --}}
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $produk->kategori->nama_kategori ?? '-' }}
                                    </span>
                                </td>

                                {{-- Harga --}}
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700 font-mono">
                                    Rp {{ number_format($produk->harga_jual_umum, 0, ',', '.') }}
                                </td>

                                {{-- Stok --}}
                                <td class="px-6 py-3 whitespace-nowrap text-center">
                                    @php
                                        $stokFisik = $produk->stokTokos->first()->stok_fisik ?? 0;
                                        $isSafe = $stokFisik > 10; // Logic contoh: aman jika > 10
                                        $isCritical = $stokFisik <= 0;
                                    @endphp

                                    @if ($isCritical)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            Habis ({{ $stokFisik }})
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $isSafe ? 'bg-green-100 text-green-800 border-green-200' : 'bg-yellow-100 text-yellow-800 border-yellow-200' }} border">
                                            {{ $stokFisik }} {{ $produk->satuanKecil->nama_satuan ?? 'Unit' }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi (Icon Buttons) --}}
                                <td class="px-6 py-3 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center items-center space-x-3">
                                        <a href="{{ route('owner.toko.produk.edit', [$toko->id_toko, $produk->id_produk]) }}"
                                            class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form
                                            action="{{ route('owner.toko.produk.destroy', [$toko->id_toko, $produk->id_produk]) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-sm">Belum ada data produk tersedia.</p>
                                        <p class="text-xs text-gray-400 mt-1">Silakan tambahkan produk baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $produks->links() }}
            </div>
        </div>
    </div>
@endsection
