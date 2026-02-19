<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $guestSiteName = \App\Models\Setting::getValue('site_name', 'Prosiding LPKD-APJI');
        $guestTagline = \App\Models\Setting::getValue('site_tagline', 'Sistem Manajemen Prosiding');
        $guestFavicon = \App\Models\Setting::getValue('site_favicon');
        $guestLogo = \App\Models\Setting::getValue('site_logo');
        $__themePreset = \App\Models\ThemePreset::getActive();
        $__loginBg = $__themePreset->login_bg_image ?? null;
        $__primary = $__themePreset->primary_color ?? '#2563eb';
        $__secondary = $__themePreset->secondary_color ?? '#4f46e5';
        $__accent = $__themePreset->accent_color ?? '#0891b2';
        $__bodyBg = $__themePreset->body_bg ?? '#f3f4f6';
        $__heroBg = $__themePreset->hero_bg ?? '#1e40af';
        $__buttonBg = $__themePreset->button_bg ?? '#0d9488';
        $__buttonText = $__themePreset->button_text ?? '#ffffff';
        $__fontFamily = $__themePreset->font_family ?? 'Inter';
        $__borderRadius = $__themePreset->border_radius ?? '8';
    @endphp
    <title>@yield('title', $guestSiteName)</title>
    @if($guestFavicon)
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $guestFavicon) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    @if($__fontFamily && $__fontFamily !== 'Inter')
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($__fontFamily) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif
    @livewireStyles
    <style>
        :root {
            --login-primary: {{ $__primary }};
            --login-secondary: {{ $__secondary }};
            --login-accent: {{ $__accent }};
            --login-btn-bg: {{ $__buttonBg }};
            --login-btn-text: {{ $__buttonText }};
            --login-radius: {{ $__borderRadius }}px;
        }
        body.login-page {
            font-family: '{{ $__fontFamily }}', 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
        /* Form input focus rings match theme */
        .login-form input:focus {
            --tw-ring-color: {{ $__primary }}40;
            border-color: {{ $__primary }};
        }
        .login-form input[type="checkbox"] {
            color: {{ $__primary }};
        }
        .login-form input[type="checkbox"]:focus {
            --tw-ring-color: {{ $__primary }}40;
        }
        /* Links match theme */
        .login-form a { color: {{ $__primary }}; }
        .login-form a:hover { color: {{ $__secondary }}; }
        /* Primary button */
        .login-btn-primary {
            background-color: {{ $__buttonBg }};
            color: {{ $__buttonText }};
            border-radius: var(--login-radius);
        }
        .login-btn-primary:hover {
            background-color: color-mix(in srgb, {{ $__buttonBg }} 85%, black);
        }
        .login-btn-primary:focus {
            box-shadow: 0 0 0 4px {{ $__buttonBg }}30;
        }
        /* Background image overlay */
        @if($__loginBg)
        .login-bg-image {
            background-image: url('{{ asset('storage/' . $__loginBg) }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .login-bg-image::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, {{ $__primary }}cc, {{ $__secondary }}99);
            z-index: 0;
        }
        @endif
    </style>
</head>
<body class="login-page min-h-screen flex items-center justify-center {{ $__loginBg ? 'login-bg-image' : '' }}"
      @if(!$__loginBg)
      style="background: linear-gradient(135deg, color-mix(in srgb, {{ $__primary }} 8%, white), color-mix(in srgb, {{ $__secondary }} 12%, white));"
      @endif>
    <div class="w-full max-w-3xl relative z-10 px-4">
        <div class="text-center mb-8">
            @if($guestLogo)
            <img src="{{ asset('storage/' . $guestLogo) }}" alt="{{ $guestSiteName }}" class="h-14 mx-auto mb-3 object-contain {{ $__loginBg ? 'drop-shadow-lg' : '' }}">
            @endif
            <h1 class="text-3xl font-bold {{ $__loginBg ? 'text-white drop-shadow-lg' : '' }}"
                @if(!$__loginBg) style="color: {{ $__primary }};" @endif>
                {{ $guestSiteName }}
            </h1>
            @if($guestTagline)
            <p class="{{ $__loginBg ? 'text-white/80' : 'text-gray-500' }} mt-2">{{ $guestTagline }}</p>
            @endif
        </div>
        <div class="login-form">
            @yield('content')
        </div>
    </div>
    @livewireScripts
</body>
</html>
