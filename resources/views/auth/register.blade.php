@extends('layouts.guest')

@section('title', 'Register - ' . \App\Models\Setting::getValue('site_name', config('app.name')))

@section('content')
@php
    $preselectedPackage = null;
    $packageQueryId = request()->query('package');
    if ($packageQueryId) {
        $preselectedPackage = \App\Models\RegistrationPackage::find($packageQueryId);
    }
    $defaultRole = $preselectedPackage ? 'participant' : old('role', 'author');
@endphp

<div class="max-w-4xl mx-auto" x-data="registerForm('{{ $defaultRole }}', {{ $preselectedPackage ? $preselectedPackage->price : 'null' }}, {{ $preselectedPackage && $preselectedPackage->is_free ? 'true' : 'false' }})">

    {{-- Header Card --}}
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-8 mb-6 text-white shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold">Formulir Pendaftaran</h1>
                <p class="text-orange-100 text-sm mt-0.5">Lengkapi data diri Anda untuk membuat akun</p>
            </div>
        </div>

        @if($preselectedPackage)
        <div class="mt-5 bg-white/15 border border-white/30 rounded-xl px-5 py-3 flex items-center gap-3">
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <span class="text-xs text-orange-100 font-semibold uppercase tracking-wider">Paket Dipilih</span>
                <p class="font-bold text-white">{{ $preselectedPackage->name }} &mdash; {{ $preselectedPackage->formatted_price }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            @if($preselectedPackage)
            <input type="hidden" name="registration_package_id" value="{{ $preselectedPackage->id }}">
            @endif

            {{-- ── SEKSI 1: Kategori Pendaftaran ────────────────────────────── --}}
            <div class="px-8 pt-8 pb-6 border-b border-gray-100">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Kategori Pendaftaran</h3>
                </div>

                @if($preselectedPackage)
                <input type="hidden" name="role" value="participant">
                <div class="flex items-center gap-4 border-2 border-teal-400 bg-teal-50 px-5 py-4 rounded-xl">
                    <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800">Partisipan</p>
                        <p class="text-xs text-teal-600 mt-0.5">Terdaftar pada paket <strong>{{ $preselectedPackage->name }}</strong></p>
                    </div>
                    <span class="text-xs text-teal-700 bg-teal-100 border border-teal-200 px-3 py-1 rounded-full font-semibold">🔒 Terkunci</span>
                </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex items-center gap-4 border-2 px-5 py-4 cursor-pointer rounded-xl transition-all hover:shadow-md"
                        :class="role === 'author' ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-gray-200 hover:border-blue-300'">
                        <input type="radio" name="role" value="author" x-model="role" class="sr-only">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 transition"
                            :class="role === 'author' ? 'bg-blue-500' : 'bg-gray-100'">
                            <svg class="w-5 h-5 transition" :class="role === 'author' ? 'text-white' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">Author / Penulis</p>
                            <p class="text-xs text-gray-500 mt-0.5">Submit paper & presentasi</p>
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition"
                            :class="role === 'author' ? 'border-blue-500' : 'border-gray-300'">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-500 transition" x-show="role === 'author'"></div>
                        </div>
                    </label>
                    <label class="relative flex items-center gap-4 border-2 px-5 py-4 cursor-pointer rounded-xl transition-all hover:shadow-md"
                        :class="role === 'participant' ? 'border-teal-500 bg-teal-50 shadow-sm' : 'border-gray-200 hover:border-teal-300'">
                        <input type="radio" name="role" value="participant" x-model="role" class="sr-only">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 transition"
                            :class="role === 'participant' ? 'bg-teal-500' : 'bg-gray-100'">
                            <svg class="w-5 h-5 transition" :class="role === 'participant' ? 'text-white' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">Partisipan</p>
                            <p class="text-xs text-gray-500 mt-0.5">Mengikuti kegiatan seminar</p>
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition"
                            :class="role === 'participant' ? 'border-teal-500' : 'border-gray-300'">
                            <div class="w-2.5 h-2.5 rounded-full bg-teal-500 transition" x-show="role === 'participant'"></div>
                        </div>
                    </label>
                </div>
                @endif
                @error('role')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── SEKSI 2: Data Pribadi ─────────────────────────────────────── --}}
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Data Pribadi</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('name') border-red-400 bg-red-50 @enderror"
                                placeholder="Masukkan nama lengkap">
                        </div>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </span>
                            <select id="gender" name="gender" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-white appearance-none @error('gender') border-red-400 bg-red-50 @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Institution --}}
                    <div>
                        <label for="institution" class="block text-sm font-medium text-gray-700 mb-1.5">Institusi / Universitas <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            <input id="institution" type="text" name="institution" value="{{ old('institution') }}" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('institution') border-red-400 bg-red-50 @enderror"
                                placeholder="Nama institusi/universitas">
                        </div>
                        @error('institution')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Country --}}
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1.5">Negara <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <select id="country" name="country" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-white appearance-none @error('country') border-red-400 bg-red-50 @enderror">
                                <option value="">-- Pilih Negara --</option>
                                <option value="Indonesia" {{ old('country', 'Indonesia') == 'Indonesia' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                                <option value="" disabled>─────────────────</option>
                                @php
                                    $countries = [
                                        'Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia','Australia','Austria',
                                        'Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan',
                                        'Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi','Cabo Verde','Cambodia',
                                        'Cameroon','Canada','Central African Republic','Chad','Chile','China','Colombia','Comoros','Congo','Costa Rica',
                                        'Croatia','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt',
                                        'El Salvador','Equatorial Guinea','Eritrea','Estonia','Eswatini','Ethiopia','Fiji','Finland','France','Gabon',
                                        'Gambia','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana',
                                        'Haiti','Honduras','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel',
                                        'Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Korea (North)','Korea (South)','Kuwait',
                                        'Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg',
                                        'Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico',
                                        'Micronesia','Moldova','Monaco','Mongolia','Montenegro','Morocco','Mozambique','Myanmar','Namibia','Nauru',
                                        'Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','North Macedonia','Norway','Oman','Pakistan',
                                        'Palau','Palestine','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar',
                                        'Romania','Russia','Rwanda','Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Samoa','San Marino',
                                        'Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia',
                                        'Solomon Islands','Somalia','South Africa','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland',
                                        'Syria','Taiwan','Tajikistan','Tanzania','Thailand','Timor-Leste','Togo','Tonga','Trinidad and Tobago','Tunisia',
                                        'Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay',
                                        'Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Yemen','Zambia','Zimbabwe'
                                    ];
                                @endphp
                                @foreach($countries as $c)
                                    <option value="{{ $c }}" {{ old('country', 'Indonesia') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('email') border-red-400 bg-red-50 @enderror"
                                placeholder="email@example.com">
                        </div>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <input id="phone" type="text" name="phone" value="{{ old('phone', '+62') }}" required placeholder="+62xxx"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('phone') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Research Interest --}}
                    <div class="md:col-span-2">
                        <label for="research_interest" class="block text-sm font-medium text-gray-700 mb-1.5">Research Interest <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            </span>
                            <input id="research_interest" type="text" name="research_interest" value="{{ old('research_interest') }}"
                                placeholder="e.g. Pharmaceutical Technology, Pharmacology, etc."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('research_interest') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('research_interest')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Other Information --}}
                    <div class="md:col-span-2">
                        <label for="other_info" class="block text-sm font-medium text-gray-700 mb-1.5">Informasi Lainnya <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea id="other_info" name="other_info" rows="2"
                            placeholder="Informasi tambahan yang ingin Anda sampaikan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition resize-none @error('other_info') border-red-400 bg-red-50 @enderror">{{ old('other_info') }}</textarea>
                        @error('other_info')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- ── SEKSI 3: Keamanan Akun ────────────────────────────────────── --}}
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Keamanan Akun</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password" type="password" name="password" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('password') border-red-400 bg-red-50 @enderror"
                                placeholder="Minimal 8 karakter">
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition"
                                placeholder="Ulangi password">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SEKSI 4: Pembayaran & Dokumen ────────────────────────────── --}}
            <div class="px-8 py-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Dokumen</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

@if($preselectedPackage && !$preselectedPackage->is_free && $preselectedPackage->require_payment_proof)
                    {{-- Payment Amount --}}
                    <div>
                        <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nominal Pembayaran (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <input id="payment_amount" type="number" name="payment_amount"
                                value="{{ old('payment_amount', $preselectedPackage->price) }}"
                                readonly min="0" step="1"
                                class="w-full pl-10 pr-4 py-2.5 border border-teal-300 rounded-lg text-sm bg-teal-50 font-bold text-teal-800 outline-none @error('payment_amount') border-red-400 @enderror">
                        </div>
                        <p class="text-xs text-teal-600 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Nominal otomatis dari paket yang dipilih
                        </p>
                        @error('payment_amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Proof of Payment --}}
                    <div>
                        <label for="proof_of_payment" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Bukti Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input id="proof_of_payment" type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                            @change="previewPayment($event)"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition file:mr-3 file:py-1.5 file:px-3 file:border-0 file:rounded-md file:bg-orange-100 file:text-orange-700 file:text-xs file:font-medium file:cursor-pointer hover:file:bg-orange-200 @error('proof_of_payment') border-red-400 bg-red-50 @enderror">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, atau PDF &bull; Maks 5MB</p>
                        <template x-if="paymentPreview">
                            <img :src="paymentPreview" class="mt-2 max-h-28 rounded-lg border border-gray-200 shadow-sm">
                        </template>
                        @error('proof_of_payment')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
@else
                    {{-- Amber notice: upload via dashboard --}}
                    <div class="md:col-span-2 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-amber-800 text-sm">Bukti Pembayaran Diunggah via Dashboard</p>
                            <p class="text-xs text-amber-700 mt-0.5">Setelah mendaftar, login ke dashboard dan unggah bukti pembayaran di menu <strong>Pembayaran</strong>.</p>
                        </div>
                    </div>
@endif

                    {{-- Signature --}}
                    <div @if(!($preselectedPackage && !$preselectedPackage->is_free)) class="md:col-span-2" @endif>
                        <label for="signature" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tanda Tangan <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input id="signature" type="file" name="signature" accept=".jpg,.jpeg,.png"
                            @change="previewSignature($event)"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition file:mr-3 file:py-1.5 file:px-3 file:border-0 file:rounded-md file:bg-gray-100 file:text-gray-600 file:text-xs file:font-medium file:cursor-pointer hover:file:bg-gray-200 @error('signature') border-red-400 bg-red-50 @enderror">
                        <p class="text-xs text-gray-400 mt-1">JPG atau PNG &bull; Maks 2MB</p>
                        <template x-if="signaturePreview">
                            <img :src="signaturePreview" class="mt-2 max-h-28 rounded-lg border border-gray-200 shadow-sm">
                        </template>
                        @error('signature')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- ── Free Package Notice ───────────────────────────────────────── --}}
            <div x-show="role === 'participant' && isFree" x-transition
                class="mx-8 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4 flex items-start gap-3">
                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-emerald-800 text-sm">Pendaftaran Gratis — Akun Langsung Aktif</p>
                    <p class="text-xs text-emerald-600 mt-0.5">Tidak diperlukan bukti pembayaran. Klik tombol di bawah untuk melanjutkan.</p>
                </div>
            </div>

            {{-- ── Submit Button ─────────────────────────────────────────────── --}}
            <div class="px-8 pb-8">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3.5 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all shadow-md text-base flex items-center justify-center gap-2"
                    x-text="(role === 'participant' && isFree) ? '✓  Daftar Gratis' : 'Buat Akun'">
                    Buat Akun
                </button>
                <p class="text-center text-sm text-gray-500 mt-4">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-700 transition">Login di sini</a>
                </p>
            </div>

        </form>
    </div>
</div>

<script>
function registerForm(defaultRole, packageAmount, isFree) {
    return {
        role: defaultRole || '{{ old('role', 'author') }}',
        packageAmount: packageAmount || null,
        isFree: isFree || false,
        paymentPreview: null,
        signaturePreview: null,
        previewPayment(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                this.paymentPreview = URL.createObjectURL(file);
            } else {
                this.paymentPreview = null;
            }
        },
        previewSignature(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                this.signaturePreview = URL.createObjectURL(file);
            } else {
                this.signaturePreview = null;
            }
        }
    }
}
</script>
@endsection
