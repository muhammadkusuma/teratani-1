@extends('layouts.owner')

@section('title', 'Tambah Transaksi Utang Piutang Distributor')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-lg border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-plus-circle text-blue-700"></i> Tambah Transaksi Utang Piutang
    </h2>
    <a href="{{ route('owner.distributor.hutang.index') }}" class="w-full md:w-auto text-center px-4 py-1.5 bg-gray-200 border border-gray-400 hover:bg-gray-300 text-xs font-bold transition-all uppercase">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white border border-gray-300 p-6 shadow-sm rounded-sm">
    <form action="{{ route('owner.distributor.hutang.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Distributor <span class="text-rose-600">*</span></label>
                <select name="id_distributor" required class="w-full border p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none @error('id_distributor') border-rose-500 @else border-gray-300 @enderror">
                    <option value="">-- Pilih Distributor --</option>
                    @foreach($distributors as $d)
                        <option value="{{ $d->id_distributor }}" {{ (old('id_distributor', $selectedDistributorId ?? null) == $d->id_distributor) ? 'selected' : '' }}>
                            {{ $d->nama_distributor }} ({{ $d->toko->nama_toko }})
                        </option>
                    @endforeach
                </select>
                @error('id_distributor')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Tanggal Transaksi <span class="text-rose-600">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full border p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none @error('tanggal') border-rose-500 @else border-gray-300 @enderror">
                @error('tanggal')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div id="jatuh-tempo-wrapper" style="display: none;">
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Tanggal Jatuh Tempo <span class="text-rose-600">*</span></label>
                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" class="w-full border p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none @error('tanggal_jatuh_tempo') border-rose-500 @else border-gray-300 @enderror">
                <p class="text-[9px] text-gray-500 mt-1 italic"><i class="fa fa-info-circle"></i> Tanggal jatuh tempo hanya untuk transaksi utang</p>
                @error('tanggal_jatuh_tempo')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Jenis Transaksi <span class="text-rose-600">*</span></label>
                <select name="jenis_transaksi" id="jenis_transaksi" required class="manual-select2 w-full border p-2 text-xs shadow-inner bg-gray-50 focus:bg-white focus:border-blue-500 transition-all outline-none @error('jenis_transaksi') border-rose-500 @else border-gray-300 @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="utang" {{ old('jenis_transaksi') == 'utang' ? 'selected' : '' }}>Utang (Tambah Tagihan)</option>
                    <option value="pembayaran" {{ old('jenis_transaksi') == 'pembayaran' ? 'selected' : '' }}>Pembayaran (Kurangi Tagihan)</option>
                </select>
                @error('jenis_transaksi')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Nominal (Rp) <span class="text-rose-600">*</span></label>
                <!-- Hidden input for the actual numeric value sent to server -->
                <input type="hidden" name="nominal" id="nominal" value="{{ old('nominal') }}">
                <!-- Visible input for user interaction with formatting -->
                <input type="text" id="nominal_display" value="{{ old('nominal') }}" required placeholder="0" class="w-full border p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none @error('nominal') border-rose-500 @else border-gray-300 @enderror">
                
                @error('nominal')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">No Referensi / Bukti</label>
                <input type="text" name="no_referensi" value="{{ old('no_referensi') }}" maxlength="50" placeholder="No Invoice, Kwitansi, dll" class="w-full border p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none @error('no_referensi') border-rose-500 @else border-gray-300 @enderror">
                @error('no_referensi')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2 mt-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-wider">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="3" class="w-full border p-2 text-xs shadow-inner focus:border-blue-500 transition-all outline-none @error('keterangan') border-rose-500 @else border-gray-300 @enderror" placeholder="Detail transaksi...">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <span class="text-rose-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-3 mt-8 border-t border-gray-100 pt-5">
            <button type="submit" class="w-full md:w-auto bg-blue-700 text-white border border-blue-900 px-8 py-3 text-xs font-black shadow-lg hover:bg-blue-600 hover:scale-[1.02] transition-all rounded-sm uppercase tracking-widest">
                <i class="fa fa-save"></i> Simpan Transaksi
            </button>
            <a href="{{ route('owner.distributor.hutang.index') }}" class="w-full md:w-auto text-center bg-gray-100 text-gray-700 border border-gray-300 px-8 py-3 text-xs font-black hover:bg-gray-200 transition-all rounded-sm uppercase tracking-widest">
                <i class="fa fa-times"></i> Batalkan
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const $jenisSelect = $('#jenis_transaksi');
    const $jatuhTempoWrapper = $('#jatuh-tempo-wrapper');
    const $jatuhTempoInput = $('#tanggal_jatuh_tempo');
    
    // --- Currency Formatting Logic ---
    const $nominalInput = $('#nominal');
    const $nominalDisplay = $('#nominal_display');

    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString();
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    // Initialize display value if hidden input has numeric value (e.g. validasi error)
    if ($nominalInput.val()) {
        let cleanVal = parseFloat($nominalInput.val()).toString();
        $nominalDisplay.val(formatRupiah(cleanVal));
    }

    $nominalDisplay.on('keyup input', function(e) {
        // Update display with formatted value
        $(this).val(formatRupiah($(this).val()));
        
        // Update hidden input with raw numeric value (replace dot with empty string, comma with dot for decimals)
        // Standard Indonesia: 1.000.000,00 -> 1000000.00 (Standard SQL/PHP)
        let rawValue = $(this).val().replace(/\./g, '').replace(/,/g, '.');
        $nominalInput.val(rawValue);
    });
    // ---------------------------------

    // Init manual select2
    $jenisSelect.select2({
        width: '100%',
        placeholder: "-- Pilih Jenis --"
    });

    function toggleJatuhTempo() {
        console.log('Value changed to: ' + $jenisSelect.val());
        if ($jenisSelect.val() === 'utang') {
            $jatuhTempoWrapper.slideDown();
            $jatuhTempoInput.prop('required', true);
        } else {
            $jatuhTempoWrapper.slideUp();
            $jatuhTempoInput.val('');
            $jatuhTempoInput.prop('required', false);
        }
    }
    
    // Listen to Select2 events AND standard change
    $jenisSelect.on('select2:select change', function(e) {
        toggleJatuhTempo();
    });
    
    // Run initial check (delay slightly to ensure Select2 is ready if needed, but not strictly necessary)
    setTimeout(toggleJatuhTempo, 100);
});
</script>
@endpush
@endsection
