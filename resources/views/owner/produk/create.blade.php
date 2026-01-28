@extends('layouts.owner')

@section('title', 'Tambah Produk')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-6">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight text-blue-900">
        <i class="fa fa-plus-circle text-blue-700"></i> Tambah Produk Baru
    </h2>
    <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}" class="w-full md:w-auto text-center px-4 py-1.5 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs font-bold transition-all uppercase tracking-wider rounded-sm text-gray-700">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white border border-gray-300 p-6 shadow-lg rounded-sm">
    <form action="{{ route('owner.toko.produk.store', $toko->id_toko) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- SECTION: INFORMASI DASAR --}}
                <div>
                    <div class="flex items-center gap-2 mb-3 border-b border-gray-100 pb-2">
                        <i class="fa fa-box text-blue-700"></i>
                        <h3 class="font-black text-xs uppercase tracking-widest text-gray-700">Informasi Dasar</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">Nama Produk <span class="text-rose-600">*</span></label>
                            <input type="text" name="nama_produk" required
                                class="w-full border border-gray-300 p-2.5 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 outline-none transition-all rounded-sm font-bold uppercase placeholder:normal-case"
                                placeholder="Contoh: BERAS ROJO LELE 5KG">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">Kategori</label>
                                <select name="id_kategori" class="w-full border border-gray-300 p-2.5 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 outline-none transition-all rounded-sm font-bold">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kat)
                                        <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">Foto Produk</label>
                                <input type="file" name="gambar_produk" 
                                    class="w-full border border-gray-300 text-[10px] bg-gray-50 file:mr-3 file:py-2 file:px-4 file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-wider file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 rounded-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-4 bg-gray-50 border border-gray-200 rounded-sm dashed-border">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-wider italic">Kode SKU (Opsional)</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fa fa-barcode text-gray-400"></i>
                                    </div>
                                    <input type="text" name="sku" 
                                        class="w-full border border-gray-300 pl-9 p-2.5 text-xs shadow-sm bg-white focus:border-blue-500 outline-none rounded-sm font-mono" 
                                        placeholder="Kode Unik">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-wider italic">Scan Barcode (Opsional)</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fa fa-qrcode text-gray-400"></i>
                                    </div>
                                    <input type="text" name="barcode" 
                                        class="w-full border border-gray-300 pl-9 p-2.5 text-xs shadow-sm bg-white focus:border-blue-500 outline-none rounded-sm font-mono" 
                                        placeholder="Scan alat...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION: SATUAN --}}
                <div class="pt-4">
                    <div class="flex items-center gap-2 mb-3 border-b border-gray-100 pb-2">
                        <i class="fa fa-ruler-combined text-purple-700"></i>
                        <h3 class="font-black text-xs uppercase tracking-widest text-gray-700">Satuan & Konversi</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">
                                Satuan Eceran (Kecil) <span class="text-rose-600">*</span>
                            </label>
                            <select name="id_satuan_kecil" required class="satuan-select2 w-full">
                                @if(isset($satuans['kecil']))
                                    <optgroup label="━━ SATUAN ECERAN ━━">
                                        @foreach ($satuans['kecil'] as $sat)
                                            <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if(isset($satuans['besar']))
                                    <optgroup label="━━ SATUAN GROSIR ━━">
                                        @foreach ($satuans['besar'] as $sat)
                                            <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                        
                        <div class="p-4 bg-purple-50 rounded-sm border border-purple-100">
                             <label class="block text-[10px] font-black text-purple-800 uppercase mb-2 tracking-wider italic">
                                <i class="fa fa-cubes"></i> Jual Paket/Grosir?
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <select name="id_satuan_besar" class="satuan-select2 w-full">
                                        <option value="">- Tidak Ada -</option>
                                        @if(isset($satuans['besar']))
                                            <optgroup label="━━ SATUAN GROSIR (PAKET) ━━">
                                                @foreach ($satuans['besar'] as $sat)
                                                    <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                        @if(isset($satuans['kecil']))
                                            <optgroup label="━━ SATUAN ECERAN ━━">
                                                @foreach ($satuans['kecil'] as $sat)
                                                    <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                </div>
                                <div class="relative">
                                    <input type="number" name="nilai_konversi" value="1" min="1" 
                                        class="w-full border border-purple-200 text-xs p-2 text-center shadow-sm focus:border-purple-500 outline-none rounded-sm font-bold text-purple-900" 
                                        placeholder="Isi Qty">
                                    <span class="absolute right-2 top-2 text-[9px] text-purple-400 font-bold uppercase">Pcs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="space-y-6">
                
                {{-- CARD: MODAL BELI --}}
                <div class="bg-emerald-50/50 border border-emerald-100 rounded-sm p-4">
                     <div class="flex items-center gap-2 mb-3 border-b border-emerald-200 pb-2">
                        <i class="fa fa-calculator text-emerald-700"></i>
                        <h3 class="font-black text-xs uppercase tracking-widest text-emerald-800">Kalkulasi Modal</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] font-black text-gray-400 uppercase mb-1 tracking-wider italic">Min</label>
                                <input type="number" id="harga_min" oninput="hitungModalTengah()" 
                                    class="w-full border border-gray-300 p-2 text-xs text-right shadow-inner bg-white focus:border-emerald-500 outline-none rounded-sm" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-gray-400 uppercase mb-1 tracking-wider italic">Max</label>
                                <input type="number" id="harga_max" oninput="hitungModalTengah()" 
                                    class="w-full border border-gray-300 p-2 text-xs text-right shadow-inner bg-white focus:border-emerald-500 outline-none rounded-sm" placeholder="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1 tracking-wider">MODAL (RATA-RATA)</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-emerald-600 font-bold text-xs">Rp</span>
                                </div>
                                <input type="number" name="harga_beli" id="harga_beli" value="0" readonly
                                    class="w-full border-2 border-emerald-400 bg-white pl-10 pr-4 py-2 text-lg font-black text-emerald-700 shadow-sm rounded-sm text-right focus:outline-none cursor-not-allowed">
                            </div>
                            <p class="text-[9px] text-emerald-500 mt-1 text-right italic font-medium">*Auto-calculated</p>
                        </div>
                    </div>
                </div>

                {{-- CARD: HARGA JUAL --}}
                <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                     <div class="flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                        <i class="fa fa-tags text-blue-700"></i>
                        <h3 class="font-black text-xs uppercase tracking-widest text-gray-700">Harga Jual</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-600 uppercase mb-1 tracking-wider">Harga Umum (Eceran)</label>
                            <input type="number" name="harga_jual_umum" required
                                class="w-full border border-blue-300 bg-white p-2.5 text-sm text-right font-black text-blue-900 shadow-inner focus:border-blue-500 outline-none rounded-sm" placeholder="0">
                        </div>
                        
                        <div class="border-t border-dashed border-gray-300 my-2"></div>

                        <div class="space-y-3">
                            <div>
                                <label class="flex justify-between text-[9px] font-black text-gray-400 uppercase tracking-wider italic">
                                    <span>Harga Grosir</span>
                                    <span class="bg-gray-200 px-1 rounded-sm text-gray-500">Opsional</span>
                                </label>
                                <input type="number" name="harga_jual_grosir" 
                                    class="w-full border border-gray-300 bg-white p-2 text-xs text-right font-bold text-gray-700 shadow-inner focus:border-blue-500 outline-none rounded-sm mt-1" placeholder="0">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-1 tracking-wider italic">Harga R1</label>
                                    <input type="number" name="harga_r1" 
                                        class="w-full border border-gray-300 bg-white p-2 text-xs text-right font-bold text-gray-700 shadow-inner focus:border-blue-500 outline-none rounded-sm" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-1 tracking-wider italic">Harga R2</label>
                                    <input type="number" name="harga_r2" 
                                        class="w-full border border-gray-300 bg-white p-2 text-xs text-right font-bold text-gray-700 shadow-inner focus:border-blue-500 outline-none rounded-sm" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD: STOK INIT --}}
                <div class="bg-amber-50/50 border border-amber-100 rounded-sm p-4">
                     <div class="flex items-center gap-2 mb-3 border-b border-amber-200 pb-2">
                        <i class="fa fa-warehouse text-amber-700"></i>
                        <h3 class="font-black text-xs uppercase tracking-widest text-amber-800">Lokasi & Stok Awal</h3>
                    </div>

                    <div class="space-y-3">
                         <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">Lokasi Stok</label>
                            <select name="lokasi_stok_awal" class="w-full border border-amber-200 bg-white p-2 text-xs shadow-sm font-bold text-amber-900 focus:border-amber-500 outline-none rounded-sm">
                                <option value="toko">TOKO (ETALASE DEPAN)</option>
                                @foreach ($gudangs as $g)
                                    <option value="{{ $g->id_gudang }}">{{ $g->nama_gudang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider italic">Jumlah Stok Awal</label>
                            <input type="number" name="stok_awal" required min="1"
                                class="w-full border border-amber-200 bg-white p-2 text-right font-black text-lg text-amber-800 shadow-inner focus:border-amber-500 outline-none rounded-sm" placeholder="0">
                        </div>
                    </div>
                </div>

                {{-- CARD: STATUS --}}
                <div class="bg-gray-100 border border-gray-200 rounded-sm p-4 flex items-center justify-between">
                     <span class="text-xs font-black text-gray-600 uppercase tracking-wider">Status Produk</span>
                     <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" class="sr-only peer" checked>
                        <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-2 text-[10px] font-bold text-gray-700 uppercase tracking-wider">Aktif</span>
                    </label>
                </div>

            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col-reverse sm:flex-row justify-end gap-3 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 sm:static sm:bg-transparent sm:p-0 z-10 mx-[-1.5rem] px-6 sm:mx-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sm:shadow-none">
            <a href="{{ route('owner.toko.produk.index', $toko->id_toko) }}" 
               class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-300 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-sm font-black text-xs uppercase tracking-widest text-center transition-all">
                Batal
            </a>
            <button type="submit" 
                class="w-full sm:w-auto px-10 py-3 bg-blue-700 text-white rounded-sm font-black text-xs uppercase tracking-widest shadow-lg hover:bg-blue-600 hover:shadow-xl active:scale-95 transition-all flex justify-center items-center gap-2">
                <i class="fa fa-save"></i> Simpan Data Produk
            </button>
        </div>

    </form>
</div>

{{-- Scripts --}}
<script>
    $(document).ready(function() {
        $('.satuan-select2').select2({
            placeholder: '-- Pilih --',
            allowClear: false,
            width: '100%',
        });
    });

    function hitungModalTengah() {
        let min = parseFloat(document.getElementById('harga_min').value) || 0;
        let max = parseFloat(document.getElementById('harga_max').value) || 0;
        
        let avg = 0;
        if (min > 0 && max > 0) {
            avg = Math.ceil((min + max) / 2);
        } else if (min > 0) avg = min;
        else if (max > 0) avg = max;

        document.getElementById('harga_beli').value = avg;
    }
</script>

<style>
    /* Custom Styling for Select2 to match the Admin Theme */
    .select2-container .select2-selection--single {
        height: 38px !important;
        border-color: #d1d5db !important; /* gray-300 */
        border-radius: 0.125rem !important; /* rounded-sm */
        padding-top: 5px !important;
        background-color: #f9fafb !important; /* gray-50 */
        font-weight: 700 !important;
        font-size: 0.75rem !important; /* text-xs */
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-dropdown {
        border-color: #d1d5db !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
    }
</style>
@endsection
