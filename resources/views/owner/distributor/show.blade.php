@extends('layouts.owner')

@section('title', 'Detail Distributor')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-3">
    <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">
        <i class="fa fa-truck"></i> DETAIL DISTRIBUTOR
    </h2>
    <div class="flex gap-2 w-full md:w-auto">
        <a href="{{ route('owner.distributor.edit', $distributor->id_distributor) }}" class="flex-1 md:flex-none text-center px-3 py-1 bg-yellow-500 text-white border border-yellow-700 shadow hover:bg-yellow-400 text-xs uppercase font-bold">
            <i class="fa fa-edit"></i> EDIT
        </a>
        <a href="{{ route('owner.distributor.index') }}" class="flex-1 md:flex-none text-center px-3 py-1 bg-gray-500 text-white border border-gray-700 shadow hover:bg-gray-400 text-xs uppercase font-bold">
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
    <h3 class="font-bold text-sm mb-3 border-b pb-1 uppercase tracking-wider">INFORMASI DISTRIBUTOR</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
        <div class="space-y-3">
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">Kode Distributor:</span>
                <div class="font-mono bg-gray-50 border border-gray-200 p-2">{{ $distributor->kode_distributor }}</div>
            </div>
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">Nama Distributor:</span>
                <div class="bg-gray-50 border border-gray-200 p-2 font-bold">{{ $distributor->nama_distributor }}</div>
            </div>
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">Nama Perusahaan:</span>
                <div class="bg-gray-50 border border-gray-200 p-2">{{ $distributor->nama_perusahaan ?? '-' }}</div>
            </div>
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">Toko:</span>
                <div class="bg-gray-50 border border-gray-200 p-2 text-blue-700 font-bold italic">{{ $distributor->toko->nama_toko }}</div>
            </div>
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">NPWP:</span>
                <div class="bg-gray-50 border border-gray-200 p-2">{{ $distributor->npwp ?? '-' }}</div>
            </div>
        </div>
        
        <div class="space-y-3">
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">Alamat:</span>
                <div class="bg-gray-50 border border-gray-200 p-2">{{ $distributor->alamat ?? '-' }}</div>
            </div>
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">Kota / Provinsi:</span>
                <div class="bg-gray-50 border border-gray-200 p-2">
                    {{ $distributor->kota ?? '-' }}
                    @if($distributor->provinsi)
                        , {{ $distributor->provinsi }}
                    @endif
                    @if($distributor->kode_pos)
                        ({{ $distributor->kode_pos }})
                    @endif
                </div>
            </div>
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">Kontak Person:</span>
                <div class="bg-gray-50 border border-gray-200 p-2 font-semibold">{{ $distributor->nama_kontak ?? '-' }}</div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">No. Telepon:</span>
                    <div class="bg-gray-50 border border-gray-200 p-2">{{ $distributor->no_telp ?? '-' }}</div>
                </div>
                <div>
                    <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">No. HP Kontak:</span>
                    <div class="bg-gray-50 border border-gray-200 p-2">{{ $distributor->no_hp_kontak ?? '-' }}</div>
                </div>
            </div>
            <div>
                <span class="font-bold text-gray-600 block mb-1 uppercase text-[10px]">Email:</span>
                <div class="bg-gray-50 border border-gray-200 p-2 text-blue-600">{{ $distributor->email ?? '-' }}</div>
            </div>
        </div>
    </div>

    @if($distributor->keterangan)
    <div class="mt-4 border-t pt-3">
        <span class="font-bold text-gray-600 text-[10px] uppercase">Keterangan:</span>
        <div class="bg-gray-50 border border-gray-200 p-3 mt-1 text-xs italic">{{ $distributor->keterangan }}</div>
    </div>
    @endif

    <div class="mt-4 flex items-center gap-2">
        <span class="font-bold text-gray-600 text-[10px] uppercase">Status:</span>
        @if($distributor->is_active)
            <span class="px-3 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-bold border border-green-200">AKTIF</span>
        @else
            <span class="px-3 py-0.5 rounded-full bg-red-100 text-red-700 text-[10px] font-bold border border-red-200">NONAKTIF</span>
        @endif
    </div>
</div>

<div class="bg-white border border-gray-400 p-4 mb-3">
    <h3 class="font-bold text-sm mb-3 border-b pb-1 uppercase tracking-wider">SALDO HUTANG SAAT INI</h3>
    <div class="text-center py-4 bg-gray-50 border border-gray-100 rounded">
        <div class="text-[10px] text-gray-500 uppercase font-bold mb-1">Total Saldo Hutang</div>
        <div class="text-4xl font-black {{ $saldoUtang > 0 ? 'text-red-600' : 'text-green-600' }}">
            Rp {{ number_format($saldoUtang, 0, ',', '.') }}
        </div>
        @if($saldoUtang > 0)
            <div class="text-xs text-red-500 mt-1 font-bold italic"><i class="fa fa-exclamation-triangle"></i> Terdeteksi sisa hutang yang belum dibayar</div>
        @else
            <div class="text-xs text-green-500 mt-1 font-bold uppercase tracking-widest"><i class="fa fa-check-circle"></i> Hutang Lunas</div>
        @endif
    </div>
</div>

<div class="bg-white border border-gray-400 p-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-3 border-b pb-1 gap-2">
        <h3 class="font-bold text-sm uppercase">RIWAYAT HUTANG / PIUTANG</h3>
        <a href="{{ route('owner.distributor.hutang.create', ['id_distributor' => $distributor->id_distributor]) }}" class="w-full md:w-auto px-4 py-1.5 bg-blue-700 text-white border border-blue-900 shadow hover:bg-blue-600 text-xs font-bold text-center">
            <i class="fa fa-plus"></i> TRANSAKSI BARU
        </a>
    </div>

    {{-- Mobile History View --}}
    <div class="block md:hidden space-y-3">
        @forelse($utangPiutang as $key => $row)
        <div class="bg-gray-50 border border-gray-300 p-3 relative overflow-hidden">
            <div class="absolute top-0 right-0">
                @if($row->jenis_transaksi == 'utang')
                    <div class="bg-red-500 text-white px-3 py-0.5 text-[9px] font-bold uppercase">UTANG</div>
                @else
                    <div class="bg-green-600 text-white px-3 py-0.5 text-[9px] font-bold uppercase">BAYAR</div>
                @endif
            </div>

            <div class="text-[10px] text-gray-500 font-bold mb-1">{{ $row->tanggal->format('d/m/Y') }}</div>
            <div class="text-[10px] font-mono mb-2">REF: {{ $row->no_referensi ?? '-' }}</div>
            
            <div class="text-xs italic text-gray-700 mb-3 border-l-2 border-gray-300 pl-2">
                {{ $row->keterangan ?? 'Tanpa keterangan' }}
            </div>

            <div class="flex justify-between items-end pt-2 border-t border-gray-200">
                <div>
                    <div class="text-[8px] uppercase text-gray-500">Nominal</div>
                    <div class="font-bold text-sm {{ $row->jenis_transaksi == 'utang' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $row->jenis_transaksi == 'utang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[8px] uppercase text-gray-500">Saldo Akhir</div>
                    <div class="font-bold text-sm text-gray-900">
                        Rp {{ number_format($row->saldo_utang, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-4 text-center text-gray-500 italic text-xs bg-gray-50 border border-dashed border-gray-300">Belum ada transaksi</div>
        @endforelse
    </div>

    {{-- Desktop History View --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
                    <th class="border border-gray-400 p-2 text-center w-10">No</th>
                    <th class="border border-gray-400 p-2 text-center">Tanggal</th>
                    <th class="border border-gray-400 p-2 text-center">Jenis</th>
                    <th class="border border-gray-400 p-2">No. Referensi</th>
                    <th class="border border-gray-400 p-2">Keterangan</th>
                    <th class="border border-gray-400 p-2 text-right">Nominal</th>
                    <th class="border border-gray-400 p-2 text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($utangPiutang as $key => $row)
                <tr class="hover:bg-yellow-50 text-xs">
                    <td class="border border-gray-300 p-2 text-center font-bold text-gray-500">{{ $key + 1 }}</td>
                    <td class="border border-gray-300 p-2 text-center font-mono">{{ $row->tanggal->format('d/m/Y') }}</td>
                    <td class="border border-gray-300 p-2 text-center">
                        @if($row->jenis_transaksi == 'utang')
                            <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold border border-red-200 uppercase">UTANG</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 text-[10px] font-bold border border-green-200 uppercase">BAYAR</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 p-2 font-mono text-[10px]">{{ $row->no_referensi ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ Str::limit($row->keterangan ?? '-', 50) }}</td>
                    <td class="border border-gray-300 p-2 text-right font-semibold {{ $row->jenis_transaksi == 'utang' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $row->jenis_transaksi == 'utang' ? '+' : '-' }} Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </td>
                    <td class="border border-gray-300 p-2 text-right font-bold text-gray-900">
                        Rp {{ number_format($row->saldo_utang, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500 italic border border-gray-300 bg-gray-50">
                        Belum ada transaksi hutang/piutang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($utangPiutang->count() > 0)
    <div class="mt-4 text-[10px] text-gray-500 border-t pt-2 italic flex items-center gap-2">
        <i class="fa fa-info-circle text-blue-500 text-xs"></i> 
        <span>Saldo menunjukkan total hutang akumulasi. <strong>+ UTANG</strong> menambah kewajiban, <strong>- BAYAR</strong> melunasi kewajiban.</span>
    </div>
    @endif
</div>
</div>

@endsection
