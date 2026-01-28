@extends('layouts.owner')

@section('title', 'Master Data Produk')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
        <div>
            <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 inline-block uppercase tracking-tight">
                <i class="fa fa-box text-blue-700"></i> Data Produk
            </h2>
            <span class="text-xs text-gray-500 block md:inline md:ml-2 mt-1 md:mt-0">Toko: {{ $toko->nama_toko }}</span>
        </div>
        <a href="{{ route('owner.toko.produk.create', $toko->id_toko) }}"
            class="w-full md:w-auto text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-plus"></i> Tambah Produk
        </a>
    </div>

    {{-- Store Selector --}}
    <div class="mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-600 p-4 rounded-sm shadow-sm">
        <label class="block text-[10px] font-black text-gray-600 uppercase mb-2 tracking-wider">
            <i class="fa fa-store text-blue-600"></i> Pilih Toko untuk Melihat Produk
        </label>
        <form action="{{ route('owner.toko.produk.index', $toko->id_toko) }}" method="GET" id="tokoSelectorForm">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <select name="selected_toko" 
                    class="w-full md:w-1/2 border-2 border-blue-300 p-2.5 text-sm font-bold shadow-inner bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all rounded-sm"
                    onchange="this.form.submit()">
                @foreach($allTokos as $t)
                    <option value="{{ $t->id_toko }}" {{ $selectedToko->id_toko == $t->id_toko ? 'selected' : '' }}>
                        {{ $t->nama_toko }} {{ $t->id_toko == $toko->id_toko ? '(Toko Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
        @if($selectedToko->id_toko != $toko->id_toko)
            <div class="mt-2 text-xs text-blue-700 font-bold">
                <i class="fa fa-info-circle"></i> Sedang menampilkan produk dari: <span class="text-blue-900">{{ $selectedToko->nama_toko }}</span>
            </div>
        @endif
    </div>

    {{-- Search Form --}}
    <form action="{{ route('owner.toko.produk.index', $toko->id_toko) }}" method="GET" class="mb-3 md:mb-4 flex flex-col sm:flex-row gap-2">
        <input type="hidden" name="selected_toko" value="{{ request('selected_toko', $toko->id_toko) }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / SKU / Barcode..."
            class="flex-1 border border-gray-300 p-2.5 md:p-2 text-xs shadow-inner focus:border-blue-500 focus:ring-1 focus:ring-blue-200 outline-none transition-all rounded-sm">
        <button type="submit" class="px-4 py-2.5 md:py-2 bg-gray-600 text-white border border-gray-800 text-xs font-bold hover:bg-gray-500 transition-all shadow-md rounded-sm uppercase">
            <i class="fa fa-search"></i> Cari
        </button>
        @if (request('search'))
            <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}?selected_toko={{ request('selected_toko', $toko->id_toko) }}"
                class="px-4 py-2.5 md:py-2 bg-red-600 text-white border border-red-800 text-xs font-bold hover:bg-red-500 transition-all shadow-md rounded-sm uppercase text-center">
                <i class="fa fa-times"></i> Reset
            </a>
        @endif
    </form>

    @if (session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Mobile Card View --}}
    <div class="block md:hidden space-y-3 mb-4">
        @forelse($produks as $index => $item)
            @php
                // Get current toko stock
                $currentStokData = collect($item->stokTokos)->firstWhere('id_toko', $selectedToko->id_toko);
                $stokToko = $currentStokData ? $currentStokData->stok_fisik : 0;
                $stokGudangTotal = $item->total_stok_gudang ?? 0;
                $jumlahStok = $stokToko + $stokGudangTotal;
                $bgStok = $jumlahStok <= ($currentStokData->stok_minimal ?? 0) ? 'text-red-600 font-bold' : 'text-emerald-700';
                $cardBg = $jumlahStok <= 0 ? 'from-red-50 to-white border-red-500' : 'from-white to-gray-50 border-blue-500';
            @endphp
            <div class="bg-gradient-to-br {{ $cardBg }} border-l-4 p-3 shadow-sm rounded-sm">
                <div class="flex gap-3 mb-2">
                    {{-- Image --}}
                    <div class="flex-shrink-0">
                        @if ($item->gambar_produk)
                            <img src="{{ asset('storage/' . $item->gambar_produk) }}" alt="img"
                                class="h-16 w-16 object-cover border border-gray-300 rounded-sm">
                        @else
                            <div class="h-16 w-16 bg-gray-100 border border-gray-300 rounded-sm flex items-center justify-center">
                                <i class="fa fa-image text-gray-300 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Product Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2 mb-1">
                            <h3 class="font-black text-sm text-gray-800 line-clamp-2">{{ $item->nama_produk }}</h3>
                            @if ($item->is_active)
                                <span class="px-2 py-0.5 bg-emerald-200 text-emerald-800 border border-emerald-400 rounded text-[10px] font-bold whitespace-nowrap">AKTIF</span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-200 text-gray-600 border border-gray-400 rounded text-[10px] font-bold whitespace-nowrap">NON-AKTIF</span>
                            @endif
                        </div>
                        <p class="text-[10px] font-mono text-gray-500 mb-1">
                            SKU: {{ $item->sku ?? '-' }} | BC: {{ $item->barcode ?? '-' }}
                        </p>
                        <p class="text-xs text-blue-600 font-semibold"><i class="fa fa-tag"></i> {{ $item->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                </div>
                
                {{-- Stok & Price Info --}}
                <div class="grid grid-cols-2 gap-2 mb-2 pt-2 border-t border-gray-200">
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Stok</span>
                        <div class="font-mono font-bold {{ $bgStok }} text-sm">{{ number_format($jumlahStok, 0, ',', '.') }}</div>
                        @if($stokGudangTotal > 0)
                            <div class="text-[9px] text-gray-500">Toko: {{ $stokToko }} | Gudang: {{ $stokGudangTotal }}</div>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Harga Jual</span>
                        <div class="font-mono font-bold text-amber-600 text-sm">Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}</div>
                        <div class="text-[9px] text-gray-600">{{ $item->satuanKecil->nama_satuan ?? '-' }}</div>
                    </div>
                </div>
                
                @if ($item->id_satuan_besar)
                    <div class="text-[9px] text-blue-600 bg-blue-50 p-1 rounded-sm mb-2">
                        <i class="fa fa-exchange-alt"></i> 1 {{ $item->satuanBesar->nama_satuan }} = {{ $item->nilai_konversi }} {{ $item->satuanKecil->nama_satuan }}
                    </div>
                @endif
                
                {{-- Action Buttons --}}
                <div class="grid grid-cols-2 gap-1 pt-2 border-t border-gray-200">
                    <a href="{{ route('owner.toko.produk.edit', [$toko->id_toko, $item->id_produk]) }}"
                        class="text-center bg-amber-400 text-black border border-amber-600 px-2 py-1.5 text-[10px] font-bold hover:bg-amber-300 transition-colors rounded-sm uppercase shadow-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('owner.toko.produk.destroy', [$toko->id_toko, $item->id_produk]) }}"
                        method="POST" onsubmit="return confirm('Hapus produk ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full bg-red-600 text-white border border-red-800 px-2 py-1.5 text-[10px] font-bold hover:bg-red-500 transition-colors rounded-sm uppercase shadow-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white border border-gray-300 rounded-sm">
                <i class="fa fa-box text-gray-200 text-5xl block mb-3"></i>
                <p class="text-gray-400 italic text-sm">Belum ada data produk</p>
            </div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block overflow-x-auto border border-gray-300 bg-white rounded-sm shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                    <th class="border border-blue-900 p-3 text-center w-12">No</th>
                    <th class="border border-blue-900 p-3 text-center w-20">Img</th>
                    <th class="border border-blue-900 p-3">Nama Produk</th>
                    <th class="border border-blue-900 p-3">Kategori</th>
                    <th class="border border-blue-900 p-3 text-center">Stok</th>
                    <th class="border border-blue-900 p-3 text-center">Toko Lain</th>
                    <th class="border border-blue-900 p-3 text-center">Gudang</th>
                    <th class="border border-blue-900 p-3">Satuan</th>
                    <th class="border border-blue-900 p-3 text-right">Harga Jual</th>
                    <th class="border border-blue-900 p-3 text-center w-24">Status</th>
                    <th class="border border-blue-900 p-3 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $index => $item)
                    @php
                        // Get current toko stock
                        $currentStokData = collect($item->stokTokos)->firstWhere('id_toko', $selectedToko->id_toko);
                        $stokToko = $currentStokData ? $currentStokData->stok_fisik : 0;
                        $stokGudangTotal = $item->total_stok_gudang ?? 0;
                        $jumlahStok = $stokToko + $stokGudangTotal;
                        $bgStok = $jumlahStok <= ($currentStokData->stok_minimal ?? 0) ? 'text-red-600 font-bold' : 'text-emerald-700';
                        $rowClass = $jumlahStok <= 0 ? 'bg-red-50' : 'hover:bg-blue-50';
                    @endphp
                    <tr class="{{ $rowClass }} transition-colors text-xs border-b border-gray-200">
                        <td class="p-3 text-center font-bold text-gray-400">{{ $produks->firstItem() + $index }}</td>
                        <td class="p-3 text-center">
                            @if ($item->gambar_produk)
                                <img src="{{ asset('storage/' . $item->gambar_produk) }}" alt="img"
                                    class="h-12 w-12 object-cover border border-gray-300 mx-auto rounded-sm">
                            @else
                                <div class="h-12 w-12 bg-gray-100 border border-gray-300 mx-auto rounded-sm flex items-center justify-center">
                                    <i class="fa fa-image text-gray-300"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="font-bold text-gray-800">{{ $item->nama_produk }}</div>
                            <div class="text-[10px] text-gray-500 font-mono">
                                SKU: {{ $item->sku ?? '-' }} | BC: {{ $item->barcode ?? '-' }}
                            </div>
                        </td>
                        <td class="p-3 text-gray-700">{{ $item->kategori->nama_kategori ?? '-' }}</td>

                        <td class="p-3 text-center {{ $bgStok }}">
                            <div class="font-mono font-bold">{{ number_format($jumlahStok, 0, ',', '.') }}</div>
                            @if($stokGudangTotal > 0)
                                <div class="text-[9px] text-gray-500">Toko: {{ $stokToko }} | Gudang: {{ $stokGudangTotal }}</div>
                            @endif
                        </td>

                        {{-- Toko Lain Column --}}
                        <td class="p-3 text-center">
                            @php
                                // Get stocks from other stores (excluding selected toko)
                                $otherStores = collect($item->stokTokos)->filter(function($stock) use ($selectedToko) {
                                    return $stock->id_toko != $selectedToko->id_toko;
                                });
                            @endphp
                            @if($otherStores->isNotEmpty())
                                @foreach($otherStores as $otherStock)
                                    <div class="text-[9px] {{ $otherStock->stok_fisik > 0 ? 'text-blue-600 font-bold' : 'text-gray-400' }} mb-1">
                                        <span class="inline-block px-1.5 py-0.5 bg-blue-50 border border-blue-200 rounded-sm">
                                            {{ $otherStock->toko->nama_toko ?? 'N/A' }}: 
                                            <span class="font-mono">{{ number_format($otherStock->stok_fisik, 0, ',', '.') }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-[9px] text-gray-400 italic">-</div>
                            @endif
                        </td>

                        {{-- Gudang Column --}}
                        <td class="p-3 text-center">
                            @php
                                $warehouses = collect($item->stokGudangs ?? []);
                            @endphp
                            @if($warehouses->isNotEmpty())
                                @foreach($warehouses as $gudangStock)
                                    <div class="text-[9px] {{ $gudangStock->stok_fisik > 0 ? 'text-green-600 font-bold' : 'text-gray-400' }} mb-1">
                                        <span class="inline-block px-1.5 py-0.5 bg-green-50 border border-green-200 rounded-sm">
                                            {{ $gudangStock->gudang->nama_gudang ?? 'N/A' }}: 
                                            <span class="font-mono">{{ number_format($gudangStock->stok_fisik, 0, ',', '.') }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-[9px] text-gray-400 italic">-</div>
                            @endif
                        </td>

                        <td class="p-3 text-gray-700">
                            {{ $item->satuanKecil->nama_satuan ?? '-' }}
                            @if ($item->id_satuan_besar)
                                <div class="text-[9px] text-blue-600">
                                    1 {{ $item->satuanBesar->nama_satuan }} = {{ $item->nilai_konversi }}
                                    {{ $item->satuanKecil->nama_satuan }}
                                </div>
                            @endif
                        </td>
                        <td class="p-3 text-right font-mono font-bold text-amber-600">
                            Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}
                        </td>

                        <td class="p-3 text-center">
                            @if ($item->is_active)
                                <span class="inline-block px-2 py-0.5 bg-emerald-200 text-emerald-800 border border-emerald-400 rounded text-[10px] font-bold">
                                    AKTIF
                                </span>
                            @else
                                <span class="inline-block px-2 py-0.5 bg-gray-200 text-gray-600 border border-gray-400 rounded text-[10px] font-bold">
                                    NON-AKTIF
                                </span>
                            @endif
                        </td>

                        <td class="p-3 text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('owner.toko.produk.edit', [$toko->id_toko, $item->id_produk]) }}"
                                    class="bg-amber-400 text-black border border-amber-600 px-2 py-1 text-[10px] font-bold hover:bg-amber-300 transition-colors rounded-sm uppercase shadow-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('owner.toko.produk.destroy', [$toko->id_toko, $item->id_produk]) }}"
                                    method="POST" onsubmit="return confirm('Hapus produk ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white border border-red-800 px-2 py-1 text-[10px] font-bold hover:bg-red-500 transition-colors rounded-sm uppercase shadow-sm">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="p-8 text-center border border-gray-300">
                            <i class="fa fa-box text-gray-200 text-5xl block mb-3"></i>
                            <p class="text-gray-400 italic text-sm">Belum ada data produk</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 md:mt-4 text-xs">
        {{ $produks->links() }}
    </div>
@endsection
