@extends('layouts.owner')

@section('title', 'Tambah Stok')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-lg md:text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-plus-circle text-blue-700"></i> Tambah Stok Manual
    </h2>
    <a href="{{ route('owner.stok.index') }}" class="w-full md:w-auto text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-gray-300 p-4 md:p-6 shadow-sm rounded-sm">
        @if ($errors->any())
            <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
                <p class="font-bold text-xs mb-2"><i class="fa fa-exclamation-triangle"></i> Terdapat kesalahan:</p>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.stok.store') }}" method="POST">
            @csrf
            
            {{-- Lokasi Penambahan --}}
            <div class="mb-4">
                <label class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-map-marker-alt"></i> Lokasi Penambahan
                </label>
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center bg-blue-50 p-3 border border-blue-200 rounded-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="location_type" value="toko" checked onchange="toggleLocation(this)" class="w-4 h-4 text-blue-600">
                        <span class="text-xs font-bold text-blue-800"><i class="fa fa-store"></i> Toko Utama</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="location_type" value="gudang" onchange="toggleLocation(this)" class="w-4 h-4 text-blue-600">
                        <span class="text-xs font-bold text-blue-800"><i class="fa fa-warehouse"></i> Gudang</span>
                    </label>
                </div>
            </div>

            {{-- Pilih Gudang (Hidden by default) --}}
            <div id="gudang_select" class="mb-4 hidden">
                <label class="block font-black mb-2 text-[10px] text-purple-700 uppercase tracking-wider">
                    <i class="fa fa-warehouse"></i> Pilih Gudang
                </label>
                <select name="location_id_gudang" id="location_id_gudang" class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-purple-500 focus:ring-1 focus:ring-purple-200 outline-none transition-all rounded-sm">
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id_gudang }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>
            
            <input type="hidden" name="location_id" id="location_id" value="{{ $toko->id_toko }}">

            {{-- Produk --}}
            <div class="mb-4">
                <label for="id_produk" class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-box"></i> Produk <span class="text-red-600">*</span>
                </label>
                <select name="id_produk" id="id_produk" required class="w-full border border-gray-300 p-2.5 md:p-2 text-xs bg-white shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm select2">
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($produk as $item)
                        <option value="{{ $item->id_produk }}" {{ old('id_produk') == $item->id_produk ? 'selected' : '' }}>
                            {{ $item->nama_produk }} ({{ $item->sku }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah Stok --}}
            <div class="mb-4">
                <label for="qty" class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-plus-square"></i> Jumlah Stok (+) <span class="text-red-600">*</span>
                </label>
                <input type="number" name="jumlah" id="qty" required min="1" value="{{ old('jumlah') }}"
                    class="w-full border border-gray-300 p-2.5 md:p-2 text-xs text-right shadow-inner focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200 outline-none transition-all rounded-sm font-bold" placeholder="Masukkan jumlah">
                <small class="text-[10px] text-gray-500 flex items-center gap-1 mt-1">
                    <i class="fa fa-info-circle text-emerald-500"></i> Masukkan jumlah produk yang ingin ditambahkan ke stok
                </small>
            </div>

            {{-- Keterangan --}}
            <div class="mb-6">
                <label for="keterangan" class="block font-black mb-2 text-[10px] text-gray-500 uppercase tracking-wider">
                    <i class="fa fa-sticky-note"></i> Keterangan (Opsional)
                </label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="w-full border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm"
                    placeholder="Contoh: Pembelian dari supplier, Retur, dll.">{{ old('keterangan') }}</textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col md:flex-row justify-end gap-2 pt-4 border-t border-gray-200">
                <a href="{{ route('owner.stok.index') }}" class="w-full md:w-auto text-center px-6 py-2.5 md:py-2 bg-gray-100 text-gray-700 border border-gray-300 text-xs font-bold hover:bg-gray-200 transition-colors shadow-sm rounded-sm uppercase no-underline">
                    <i class="fa fa-times"></i> Batal
                </a>
                <button type="submit" class="w-full md:w-auto px-6 py-2.5 md:py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 font-bold text-xs transition-all rounded-sm uppercase">
                    <i class="fa fa-save"></i> Simpan Stok
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
