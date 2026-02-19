<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Materi & Benefit Peserta</h1>
        <p class="text-gray-500 text-sm mt-1">Download sertifikat, materi, dan presentasi dari konferensi yang Anda ikuti</p>
    </div>

    @if(!$hasAccess)
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 text-center">
        <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h3 class="font-semibold text-orange-800 mb-1">Akses Terkunci</h3>
        <p class="text-sm text-orange-700">Materi dan sertifikat hanya tersedia setelah pembayaran registrasi Anda diverifikasi.</p>
        <a href="{{ route('participant.payment') }}" class="inline-block mt-3 px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-medium hover:bg-orange-600 transition">Upload Bukti Pembayaran</a>
    </div>

    @elseif($conferenceGroups->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        <p class="font-medium">Belum ada materi yang tersedia</p>
        <p class="text-sm mt-1">Panitia akan segera mengupload materi konferensi.</p>
    </div>

    @else

    @foreach($conferenceGroups as $group)
    @php $conference = $group['conference']; $materials = $group['materials']; $total = $group['total']; @endphp

    {{-- Conference Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4 pb-3 border-b-2 border-teal-100">
            <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $conference->name }}</h2>
                <p class="text-xs text-gray-500">
                    {{ $conference->start_date ? \Carbon\Carbon::parse($conference->start_date)->translatedFormat('d F Y') : '' }}
                    @if($conference->end_date && $conference->end_date != $conference->start_date)
                        &mdash; {{ \Carbon\Carbon::parse($conference->end_date)->translatedFormat('d F Y') }}
                    @endif
                    &nbsp;&middot;&nbsp; {{ $total }} file tersedia
                </p>
            </div>
        </div>

        @if($materials->isEmpty())
        <p class="text-sm text-gray-400 italic pl-2">Belum ada materi yang diupload untuk kegiatan ini.</p>
        @else

        {{-- Sertifikat --}}
        @if($materials->has('sertifikat'))
        <div class="mb-5">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">&#127942; Sertifikat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($materials['sertifikat'] as $mat)
                <div class="bg-gradient-to-br from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
                    <div class="shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center text-xl">&#127942;</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm leading-tight">{{ $mat->title }}</p>
                        @if($mat->description)<p class="text-xs text-gray-500 mt-0.5">{{ $mat->description }}</p>@endif
                        <div class="flex items-center gap-2 mt-2">
                            <a href="{{ asset('storage/'.$mat->file_path) }}" download="{{ $mat->file_name }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-500 text-white rounded-lg text-xs font-medium hover:bg-yellow-600 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download
                            </a>
                            @if($mat->file_size)<span class="text-xs text-gray-400">{{ $mat->file_size }}</span>@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- PPT --}}
        @if($materials->has('ppt'))
        <div class="mb-5">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">&#128202; Presentasi (PPT)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($materials['ppt'] as $mat)
                <div class="bg-white border border-blue-100 rounded-xl p-4 flex items-start gap-3 hover:shadow-sm transition">
                    <div class="shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-xl">&#128202;</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm leading-tight">{{ $mat->title }}</p>
                        @if($mat->description)<p class="text-xs text-gray-500 mt-0.5 truncate">{{ $mat->description }}</p>@endif
                        <div class="flex items-center gap-2 mt-2">
                            <a href="{{ asset('storage/'.$mat->file_path) }}" download="{{ $mat->file_name }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-600 text-white rounded text-xs font-medium hover:bg-blue-700 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download
                            </a>
                            <a href="{{ asset('storage/'.$mat->file_path) }}" target="_blank"
                                class="inline-flex items-center gap-1 px-2.5 py-1 border border-gray-200 text-gray-600 rounded text-xs font-medium hover:bg-gray-50 transition">Lihat</a>
                            @if($mat->file_size)<span class="text-xs text-gray-400">{{ $mat->file_size }}</span>@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Materi --}}
        @if($materials->has('materi'))
        <div class="mb-5">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">&#128196; Materi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($materials['materi'] as $mat)
                <div class="bg-white border border-teal-100 rounded-xl p-4 flex items-start gap-3 hover:shadow-sm transition">
                    <div class="shrink-0 w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-xl">&#128196;</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm leading-tight">{{ $mat->title }}</p>
                        @if($mat->description)<p class="text-xs text-gray-500 mt-0.5 truncate">{{ $mat->description }}</p>@endif
                        <div class="flex items-center gap-2 mt-2">
                            <a href="{{ asset('storage/'.$mat->file_path) }}" download="{{ $mat->file_name }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-600 text-white rounded text-xs font-medium hover:bg-teal-700 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download
                            </a>
                            <a href="{{ asset('storage/'.$mat->file_path) }}" target="_blank"
                                class="inline-flex items-center gap-1 px-2.5 py-1 border border-gray-200 text-gray-600 rounded text-xs font-medium hover:bg-gray-50 transition">Lihat</a>
                            @if($mat->file_size)<span class="text-xs text-gray-400">{{ $mat->file_size }}</span>@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Lainnya --}}
        @if($materials->has('lainnya'))
        <div class="mb-5">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">&#128206; Lainnya</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($materials['lainnya'] as $mat)
                <div class="bg-white border rounded-xl p-4 flex items-start gap-3 hover:shadow-sm transition">
                    <div class="shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-xl">&#128206;</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm leading-tight">{{ $mat->title }}</p>
                        @if($mat->description)<p class="text-xs text-gray-500 mt-0.5 truncate">{{ $mat->description }}</p>@endif
                        <div class="flex items-center gap-2 mt-2">
                            <a href="{{ asset('storage/'.$mat->file_path) }}" download="{{ $mat->file_name }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-700 text-white rounded text-xs font-medium hover:bg-gray-800 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download
                            </a>
                            @if($mat->file_size)<span class="text-xs text-gray-400">{{ $mat->file_size }}</span>@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif {{-- end materials not empty --}}
    </div> {{-- end conference group --}}
    @endforeach

    @endif
</div>
