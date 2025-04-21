@extends('layouts.login')

@section('content')
<body class="bg-gray-100 dark:bg-gray-900 flex justify-center items-center min-h-screen p-4">
    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-md w-full max-w-md mx-auto">
        <div class="text-center mb-6 flex flex-col items-center">
            <div class="mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16">
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Sign in to your account</h1>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Email</label>
                <input type="email" id="email"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required
                    autocomplete="email" autofocus placeholder="Enter your email">
                @error('email')
                    <span class="text-red-500 text-xs mt-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Password</label>
                <input type="password" id="password"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"
                    placeholder="Enter your password">
                @error('password')
                    <span class="text-red-500 text-xs mt-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                        href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Sign in
            </button>

            <div class="mt-6 text-center">
                <p class="text-gray-600 dark:text-gray-400">Don't have an account? <a href="{{ route('register') }}"
                        class="text-blue-600 hover:underline dark:text-blue-400">Register
                    </a></p>
            </div>
        </form>
    </div>
</body>
@endsection