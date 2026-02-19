    {{-- ═══════════════════════════════════════════════════════════════════
         CTA — Call for Paper
    ═══════════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-blue-300 rounded-full filter blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">{{ __('welcome.cta.title') }}</h2>
            <p class="text-blue-100 mb-10 text-lg max-w-2xl mx-auto">{{ __('welcome.cta.description') }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-white text-blue-700 px-10 py-4 rounded-xl font-bold hover:bg-blue-50 transition text-lg shadow-xl inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        {{ __('welcome.cta.submit_now') }}
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-blue-700 px-10 py-4 rounded-xl font-bold hover:bg-blue-50 transition text-lg shadow-xl inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        {{ __('welcome.cta.daftar_submit') }}
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-white/80 text-white px-10 py-4 rounded-xl font-bold hover:bg-white hover:text-blue-700 transition text-lg inline-flex items-center justify-center">Login</a>
                @endauth
            </div>
        </div>
    </section>
