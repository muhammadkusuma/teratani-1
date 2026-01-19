@extends('layouts.owner')

@section('title', 'Tambah Stok')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-plus-circle"></i> TAMBAH STOK MANUAL
    </h2>
    <a href="{{ route('owner.stok.index') }}" class="px-3 py-1 bg-gray-200 text-gray-700 border border-gray-400 shadow hover:bg-gray-300 text-xs text-decoration-none">
        <i class="fa fa-arrow-left"></i> KEMBALI
    </a>
</div>

<div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto bg-gray-100 border border-gray-400 p-6">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 text-xs">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.stok.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-xs font-bold mb-1">LOKASI PENAMBAHAN</label>
                <div class="flex gap-4 items-center">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="location_type" value="toko" checked onchange="toggleLocation(this)"> Toko Utama
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="location_type" value="gudang" onchange="toggleLocation(this)"> Gudang
                    </label>
                </div>
            </div>

            <div id="gudang_select" class="mb-4 hidden">
                <label class="block text-xs font-bold mb-1">PILIH GUDANG</label>
                <select name="location_id_gudang" id="location_id_gudang" class="win98-input w-full text-xs">
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id_gudang }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>
            
            <input type="hidden" name="location_id" id="location_id" value="{{ $toko->id_toko }}">

            <div class="mb-4">
                <label for="id_produk" class="block text-xs font-bold mb-1">PRODUK</label>
                <select name="id_produk" id="id_produk" required class="win98-input w-full text-xs select2">
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($produk as $item)
                        <option value="{{ $item->id_produk }}" {{ old('id_produk') == $item->id_produk ? 'selected' : '' }}>
                            {{ $item->nama_produk }} ({{ $item->sku }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="qty" class="block text-xs font-bold mb-1">JUMLAH STOK (+) </label>
                <input type="number" name="jumlah" id="qty" required min="1" value="{{ old('jumlah') }}"
                    class="win98-input w-full text-xs" placeholder="Masukkan jumlah">
            </div>

            <div class="mb-6">
                <label for="keterangan" class="block text-xs font-bold mb-1">KETERANGAN (OPSIONAL)</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="win98-input w-full text-xs"
                    placeholder="Contoh: Pembelian dari supplier, Retur, dll.">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-700 text-white border-2 border-blue-900 shadow hover:bg-blue-600 font-bold text-sm">
                    <i class="fas fa-save"></i> SIMPAN STOK
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleLocation(radio) {
        const gudangSelect = document.getElementById('gudang_select');
        const locationId = document.getElementById('location_id');
        const gudangId = document.getElementById('location_id_gudang');
        
        if (radio.value === 'gudang') {
            gudangSelect.classList.remove('hidden');
            locationId.value = gudangId.value;
            
            gudangId.addEventListener('change', function() {
                locationId.value = this.value;
            });
        } else {
            gudangSelect.classList.add('hidden');
            locationId.value = "{{ $toko->id_toko }}";
        }
    }
</script>
@endpush
@endsection
