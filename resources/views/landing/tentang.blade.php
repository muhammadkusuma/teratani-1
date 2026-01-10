@extends('layouts.landing')

@section('title', 'Tentang Teratani')

@section('content')
    <div class="relative isolate overflow-hidden bg-white py-24 sm:py-32">
        <div class="absolute -top-40 -right-32 -z-10 transform-gpu overflow-hidden blur-3xl sm:-right-10">
            <div
                class="aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-green-200 to-green-400 opacity-30 sm:w-[72.1875rem]">
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-y-16 lg:grid-cols-2 lg:gap-x-16 lg:items-center">

                <div class="lg:pr-8">
                    <div class="lg:max-w-lg">
                        <p class="text-base font-semibold leading-7 text-green-600">Cerita Kami</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Modernisasi Kios
                            Pertanian Indonesia</h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600">
                            Teratani lahir dari keluhan nyata para pemilik kios pupuk yang sering kewalahan. Buku catatan
                            hilang, stok selisih, dan piutang petani yang tak tertagih menjadi masalah klasik yang kami
                            selesaikan.
                        </p>

                        <div class="mt-8 border-l-4 border-green-500 pl-4 bg-green-50 py-4 pr-4 rounded-r-lg">
                            <p class="italic text-gray-700">"Kami menyadari bahwa Toko Tani adalah jantung ekonomi desa.
                                Jika manajemen toko berantakan, distribusi pangan juga terhambat."</p>
                        </div>

                        <p class="mt-6 text-base leading-7 text-gray-600">
                            Sejak 2024, kami fokus membangun sistem kasir yang <strong>sederhana namun bertenaga</strong>.
                            Tidak perlu komputer canggih, cukup laptop biasa atau tablet, toko Anda sudah bisa dikelola
                            secara profesional.
                        </p>

                        <dl class="mt-10 max-w-xl grid grid-cols-1 gap-y-8 gap-x-8 sm:grid-cols-2 lg:max-w-none">
                            <div class="relative pl-12">
                                <dt class="inline font-semibold text-gray-900">
                                    <div
                                        class="absolute left-0 top-1 h-8 w-8 flex items-center justify-center rounded-lg bg-green-600">
                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    Anti Stok Expired
                                </dt>
                                <dd class="inline text-gray-600"><br>Sistem pengingat otomatis agar barang dijual sebelum
                                    kadaluarsa.</dd>
                            </div>
                            <div class="relative pl-12">
                                <dt class="inline font-semibold text-gray-900">
                                    <div
                                        class="absolute left-0 top-1 h-8 w-8 flex items-center justify-center rounded-lg bg-green-600">
                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    Piutang Terpantau
                                </dt>
                                <dd class="inline text-gray-600"><br>Catat bon petani dengan rapi dan tagih tepat waktu.
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="relative lg:row-span-2">
                    <div
                        class="relative rounded-2xl bg-gray-900/5 p-2 ring-1 ring-inset ring-gray-900/10 lg:-m-4 lg:rounded-2xl lg:p-4">
                        <img src="https://images.unsplash.com/photo-1605000797499-95a51c5269ae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                            alt="Pemilik Toko Tani Menggunakan Tablet"
                            class="rounded-xl shadow-2xl ring-1 ring-gray-900/10 w-full h-auto object-cover">

                        <div
                            class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-xl border border-gray-100 hidden sm:flex items-center gap-4 animate-bounce-slow">
                            <div class="flex -space-x-4">
                                <img class="w-10 h-10 rounded-full border-2 border-white"
                                    src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80"
                                    alt="">
                                <img class="w-10 h-10 rounded-full border-2 border-white"
                                    src="https://images.unsplash.com/photo-1554151228-14d9def656ec?auto=format&fit=crop&w=100&q=80"
                                    alt="">
                                <img class="w-10 h-10 rounded-full border-2 border-white"
                                    src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=100&q=80"
                                    alt="">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Dipercaya 500+ Toko</p>
                                <p class="text-xs text-green-600">di seluruh Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Animasi lambat untuk floating card */
        .animate-bounce-slow {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }
    </style>
@endsection
