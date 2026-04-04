<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $guestSiteName = \App\Models\Setting::getValue('site_name', config('app.name', 'Prosiding'));
        $guestSiteTagline = \App\Models\Setting::getValue('site_tagline', 'Sistem Manajemen Prosiding');
        $guestSiteLogo = \App\Models\Setting::getValue('site_logo');
        $guestFavicon = \App\Models\Setting::getValue('site_favicon');
    @endphp
    <title>@yield('title', $guestSiteName)</title>
    @if($guestFavicon)
        <link rel="icon" href="{{ asset('storage/' . $guestFavicon) }}" type="image/png">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            @if($guestSiteLogo)
                <img src="{{ asset('storage/' . $guestSiteLogo) }}" alt="{{ $guestSiteName }}" class="h-16 mx-auto mb-3 object-contain">
            @endif
            <h1 class="text-3xl font-bold text-blue-800">{{ $guestSiteName }}</h1>
            <p class="text-gray-500 mt-2">{{ $guestSiteTagline }}</p>
        </div>
        @yield('content')
    </div>
    @livewireScripts
</body>
</html>
