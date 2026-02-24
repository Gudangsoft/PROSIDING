@props(['date', 'label' => 'Deadline', 'class' => ''])
@php
    use Carbon\Carbon;
    $deadline = Carbon::parse($date);
    $now = Carbon::now();
    $isPast = $deadline->isPast();
    $diff = $deadline->diff($now);
    $totalHours = $deadline->diffInHours($now, false);
    $isUrgent = !$isPast && $totalHours > 0 && $totalHours <= 72; // within 3 days
@endphp

<div class="inline-flex items-center gap-2 {{ $class }}">
    @if($isPast)
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $label }}: Sudah Lewat
        <span class="font-normal">({{ $deadline->format('d M Y') }})</span>
    </span>
    @elseif($isUrgent)
    <span
        wire:poll.30s="$refresh"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-300 animate-pulse">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        ⚠️ {{ $label }}:
        @if($diff->d > 0){{ $diff->d }}h @endif
        {{ $diff->h }}j {{ $diff->i }}m lagi
    </span>
    @else
    <span
        wire:poll.60s="$refresh"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        ⏳ {{ $label }}:
        @if($diff->m > 0){{ $diff->m }} bln @endif
        @if($diff->d > 0){{ $diff->d }} hari @endif
        @if($diff->m === 0){{ $diff->h }}j {{ $diff->i }}m @endif
        lagi
    </span>
    @endif
</div>
