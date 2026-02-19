{{-- Navbar Brand: Site Name + Tagline (shared across all templates) --}}
@php
    $__navName = $siteName ?? \App\Models\Setting::getValue('site_name', 'Prosiding');
    $__navTagline = ($siteTagline ?? null) ?: \App\Models\Setting::getValue('site_tagline');
@endphp
<div class="min-w-0">
    <span class="text-lg font-bold text-gray-800 leading-tight block">{{ $__navName }}</span>
    @if($__navTagline)
    <span class="text-xs text-gray-500 leading-tight block truncate max-w-[200px] sm:max-w-xs">{{ $__navTagline }}</span>
    @endif
</div>
