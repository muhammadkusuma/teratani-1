@extends('layouts.landing')

@section('title', 'Fitur Toko Tani')

@section('content')
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-green-600">Fitur Lengkap</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Solusi Digital untuk Kios
                    Pertanian</p>
                <p class="mt-6 text-lg leading-8 text-gray-600">Tidak perlu lagi pusing dengan stok yang selisih atau bon
                    pelanggan yang hilang. Teratani membereskan administrasi toko Anda.</p>
            </div>

            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-green-600 text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </div>
                            Multi-Satuan (Grosir & Ecer)
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Beli barang dari distributor dalam satuan <strong>Dus/Karton</strong>, lalu
                                jual ke petani dalam satuan <strong>Botol/Sachet</strong>. Stok otomatis terkonversi tanpa
                                ribet.</p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-green-600 text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Notifikasi Kadaluarsa (Expired)
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Obat-obatan pertanian punya masa pakai. Sistem akan mengingatkan Anda
                                barang mana yang akan expired agar bisa dijual lebih dulu (FEFO - First Expired First Out).
                            </p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-green-600 text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            Pencatatan Hutang (Bon)
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Catat pembelian "bon" petani dengan rapi. Cetak tagihan saat panen tiba.
                                Riwayat hutang tersimpan aman per pelanggan.</p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-green-600 text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                    </path>
                                </svg>
                            </div>
                            Kasir Barcode Scanner
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Percepat antrian saat musim tanam. Scan barcode produk, hitung total
                                otomatis, dan cetak struk profesional.</p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-green-600 text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            Laporan Keuangan Toko
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Ketahui omzet harian, laba kotor, dan produk paling laris (fast moving)
                                hanya dengan satu klik.</p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-green-600 text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            Akses via HP (Cloud)
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Pantau penjualan toko dari rumah atau saat sedang belanja stok di kota.
                                Data tersimpan aman di cloud.</p>
                        </dd>
                    </div>

                </dl>
            </div>
        </div>
    </div>
@endsection
