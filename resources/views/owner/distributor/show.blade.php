@extends('layouts.owner')

@section('title', 'Detail Distributor')

@section('content')
<div class="flex justify-between items-center mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-truck"></i> DETAIL DISTRIBUTOR
    </h2>
    <div class="flex gap-2">
        <a href="{{ route('owner.distributor.edit', $distributor->id_distributor) }}" class="px-3 py-1 bg-yellow-500 text-white border border-yellow-700 shadow hover:bg-yellow-400 text-xs">
            <i class="fa fa-edit"></i> EDIT
        </a>
        <a href="{{ route('owner.distributor.index') }}" class="px-3 py-1 bg-gray-500 text-white border border-gray-700 shadow hover:bg-gray-400 text-xs">
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
    <h3 class="font-bold text-sm mb-3 border-b pb-1">INFORMASI DISTRIBUTOR</h3>
    
    <div class="grid grid-cols-2 gap-4 text-xs">
        <div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Kode Distributor:</span>
                <div class="font-mono bg-gray-100 p-1 mt-1">{{ $distributor->kode_distributor }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Nama Distributor:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $distributor->nama_distributor }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Nama Perusahaan:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $distributor->nama_perusahaan ?? '-' }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Toko:</span>
                <div class="bg-gray-100 p-1 mt-1 text-blue-700 font-semibold">{{ $distributor->toko->nama_toko }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">NPWP:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $distributor->npwp ?? '-' }}</div>
            </div>
        </div>
        
        <div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Alamat:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $distributor->alamat ?? '-' }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Kota / Provinsi:</span>
                <div class="bg-gray-100 p-1 mt-1">
                    {{ $distributor->kota ?? '-' }}
                    @if($distributor->provinsi)
                        , {{ $distributor->provinsi }}
                    @endif
                    @if($distributor->kode_pos)
                        ({{ $distributor->kode_pos }})
                    @endif
                </div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Kontak Person:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $distributor->nama_kontak ?? '-' }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">No. Telepon:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $distributor->no_telp ?? '-' }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">No. HP Kontak:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $distributor->no_hp_kontak ?? '-' }}</div>
            </div>
            <div class="mb-2">
                <span class="font-bold text-gray-600">Email:</span>
                <div class="bg-gray-100 p-1 mt-1">{{ $distributor->email ?? '-' }}</div>
            </div>
        </div>
    </div>

    @if($distributor->keterangan)
    <div class="mt-3">
        <span class="font-bold text-gray-600 text-xs">Keterangan:</span>
        <div class="bg-gray-100 p-2 mt-1 text-xs">{{ $distributor->keterangan }}</div>
    </div>
    @endif

    <div class="mt-3">
        <span class="font-bold text-gray-600 text-xs">Status:</span>
        <div class="mt-1">
            @if($distributor->is_active)
                <span class="px-3 py-1 rounded bg-green-200 text-green-800 text-xs font-bold">AKTIF</span>
            @else
                <span class="px-3 py-1 rounded bg-red-200 text-red-800 text-xs font-bold">NONAKTIF</span>
            @endif
        </div>
    </div>
</div>


<div class="bg-white border border-gray-400 p-4 mb-3">
    <h3 class="font-bold text-sm mb-3 border-b pb-1">SALDO HUTANG SAAT INI</h3>
    <div class="text-center py-4">
        <div class="text-xs text-gray-600 mb-1">Total Saldo Hutang</div>
        <div class="text-3xl font-bold {{ $saldoUtang > 0 ? 'text-red-600' : 'text-green-600' }}">
            Rp {{ number_format($saldoUtang, 0, ',', '.') }}
        </div>
        @if($saldoUtang > 0)
            <div class="text-xs text-red-500 mt-1">Anda masih memiliki hutang kepada distributor ini</div>
        @else
            <div class="text-xs text-green-500 mt-1">Tidak ada hutang</div>
        @endif
    </div>
</div>


<div class="bg-white border border-gray-400 p-4">
    <div class="flex justify-between items-center mb-3 border-b pb-1">
        <h3 class="font-bold text-sm">RIWAYAT HUTANG / PIUTANG</h3>
        <a href="{{ route('owner.distributor.hutang.create', ['id_distributor' => $distributor->id_distributor]) }}" class="px-3 py-1 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs">
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
                @forelse($utangPiutang as $key => $row)
                <tr class="hover:bg-yellow-50 text-xs">
                    <td class="border border-gray-300 p-2 text-center">{{ $key + 1 }}</td>
                    <td class="border border-gray-300 p-2">{{ $row->tanggal->format('d/m/Y') }}</td>
                    <td class="border border-gray-300 p-2">
                        @if($row->jenis_transaksi == 'utang')
                            <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold">UTANG</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 text-[10px] font-bold">PEMBAYARAN</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 p-2 font-mono text-[10px]">{{ $row->no_referensi ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ $row->keterangan ?? '-' }}</td>
                    <td class="border border-gray-300 p-2 text-right font-semibold {{ $row->jenis_transaksi == 'utang' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $row->jenis_transaksi == 'utang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </td>
                    <td class="border border-gray-300 p-2 text-right font-bold">
                        Rp {{ number_format($row->saldo_utang, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300">
                        Belum ada transaksi hutang/piutang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($utangPiutang->count() > 0)
    <div class="mt-3 text-xs text-gray-600">
        <i class="fa fa-info-circle"></i> 
        <strong>Catatan:</strong> Saldo menunjukkan total hutang setelah transaksi tersebut. 
        Transaksi "Utang" menambah saldo hutang, sedangkan "Pembayaran" mengurangi saldo hutang.
    </div>
    @endif
</div>

@endsection
