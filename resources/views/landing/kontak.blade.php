@extends('layouts.landing')
@section('title', 'Hubungi Kami')
@section('content')
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl space-y-16 divide-y divide-gray-100 lg:mx-0 lg:max-w-none">
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-3">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Bantuan & Support</h2>
                        <p class="mt-4 leading-7 text-gray-600">Bingung cara setting printer thermal? Atau lupa password? Tim
                            kami siap memandu Anda.</p>

                        <div class="mt-8 space-y-4">
                            <div class="flex gap-x-4">
                                <dt class="flex-none"><span class="sr-only">Alamat</span>🏠</dt>
                                <dd class="text-sm leading-6 text-gray-600">Jl. Tani Makmur No. 88<br>Jakarta Selatan,
                                    Indonesia</dd>
                            </div>
                            <div class="flex gap-x-4">
                                <dt class="flex-none"><span class="sr-only">WhatsApp</span>📱</dt>
                                <dd class="text-sm leading-6 text-gray-600">0812-3456-7890 (Chat Only)</dd>
                            </div>
                            <div class="flex gap-x-4">
                                <dt class="flex-none"><span class="sr-only">Email</span>✉️</dt>
                                <dd class="text-sm leading-6 text-gray-600">bantuan@teratani.id</dd>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:col-span-2 lg:gap-8">
                        <div class="rounded-2xl bg-gray-50 p-10">
                            <h3 class="text-base font-semibold leading-7 text-gray-900">Demo Produk</h3>
                            <p class="mt-3 text-sm leading-6 text-gray-600">Ingin melihat cara kerja aplikasi kasir ini
                                sebelum berlangganan?</p>
                            <p class="mt-4"><a href="#"
                                    class="text-sm font-semibold leading-6 text-green-600">Jadwalkan Demo <span
                                        aria-hidden="true">&rarr;</span></a></p>
                        </div>
                        <div class="rounded-2xl bg-gray-50 p-10">
                            <h3 class="text-base font-semibold leading-7 text-gray-900">Kemitraan</h3>
                            <p class="mt-3 text-sm leading-6 text-gray-600">Tertarik menjadi reseller software Teratani di
                                daerah Anda?</p>
                            <p class="mt-4"><a href="#"
                                    class="text-sm font-semibold leading-6 text-green-600">Gabung Mitra <span
                                        aria-hidden="true">&rarr;</span></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
