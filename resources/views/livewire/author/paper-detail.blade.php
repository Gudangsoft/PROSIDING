<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6">
        <a href="{{ route('author.papers') }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; Kembali ke Daftar Paper</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $paper->title }}</h1>
        <div class="flex items-center gap-3 mt-2">
            @php $color = $paper->status_color; @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                @if($color==='green') bg-green-100 text-green-800
                @elseif($color==='red') bg-red-100 text-red-800
                @elseif($color==='yellow' || $color==='amber') bg-yellow-100 text-yellow-800
                @elseif($color==='blue' || $color==='cyan') bg-blue-100 text-blue-800
                @elseif($color==='orange') bg-orange-100 text-orange-800
                @elseif($color==='indigo' || $color==='purple') bg-indigo-100 text-indigo-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $paper->status_label }}
            </span>
            <span class="text-sm text-gray-500">Disubmit: {{ $paper->submitted_at?->format('d M Y H:i') }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Paper Info --}}
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Informasi Paper</h3>
                <div class="space-y-3 text-sm">
                    <div><span class="text-gray-500 font-medium">Topik:</span> <span class="text-gray-800">{{ $paper->topic }}</span></div>
                    <div><span class="text-gray-500 font-medium">Kata Kunci:</span> <span class="text-gray-800">{{ $paper->keywords }}</span></div>
                    <div>
                        <span class="text-gray-500 font-medium">Abstrak:</span>
                        <p class="text-gray-800 mt-1 whitespace-pre-line">{{ $paper->abstract }}</p>
                    </div>
                </div>
            </div>

            {{-- Files --}}
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">File yang Diunggah</h3>
                <div class="space-y-2">
                    @foreach($paper->files as $file)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $file->original_name }}</p>
                                <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $file->type)) }} &bull; {{ $file->file_size_human }} &bull; {{ $file->created_at->format('d M Y H:i') }}</p>
                                @if($file->notes) <p class="text-xs text-gray-500 mt-1">{{ $file->notes }}</p> @endif
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Download</a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Reviews --}}
            @if($paper->reviews->count())
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Hasil Review</h3>
                <div class="space-y-4">
                    @foreach($paper->reviews->where('status', 'completed') as $review)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-medium text-gray-800">Reviewer</p>
                                <p class="text-xs text-gray-400">{{ $review->reviewed_at?->format('d M Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($review->recommendation==='accept') bg-green-100 text-green-800
                                    @elseif($review->recommendation==='reject') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ \App\Models\Review::RECOMMENDATION_LABELS[$review->recommendation] ?? '-' }}
                                </span>
                                <p class="text-sm text-gray-500 mt-1">Skor: {{ $review->score }}/100</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 whitespace-pre-line bg-gray-50 p-3 rounded">{{ $review->comments }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Editor Notes --}}
            @if($paper->editor_notes)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <h3 class="font-semibold text-yellow-800 mb-2">Catatan Editor</h3>
                <p class="text-sm text-yellow-700 whitespace-pre-line">{{ $paper->editor_notes }}</p>
            </div>
            @endif

            {{-- LOA & Payment Info --}}
            @if($paper->loa_link)
            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-semibold text-green-800 text-lg">Letter of Acceptance (LOA)</h3>
                </div>
                <p class="text-sm text-green-700 mb-3">Paper Anda telah diterima! Silakan unduh LOA dan lakukan pembayaran.</p>
                <a href="{{ $paper->loa_link }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka LOA
                </a>

                @if($paper->payment)
                <div class="mt-4 pt-4 border-t border-green-200">
                    <h4 class="text-sm font-bold text-green-800 mb-2">Tagihan Pembayaran</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-green-600 text-xs">Invoice:</span>
                            <p class="font-mono font-medium text-green-800">{{ $paper->payment->invoice_number }}</p>
                        </div>
                        <div>
                            <span class="text-green-600 text-xs">Jumlah:</span>
                            <p class="font-bold text-green-800 text-lg">Rp {{ number_format($paper->payment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Info Rekening Bank --}}
                    @php
                        $pdConf = $paper->conference;
                        $pdBankName = $pdConf->payment_bank_name ?? null;
                        $pdBankAccount = $pdConf->payment_bank_account ?? null;
                        $pdAccountHolder = $pdConf->payment_account_holder ?? null;
                        $pdInstructions = $pdConf->payment_instructions ?? null;
                    @endphp
                    @if($pdBankName || $pdBankAccount)
                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-xs font-bold text-blue-800 mb-1.5 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Transfer ke Rekening:
                        </p>
                        <div class="text-xs space-y-0.5">
                            @if($pdBankName)<p class="text-gray-700">Bank: <span class="font-medium">{{ $pdBankName }}</span></p>@endif
                            @if($pdBankAccount)<p class="text-gray-700">No. Rekening: <span class="font-mono font-bold text-gray-900">{{ $pdBankAccount }}</span></p>@endif
                            @if($pdAccountHolder)<p class="text-gray-700">a.n. <span class="font-medium">{{ $pdAccountHolder }}</span></p>@endif
                        </div>
                        @if($pdInstructions)
                        <p class="text-[10px] text-blue-600 mt-1.5 italic">{{ $pdInstructions }}</p>
                        @endif
                    </div>
                    @endif

                    @if(in_array($paper->payment->status, ['pending', 'rejected']))
                    <a href="{{ route('author.paper.payment', $paper) }}" class="inline-flex items-center gap-2 mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Upload Bukti Bayar
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endif

            {{-- Deliverables from Admin (Buku Prosiding, Sertifikat) --}}
            @if(in_array($paper->status, ['deliverables_pending', 'completed']))
            @php
                $adminSentDeliverables = $paper->deliverables->where('direction', 'admin_send');
            @endphp
            @if($adminSentDeliverables->count())
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h3 class="font-semibold text-purple-800 text-lg">Luaran dari Panitia</h3>
                </div>
                <div class="space-y-3">
                    @foreach($adminSentDeliverables as $deliverable)
                    <div class="bg-white rounded-lg border border-purple-100 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($deliverable->type === 'prosiding_book')
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                @else
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ \App\Models\Deliverable::TYPE_LABELS[$deliverable->type] ?? $deliverable->type }}</p>
                                    <p class="text-xs text-gray-500">{{ $deliverable->original_name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Dikirim: {{ $deliverable->sent_at?->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $deliverable->file_path) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Download
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status Progress --}}
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Progress</h3>
                @php
                    $steps = [
                        ['status' => 'submitted', 'label' => 'Submitted'],
                        ['status' => 'in_review', 'label' => 'Review'],
                        ['status' => 'accepted', 'label' => 'Accepted'],
                        ['status' => 'payment_verified', 'label' => 'Pembayaran'],
                        ['status' => 'completed', 'label' => 'Selesai'],
                    ];
                    $statusOrder = ['submitted','screening','in_review','revision_required','revised','accepted','rejected','payment_pending','payment_uploaded','payment_verified','deliverables_pending','completed'];
                    $currentIdx = array_search($paper->status, $statusOrder);
                @endphp
                <div class="space-y-3">
                    @foreach($steps as $idx => $step)
                        @php
                            $stepIdx = array_search($step['status'], $statusOrder);
                            $done = $currentIdx >= $stepIdx && $paper->status !== 'rejected';
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $done ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                @if($done) ✓ @else {{ $idx+1 }} @endif
                            </div>
                            <span class="text-sm {{ $done ? 'text-green-700 font-medium' : 'text-gray-500' }}">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Revision Upload --}}
            @if($paper->status === 'revision_required')
            <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-6 mb-6">
                <div class="flex items-start gap-3 mb-4">
                    <svg class="w-6 h-6 text-orange-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-bold text-orange-800 text-lg">Revisi Diperlukan</h3>
                        <p class="text-sm text-orange-700 mt-1">Paper Anda memerlukan revisi. Silakan upload file revisi terbaru di bawah ini.</p>
                    </div>
                </div>

                @php
                    $revisionFiles = $paper->files->where('type', 'revision');
                @endphp

                @if($revisionFiles->count() > 0)
                <div class="mb-4">
                    <p class="text-sm font-semibold text-orange-800 mb-2">File Revisi yang Sudah Diupload:</p>
                    <div class="space-y-2">
                        @foreach($revisionFiles as $file)
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-orange-200">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $file->original_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $file->file_size_human }} &bull; {{ $file->created_at->format('d M Y H:i') }}</p>
                                    @if($file->notes) <p class="text-xs text-gray-600 mt-1">Catatan: {{ $file->notes }}</p> @endif
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-orange-600 hover:text-orange-800 text-sm font-medium">Download</a>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-orange-600 mt-2">💡 Anda dapat mengupload file revisi baru jika diperlukan</p>
                </div>
                @endif

                <div class="bg-white rounded-lg p-4 border border-orange-200">
                    <h4 class="font-semibold text-gray-800 mb-3">Upload File Revisi Baru</h4>
                    <form wire:submit="submitRevision">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Revisi (.pdf, .doc, .docx, max 10MB)</label>
                            <input type="file" wire:model="revisionFile" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500" accept=".pdf,.doc,.docx">
                            @error('revisionFile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <div wire:loading wire:target="revisionFile" class="text-orange-600 text-xs mt-1 flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengunggah file...
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Revisi (opsional)</label>
                            <textarea wire:model="revisionNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Jelaskan perubahan yang telah Anda lakukan..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-orange-600 text-white py-2.5 rounded-lg hover:bg-orange-700 text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Submit Revisi
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Aksi</h3>
                <div class="space-y-2">
                    @if(in_array($paper->status, ['payment_pending','payment_uploaded']))
                        <a href="{{ route('author.paper.payment', $paper) }}" class="block w-full text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 text-sm font-medium">Upload Pembayaran</a>
                    @endif
                    @if(in_array($paper->status, ['payment_verified','deliverables_pending','completed']))
                        <a href="{{ route('author.paper.deliverables', $paper) }}" class="block w-full text-center bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 text-sm font-medium">Upload Luaran</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
