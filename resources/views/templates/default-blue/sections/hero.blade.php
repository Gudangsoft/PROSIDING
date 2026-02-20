    {{-- ═══════════════════════════════════════════════════════════════════
         HERO SLIDER
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($sliders->count() > 0 && (!$activeConference || $activeConference->isSectionVisible('hero')))
    <section x-data="{
        current: 0,
        total: {{ $sliders->count() }},
        paused: false,
        init() { setInterval(() => { if (!this.paused) this.current = (this.current + 1) % this.total; }, 5000); },
        next() { this.current = (this.current + 1) % this.total; },
        prev() { this.current = (this.current - 1 + this.total) % this.total; },
    }" @mouseenter="paused = true" @mouseleave="paused = false" class="relative w-full overflow-hidden" style="height: 520px;">
        @foreach($sliders as $index => $slider)
        <div x-show="current === {{ $index }}"
             x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="absolute inset-0 w-full h-full" {{ $index > 0 ? 'x-cloak' : '' }}>
            <div class="absolute inset-0">
                <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-0" style="background: {{ $slider->overlay_color ?? 'rgba(0,0,0,0.45)' }};"></div>
            </div>
            <div class="relative h-full flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl {{ $slider->text_position === 'center' ? 'mx-auto text-center' : ($slider->text_position === 'right' ? 'ml-auto text-right' : 'text-left') }}">
                        @if($slider->subtitle)
                        <p class="text-sm md:text-base font-semibold mb-3 tracking-widest uppercase {{ $slider->text_color === 'dark' ? 'text-gray-700' : 'text-blue-200' }}">{{ $slider->subtitle }}</p>
                        @endif
                        @if($slider->title)
                        <h2 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight {{ $slider->text_color === 'dark' ? 'text-gray-900' : 'text-white' }}">{{ $slider->title }}</h2>
                        @endif
                        @if($slider->description)
                        <p class="text-base md:text-lg mb-8 leading-relaxed {{ $slider->text_color === 'dark' ? 'text-gray-600' : 'text-gray-200' }}">{{ $slider->description }}</p>
                        @endif
                        <div class="flex gap-3 {{ $slider->text_position === 'center' ? 'justify-center' : ($slider->text_position === 'right' ? 'justify-end' : 'justify-start') }}">
                            @if($slider->button_text && $slider->button_url)
                            <a href="{{ $slider->button_url }}" class="inline-flex items-center gap-2 px-7 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg">{{ $slider->button_text }} <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
                            @endif
                            @if($slider->button_text_2 && $slider->button_url_2)
                            <a href="{{ $slider->button_url_2 }}" class="inline-flex items-center gap-2 px-7 py-3 border-2 rounded-lg font-semibold transition {{ $slider->text_color === 'dark' ? 'border-gray-800 text-gray-800 hover:bg-gray-800 hover:text-white' : 'border-white text-white hover:bg-white hover:text-gray-800' }}">{{ $slider->button_text_2 }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @if($sliders->count() > 1)
        <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-black/40 text-white p-3 rounded-full transition z-10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
        <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-black/40 text-white p-3 rounded-full transition z-10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
            @foreach($sliders as $index => $s)
            <button @click="current={{ $index }}" :class="current==={{ $index }} ? 'bg-white w-8' : 'bg-white/40 w-3'" class="h-3 rounded-full transition-all duration-300"></button>
            @endforeach
        </div>
        @endif
    </section>
    @else
    {{-- Fallback Hero --}}
    <section class="relative bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-300 rounded-full filter blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">{{ $siteName }}</h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-4 max-w-3xl mx-auto">{{ $siteTagline }}</p>
            <p class="text-blue-200 mb-10 max-w-2xl mx-auto">{{ __('welcome.hero.description') }}</p>
            <div class="flex justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-white text-blue-700 px-8 py-3.5 rounded-xl font-semibold hover:bg-blue-50 transition text-lg shadow-lg">{{ __('welcome.hero.dashboard') }}</a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-blue-700 px-8 py-3.5 rounded-xl font-semibold hover:bg-blue-50 transition text-lg shadow-lg">{{ __('welcome.hero.submit_paper') }}</a>
                    <a href="{{ route('login') }}" class="border-2 border-white/80 text-white px-8 py-3.5 rounded-xl font-semibold hover:bg-white hover:text-blue-700 transition text-lg">Login</a>
                @endauth
            </div>
        </div>
    </section>
    @endif
