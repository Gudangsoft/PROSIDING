@extends('layouts.guest')

@section('title', 'Login - ' . \App\Models\Setting::getValue('site_name', config('app.name')))

@section('content')
<div class="bg-white shadow-2xl p-8 sm:p-12 max-w-lg mx-auto" style="border-radius: 16px;">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Login</h2>

    @if (session('status'))
        <div class="mb-5 text-sm text-green-700 bg-green-50 border border-green-200 p-3 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-5">
            <label for="email" class="block text-base font-medium text-gray-700 mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('email') border-red-500 @enderror"
                placeholder="Masukkan email Anda">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="password" class="block text-base font-medium text-gray-700 mb-2">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('password') border-red-500 @enderror"
                placeholder="Masukkan password Anda">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium transition">
                Lupa Password?
            </a>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3.5 rounded-lg font-semibold hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all shadow-md text-base">
            Masuk
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-orange-600 hover:text-orange-700 transition">Daftar di sini</a>
    </p>
</div>
@endsection
