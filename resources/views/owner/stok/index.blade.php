@extends('layouts.owner')

@section('title', 'Kelola Stok')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-warehouse"></i> KELOLA STOK PRODUK
    </h2>
    <a href="{{ route('owner.stok.tambah') }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs text-decoration-none">
        <i class="fa fa-plus"></i> TAMBAH STOK
    </a>
</div>

<div class="bg-gray-100 border border-gray-400 p-3 mb-3">
    <form action="" method="GET" class="flex flex-wrap gap-2 items-center" id="filterForm">
        <label class="text-xs font-bold">LOKASI:</label>
        <select name="location_selector" id="location_selector" class="win98-input text-xs w-48">
            <option value="toko|{{ $toko->id_toko }}" {{ $location_type == 'toko' ? 'selected' : '' }}>Toko: {{ $toko->nama_toko }}</option>
            @foreach($gudangs as $gudang)
                <option value="gudang|{{ $gudang->id_gudang }}" {{ $location_type == 'gudang' && $location_id == $gudang->id_gudang ? 'selected' : '' }}>
                    Gudang: {{ $gudang->nama_gudang }}
                </option>
            @endforeach
        </select>
        
        <input type="hidden" name="location_type" id="location_type" value="{{ $location_type }}">
        <input type="hidden" name="location_id" id="location_id" value="{{ $location_id }}">
    </form>
</div>

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
                <th class="border border-gray-400 p-2">SKU</th>
                <th class="border border-gray-400 p-2">Nama Produk</th>
                <th class="border border-gray-400 p-2">Kategori</th>
                <th class="border border-gray-400 p-2 text-right">Stok</th>
                @if($location_type == 'toko')
                    <th class="border border-gray-400 p-2 text-right">Min</th>
                @endif
                <th class="border border-gray-400 p-2 text-center w-24">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produk as $index => $item)
                @php
                    if ($location_type == 'gudang') {
                        $stok = $item->stokGudang->first(); // Since we eager loaded with where id_gudang, this should be fine
                        $stok_fisik = $stok ? $stok->stok_fisik : 0;
                        $stok_minimal = 0; // Gudang usually serves multiple stores, min stock concept might differ
                        $status = $stok_fisik <= 0 ? 'habis' : 'aman';
                    } else {
                        $stok = $item->stokToko->first();
                        $stok_fisik = $stok ? $stok->stok_fisik : 0;
                        $stok_minimal = $stok ? $stok->stok_minimal : 5;
                        $status = $stok_fisik <= 0 ? 'habis' : ($stok_fisik <= $stok_minimal ? 'rendah' : 'aman');
                    }
                @endphp
                <tr class="hover:bg-yellow-50 text-xs">
                    <td class="border border-gray-300 p-2 text-center">{{ $produk->firstItem() + $index }}</td>
                    <td class="border border-gray-300 p-2 font-mono">{{ $item->sku }}</td>
                    <td class="border border-gray-300 p-2">
                        <div class="font-bold">{{ $item->nama_produk }}</div>
                        <div class="text-[10px] text-gray-500">{{ $item->satuanKecil->nama_satuan ?? '-' }}</div>
                    </td>
                    <td class="border border-gray-300 p-2">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    <td class="border border-gray-300 p-2 text-right font-mono font-bold">{{ number_format($stok_fisik) }}</td>
                    @if($location_type == 'toko')
                        <td class="border border-gray-300 p-2 text-right font-mono text-gray-500">{{ number_format($stok_minimal) }}</td>
                    @endif
                    <td class="border border-gray-300 p-2 text-center">
                        @if ($status == 'habis')
                            <span class="px-2 py-0.5 rounded bg-red-200 text-red-800 text-[10px] font-bold">HABIS</span>
                        @elseif ($status == 'rendah')
                            <span class="px-2 py-0.5 rounded bg-yellow-200 text-yellow-800 text-[10px] font-bold">RENDAH</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-green-200 text-green-800 text-[10px] font-bold">AMAN</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $location_type == 'toko' ? '7' : '6' }}" class="p-4 text-center text-gray-500 italic border border-gray-300">
                        Belum ada produk
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs">
    {{ $produk->appends(['location_type' => $location_type, 'location_id' => $location_id])->links() }}
</div>

@push('scripts')
<script>
    document.getElementById('location_selector').addEventListener('change', function() {
        const parts = this.value.split('|');
        document.getElementById('location_type').value = parts[0];
        document.getElementById('location_id').value = parts[1];
        document.getElementById('filterForm').submit();
    });
</script>
@endpush
@endsection
