<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sertifikat Saya</h1>
        <p class="text-gray-500 text-sm mt-1">Download sertifikat keikutsertaan Anda</p>
    </div>

    @if($certificates->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium mb-2">Belum ada sertifikat</p>
        <p class="text-gray-400 text-sm">Sertifikat akan tersedia setelah kegiatan selesai dan diterbitkan oleh panitia.</p>
    </div>
    @else
    <div class="grid gap-4">
        @foreach($certificates as $cert)
        <div class="bg-white rounded-xl shadow-sm border p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4">
                    {{-- Certificate Icon --}}
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-800">Sertifikat {{ $cert->type_label }}</h3>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $cert->conference?->name ?? '-' }}</p>
                        
                        @if($cert->paper)
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="font-medium">Paper:</span> {{ Str::limit($cert->paper->title, 60) }}
                        </p>
                        @endif
                        
                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                            <span>No: {{ $cert->certificate_number }}</span>
                            <span>•</span>
                            <span>Diterbitkan: {{ $cert->issued_at?->isoFormat('D MMMM Y') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                
                {{-- Download Button --}}
                <a href="{{ route('author.certificates.download', $cert) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg text-sm font-medium hover:from-amber-600 hover:to-orange-600 transition-all shadow-sm hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
