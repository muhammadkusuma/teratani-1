{{-- resources/views/maintenance.blade.php --}}
@extends('layouts.landing')

@section('title', 'Sedang Dalam Perbaikan')

@section('content')
    <div class="min-h-[70vh] flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 bg-green-50/30">
        <div class="max-w-md w-full text-center space-y-8">
            {{-- Icon Ilustrasi --}}
            <div class="relative mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>

            <div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 tracking-tight">
                    Sedang Dalam Peningkatan
                </h2>
                <p class="mt-2 text-base text-gray-600">
                    Halaman ini sedang kami perbaiki untuk memberikan pengalaman yang lebih baik bagi Petani Indonesia.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-full text-white bg-green-600 hover:bg-green-700 transition duration-150 ease-in-out shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
                <a href="{{ url('/kontak') }}"
                    class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 text-base font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 hover:text-green-600 transition duration-150 ease-in-out">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
@endsection
