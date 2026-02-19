    {{-- ════════════════════════════════════════════════════
         PRICING — Registration packages
    ════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->registrationPackages->where('is_active', true)->count())
    <section id="pricing" class="py-20 bg-gray-50/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14">
                <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider">{{ __('welcome.pricing.badge') }}</span>
                <h2 class="text-3xl font-extrabold text-gray-900">{{ __('welcome.pricing.title') }}</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min($activeConference->registrationPackages->where('is_active', true)->count(), 4) }} gap-6 max-w-4xl mx-auto">
                @foreach($activeConference->registrationPackages->where('is_active', true)->sortBy('sort_order') as $package)
                <div class="relative bg-white rounded-2xl border {{ $package->is_featured ? 'border-teal-400 ring-2 ring-teal-200 shadow-lg' : 'border-gray-200' }} overflow-hidden flex flex-col">
                    @if($package->is_featured)
                    <div class="bg-teal-600 text-white text-[10px] font-bold text-center py-1 uppercase tracking-wider">Recommended</div>
                    @endif
                    <div class="p-6 text-center flex-1">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">{{ $package->name }}</h3>
                        <p class="text-3xl font-extrabold text-teal-700 mb-4">{{ $package->formatted_price }}</p>
                        @if($package->description)
                        <p class="text-xs text-gray-500 italic mb-4">{{ $package->description }}</p>
                        @endif
                        @if($package->features && count($package->features))
                        <ul class="space-y-2 text-center">
                            @foreach($package->features as $feature)
                            <li class="text-sm text-gray-600 flex items-center gap-2 justify-center">
                                <svg class="w-4 h-4 text-teal-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    <div class="px-6 pb-6">
                        @auth
                            <a href="{{ route('participant.payment', ['package' => $package->id]) }}" class="block w-full bg-teal-600 text-white text-center py-2.5 rounded-full font-semibold hover:bg-teal-700 transition text-sm">{{ __('welcome.pricing.book_now') }}</a>
                        @else
                            <a href="{{ route('register', ['package' => $package->id]) }}" class="block w-full bg-teal-600 text-white text-center py-2.5 rounded-full font-semibold hover:bg-teal-700 transition text-sm">{{ __('welcome.pricing.book_now') }}</a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Payment Info --}}
            @if($activeConference->payment_bank_name || $activeConference->payment_bank_account)
            <div class="mt-10 bg-white rounded-2xl border p-6 max-w-2xl mx-auto text-center">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    {{ __('welcome.pricing.payment_title') }}
                </h3>
                <div class="space-y-1 text-sm text-gray-600">
                    @if($activeConference->payment_bank_name)
                    <p>{{ __('welcome.pricing.bank') }}: <strong>{{ $activeConference->payment_bank_name }}</strong></p>
                    @endif
                    @if($activeConference->payment_bank_account)
                    <p>{{ __('welcome.pricing.no_rekening') }}: <strong class="font-mono">{{ $activeConference->payment_bank_account }}</strong></p>
                    @endif
                    @if($activeConference->payment_account_holder)
                    <p>{{ __('welcome.pricing.atas_nama') }}: <strong>{{ $activeConference->payment_account_holder }}</strong></p>
                    @endif
                    @if($activeConference->payment_contact_phone)
                    <p class="mt-2">{{ __('welcome.pricing.kontak_pembayaran') }}: <strong>{{ $activeConference->payment_contact_phone }}</strong></p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif