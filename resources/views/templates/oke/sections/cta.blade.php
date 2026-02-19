    {{-- ════════════════════════════════════════════════════
         CTA — Soft gradient
    ════════════════════════════════════════════════════ --}}
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-600 via-teal-700 to-emerald-700"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">{{ __('welcome.cta.title') }}</h2>
            <p class="text-teal-100 mb-10 text-lg max-w-xl mx-auto font-light">{{ __('welcome.cta.description') }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-white text-teal-700 px-10 py-4 rounded-full font-bold hover:bg-teal-50 transition text-lg shadow-xl">{{ __('welcome.cta.submit_now') }}</a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-teal-700 px-10 py-4 rounded-full font-bold hover:bg-teal-50 transition text-lg shadow-xl">{{ __('welcome.cta.daftar_submit') }}</a>
                    <a href="{{ route('login') }}" class="border-2 border-white/80 text-white px-10 py-4 rounded-full font-bold hover:bg-white hover:text-teal-700 transition text-lg">Login</a>
                @endauth
            </div>
        </div>
    </section>
