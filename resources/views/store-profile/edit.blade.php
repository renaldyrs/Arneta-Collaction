@extends('layouts.app')

@section('content')
<div class="container flex justify-center mt-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Perbarui Informasi Toko</h1>
</div>

<div class="container mx-auto p-4 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg dark:bg-gray-800">
        <form action="{{ route('store-profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Logo Toko -->
            <div class="flex flex-col items-center mb-6">
                @php
                    $defaultLogo = asset('images/default-logo.png');
                    $logoUrl = $defaultLogo;
                    
                    if (!empty($profile->logo)) {
                        try {
                            if (filter_var($profile->logo, FILTER_VALIDATE_URL)) {
                                $logoUrl = $profile->logo;
                            } elseif (Storage::disk('laravelcloud')->exists($profile->logo)) {
                                $logoUrl = Storage::disk('laravelcloud')->url($profile->logo);
                            } else {
                                $logoUrl = env('LARAVELCLOUD_ENDPOINT').'/'.$profile->logo;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Logo Error: '.$e->getMessage());
                        }
                    }
                @endphp

                <img src="{{ $logoUrl }}" 
                     alt="Logo Toko Saat Ini"
                     class="mt-4 mb-2 w-32 h-32 object-cover rounded-md shadow-md"
                     onerror="this.onerror=null;this.src='{{ $defaultLogo }}'"
                     id="current-logo">

                <input type="file" name="logo" id="logo" 
                    class="text-gray-800 dark:text-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full p-2"
                    accept="image/jpeg,image/png,image/jpg,image/gif">
                <p class="text-sm text-gray-500 mt-2">Unggah logo baru (JPEG, PNG, JPG, GIF - maks 2MB)</p>
                @error('logo')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Toko -->
            <div class="mb-6">
                <label for="name" class="text-lg font-medium text-gray-700 dark:text-gray-400">Nama Toko</label>
                <input type="text" name="name" id="name" value="{{ old('name', $profile->name) }}" 
                    class="mt-2 text-gray-800 dark:text-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full p-2" 
                    required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat Toko -->
            <div class="mb-6">
                <label for="address" class="text-lg font-medium text-gray-700 dark:text-gray-400">Alamat Toko</label>
                <textarea name="address" id="address" rows="3" 
                    class="mt-2 text-gray-800 dark:text-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full p-2" 
                    required>{{ old('address', $profile->address) }}</textarea>
                @error('address')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-6">
                <label for="phone" class="text-lg font-medium text-gray-700 dark:text-gray-400">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}" 
                    class="mt-2 text-gray-800 dark:text-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full p-2" 
                    required>
                @error('phone')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('store-profile.index') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-6 py-2 rounded-md shadow-md transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md shadow-md transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection