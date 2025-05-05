@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-extrabold mb-8 text-center text-gray-800 dark:text-white">Edit Profil Toko</h1>

        <div class="max-w-md mx-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 space-y-6">

            <form action="{{ route('store-profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Logo Preview -->
                <div class="flex flex-col items-center">
                    @php
                        $logoUrl = !empty($profile->logo) ? $profile->logo : asset('images/default-logo.png');
                    @endphp

                    <img 
                        src="{{ $logoUrl }}" 
                        alt="Logo Toko" 
                        id="logo-preview"
                        class="w-32 h-32 object-cover rounded-full border-4 border-gray-200 dark:border-gray-700 shadow-lg"
                        onerror="this.onerror=null;this.src='{{ asset('images/default-logo.png') }}';"
                    >
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo Baru</label>
                    <input type="file" name="logo" accept="image/*"
                        class="block w-full text-sm text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Toko</label>
                    <input type="text" name="name" value="{{ old('name', $profile->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                    <textarea name="address" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('address', $profile->address) }}</textarea>
                </div>

                <!-- Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Tombol -->
                <div class="flex justify-center pt-4">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-md transition duration-200">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Preview logo baru sebelum upload
    document.querySelector('input[name="logo"]').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logo-preview').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    });
</script>
@endpush
