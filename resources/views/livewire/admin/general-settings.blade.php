<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Identitas Website</h1>
        <p class="text-sm text-gray-500 mt-1">Pengaturan nama, logo, favicon, kontak, dan informasi umum website</p>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form wire:submit.prevent="save">
        {{-- ── Bahasa Website ── --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Bahasa & Tema</h2>
            <p class="text-xs text-gray-400 mb-5">Pilih bahasa dan tema tampilan halaman publik website</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa Halaman Utama</label>
                    <select wire:model="settings.site_language" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="id">🇮🇩 Bahasa Indonesia</option>
                        <option value="en">🇬🇧 English</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Bahasa default: Indonesia. Perubahan berlaku langsung di halaman depan.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tema Aktif</label>
                    <select wire:model="activePresetId" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($themePresets as $preset)
                            <option value="{{ $preset->id }}">
                                {{ $preset->name }}{{ $preset->linked_template ? ' — Template: ' . ucfirst($preset->linked_template) : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Pilih tema dari <a href="{{ url('/admin/settings/theme') }}" class="text-blue-600 hover:underline">Pengaturan Tema</a>. Warna, layout, dan template akan diterapkan otomatis.</p>
                </div>
            </div>
        </div>

        {{-- ── Nama & Tagline ── --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Informasi Dasar</h2>
            <p class="text-xs text-gray-400 mb-5">Nama dan tagline website yang ditampilkan di header, browser, dan SEO</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Website <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="settings.site_name" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Prosiding LPKD-APJI">
                    @error('settings.site_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                    <input type="text" wire:model="settings.site_tagline" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Sistem Prosiding Online">
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100"
                 x-data="{ showTagline: @js(($settings['show_tagline_in_sidebar'] ?? '1') === '1') }">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" x-model="showTagline"
                           x-on:change="$wire.set('settings.show_tagline_in_sidebar', showTagline ? '1' : '0')"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Tampilkan tagline di sidebar</span>
                        <p class="text-xs text-gray-400">Tagline akan muncul di bawah nama website pada sidebar dashboard</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- ── Logo & Favicon ── --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Logo & Favicon</h2>
            <p class="text-xs text-gray-400 mb-5">Logo utama dan ikon browser website Anda</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Logo Website</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center">
                        <div class="w-32 h-32 mx-auto bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden mb-4 border">
                            @if($siteLogo)
                                <img src="{{ $siteLogo->temporaryUrl() }}" class="max-w-full max-h-full object-contain">
                            @elseif($settings['site_logo'] ?? false)
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="max-w-full max-h-full object-contain">
                            @else
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-xs text-gray-400 mt-1">Belum ada logo</p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium cursor-pointer hover:bg-blue-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload Logo
                                <input type="file" wire:model="siteLogo" accept="image/png,image/svg+xml,image/jpeg,image/webp" class="sr-only">
                            </label>
                            @if($settings['site_logo'] ?? false)
                            <button type="button" wire:click="removeLogo" wire:confirm="Hapus logo website?" class="ml-2 px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg text-sm transition">Hapus</button>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-3">Format: PNG, SVG, JPG, WebP. Maks 2MB. Rekomendasi: 200x60 px</p>
                        @error('siteLogo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Favicon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Favicon</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center">
                        <div class="w-32 h-32 mx-auto bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden mb-4 border">
                            @if($siteFavicon)
                                <img src="{{ $siteFavicon->temporaryUrl() }}" class="w-16 h-16 object-contain">
                            @elseif($settings['site_favicon'] ?? false)
                                <img src="{{ asset('storage/' . $settings['site_favicon']) }}" class="w-16 h-16 object-contain">
                            @else
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <p class="text-xs text-gray-400 mt-1">Belum ada favicon</p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium cursor-pointer hover:bg-blue-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload Favicon
                                <input type="file" wire:model="siteFavicon" accept="image/png,image/x-icon,image/svg+xml" class="sr-only">
                            </label>
                            @if($settings['site_favicon'] ?? false)
                            <button type="button" wire:click="removeFavicon" wire:confirm="Hapus favicon?" class="ml-2 px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg text-sm transition">Hapus</button>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-3">Format: ICO, PNG, SVG. Maks 1MB. Rekomendasi: 32x32 px</p>
                        @error('siteFavicon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SEO & Meta ── --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">SEO & Meta</h2>
            <p class="text-xs text-gray-400 mb-5">Pengaturan untuk optimisasi mesin pencari</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Website</label>
                    <textarea wire:model="settings.site_description" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Deskripsi singkat website untuk SEO..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">Ditampilkan di hasil pencarian Google. Idealnya 150-160 karakter.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keywords SEO</label>
                    <input type="text" wire:model="settings.site_keywords" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="prosiding, konferensi, paper, jurnal...">
                    <p class="text-xs text-gray-400 mt-1">Pisahkan dengan koma</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                    <input type="text" wire:model="settings.google_analytics" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="G-XXXXXXXXXX">
                    <p class="text-xs text-gray-400 mt-1">Masukkan Measurement ID dari Google Analytics 4</p>
                </div>
            </div>
        </div>

        {{-- ── Kontak ── --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Informasi Kontak</h2>
            <p class="text-xs text-gray-400 mb-5">Kontak yang ditampilkan di website</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Email Kontak
                        </span>
                    </label>
                    <input type="email" wire:model="settings.contact_email" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="info@prosiding.test">
                    @error('settings.contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            No. Telepon
                        </span>
                    </label>
                    <input type="text" wire:model="settings.contact_phone" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="+62 812 xxxx xxxx">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Alamat
                        </span>
                    </label>
                    <textarea wire:model="settings.contact_address" rows="2" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Jl. Contoh No. 123, Kota, Provinsi"></textarea>
                </div>
            </div>
        </div>

        {{-- ── Sosial Media ── --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Sosial Media</h2>
            <p class="text-xs text-gray-400 mb-5">Link sosial media yang ditampilkan di website</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </span>
                    </label>
                    <input type="url" wire:model="settings.social_facebook" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://facebook.com/...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            Instagram
                        </span>
                    </label>
                    <input type="url" wire:model="settings.social_instagram" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://instagram.com/...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter / X
                        </span>
                    </label>
                    <input type="url" wire:model="settings.social_twitter" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://x.com/...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            YouTube
                        </span>
                    </label>
                    <input type="url" wire:model="settings.social_youtube" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://youtube.com/...">
                </div>
            </div>
        </div>

        {{-- ── Konten Homepage ── --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Konten Homepage</h2>
            <p class="text-xs text-gray-400 mb-5">Informasi publikasi dan makalah terpilih yang ditampilkan di homepage</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informasi Publikasi Prosiding</label>
                    <textarea wire:model="settings.publication_info" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Informasi mengenai publikasi prosiding..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">Ditampilkan pada kartu "Publikasi Prosiding" di homepage</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informasi Makalah Terpilih</label>
                    <textarea wire:model="settings.selected_papers_info" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Informasi mengenai makalah terpilih..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">Ditampilkan pada kartu "Makalah Terpilih" di homepage</p>
                </div>
            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Footer</h2>
            <p class="text-xs text-gray-400 mb-5">Teks yang ditampilkan di bagian bawah halaman</p>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teks Footer / Copyright</label>
                <input type="text" wire:model="settings.footer_text" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="© 2026 Prosiding LPKD-APJI. All rights reserved.">
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Powered By</label>
                <input type="text" wire:model="settings.powered_by" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Powered by Laravel">
                <p class="text-xs text-gray-400 mt-1">Teks "Powered by" di bagian bawah footer. Kosongkan jika tidak ingin ditampilkan.</p>
            </div>
        </div>

        {{-- ── Save Button ── --}}
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
