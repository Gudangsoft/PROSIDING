@extends('layouts.app')

@section('title', 'Dashboard - Prosiding LPKD-APJI')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}! <span class="text-xs">({{ ucfirst(Auth::user()->role) }})</span></p>
    </div>

    @php
        $user = Auth::user();
    @endphp

    {{-- ============ ACTIVE ANNOUNCEMENTS (ALL ROLES) ============ --}}
    @php
        $activeAnnouncements = \App\Models\Announcement::published()
            ->forAudience($user->role)
            ->orderByDesc('is_pinned')
            ->orderByDesc('priority')
            ->latest('published_at')
            ->take(5)->get();
    @endphp
    @if($activeAnnouncements->count())
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengumuman</h2>
        <div class="space-y-3">
            @foreach($activeAnnouncements as $ann)
            @php
                $colors = ['info'=>'blue','warning'=>'yellow','success'=>'green','danger'=>'red','deadline'=>'orange','result'=>'purple'];
                $c = $colors[$ann->type] ?? 'gray';
            @endphp
            <div class="bg-{{ $c }}-50 border-l-4 border-{{ $c }}-500 rounded-r-lg p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            @if($ann->is_pinned)<svg class="w-4 h-4 text-{{ $c }}-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 5a2 2 0 012-2h6a2 2 0 012 2v2h2a1 1 0 010 2h-1l-1 9a2 2 0 01-2 2H7a2 2 0 01-2-2L4 9H3a1 1 0 110-2h2V5z"/></svg>@endif
                            <h3 class="font-semibold text-{{ $c }}-800 text-sm">{{ $ann->title }}</h3>
                        </div>
                        <p class="text-{{ $c }}-700 text-sm mt-1">{{ Str::limit(strip_tags($ann->content), 150) }}</p>
                    </div>
                    <span class="text-xs text-{{ $c }}-500 whitespace-nowrap ml-3">{{ $ann->published_at?->diffForHumans() }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ============ ACTIVE CONFERENCE INFO (ALL ROLES) ============ --}}
    @php
        $activeConference = \App\Models\Conference::active()->first();
    @endphp
    @if($activeConference)
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $activeConference->name }}</h2>
                @if($activeConference->theme)<p class="text-sm text-gray-500 mt-1">{{ $activeConference->theme }}</p>@endif
                    @if($activeConference->meeting_link)
                    <div class="mt-2">
                        <a href="{{ $activeConference->meeting_link }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Join Zoom Kegiatan
                        </a>
                    </div>
                    @endif
            </div>
            @if($activeConference->acronym)<span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded">{{ $activeConference->acronym }}</span>@endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            @if($activeConference->start_date)
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $activeConference->date_range }}
            </div>
            @endif
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\Conference::VENUE_TYPE_ICONS[$activeConference->venue_type ?? 'offline'] ?? '' }}"/></svg>
                <span class="inline-flex items-center gap-1.5">
                    <span class="px-1.5 py-0.5 rounded text-xs font-medium {{ \App\Models\Conference::VENUE_TYPE_COLORS[$activeConference->venue_type ?? 'offline'] ?? '' }}">{{ $activeConference->venue_type_label }}</span>
                    {{ $activeConference->venue_display }}
                </span>
            </div>
            @if($activeConference->organizer)
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                {{ $activeConference->organizer }}
            </div>
            @endif
        </div>
        @php $importantDates = $activeConference->importantDates()->orderBy('date')->get(); @endphp
        @if($importantDates->count())
        <div class="mt-5 pt-4 border-t">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Tanggal Penting</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                @foreach($importantDates as $d)
                <div class="flex items-center gap-2 text-sm {{ $d->is_past ? 'text-gray-400 line-through' : 'text-gray-700' }}">
                    <span class="w-2 h-2 rounded-full {{ $d->is_past ? 'bg-gray-300' : 'bg-blue-500' }} shrink-0"></span>
                    <span class="font-medium">{{ $d->date->format('d M Y') }}</span>
                    <span class="text-gray-500">— {{ $d->title }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ============ AUTHOR DASHBOARD ============ --}}
    @if($user->isAuthor())
    @php
        $totalPapers = $user->papers()->count();
        $accepted = $user->papers()->whereIn('status', ['accepted','payment_pending','payment_uploaded','payment_verified','deliverables_pending','completed'])->count();
        $inReview = $user->papers()->whereIn('status', ['screening','in_review','revised'])->count();
        $needsAction = $user->papers()->whereIn('status', ['revision_required','payment_pending'])->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                <div class="ml-4"><p class="text-sm text-gray-500">Total Paper</p><p class="text-2xl font-bold text-gray-800">{{ $totalPapers }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div class="ml-4"><p class="text-sm text-gray-500">Accepted</p><p class="text-2xl font-bold text-gray-800">{{ $accepted }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg"><svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div class="ml-4"><p class="text-sm text-gray-500">Dalam Review</p><p class="text-2xl font-bold text-gray-800">{{ $inReview }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center">
                <div class="bg-orange-100 p-3 rounded-lg"><svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg></div>
                <div class="ml-4"><p class="text-sm text-gray-500">Perlu Tindakan</p><p class="text-2xl font-bold text-gray-800">{{ $needsAction }}</p></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('author.submit') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <div><p class="font-medium text-gray-800">Submit Paper</p><p class="text-sm text-gray-500">Upload paper baru</p></div>
            </a>
            <a href="{{ route('author.papers') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <div><p class="font-medium text-gray-800">Paper Saya</p><p class="text-sm text-gray-500">Lihat semua paper</p></div>
            </a>
            <a href="{{ route('author.papers') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <div><p class="font-medium text-gray-800">Tracking</p><p class="text-sm text-gray-500">Cek progress paper</p></div>
            </a>
        </div>
    </div>

    {{-- Recent Papers --}}
    @php $recentPapers = $user->papers()->latest()->take(5)->get(); @endphp
    @if($recentPapers->count())
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b"><h3 class="font-semibold text-gray-800">Paper Terbaru</h3></div>
        <table class="w-full text-sm">
            <tbody class="divide-y">
                @foreach($recentPapers as $paper)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ Str::limit($paper->title, 60) }}</td>
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if($paper->status_color==='green') bg-green-100 text-green-800
                            @elseif($paper->status_color==='red') bg-red-100 text-red-800
                            @elseif(in_array($paper->status_color,['yellow','amber'])) bg-yellow-100 text-yellow-800
                            @elseif(in_array($paper->status_color,['blue','cyan'])) bg-blue-100 text-blue-800
                            @elseif($paper->status_color==='orange') bg-orange-100 text-orange-800
                            @else bg-gray-100 text-gray-800 @endif">{{ $paper->status_label }}</span>
                    </td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $paper->submitted_at?->format('d M Y') }}</td>
                    <td class="px-6 py-3"><a href="{{ route('author.paper.detail', $paper) }}" class="text-blue-600 text-sm">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endif

    {{-- ============ REVIEWER DASHBOARD ============ --}}
    @if($user->isReviewer())
    @php
        $pendingReviews = $user->reviews()->where('status', 'pending')->count();
        $inProgress = $user->reviews()->where('status', 'in_progress')->count();
        $completedReviews = $user->reviews()->where('status', 'completed')->count();
        $totalReviews = $user->reviews()->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
                <div class="ml-4"><p class="text-sm text-gray-500">Total Tugas</p><p class="text-2xl font-bold text-gray-800">{{ $totalReviews }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg"><svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div class="ml-4"><p class="text-sm text-gray-500">Pending</p><p class="text-2xl font-bold text-gray-800">{{ $pendingReviews }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center">
                <div class="bg-indigo-100 p-3 rounded-lg"><svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
                <div class="ml-4"><p class="text-sm text-gray-500">In Progress</p><p class="text-2xl font-bold text-gray-800">{{ $inProgress }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div class="ml-4"><p class="text-sm text-gray-500">Selesai</p><p class="text-2xl font-bold text-gray-800">{{ $completedReviews }}</p></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
        <a href="{{ route('reviewer.reviews') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">Lihat Semua Tugas Review</a>
    </div>
    @endif

    {{-- ============ EDITOR DASHBOARD ============ --}}
    @if($user->isEditor())
    @php
        $totalPapers = \App\Models\Paper::count();
        $assignedToMe = \App\Models\Paper::where('assigned_editor_id', $user->id)->count();
        $newSubmissions = \App\Models\Paper::where('status', 'submitted')->count();
        $inReview = \App\Models\Paper::where('status', 'in_review')->count();
        $accepted = \App\Models\Paper::whereIn('status', ['accepted','payment_pending','payment_uploaded','payment_verified','deliverables_pending','completed'])->count();
        $pendingPayments = \App\Models\Payment::where('status', 'uploaded')->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $totalPapers }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Paper</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-purple-600">{{ $assignedToMe }}</p>
            <p class="text-xs text-gray-500 mt-1">Ditugaskan ke Saya</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $newSubmissions }}</p>
            <p class="text-xs text-gray-500 mt-1">Baru Masuk</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $inReview }}</p>
            <p class="text-xs text-gray-500 mt-1">Dalam Review</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-green-600">{{ $accepted }}</p>
            <p class="text-xs text-gray-500 mt-1">Accepted</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $pendingPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Bayar Pending</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.papers') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Kelola Paper</p><p class="text-xs text-gray-500">Review, assign reviewer, update status</p></div>
                </a>
                <a href="{{ route('admin.payments') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Verifikasi Pembayaran</p><p class="text-xs text-gray-500">{{ $pendingPayments }} pembayaran menunggu</p></div>
                </a>
                <a href="{{ route('admin.conferences') }}" class="flex items-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                    <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Kegiatan Prosiding</p><p class="text-xs text-gray-500">Lihat kegiatan terdaftar</p></div>
                </a>
            </div>
        </div>

        {{-- Latest Submissions --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b"><h3 class="font-semibold text-gray-800">Submission Terbaru</h3></div>
            @php $latestPapers = \App\Models\Paper::with('user')->latest()->take(5)->get(); @endphp
            @if($latestPapers->count())
            <div class="divide-y">
                @foreach($latestPapers as $paper)
                <div class="px-6 py-3 flex justify-between items-center hover:bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ Str::limit($paper->title, 40) }}</p>
                        <p class="text-xs text-gray-400">{{ $paper->user->name }} &bull; {{ $paper->submitted_at?->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.paper.detail', $paper) }}" class="text-blue-600 text-xs font-medium">Kelola</a>
                </div>
                @endforeach
            </div>
            @else
            <p class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada submission.</p>
            @endif
        </div>
    </div>
    @endif

    {{-- ============ TREASURER (BENDAHARA) DASHBOARD ============ --}}
    @if($user->isTreasurer())
    @php
        $pendingPayments = \App\Models\Payment::where('status', 'uploaded')->count();
        $verifiedPayments = \App\Models\Payment::where('status', 'verified')->count();
        $totalRevenue = \App\Models\Payment::where('status', 'verified')->sum('amount');
        $todayPayments = \App\Models\Payment::whereDate('created_at', today())->count();
        $weekPayments = \App\Models\Payment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthPayments = \App\Models\Payment::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $pendingPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Menunggu Verifikasi</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-green-600">{{ $verifiedPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Terverifikasi</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Pendapatan</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $todayPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Hari Ini</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $weekPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Minggu Ini</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-purple-600">{{ $monthPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Bulan Ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
            <div class="space-y-3">
                <a href="{{ route('treasurer.payments') }}" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                    <svg class="w-6 h-6 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Verifikasi Pembayaran</p><p class="text-xs text-gray-500">{{ $pendingPayments }} pembayaran menunggu verifikasi</p></div>
                </a>
                <a href="{{ route('treasurer.reports') }}" class="flex items-center p-3 bg-teal-50 rounded-lg hover:bg-teal-100 transition">
                    <svg class="w-6 h-6 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Laporan Keuangan</p><p class="text-xs text-gray-500">Lihat laporan & eksport data</p></div>
                </a>
                <a href="{{ route('treasurer.reports.revenue') }}" class="flex items-center p-3 bg-cyan-50 rounded-lg hover:bg-cyan-100 transition">
                    <svg class="w-6 h-6 text-cyan-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Export Pendapatan</p><p class="text-xs text-gray-500">Download laporan Excel</p></div>
                </a>
            </div>
        </div>

        {{-- Recent Payments --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Pembayaran Terbaru</h3>
                <a href="{{ route('treasurer.payments') }}" class="text-emerald-600 text-xs font-medium">Lihat Semua</a>
            </div>
            @php $recentPayments = \App\Models\Payment::with('user')->latest()->take(5)->get(); @endphp
            @if($recentPayments->count())
            <div class="divide-y">
                @foreach($recentPayments as $payment)
                <div class="px-6 py-3 flex justify-between items-center hover:bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $payment->user?->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">Rp {{ number_format($payment->amount, 0, ',', '.') }} &bull; {{ $payment->created_at?->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $payment->status === 'verified' ? 'bg-green-100 text-green-700' : ($payment->status === 'uploaded' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada pembayaran.</p>
            @endif
        </div>
    </div>
    @endif

    {{-- ============ ADMIN DASHBOARD ============ --}}
    @if($user->isAdmin())
    @php
        $totalPapers = \App\Models\Paper::count();
        $newSubmissions = \App\Models\Paper::where('status', 'submitted')->count();
        $inReview = \App\Models\Paper::where('status', 'in_review')->count();
        $accepted = \App\Models\Paper::whereIn('status', ['accepted','payment_pending','payment_uploaded','payment_verified','deliverables_pending','completed'])->count();
        $pendingPayments = \App\Models\Payment::where('status', 'uploaded')->count();
        $totalUsers = \App\Models\User::count();
        $totalConferences = \App\Models\Conference::count();
        $totalNews = \App\Models\News::count();
        $totalAnnouncements = \App\Models\Announcement::count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $totalPapers }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Paper</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $newSubmissions }}</p>
            <p class="text-xs text-gray-500 mt-1">Baru Masuk</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $inReview }}</p>
            <p class="text-xs text-gray-500 mt-1">Dalam Review</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-green-600">{{ $accepted }}</p>
            <p class="text-xs text-gray-500 mt-1">Accepted</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-purple-600">{{ $pendingPayments }}</p>
            <p class="text-xs text-gray-500 mt-1">Bayar Pending</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border text-center">
            <p class="text-2xl font-bold text-gray-600">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-500 mt-1">Pengguna</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.conferences') }}" class="flex items-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                    <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Kegiatan Prosiding</p><p class="text-xs text-gray-500">{{ $totalConferences }} kegiatan terdaftar</p></div>
                </a>
                <a href="{{ route('admin.papers') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Kelola Paper</p><p class="text-xs text-gray-500">Review, assign reviewer, update status</p></div>
                </a>
                <a href="{{ route('admin.payments') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Verifikasi Pembayaran</p><p class="text-xs text-gray-500">{{ $pendingPayments }} pembayaran menunggu</p></div>
                </a>
                <a href="{{ route('admin.news') }}" class="flex items-center p-3 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                    <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Kelola Berita</p><p class="text-xs text-gray-500">{{ $totalNews }} berita</p></div>
                </a>
                <a href="{{ route('admin.announcements') }}" class="flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <div><p class="font-medium text-sm text-gray-800">Kelola Pengumuman</p><p class="text-xs text-gray-500">{{ $totalAnnouncements }} pengumuman</p></div>
                </a>
            </div>
        </div>

        {{-- Latest Submissions --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b"><h3 class="font-semibold text-gray-800">Submission Terbaru</h3></div>
            @php $latestPapers = \App\Models\Paper::with('user')->latest()->take(5)->get(); @endphp
            @if($latestPapers->count())
            <div class="divide-y">
                @foreach($latestPapers as $paper)
                <div class="px-6 py-3 flex justify-between items-center hover:bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ Str::limit($paper->title, 40) }}</p>
                        <p class="text-xs text-gray-400">{{ $paper->user->name }} &bull; {{ $paper->submitted_at?->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.paper.detail', $paper) }}" class="text-blue-600 text-xs font-medium">Kelola</a>
                </div>
                @endforeach
            </div>
            @else
            <p class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada submission.</p>
            @endif
        </div>
    </div>

    {{-- Latest News & Announcements --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Latest News --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Berita Terbaru</h3>
                <a href="{{ route('admin.news') }}" class="text-blue-600 text-xs font-medium">Lihat Semua</a>
            </div>
            @php $latestNews = \App\Models\News::latest()->take(5)->get(); @endphp
            @if($latestNews->count())
            <div class="divide-y">
                @foreach($latestNews as $n)
                <div class="px-6 py-3 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ Str::limit($n->title, 40) }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">{{ $n->category_label }}</span>
                                <span class="text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <span class="text-xs px-1.5 py-0.5 rounded {{ $n->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($n->status) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada berita.</p>
            @endif
        </div>

        {{-- Latest Announcements --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Pengumuman Terbaru</h3>
                <a href="{{ route('admin.announcements') }}" class="text-blue-600 text-xs font-medium">Lihat Semua</a>
            </div>
            @php $latestAnns = \App\Models\Announcement::latest()->take(5)->get(); @endphp
            @if($latestAnns->count())
            <div class="divide-y">
                @foreach($latestAnns as $ann)
                <div class="px-6 py-3 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ Str::limit($ann->title, 40) }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs px-1.5 py-0.5 rounded bg-{{ \App\Models\Announcement::TYPE_COLORS[$ann->type] ?? 'gray' }}-100 text-{{ \App\Models\Announcement::TYPE_COLORS[$ann->type] ?? 'gray' }}-700">{{ $ann->type_label }}</span>
                                <span class="text-xs text-gray-400">Untuk: {{ implode(', ', array_map('ucfirst', (array) $ann->audience)) }}</span>
                            </div>
                        </div>
                        <span class="text-xs px-1.5 py-0.5 rounded {{ $ann->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($ann->status) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada pengumuman.</p>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
