@extends('layouts.landing')

@section('title', 'Syarat & Ketentuan')

@section('content')
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-3xl">

                <div class="text-center mb-16">
                    <h2 class="text-base font-semibold leading-7 text-green-600">Legal</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Syarat & Ketentuan Penggunaan
                    </p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Terakhir diperbarui: {{ date('d F Y') }}
                    </p>
                </div>

                <div class="prose prose-lg prose-green mx-auto text-gray-600">
                    <p>
                        Selamat datang di Teratani. Halaman ini menjelaskan aturan main penggunaan aplikasi kasir dan
                        manajemen stok kami. Dengan mendaftar dan menggunakan layanan Teratani, Anda dianggap telah membaca
                        dan menyetujui poin-poin berikut.
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-10 mb-4">1. Akun & Keamanan</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Anda bertanggung jawab penuh atas keamanan <strong>username</strong> dan
                            <strong>password</strong> akun Anda.</li>
                        <li>Satu akun hanya boleh digunakan oleh satu entitas toko (kecuali paket Distributor yang mendukung
                            multi-cabang).</li>
                        <li>Kami berhak menonaktifkan akun jika terindikasi melakukan aktivitas mencurigakan atau melanggar
                            hukum.</li>
                    </ul>

                    <h3 class="text-xl font-bold text-gray-900 mt-10 mb-4">2. Layanan & Pembayaran</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Paket Gratis:</strong> Diberikan tanpa biaya selamanya dengan batasan fitur tertentu.
                        </li>
                        <li><strong>Paket Berbayar (Juragan/Distributor):</strong> Tagihan dibayarkan di muka (pre-paid)
                            secara bulanan atau tahunan.</li>
                        <li><strong>Keterlambatan:</strong> Jika masa langganan habis, akses ke fitur premium akan dikunci
                            sementara hingga pembayaran dilakukan. Data Anda tetap aman dan tidak akan dihapus.</li>
                        <li><strong>Refund:</strong> Kami tidak melayani pengembalian dana (refund) untuk masa berlangganan
                            yang sudah berjalan.</li>
                    </ul>

                    <h3 class="text-xl font-bold text-gray-900 mt-10 mb-4">3. Data & Privasi</h3>
                    <p>
                        Kami menghormati data usaha Anda. Data stok, harga beli, dan omzet adalah rahasia dapur toko Anda.
                        Teratani <strong>tidak akan pernah</strong> menjual data transaksi spesifik toko Anda ke pihak
                        ketiga (seperti kompetitor atau produsen pupuk) tanpa izin tertulis dari Anda.
                    </p>
                    <p class="mt-2">
                        Silakan baca <a href="{{ url('/privasi') }}"
                            class="text-green-600 font-semibold hover:underline">Kebijakan Privasi</a> kami untuk detail
                        lebih lanjut.
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-10 mb-4">4. Batasan Tanggung Jawab</h3>
                    <p>
                        Layanan Teratani disediakan "sebagaimana adanya" (as is). Meskipun kami berusaha menjaga uptime
                        server 99.9%, kami tidak bertanggung jawab atas kerugian materiil akibat gangguan koneksi internet
                        di lokasi Anda, kerusakan perangkat keras (HP/Laptop), atau kelalaian karyawan toko Anda dalam
                        menginput data.
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-10 mb-4">5. Perubahan Ketentuan</h3>
                    <p>
                        Kami dapat mengubah syarat dan ketentuan ini sewaktu-waktu untuk menyesuaikan dengan perkembangan
                        fitur aplikasi. Perubahan signifikan akan kami informasikan melalui email atau notifikasi di
                        dashboard aplikasi.
                    </p>

                    <div class="mt-16 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="font-bold text-gray-900">Punya Pertanyaan?</h4>
                        <p class="mt-2 text-sm">
                            Jika ada poin yang kurang jelas, jangan ragu untuk menghubungi tim legal kami di <a
                                href="mailto:legal@teratani.id" class="text-green-600 font-semibold">legal@teratani.id</a>.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
