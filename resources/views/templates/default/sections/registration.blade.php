    {{-- ═══════════════════════════════════════════════════════════════════
         REGISTRATION / PRICING — Biaya Pendaftaran
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->isSectionVisible('registration') && $activeConference->registrationPackages->where('is_active', true)->count())
    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider">{{ __('welcome.pricing.badge') }}</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">{{ __('welcome.pricing.title') }}</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min($activeConference->registrationPackages->where('is_active', true)->count(), 4) }} gap-6">
                @foreach($activeConference->registrationPackages->where('is_active', true)->sortBy('sort_order') as $package)
                <div class="relative bg-white rounded-2xl shadow-sm border {{ $package->is_featured ? 'border-green-400 ring-2 ring-green-200 shadow-lg' : 'border-gray-200' }} overflow-hidden flex flex-col">
                    {{-- Featured badge --}}
                    @if($package->is_featured)
                    <div class="absolute -right-8 top-5 bg-red-500 text-white text-[10px] font-bold px-10 py-1 rotate-45 shadow">Featured!</div>
                    @endif

                    {{-- Header --}}
                    <div class="bg-emerald-500 px-6 py-6 text-center">
                        <h3 class="text-lg font-extrabold text-white uppercase tracking-wide leading-tight">{{ $package->name }}</h3>
                        <p class="text-2xl font-extrabold text-white mt-2">{{ $package->formatted_price }}</p>
                    </div>

                    {{-- Body --}}
                    <div class="flex-1 px-6 py-6">
                        @if($package->description)
                        <p class="text-sm text-gray-600 text-center italic mb-4">{{ $package->description }}</p>
                        @endif

                        @if($package->features && count($package->features))
                        <ul class="space-y-2 text-center">
                            @foreach($package->features as $feature)
                            <li class="text-sm text-gray-600 italic">{{ $feature }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    {{-- Button --}}
                    <div class="px-6 pb-6">
                        @auth
                            <a href="{{ route('participant.payment', ['package' => $package->id]) }}" class="block w-full bg-amber-600 text-white text-center py-3 rounded-lg font-bold uppercase text-sm hover:bg-amber-700 transition shadow">{{ __('welcome.pricing.book_now') }}</a>
                        @else
                            <a href="{{ route('register', ['package' => $package->id]) }}" class="block w-full bg-amber-600 text-white text-center py-3 rounded-lg font-bold uppercase text-sm hover:bg-amber-700 transition shadow">{{ __('welcome.pricing.book_now') }}</a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Payment / Bank Info --}}
            @if($activeConference->payment_bank_name || $activeConference->payment_bank_account || $activeConference->payment_contact_phone)
            <div class="mt-12 bg-white rounded-2xl shadow-sm border p-6 md:p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 text-center flex items-center justify-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    {{ __('welcome.pricing.payment_title') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Bank Info --}}
                    @if($activeConference->payment_bank_name || $activeConference->payment_bank_account)
                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                        <h4 class="text-sm font-bold text-blue-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ __('welcome.pricing.rekening_bank') }}
                        </h4>
                        <div class="space-y-2">
                            @if($activeConference->payment_bank_name)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ __('welcome.pricing.bank') }}</span>
                                <span class="text-sm font-bold text-gray-800">{{ $activeConference->payment_bank_name }}</span>
                            </div>
                            @endif
                            @if($activeConference->payment_bank_account)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ __('welcome.pricing.no_rekening') }}</span>
                                <span class="text-sm font-bold text-gray-800 font-mono">{{ $activeConference->payment_bank_account }}</span>
                            </div>
                            @endif
                            @if($activeConference->payment_account_holder)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ __('welcome.pricing.atas_nama') }}</span>
                                <span class="text-sm font-bold text-gray-800">{{ $activeConference->payment_account_holder }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Contact & Instructions --}}
                    <div class="bg-green-50 rounded-xl p-5 border border-green-200">
                        <h4 class="text-sm font-bold text-green-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ __('welcome.pricing.kontak_pembayaran') }}
                        </h4>
                        <div class="space-y-2">
                            @if($activeConference->payment_contact_phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span class="text-sm font-bold text-gray-800">{{ $activeConference->payment_contact_phone }}</span>
                            </div>
                            @endif
                            @if($activeConference->payment_instructions)
                            <div class="mt-3 pt-3 border-t border-green-200">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ __('welcome.pricing.instruksi') }}</p>
                                <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($activeConference->payment_instructions)) !!}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif
