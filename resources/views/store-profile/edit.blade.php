@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Edit Profil Toko</h2>
        
        <form action="{{ route('store-profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Logo Preview -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Toko</label>
                <div class="flex items-center space-x-4">
                    @php
                        $logoUrl = !empty($profile->logo) ? $profile->logo : asset('images/default-logo.png');
                    @endphp
                    <img src="{{ $profile->logo_url }}" alt="Logo {{ $profile->name }}"
                        class="w-24 h-24 rounded-lg object-cover border border-gray-300">
                    <div class="flex-1">
                        <input type="file" name="logo" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
            </div>

            <!-- Nama Toko -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                <input type="text" name="name" id="name" value="{{ old('name', $profile->name) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" id="address" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>{{ old('address', $profile->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telepon -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end">
                <a href="{{ route('store-profile.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2 hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
