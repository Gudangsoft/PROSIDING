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
<div class="bg-white shadow-2xl p-8 sm:p-12 max-w-4xl mx-auto" style="border-radius: 16px;" x-data="registerForm('{{ $defaultRole }}', {{ $preselectedPackage ? $preselectedPackage->price : 'null' }}, {{ $preselectedPackage && $preselectedPackage->is_free ? 'true' : 'false' }})">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Formulir Pendaftaran</h2>

    @if($preselectedPackage)
    <div class="mb-6 bg-teal-50 border border-teal-200 rounded-xl p-4 flex items-center gap-4">
        <div class="w-12 h-12 bg-teal-600 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-xs text-teal-600 font-semibold uppercase tracking-wider">Paket Dipilih</p>
            <p class="font-bold text-gray-800 text-lg">{{ $preselectedPackage->name }}</p>
            <p class="text-teal-700 font-bold">{{ $preselectedPackage->formatted_price }}</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf
        @if($preselectedPackage)
        <input type="hidden" name="registration_package_id" value="{{ $preselectedPackage->id }}">
        @endif

        {{-- Role Selection --}}
        <div class="mb-6">
            <label class="block text-base font-medium text-gray-700 mb-3">Daftar Sebagai <span class="text-red-500">*</span></label>
            @if($preselectedPackage)
            {{-- Terkunci ke Partisipan karena berasal dari pilihan paket --}}
            <input type="hidden" name="role" value="participant">
            <div class="flex items-center gap-3 border-2 border-teal-500 bg-teal-50 px-5 py-4 rounded-lg">
                <div class="w-5 h-5 rounded-full border-2 border-teal-500 flex items-center justify-center shrink-0">
                    <div class="w-2.5 h-2.5 rounded-full bg-teal-500"></div>
                </div>
                <div>
                    <span class="font-semibold text-gray-800 block">Partisipan</span>
                    <p class="text-xs text-teal-600 mt-0.5">Terdaftar sebagai peserta paket <strong>{{ $preselectedPackage->name }}</strong></p>
                </div>
                <span class="ml-auto text-xs text-teal-600 bg-teal-100 px-2 py-1 rounded font-medium">🔒 Terkunci</span>
            </div>
            @else
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center gap-3 border-2 px-5 py-4 cursor-pointer transition rounded-lg hover:border-blue-400"
                    :class="role === 'author' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">
                    <input type="radio" name="role" value="author" x-model="role" class="text-blue-600 w-4 h-4">
                    <div>
                        <span class="font-semibold text-gray-800 block">Author / Penulis</span>
                        <p class="text-xs text-gray-500 mt-0.5">Submit paper & presentasi</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 border-2 px-5 py-4 cursor-pointer transition rounded-lg hover:border-teal-400"
                    :class="role === 'participant' ? 'border-teal-500 bg-teal-50' : 'border-gray-300'">
                    <input type="radio" name="role" value="participant" x-model="role" class="text-teal-600 w-4 h-4">
                    <div>
                        <span class="font-semibold text-gray-800 block">Partisipan</span>
                        <p class="text-xs text-gray-500 mt-0.5">Mengikuti kegiatan seminar</p>
                    </div>
                </label>
            </div>
            @endif
            @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Name --}}
            <div class="mb-1">
                <label for="name" class="block text-base font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gender --}}
            <div class="mb-1">
                <label for="gender" class="block text-base font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select id="gender" name="gender" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-white @error('gender') border-red-500 @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Institution --}}
            <div class="mb-1">
                <label for="institution" class="block text-base font-medium text-gray-700 mb-2">Institusi / Universitas <span class="text-red-500">*</span></label>
                <input id="institution" type="text" name="institution" value="{{ old('institution') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('institution') border-red-500 @enderror"
                    placeholder="Nama institusi/universitas">
                @error('institution')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Country --}}
            <div class="mb-1">
                <label for="country" class="block text-base font-medium text-gray-700 mb-2">Negara <span class="text-red-500">*</span></label>
                <select id="country" name="country" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition bg-white @error('country') border-red-500 @enderror">
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
                @error('country')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-1">
                <label for="email" class="block text-base font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('email') border-red-500 @enderror"
                    placeholder="email@example.com">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="mb-1">
                <label for="phone" class="block text-base font-medium text-gray-700 mb-2">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', '+62') }}" required placeholder="+62xxx"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-1">
                <label for="password" class="block text-base font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('password') border-red-500 @enderror"
                    placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-1">
                <label for="password_confirmation" class="block text-base font-medium text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition"
                    placeholder="Ulangi password">
            </div>

            {{-- Research Interest --}}
            <div class="mb-1">
                <label for="research_interest" class="block text-base font-medium text-gray-700 mb-2">Research Interest <span class="text-red-500">*</span></label>
                <input id="research_interest" type="text" name="research_interest" value="{{ old('research_interest') }}"
                    placeholder="e.g. Pharmaceutical Technology, Pharmacology, etc."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('research_interest') border-red-500 @enderror">
                @error('research_interest')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Other Information --}}
            <div class="mb-1">
                <label for="other_info" class="block text-base font-medium text-gray-700 mb-2">Informasi Lainnya</label>
                <textarea id="other_info" name="other_info" rows="2"
                    placeholder="Informasi tambahan (opsional)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition resize-none @error('other_info') border-red-500 @enderror">{{ old('other_info') }}</textarea>
                @error('other_info')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Payment Amount (participant) - hidden when free package --}}
            <div class="mb-1" x-show="role === 'participant' && !isFree" x-transition>
                <label for="payment_amount" class="block text-base font-medium text-gray-700 mb-2">
                    Nominal Pembayaran (Rp) <span class="text-red-500">*</span>
                </label>
                <input id="payment_amount" type="number" name="payment_amount" :value="packageAmount ?? '{{ old('payment_amount') }}'"
                    x-bind:readonly="packageAmount !== null"
                    min="0" step="1" placeholder="Contoh: 500000"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition @error('payment_amount') border-red-500 @enderror"
                    :class="packageAmount !== null ? 'bg-teal-50 font-bold text-teal-800' : ''">
                <p x-show="packageAmount !== null" class="text-xs text-teal-600 mt-1">✓ Nominal otomatis dari paket yang dipilih</p>
                @error('payment_amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Proof of Payment (participant) - hidden when free package --}}
            <div class="mb-1" x-show="role === 'participant' && !isFree" x-transition>
                <label for="proof_of_payment" class="block text-base font-medium text-gray-700 mb-2">
                    Bukti Pembayaran <span class="text-red-500">*</span>
                </label>
                <input id="proof_of_payment" type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                    @change="previewPayment($event)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition text-sm file:mr-3 file:py-2 file:px-4 file:border-0 file:rounded-md file:bg-orange-100 file:text-orange-700 file:font-medium file:cursor-pointer hover:file:bg-orange-200 @error('proof_of_payment') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau PDF. Maks 5MB.</p>
                <template x-if="paymentPreview">
                    <img :src="paymentPreview" class="mt-2 max-h-32 rounded border">
                </template>
                @error('proof_of_payment')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Signature --}}
            <div class="mb-1">
                <label for="signature" class="block text-base font-medium text-gray-700 mb-2">Tanda Tangan (Opsional)</label>
                <input id="signature" type="file" name="signature" accept=".jpg,.jpeg,.png"
                    @change="previewSignature($event)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition text-sm file:mr-3 file:py-2 file:px-4 file:border-0 file:rounded-md file:bg-gray-100 file:text-gray-700 file:font-medium file:cursor-pointer hover:file:bg-gray-200 @error('signature') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks 2MB.</p>
                <template x-if="signaturePreview">
                    <img :src="signaturePreview" class="mt-2 max-h-32 rounded border">
                </template>
                @error('signature')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Free Package Notice --}}
        <div x-show="role === 'participant' && isFree" x-transition
            class="mt-4 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3">
            <div class="mt-0.5 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="font-semibold text-emerald-800">Pendaftaran Gratis — Tidak Perlu Bukti Pembayaran</p>
                <p class="text-sm text-emerald-600 mt-0.5">Akun Anda akan langsung aktif setelah mendaftar. Klik tombol di bawah untuk melanjutkan.</p>
            </div>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3.5 rounded-lg font-semibold hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all shadow-md text-lg"
                x-text="(role === 'participant' && isFree) ? 'Daftar Gratis ✓' : 'Daftar'">
                Daftar
            </button>
        </div>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-700 transition">Login di sini</a>
    </p>
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
