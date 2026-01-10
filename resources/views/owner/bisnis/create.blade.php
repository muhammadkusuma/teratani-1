@extends('layouts.owner')

@section('content')
    <div class="max-w-2xl mx-auto mt-10">
        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
            <div class="px-8 py-6 bg-blue-600 text-white">
                <h1 class="text-2xl font-bold">Daftarkan Bisnis Anda</h1>
                <p class="text-blue-100 mt-1">Langkah pertama untuk menggunakan aplikasi.</p>
            </div>

            <div class="p-8">
                <form action="{{ route('owner.bisnis.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Nama Bisnis --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bisnis / Perusahaan</label>
                        <input type="text" name="nama_bisnis" required
                            placeholder="Contoh: PT. Maju Jaya atau Toko Sembako Berkah"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <p class="text-xs text-gray-500 mt-1">Nama ini akan menjadi identitas utama akun bisnis Anda.</p>
                    </div>

                    {{-- No Telp --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon Bisnis</label>
                        <input type="text" name="no_telp" placeholder="0812..."
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Pusat</label>
                        <textarea name="alamat" rows="3" placeholder="Alamat lengkap kantor/bisnis pusat..."
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                        Simpan & Lanjutkan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
