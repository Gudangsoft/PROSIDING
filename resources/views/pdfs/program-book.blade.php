<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Serif', serif; font-size: 10pt; color: #1a1a1a; line-height: 1.5; }
    .cover { page-break-after: always; text-align: center; padding: 80px 40px; }
    .cover h1 { font-size: 24pt; font-weight: bold; margin-bottom: 12px; color: #1e3a5f; }
    .cover h2 { font-size: 14pt; font-weight: normal; margin-bottom: 40px; color: #555; }
    .cover .divider { border: 2px solid #1e3a5f; width: 80px; margin: 20px auto; }
    .cover .meta { font-size: 10pt; color: #666; margin-top: 20px; }
    .toc { page-break-after: always; }
    .toc h2 { font-size: 16pt; font-weight: bold; border-bottom: 2px solid #1e3a5f; padding-bottom: 8px; margin-bottom: 16px; color: #1e3a5f; }
    .toc-item { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dotted #ccc; }
    .toc-item .toc-title { flex: 1; padding-right: 10px; }
    .toc-item .toc-page { width: 30px; text-align: right; }
    .section-header { page-break-before: always; background: #1e3a5f; color: white; padding: 12px 20px; margin-bottom: 20px; font-size: 14pt; font-weight: bold; }
    .paper-entry { margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; }
    .paper-number { font-size: 8pt; color: #888; margin-bottom: 4px; }
    .paper-title { font-size: 12pt; font-weight: bold; color: #1e3a5f; margin-bottom: 6px; }
    .paper-authors { font-size: 9pt; color: #555; margin-bottom: 6px; font-style: italic; }
    .paper-meta { font-size: 8pt; color: #888; margin-bottom: 8px; }
    .paper-abstract { font-size: 9pt; line-height: 1.6; color: #333; text-align: justify; }
    .doi-badge { display: inline-block; background: #dbeafe; color: #1d4ed8; padding: 2px 8px; border-radius: 4px; font-size: 8pt; margin-top: 4px; }
    .schedule-section { margin-top: 20px; }
    .schedule-item { display: flex; gap: 16px; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .schedule-date { font-weight: bold; color: #1e3a5f; width: 120px; flex-shrink: 0; font-size: 9pt; }
    .schedule-desc { font-size: 9pt; }
    .page-footer { margin-top: 40px; text-align: center; font-size: 8pt; color: #999; }
    .keynote-card { padding: 8px 0; border-bottom: 1px solid #eee; margin-bottom: 8px; }
    .keynote-name { font-weight: bold; font-size: 11pt; color: #1e3a5f; }
    .keynote-affil { font-size: 9pt; color: #666; }
</style>
</head>
<body>

{{-- COVER --}}
@if($includeCover)
<div class="cover">
    @if($conference->logo)
    <img src="{{ public_path('storage/' . ltrim($conference->logo, '/')) }}" style="max-height:80px; max-width:200px; margin-bottom:20px;">
    @endif
    <h1>{{ $bookTitle }}</h1>
    @if($bookSubtitle) <h2>{{ $bookSubtitle }}</h2> @endif
    <div class="divider"></div>
    <p style="font-size:12pt; font-weight:bold; margin-top:20px;">{{ $conference->name }}</p>
    @if($conference->start_date)
    <p class="meta">{{ $conference->start_date->translatedFormat('d F Y') }}
        @if($conference->end_date && $conference->end_date->ne($conference->start_date)) – {{ $conference->end_date->translatedFormat('d F Y') }}@endif
    </p>
    @endif
    @if($conference->venue || $conference->city)
    <p class="meta">{{ implode(', ', array_filter([$conference->venue, $conference->city])) }}</p>
    @endif
    @if($conference->organizer)
    <p class="meta" style="margin-top:16px;">{{ $conference->organizer }}</p>
    @endif
    <p class="meta" style="margin-top:40px; font-size:8pt; color:#aaa;">Generated: {{ $generatedAt }}</p>
</div>
@endif

{{-- TABLE OF CONTENTS --}}
@if($includeToc && $papers->count() > 0)
<div class="toc">
    <h2>Daftar Isi / Table of Contents</h2>
    @foreach($papers as $i => $paper)
    <div class="toc-item">
        <span class="toc-title">{{ $i+1 }}. {{ $paper->title }}</span>
    </div>
    @endforeach
</div>
@endif

{{-- PROGRAM SCHEDULE --}}
@if($includeProgramSchedule && $conference->importantDates->count() > 0)
<div style="page-break-after: always; padding: 20px 0;">
    <h2 style="font-size:14pt; font-weight:bold; color:#1e3a5f; border-bottom:2px solid #1e3a5f; padding-bottom:8px; margin-bottom:16px;">Program & Jadwal Kegiatan</h2>
    @foreach($conference->importantDates as $d)
    <div class="schedule-item">
        <div class="schedule-date">{{ $d->date->translatedFormat('d M Y') }}</div>
        <div class="schedule-desc"><strong>{{ $d->title }}</strong>{{ $d->description ? ' — ' . $d->description : '' }}</div>
    </div>
    @endforeach
    @if($conference->keynoteSpeakers->count() > 0)
    <h3 style="font-size:12pt; font-weight:bold; margin-top:28px; margin-bottom:12px; color:#1e3a5f;">Keynote Speakers</h3>
    @foreach($conference->keynoteSpeakers as $spk)
    <div class="keynote-card">
        <div class="keynote-name">{{ $spk->name }}</div>
        <div class="keynote-affil">{{ implode(' | ', array_filter([$spk->title, $spk->affiliation])) }}</div>
        @if($spk->topic) <div class="schedule-desc" style="margin-top:4px; font-size:9pt;">Topik: {{ $spk->topic }}</div> @endif
    </div>
    @endforeach
    @endif
</div>
@endif

{{-- PAPERS --}}
@foreach($grouped as $group => $groupPapers)
<div>
    @if(count($grouped) > 1)
    <div class="section-header">{{ $group ?: 'Umum' }}</div>
    @endif
    @foreach($groupPapers as $i => $paper)
    <div class="paper-entry">
        <div class="paper-number">Paper #{{ str_pad($paper->id, 4, '0', STR_PAD_LEFT) }}{{ $paper->doi ? ' | DOI: ' . $paper->doi : '' }}</div>
        <div class="paper-title">{{ $paper->title }}</div>
        @if($includeAuthors)
        <div class="paper-authors">
            @if(!empty($paper->authors_meta))
                {{ collect($paper->authors_meta)->pluck('name')->join(', ') }}
            @else
                {{ $paper->user->name }}
            @endif
        </div>
        @endif
        @if($paper->keywords)
        <div class="paper-meta">Keywords: {{ $paper->keywords }}</div>
        @endif
        @if($includeAbstracts && $paper->abstract)
        <div class="paper-abstract"><strong>Abstract: </strong>{{ strip_tags($paper->abstract) }}</div>
        @endif
    </div>
    @endforeach
</div>
@endforeach

<div class="page-footer">
    <p>{{ $bookTitle }} &mdash; {{ $conference->name }} &mdash; {{ $generatedAt }}</p>
</div>

</body>
</html>
