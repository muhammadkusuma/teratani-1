@extends('layouts.landing')

@section('title', 'Aplikasi Toko Pertanian & Kasir')

@section('content')
    <section class="relative pt-10 pb-20 lg:pt-20 lg:pb-28 overflow-hidden">
        <div class="absolute top-0 right-0 -z-10 opacity-10 transform translate-x-1/3 -translate-y-1/4">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"
                class="w-[800px] h-[800px] text-green-200 fill-current">
                <path
                    d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.1,-19.2,95.8,-4.9C93.5,9.3,82.2,22.9,71.3,34.3C60.4,45.7,49.9,54.9,38.2,62.3C26.5,69.7,13.6,75.3,-0.6,76.3C-14.8,77.3,-28.3,73.7,-40.7,66.7C-53.1,59.7,-64.4,49.3,-73.2,36.4C-82,23.5,-88.3,8.1,-86.7,-6.6C-85.1,-21.3,-75.6,-35.3,-64.6,-46.8C-53.6,-58.3,-41.1,-67.3,-27.9,-75.1C-14.7,-82.9,-0.8,-89.5,13.7,-88.9C28.2,-88.3,42.5,-80.5,44.7,-76.4Z"
                    transform="translate(100 100)" />
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div
                        class="inline-flex items-center px-3 py-1 rounded-full border border-green-200 bg-green-50 text-green-700 text-xs font-semibold uppercase tracking-wide mb-6">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Software Khusus Toko Pertanian
                    </div>
                    <h1 class="text-4xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                        Kelola Stok Pupuk & Obat Tani Tanpa <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-green-400">Pusing</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Aplikasi kasir dan stok opname yang dirancang untuk toko tani. Atur konversi satuan (karung ke
                        eceran), pantau tanggal kadaluarsa, dan catat hutang pelanggan dalam satu aplikasi.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('register') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition shadow-lg shadow-green-600/30 flex items-center justify-center">
                            Coba Gratis Sekarang
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <a href="{{ url('/fitur') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-white text-gray-700 border border-gray-200 rounded-xl font-semibold hover:border-green-600 hover:text-green-600 transition flex items-center justify-center">
                            Lihat Demo
                        </a>
                    </div>

                    <div
                        class="mt-10 flex flex-wrap items-center justify-center lg:justify-start gap-y-4 gap-x-6 text-gray-500">
                        <span class="flex items-center text-sm font-medium"><svg class="w-5 h-5 mr-2 text-green-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg> Support Barcode</span>
                        <span class="flex items-center text-sm font-medium"><svg class="w-5 h-5 mr-2 text-green-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg> Cetak Struk</span>
                        <span class="flex items-center text-sm font-medium"><svg class="w-5 h-5 mr-2 text-green-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg> Stok Real-time</span>
                    </div>
                </div>

                <div class="relative lg:h-full flex justify-center lg:justify-end mt-10 lg:mt-0">
                    <div class="relative w-full max-w-lg aspect-square">
                        <div
                            class="absolute top-0 right-0 w-72 h-72 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                        </div>
                        <div
                            class="absolute top-0 -left-4 w-72 h-72 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
                        </div>

                        <div
                            class="relative rounded-2xl bg-white shadow-2xl border border-gray-100 p-2 overflow-hidden transform rotate-2 hover:rotate-0 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1556740758-90de374c12ad?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                                alt="Aplikasi Kasir Toko Tani" class="rounded-xl w-full h-auto object-cover opacity-90">

                            <div
                                class="absolute top-6 right-6 bg-white p-3 rounded-lg shadow-lg border border-red-100 flex items-center gap-3 animate-bounce-slow">
                                <div
                                    class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase">Stok Menipis</p>
                                    <p class="text-sm font-bold text-gray-900">Pupuk Urea (Sisa 2)</p>
                                </div>
                            </div>

                            <div
                                class="absolute bottom-6 -left-4 bg-white p-3 rounded-lg shadow-lg border border-green-100 flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase">Omzet Hari Ini</p>
                                    <p class="text-sm font-bold text-gray-900">Rp 4.500.000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-base text-green-600 font-bold tracking-wide uppercase">Fitur Khusus</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Dibuat Untuk Dinamika Toko Tani
                </p>
                <p class="mt-4 text-xl text-gray-500">
                    Kami paham toko tani beda dengan minimarket biasa. Kami punya fitur yang Anda butuhkan.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Konversi Satuan Pintar</h3>
                        <p class="text-gray-600 mb-4">Beli dalam <strong>Sak/Dus</strong>, jual dalam
                            <strong>Eceran/Kg</strong>. Stok otomatis terpotong sesuai takaran penjualan tanpa hitung
                            manual.</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center text-red-600 mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Pantau Kadaluarsa</h3>
                        <p class="text-gray-600 mb-4">Jangan sampai rugi karena obat atau benih expired. Dapatkan
                            notifikasi dini barang mana yang harus segera dijual.</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kasir & Catat Hutang</h3>
                        <p class="text-gray-600 mb-4">Proses transaksi cepat dengan barcode scanner. Fitur catat hutang
                            pelanggan (bon) yang rapi dan pengingat tagihan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-green-900 py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-4">
                    <div class="text-4xl font-bold text-white mb-2">1,500+</div>
                    <div class="text-green-200 font-medium">Toko Tani Mitra</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-white mb-2">50k+</div>
                    <div class="text-green-200 font-medium">SKU Produk</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-white mb-2">10jt+</div>
                    <div class="text-green-200 font-medium">Transaksi Tercatat</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-white mb-2">24/7</div>
                    <div class="text-green-200 font-medium">Support CS</div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Tinggalkan Buku Catatan Manual</h2>
            <p class="text-xl text-gray-600 mb-8">
                Beralih ke Teratani sekarang. Kelola stok toko lebih rapi, cegah kebocoran dana, dan tingkatkan keuntungan
                usaha tani Anda.
            </p>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="inline-block px-10 py-4 bg-green-600 text-white rounded-full font-bold text-lg hover:bg-green-700 transition transform hover:-translate-y-1 shadow-xl shadow-green-600/30">
                    Daftar Akun Toko Gratis
                </a>
            @else
                <a href="{{ url('/kontak') }}"
                    class="inline-block px-10 py-4 bg-green-600 text-white rounded-full font-bold text-lg hover:bg-green-700 transition transform hover:-translate-y-1 shadow-xl shadow-green-600/30">
                    Konsultasi Gratis
                </a>
            @endif
            <p class="mt-4 text-sm text-gray-400">Cocok untuk Kios Pupuk, Toko Obat Pertanian, & Poultry Shop.</p>
        </div>
    </section>

    <style>
        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animate-bounce-slow {
            animation: bounce 3s infinite;
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
    </style>
@endsection
