@extends('layouts.owner')

@section('title', 'Daftarkan Bisnis')

@section('content')
    <div class="max-w-4xl">
        
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg border-b-2 border-gray-500 pr-4">DAFTARKAN BISNIS ANDA</h2>
            
            
        </div>

        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mb-4 text-xs">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        
        <form action="{{ route('owner.bisnis.store') }}" method="POST"
            class="bg-gray-100 p-4 border border-gray-400 shadow-inner">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                
                <div>
                    <label class="block font-bold text-xs mb-1">Nama Bisnis / Perusahaan</label>
                    <input type="text" name="nama_bisnis" required
                        class="w-full border border-gray-400 p-1 text-sm bg-white focus:outline-none focus:border-blue-500"
                        placeholder="Contoh: PT. Maju Jaya">
                    <p class="text-[10px] text-gray-500 mt-1 italic">*Identitas utama akun bisnis Anda.</p>
                </div>

                
                <div>
                    <label class="block font-bold text-xs mb-1">No. Telepon Bisnis</label>
                    <input type="text" name="no_telp"
                        class="w-full border border-gray-400 p-1 text-sm bg-white focus:outline-none focus:border-blue-500"
                        placeholder="0812...">
                </div>
            </div>

            
            <div class="mb-4">
                <label class="block font-bold text-xs mb-1">Alamat Pusat</label>
                <textarea name="alamat" rows="3"
                    class="w-full border border-gray-400 p-1 text-sm bg-white focus:outline-none focus:border-blue-500"
                    placeholder="Alamat lengkap kantor/bisnis pusat..."></textarea>
            </div>

            
            <div class="border-t border-gray-300 pt-3 text-right">
                <button type="submit"
                    class="bg-blue-800 text-white px-4 py-2 border border-blue-900 shadow hover:bg-blue-700 font-bold text-xs uppercase">
                    Simpan & Lanjutkan
                </button>
            </div>
        </form>
    </div>
@endsection
