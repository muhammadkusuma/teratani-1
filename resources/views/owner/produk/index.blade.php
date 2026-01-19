@extends('layouts.owner')

@section('title', 'Master Data Produk')

@section('content')
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4 inline-block">DATA PRODUK</h2>
            <span class="text-xs text-gray-500 ml-2">Toko: {{ $toko->nama_toko }}</span>
        </div>
        <a href="{{ route('owner.toko.produk.create', $toko->id_toko) }}"
            class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
            + TAMBAH PRODUK
        </a>
    </div>

    
    <form action="{{ route('owner.toko.produk.index', $toko->id_toko) }}" method="GET" class="mb-3 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / SKU / Barcode..."
            class="border border-gray-400 p-1 text-xs w-64 shadow-inner">
        <button type="submit" class="bg-gray-200 border border-gray-400 px-3 py-1 text-xs hover:bg-gray-300">CARI</button>
        @if (request('search'))
            <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}"
                class="bg-red-200 border border-red-400 px-3 py-1 text-xs hover:bg-red-300 flex items-center text-red-800">RESET</a>
        @endif
    </form>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto border border-gray-400 bg-white">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <th class="border border-gray-400 p-2 text-center w-10">No</th>
                    <th class="border border-gray-400 p-2 text-center w-16">Img</th>
                    <th class="border border-gray-400 p-2">Nama Produk</th>
                    <th class="border border-gray-400 p-2">Kategori</th>
                    <th class="border border-gray-400 p-2 text-center">Stok</th>
                    <th class="border border-gray-400 p-2">Satuan</th>
                    <th class="border border-gray-400 p-2 text-right">Harga Jual</th>
                    
                    <th class="border border-gray-400 p-2 text-center w-20">Status</th>
                    <th class="border border-gray-400 p-2 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $index => $item)

                        @php
                            $stokData = $item->stokTokos->first();
                            $jumlahStok = $stokData ? $stokData->stok_fisik : 0;
                            $bgStok =
                                $jumlahStok <= ($stokData->stok_minimal ?? 0)
                                    ? 'text-red-600 font-bold'
                                    : 'text-green-700';
                            $rowClass = $jumlahStok <= 0 ? 'bg-red-100' : 'hover:bg-yellow-50';
                        @endphp
                    <tr class="{{ $rowClass }} text-xs">
                        <td class="border border-gray-300 p-2 text-center">{{ $produks->firstItem() + $index }}</td>
                        <td class="border border-gray-300 p-2 text-center">
                            @if ($item->gambar_produk)
                                <img src="{{ asset('storage/' . $item->gambar_produk) }}" alt="img"
                                    class="h-8 w-8 object-cover border border-gray-300 mx-auto">
                            @else
                                <span class="text-[10px] text-gray-400 italic">No Img</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2">
                            <div class="font-bold">{{ $item->nama_produk }}</div>
                            <div class="text-[10px] text-gray-500 font-mono">
                                SKU: {{ $item->sku ?? '-' }} | BC: {{ $item->barcode ?? '-' }}
                            </div>
                        </td>
                        <td class="border border-gray-300 p-2">{{ $item->kategori->nama_kategori ?? '-' }}</td>

                        <td class="border border-gray-300 p-2 text-center {{ $bgStok }}">
                            {{ number_format($jumlahStok, 0, ',', '.') }}
                        </td>

                        <td class="border border-gray-300 p-2">
                            {{ $item->satuanKecil->nama_satuan ?? '-' }}
                            @if ($item->id_satuan_besar)
                                <div class="text-[9px] text-blue-600">
                                    1 {{ $item->satuanBesar->nama_satuan }} = {{ $item->nilai_konversi }}
                                    {{ $item->satuanKecil->nama_satuan }}
                                </div>
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2 text-right font-mono font-bold">
                            Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}
                        </td>

                        
                        <td class="border border-gray-300 p-2 text-center">
                            @if ($item->is_active)
                                <span
                                    class="inline-block px-1.5 py-0.5 bg-green-200 text-green-800 border border-green-400 rounded text-[10px] font-bold">
                                    AKTIF
                                </span>
                            @else
                                <span
                                    class="inline-block px-1.5 py-0.5 bg-gray-200 text-gray-600 border border-gray-400 rounded text-[10px] font-bold">
                                    NON-AKTIF
                                </span>
                            @endif
                        </td>

                        <td class="border border-gray-300 p-2 text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('owner.toko.produk.edit', [$toko->id_toko, $item->id_produk]) }}"
                                    class="bg-yellow-400 border border-yellow-600 px-2 py-0.5 text-[10px] hover:bg-yellow-300">EDIT</a>
                                <form action="{{ route('owner.toko.produk.destroy', [$toko->id_toko, $item->id_produk]) }}"
                                    method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white border border-red-800 px-2 py-0.5 text-[10px] hover:bg-red-500">HAPUS</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-4 text-center text-gray-500 italic border border-gray-300">Belum ada
                            data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 text-xs">
        {{ $produks->links() }}
    </div>
@endsection
