@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-extrabold mb-8 text-center text-gray-800 dark:text-white">Profil Toko</h1>

        <div class="max-w-md mx-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 space-y-6">

            <!-- Logo Toko -->
            <div class="flex flex-col items-center">
                @php
                    $logoUrl = filter_var($profile->logo, FILTER_VALIDATE_URL) ? $profile->logo : asset('images/default-logo.png');
                @endphp

                <img src="{{ $logoUrl }}" alt="Logo Toko"
                    class="w-32 h-32 object-cover rounded-full border-4 border-gray-200 dark:border-gray-700 shadow-lg"
                    onerror="this.src='{{ asset('images/default-logo.png') }}';">


                <h2 class="mt-4 text-xl font-semibold text-gray-800 dark:text-white">{{ $profile->name }}</h2>
            </div>

            <!-- Informasi -->
            <div class="space-y-4">

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</h3>
                    <p class="mt-1 text-base text-gray-800 dark:text-gray-200">{{ $profile->address }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</h3>
                    <p class="mt-1 text-base text-gray-800 dark:text-gray-200">{{ $profile->phone }}</p>
                </div>

            </div>

            <!-- Tombol Edit -->
            <div class="flex justify-center pt-4">
                <a href="{{ route('store-profile.edit') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-md transition duration-200">
                    <i class="fas fa-edit mr-2"></i> Edit Profil
                </a>
            </div>

        </div>
    </div>
@endsection