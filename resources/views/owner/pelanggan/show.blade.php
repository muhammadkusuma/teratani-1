@extends('layouts.owner')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
    <h2 class="font-bold text-xl border-b-4 border-blue-600 pb-1 pr-6 uppercase tracking-tight">
        <i class="fa fa-user text-blue-700"></i> Detail Pelanggan
    </h2>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <a href="{{ route('owner.pelanggan.edit', $pelanggan->id_pelanggan) }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-amber-500 text-white border border-amber-700 shadow-md hover:bg-amber-400 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-edit"></i> Edit
        </a>
        <a href="{{ route('owner.pelanggan.index') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-gray-600 text-white border border-gray-800 shadow-md hover:bg-gray-500 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 mb-4 rounded-sm shadow-sm">
        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

{{-- Informasi Pelanggan --}}
<div class="bg-white border border-gray-300 p-4 md:p-6 mb-4 shadow-sm rounded-sm">
    <h3 class="font-black text-sm border-b-2 border-blue-600 pb-2 mb-4 text-gray-900 uppercase tracking-wider">
        <i class="fa fa-info-circle text-blue-600"></i> Informasi Pelanggan
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 text-xs">
        <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 p-3 rounded-sm">
            <span class="font-black text-[10px] text-blue-700 uppercase tracking-wider block mb-1">Kode Pelanggan</span>
            <div class="font-mono bg-white p-2 mt-1 border border-blue-100 text-blue-900 font-bold tracking-tighter rounded-sm">{{ $pelanggan->kode_pelanggan }}</div>
        </div>
        <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 p-3 rounded-sm">
            <span class="font-black text-[10px] text-gray-600 uppercase tracking-wider block mb-1">Nama Pelanggan</span>
            <div class="bg-white p-2 mt-1 border border-gray-200 font-bold text-gray-800 rounded-sm">{{ $pelanggan->nama_pelanggan }}</div>
        </div>
        <div class="bg-gradient-to-br from-indigo-50 to-white border border-indigo-200 p-3 rounded-sm">
            <span class="font-black text-[10px] text-indigo-700 uppercase tracking-wider block mb-1">Toko</span>
            <div class="bg-white p-2 mt-1 border border-indigo-100 text-indigo-700 font-bold rounded-sm">{{ $pelanggan->toko->nama_toko }}</div>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-200 p-3 rounded-sm">
            <span class="font-black text-[10px] text-emerald-700 uppercase tracking-wider block mb-1">Kategori Harga</span>
            <div class="bg-white p-2 mt-1 border border-emerald-100 rounded-sm">
                @if($pelanggan->kategori_harga == 'umum')
                    <span class="px-2 py-1 rounded bg-gray-200 text-gray-800 text-[10px] font-bold">UMUM</span>
                @elseif($pelanggan->kategori_harga == 'grosir')
                    <span class="px-2 py-1 rounded bg-blue-200 text-blue-800 text-[10px] font-bold">GROSIR</span>
                @elseif($pelanggan->kategori_harga == 'r1')
                    <span class="px-2 py-1 rounded bg-purple-200 text-purple-800 text-[10px] font-bold">R1</span>
                @else
                    <span class="px-2 py-1 rounded bg-green-200 text-green-800 text-[10px] font-bold">R2</span>
                @endif
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-teal-50 to-white border border-teal-200 p-3 rounded-sm">
            <span class="font-black text-[10px] text-teal-700 uppercase tracking-wider block mb-1">No. HP</span>
            <div class="bg-white p-2 mt-1 border border-teal-100 text-gray-700 rounded-sm">
                <i class="fa fa-phone text-teal-500"></i> {{ $pelanggan->no_hp ?? '-' }}
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-white border border-purple-200 p-3 rounded-sm">
            <span class="font-black text-[10px] text-purple-700 uppercase tracking-wider block mb-1">Wilayah</span>
            <div class="bg-white p-2 mt-1 border border-purple-100 text-gray-700 rounded-sm">
                <i class="fa fa-map-marker-alt text-purple-400"></i> {{ $pelanggan->wilayah ?? '-' }}
            </div>
        </div>
        <div class="md:col-span-2 bg-gradient-to-br from-slate-50 to-white border border-slate-200 p-3 rounded-sm">
            <span class="font-black text-[10px] text-slate-600 uppercase tracking-wider block mb-1">Alamat</span>
            <div class="bg-white p-2 mt-1 border border-slate-100 text-gray-700 rounded-sm">{{ $pelanggan->alamat ?? '-' }}</div>
        </div>
        <div class="md:col-span-2 bg-gradient-to-br from-amber-50 to-white border border-amber-200 p-3 rounded-sm">
            <span class="font-black text-[10px] text-amber-700 uppercase tracking-wider block mb-1">Limit Piutang</span>
            <div class="bg-white p-2 mt-1 border border-amber-100 font-black text-amber-600 rounded-sm">
                <i class="fa fa-credit-card"></i> Rp {{ number_format($pelanggan->limit_piutang, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

{{-- Saldo Piutang --}}
<div class="bg-gradient-to-br {{ $saldoPiutang > 0 ? 'from-blue-600 to-blue-700' : 'from-emerald-600 to-emerald-700' }} border {{ $saldoPiutang > 0 ? 'border-blue-800' : 'border-emerald-800' }} p-6 mb-4 shadow-md rounded-sm">
    <h3 class="font-black text-xs text-white opacity-90 uppercase tracking-wider mb-3 border-b border-white/20 pb-2">
        <i class="fa fa-wallet"></i> Saldo Piutang Saat Ini
    </h3>
    <div class="text-center">
        <div class="text-3xl md:text-4xl font-black text-white">
            Rp {{ number_format($saldoPiutang, 0, ',', '.') }}
        </div>
        @if($saldoPiutang > 0)
            <div class="text-xs text-white/90 mt-2">Pelanggan memiliki piutang</div>
            @if($saldoPiutang > $pelanggan->limit_piutang)
                <div class="bg-red-500 text-white text-xs mt-2 px-3 py-1 rounded-full inline-block font-bold shadow-md">
                    <i class="fa fa-exclamation-triangle"></i> Melebihi limit piutang!
                </div>
            @endif
        @else
            <div class="text-xs text-white/90 mt-2"><i class="fa fa-check-circle"></i> Tidak ada piutang</div>
        @endif
    </div>
</div>

{{-- Riwayat Piutang --}}
<div class="bg-white border border-gray-300 p-4 md:p-6 shadow-sm rounded-sm">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 pb-3 border-b border-gray-200">
        <h3 class="font-black text-sm text-gray-900 uppercase tracking-wider">
            <i class="fa fa-history text-blue-600"></i> Riwayat Piutang
        </h3>
        <a href="{{ route('owner.pelanggan.piutang.create', ['id_pelanggan' => $pelanggan->id_pelanggan]) }}" class="w-full md:w-auto text-center px-4 py-2 bg-blue-700 text-white border border-blue-900 shadow-md hover:bg-blue-600 text-xs font-bold transition-all rounded-sm uppercase no-underline">
            <i class="fa fa-plus"></i> Tambah Transaksi
        </a>
    </div>

    {{-- Mobile Card View --}}
    <div class="block md:hidden space-y-3 mb-4">
        @forelse($piutang as $key => $row)
        <div class="bg-gradient-to-br from-white to-gray-50 border-l-4 {{ $row->jenis_transaksi == 'piutang' ? 'border-blue-500' : 'border-emerald-500' }} p-3 shadow-sm rounded-sm">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="text-[10px] font-mono text-gray-500">{{ $row->tanggal->format('d/m/Y') }}</span>
                    <h4 class="font-black text-sm text-gray-800">{{ $row->no_referensi ?? '-' }}</h4>
                </div>
                @if($row->jenis_transaksi == 'piutang')
                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-[10px] font-bold">PIUTANG</span>
                @else
                    <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">PEMBAYARAN</span>
                @endif
            </div>
            <p class="text-xs text-gray-600 mb-2">{{ $row->keterangan ?? '-' }}</p>
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-200">
                <div>
                    <span class="text-[10px] text-gray-500 uppercase font-bold">Nominal</span>
                    <div class="font-bold text-sm {{ $row->jenis_transaksi == 'piutang' ? 'text-blue-600' : 'text-emerald-600' }}">
                        {{ $row->jenis_transaksi == 'piutang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 uppercase font-bold">Saldo</span>
                    <div class="font-black text-sm text-gray-800">Rp {{ number_format($row->saldo_piutang, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-gray-50 border border-gray-300 rounded-sm">
            <i class="fa fa-history text-gray-200 text-5xl block mb-3"></i>
            <p class="text-gray-400 italic text-sm">Belum ada transaksi piutang</p>
        </div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-900 text-white text-[10px] font-black uppercase tracking-widest">
                    <th class="border border-blue-900 p-3 text-center w-12">No</th>
                    <th class="border border-blue-900 p-3">Tanggal</th>
                    <th class="border border-blue-900 p-3">Jenis</th>
                    <th class="border border-blue-900 p-3">No. Referensi</th>
                    <th class="border border-blue-900 p-3">Keterangan</th>
                    <th class="border border-blue-900 p-3 text-right">Nominal</th>
                    <th class="border border-blue-900 p-3 text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($piutang as $key => $row)
                <tr class="hover:bg-blue-50 transition-colors text-xs border-b border-gray-200">
                    <td class="p-3 text-center font-bold text-gray-400">{{ $key + 1 }}</td>
                    <td class="p-3 font-mono text-gray-700">{{ $row->tanggal->format('d/m/Y') }}</td>
                    <td class="p-3">
                        @if($row->jenis_transaksi == 'piutang')
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-[10px] font-bold">PIUTANG</span>
                        @else
                            <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">PEMBAYARAN</span>
                        @endif
                    </td>
                    <td class="p-3 font-mono text-[10px] text-gray-600">{{ $row->no_referensi ?? '-' }}</td>
                    <td class="p-3 text-gray-700">{{ $row->keterangan ?? '-' }}</td>
                    <td class="p-3 text-right font-bold {{ $row->jenis_transaksi == 'piutang' ? 'text-blue-600' : 'text-emerald-600' }}">
                        {{ $row->jenis_transaksi == 'piutang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-right font-black text-gray-800">
                        Rp {{ number_format($row->saldo_piutang, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center border border-gray-300">
                        <i class="fa fa-history text-gray-200 text-5xl block mb-3"></i>
                        <p class="text-gray-400 italic text-sm">Belum ada transaksi piutang</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($piutang->count() > 0)
    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-sm text-xs text-blue-800">
        <i class="fa fa-info-circle text-blue-600"></i> 
        <strong>Catatan:</strong> Saldo menunjukkan total piutang setelah transaksi tersebut. 
        Transaksi "Piutang" menambah saldo piutang, sedangkan "Pembayaran" mengurangi saldo piutang.
    </div>
    @endif
</div>

@endsection
