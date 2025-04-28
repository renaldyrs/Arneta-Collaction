@extends('layouts.app')

@section('content')
    <div class="container flex justify-center mt-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Informasi Toko</h1>
    </div>

    <div class="container mx-auto p-4 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg dark:bg-gray-800">

            <!-- Logo Toko -->
            <div class="flex flex-col items-center mb-6">
                @php
                    $defaultLogo = asset('images/default-logo.png');
                    $logoUrl = $defaultLogo;

                    if (!empty($profile->logo)) {
                        $logoUrl = $profile->logo; // Langsung gunakan value dari database
                    }
                @endphp

                <img src="{{ $logoUrl }}" alt="Logo Toko" class="mt-4 w-32 h-32 object-cover rounded-md shadow-md"
                    onerror="this.onerror=null;this.src='{{ $defaultLogo }}'" id="store-logo">
            </div>

            <!-- Informasi Toko -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-400">Nama Toko</h2>
                <p class="mt-2 text-gray-800 dark:text-white">{{ $profile->name }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-400">Alamat Toko</h2>
                <p class="mt-2 text-gray-800 dark:text-white">{{ $profile->address }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-400">Nomor Telepon</h2>
                <p class="mt-2 text-gray-800 dark:text-white">{{ $profile->phone }}</p>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('store-profile.edit') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md shadow-md transition duration-200">
                    <i class="fas fa-edit mr-2"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logo = document.getElementById('store-logo');

            // Cek jika gambar gagal load
            logo.addEventListener('error', function () {
                console.log('Gagal memuat logo, menggunakan fallback');
                this.src = '{{ asset("images/default-logo.png") }}';
            });

            // Preload image untuk deteksi error lebih awal
            const imgTest = new Image();
            imgTest.src = logo.src;
            imgTest.onerror = function () {
                logo.src = '{{ asset("images/default-logo.png") }}';
            };
        });
    </script>
@endpush