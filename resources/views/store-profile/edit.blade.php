@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-center">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Perbarui Informasi Toko</h1>
    </div>

    <div class="flex justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md w-full max-w-2xl p-6">
            <form action="{{ route('store-profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Logo Toko -->
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative group">
                        @php
                            $defaultLogo = asset('images/default-store-logo.png');
                            $logoUrl = $defaultLogo;
                            
                            if (!empty($profile->logo)) {
                                try {
                                    if (filter_var($profile->logo, FILTER_VALIDATE_URL)) {
                                        $logoUrl = $profile->logo;
                                    } else {
                                        // Cek apakah file ada di storage
                                        $path = ltrim(parse_url($profile->logo, PHP_URL_PATH), '/');
                                        if (Storage::disk('laravelcloud')->exists($path)) {
                                            $logoUrl = Storage::disk('laravelcloud')->url($path);
                                        } else {
                                            // Fallback ke URL endpoint jika file ada tapi tidak terdeteksi exists()
                                            $baseUrl = rtrim(config('filesystems.disks.laravelcloud.url'), '/');
                                            $logoUrl = $baseUrl.'/'.$path;
                                        }
                                    }
                                } catch (\Exception $e) {
                                    Log::error('Logo Error: '.$e->getMessage());
                                }
                            }
                        @endphp

                        <img src="{{ $logoUrl }}" 
                             alt="Logo Toko Saat Ini"
                             class="w-40 h-40 object-cover rounded-lg shadow-md border-2 border-gray-200 dark:border-gray-600 group-hover:opacity-75 transition-opacity duration-200"
                             id="current-logo"
                             onerror="this.onerror=null;this.src='{{ $defaultLogo }}'">
                        
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <span class="text-white text-sm font-medium">Ganti Logo</span>
                        </div>
                    </div>

                    <div class="w-full">
                        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Unggah Logo Baru
                        </label>
                        <input type="file" name="logo" id="logo" 
                            class="block w-full text-sm text-gray-600 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-blue-300 dark:hover:file:bg-gray-600"
                            accept="image/jpeg,image/png,image/webp,image/avif" 
                            data-max-size="2048">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Format: JPEG, PNG, WEBP, AVIF (Maks. 2MB)
                        </p>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nama Toko -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Toko <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" 
                        value="{{ old('name', $profile->name) }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 p-2 border" 
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat Toko -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Alamat Toko <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 p-2 border"
                        required>{{ old('address', $profile->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="phone" id="phone" 
                        value="{{ old('phone', $profile->phone) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 p-2 border"
                        required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('store-profile.index') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview gambar sebelum upload
    const logoInput = document.getElementById('logo');
    const currentLogo = document.getElementById('current-logo');
    
    if (logoInput && currentLogo) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.match('image.*')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    currentLogo.src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        });
    }

    // Validasi ukuran file
    logoInput?.addEventListener('change', function() {
        const maxSize = parseInt(logoInput.dataset.maxSize) * 1024; // Convert KB to bytes
        if (this.files[0] && this.files[0].size > maxSize) {
            alert('Ukuran file melebihi 2MB');
            this.value = '';
        }
    });
});
</script>
@endpush