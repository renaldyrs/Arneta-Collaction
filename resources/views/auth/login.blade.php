@extends('layouts.login')

@section('content')
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-gray-900 flex justify-center items-center min-h-screen p-4">
    <div class="bg-white dark:bg-gray-800 p-8 sm:p-10 rounded-2xl shadow-xl w-full max-w-md mx-auto border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl">
        <!-- Animated Logo Section -->
        <div class="text-center mb-8 flex flex-col items-center transform hover:scale-105 transition-transform duration-300">
            <div class="mb-4 p-3 bg-blue-100 dark:bg-blue-900 rounded-full shadow-inner">
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
            <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent">
                Welcome Back
                <br>
                {{DB::table('store_profiles')->value('name')}}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Sign in to access your account</p>
        </div>

        <!-- Social Login Buttons -->
        <!-- <div class="flex gap-4 mb-6">
            <a href="#" class="flex-1 flex items-center justify-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg transition-all duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium">Facebook</span>
            </a>
            <a href="#" class="flex-1 flex items-center justify-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg transition-all duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"></path>
                </svg>
                <span class="text-sm font-medium">Google</span>
            </a>
        </div> -->

        <!-- Divider -->
        

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Field with Floating Label -->
            <div class="relative mb-6">
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="peer w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror"
                    required autocomplete="email" autofocus placeholder=" ">
                <label for="email" class="absolute left-3 top-3 px-1 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 transition-all duration-200 pointer-events-none peer-focus:text-blue-600 peer-focus:dark:text-blue-400 peer-focus:text-sm peer-focus:-translate-y-5 peer-focus:bg-white peer-focus:dark:bg-gray-800 peer-placeholder-shown:text-base peer-placeholder-shown:translate-y-0">
                    Email Address
                </label>
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Password Field with Floating Label -->
            <div class="relative mb-6">
                <input type="password" id="password" name="password"
                    class="peer w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror"
                    required autocomplete="current-password" placeholder=" ">
                <label for="password" class="absolute left-3 top-3 px-1 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 transition-all duration-200 pointer-events-none peer-focus:text-blue-600 peer-focus:dark:text-blue-400 peer-focus:text-sm peer-focus:-translate-y-5 peer-focus:bg-white peer-focus:dark:bg-gray-800 peer-placeholder-shown:text-base peer-placeholder-shown:translate-y-0">
                    Password
                </label>
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                    <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:underline dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200"
                        href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit Button with Animation -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <span class="font-semibold">Sign In</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Registration Link -->
            @if (Route::has('register'))
                <div class="mt-8 text-center">
                    <p class="text-gray-600 dark:text-gray-400">
                        Don't have an account? 
                        <a href="{{ route('register') }}"
                            class="text-blue-600 hover:underline dark:text-blue-400 font-medium hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200">
                            Create one
                        </a>
                    </p>
                </div>
            @endif
        </form>
    </div>

    <!-- Footer -->
    <div class="absolute bottom-4 text-center w-full">
        <p class="text-gray-500 dark:text-gray-400 text-sm">
            &copy; {{ date('Y') }} Your Company. All rights reserved.
        </p>
    </div>
</body>
@endsection