@extends('layouts.owner')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-user"></i> DETAIL PELANGGAN
    </h2>
    <div class="flex gap-2">
        <a href="{{ route('owner.pelanggan.edit', $pelanggan->id_pelanggan) }}" class="px-3 py-1 bg-yellow-500 text-white border border-yellow-700 shadow hover:bg-yellow-400 text-xs">
            <i class="fa fa-edit"></i> EDIT
        </a>
        <a href="{{ route('owner.pelanggan.index') }}" class="px-3 py-1 bg-gray-500 text-white border border-gray-700 shadow hover:bg-gray-400 text-xs">
            <i class="fa fa-arrow-left"></i> KEMBALI
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-1 mb-2 text-xs">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 mb-2 text-xs">
        {{ session('error') }}
    </div>
@endif


<div class="bg-white border border-gray-400 p-4 mb-3">
    <h3 class="font-bold text-sm mb-3 border-b pb-1">INFORMASI PELANGGAN</h3>
    
    <div class="grid grid-cols-2 gap-4 text-xs">
        <div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Kode Pelanggan:</span>
                <div class="font-mono bg-gray-100 p-1 mt-1">{{ $pelanggan->kode_pelanggan }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Nama Pelanggan:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $pelanggan->nama_pelanggan }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Toko:</span>
                <div class="bg-gray-100 p-1 mt-1 text-blue-700 font-semibold">{{ $pelanggan->toko->nama_toko }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Kategori Harga:</span>
                <div class="bg-gray-100 p-1 mt-1">
                    @if($pelanggan->kategori_harga == 'umum')
                        <span class="px-2 py-0.5 rounded bg-gray-200 text-gray-800 text-[10px] font-bold">UMUM</span>
                    @elseif($pelanggan->kategori_harga == 'grosir')
                        <span class="px-2 py-0.5 rounded bg-blue-200 text-blue-800 text-[10px] font-bold">GROSIR</span>
                    @elseif($pelanggan->kategori_harga == 'r1')
                        <span class="px-2 py-0.5 rounded bg-purple-200 text-purple-800 text-[10px] font-bold">R1</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-green-200 text-green-800 text-[10px] font-bold">R2</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">No. HP:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $pelanggan->no_hp ?? '-' }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Alamat:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $pelanggan->alamat ?? '-' }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Wilayah:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $pelanggan->wilayah ?? '-' }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Limit Piutang:</span>
                <div class="bg-gray-100 p-1 mt-1 font-semibold">Rp {{ number_format($pelanggan->limit_piutang, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>


<div class="bg-white border border-gray-400 p-4 mb-3">
    <h3 class="font-bold text-sm mb-3 border-b pb-1">SALDO PIUTANG SAAT INI</h3>
    <div class="text-center py-4">
        <div class="text-xs text-gray-600 mb-1">Total Saldo Piutang</div>
        <div class="text-3xl font-bold {{ $saldoPiutang > 0 ? 'text-blue-600' : 'text-green-600' }}">
            Rp {{ number_format($saldoPiutang, 0, ',', '.') }}
        </div>
        @if($saldoPiutang > 0)
            <div class="text-xs text-blue-500 mt-1">Pelanggan memiliki piutang</div>
            @if($saldoPiutang > $pelanggan->limit_piutang)
                <div class="text-xs text-red-500 mt-1">⚠️ Melebihi limit piutang!</div>
            @endif
        @else
            <div class="text-xs text-green-500 mt-1">Tidak ada piutang</div>
        @endif
    </div>
</div>


<div class="bg-white border border-gray-400 p-4">
    <div class="flex justify-between items-center mb-3 border-b pb-1">
        <h3 class="font-bold text-sm">RIWAYAT PIUTANG</h3>
        <a href="{{ route('owner.pelanggan.piutang.create', ['id_pelanggan' => $pelanggan->id_pelanggan]) }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
            <i class="fa fa-plus"></i> TAMBAH TRANSAKSI
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <th class="border border-gray-400 p-2 text-center w-10">No</th>
                    <th class="border border-gray-400 p-2">Tanggal</th>
                    <th class="border border-gray-400 p-2">Jenis</th>
                    <th class="border border-gray-400 p-2">No. Referensi</th>
                    <th class="border border-gray-400 p-2">Keterangan</th>
                    <th class="border border-gray-400 p-2 text-right">Nominal</th>
                    <th class="border border-gray-400 p-2 text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($piutang as $key => $row)
                <tr class="hover:bg-yellow-50 text-xs">
                    <td class="border border-gray-300 p-2 text-center">{{ $key + 1 }}</td>
                    <td class="border border-gray-300 p-2">{{ $row->tanggal->format('d/m/Y') }}</td>
                    <td class="border border-gray-300 p-2">
                        @if($row->jenis_transaksi == 'piutang')
                            <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-bold">PIUTANG</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 text-[10px] font-bold">PEMBAYARAN</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 p-2 font-mono text-[10px]">{{ $row->no_referensi ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ $row->keterangan ?? '-' }}</td>
                    <td class="border border-gray-300 p-2 text-right font-semibold {{ $row->jenis_transaksi == 'piutang' ? 'text-blue-600' : 'text-green-600' }}">
                        {{ $row->jenis_transaksi == 'piutang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </td>
                    <td class="border border-gray-300 p-2 text-right font-bold">
                        Rp {{ number_format($row->saldo_piutang, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300">
                        Belum ada transaksi piutang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($piutang->count() > 0)
    <div class="mt-3 text-xs text-gray-600">
        <i class="fa fa-info-circle"></i> 
        <strong>Catatan:</strong> Saldo menunjukkan total piutang setelah transaksi tersebut. 
        Transaksi "Piutang" menambah saldo piutang, sedangkan "Pembayaran" mengurangi saldo piutang.
    </div>
    @endif
</div>

@endsection
