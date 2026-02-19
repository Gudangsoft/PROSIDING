@extends('layouts.guest')

@section('title', 'Lupa Password - ' . \App\Models\Setting::getValue('site_name', config('app.name')))

@section('content')
<div class="bg-white shadow-2xl p-8 sm:p-12 max-w-lg mx-auto" style="border-radius: 16px;">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-3">Lupa Password</h2>
    <p class="text-center text-sm text-gray-500 mb-8">Masukkan email Anda untuk menerima link reset password.</p>

    @if (session('status'))
        <div class="mb-5 text-sm text-green-700 bg-green-50 border border-green-200 p-3 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
            <label for="email" class="block text-base font-medium text-gray-700 mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('email') border-red-500 @enderror"
                placeholder="Masukkan email Anda">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3.5 rounded-lg font-semibold hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all shadow-md text-base">
            Kirim Link Reset Password
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-700 transition">Kembali ke Login</a>
    </p>
</div>
@endsection
