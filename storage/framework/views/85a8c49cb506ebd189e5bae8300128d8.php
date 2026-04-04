<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6">
        <a href="<?php echo e(route('author.papers')); ?>" class="text-blue-600 hover:text-blue-800 text-sm">&larr; Kembali ke Daftar Paper</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2"><?php echo e($paper->title); ?></h1>
        <div class="flex items-center gap-3 mt-2">
            <?php $color = $paper->status_color; ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                <?php if($color==='green'): ?> bg-green-100 text-green-800
                <?php elseif($color==='red'): ?> bg-red-100 text-red-800
                <?php elseif($color==='yellow' || $color==='amber'): ?> bg-yellow-100 text-yellow-800
                <?php elseif($color==='blue' || $color==='cyan'): ?> bg-blue-100 text-blue-800
                <?php elseif($color==='orange'): ?> bg-orange-100 text-orange-800
                <?php elseif($color==='indigo' || $color==='purple'): ?> bg-indigo-100 text-indigo-800
                <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                <?php echo e($paper->status_label); ?>

            </span>
            <span class="text-sm text-gray-500">Disubmit: <?php echo e($paper->submitted_at?->format('d M Y H:i')); ?></span>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Informasi Paper</h3>
                <div class="space-y-3 text-sm">
                    <div><span class="text-gray-500 font-medium">Topik:</span> <span class="text-gray-800"><?php echo e($paper->topic); ?></span></div>
                    <div><span class="text-gray-500 font-medium">Kata Kunci:</span> <span class="text-gray-800"><?php echo e($paper->keywords); ?></span></div>
                    <div>
                        <span class="text-gray-500 font-medium">Abstrak:</span>
                        <p class="text-gray-800 mt-1 whitespace-pre-line"><?php echo e($paper->abstract); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">File yang Diunggah</h3>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $paper->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <div>
                                <p class="text-sm font-medium text-gray-800"><?php echo e($file->original_name); ?></p>
                                <p class="text-xs text-gray-400"><?php echo e(ucfirst(str_replace('_', ' ', $file->type))); ?> &bull; <?php echo e($file->file_size_human); ?> &bull; <?php echo e($file->created_at->format('d M Y H:i')); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($file->notes): ?> <p class="text-xs text-gray-500 mt-1"><?php echo e($file->notes); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <a href="<?php echo e(asset('storage/' . $file->file_path)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Download</a>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->reviews->count()): ?>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Hasil Review</h3>
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $paper->reviews->where('status', 'completed'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-medium text-gray-800">Reviewer</p>
                                <p class="text-xs text-gray-400"><?php echo e($review->reviewed_at?->format('d M Y H:i')); ?></p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($review->recommendation==='accept'): ?> bg-green-100 text-green-800
                                    <?php elseif($review->recommendation==='reject'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                    <?php echo e(\App\Models\Review::RECOMMENDATION_LABELS[$review->recommendation] ?? '-'); ?>

                                </span>
                                <p class="text-sm text-gray-500 mt-1">Skor: <?php echo e($review->score); ?>/100</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 whitespace-pre-line bg-gray-50 p-3 rounded"><?php echo e($review->comments); ?></div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->editor_notes): ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <h3 class="font-semibold text-yellow-800 mb-2">Catatan Editor</h3>
                <p class="text-sm text-yellow-700 whitespace-pre-line"><?php echo e($paper->editor_notes); ?></p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->loa_link || $paper->acceptance_letter_path): ?>
            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-semibold text-green-800 text-lg">Letter of Acceptance (LOA)</h3>
                </div>
                <p class="text-sm text-green-700 mb-3">Selamat! Paper Anda telah diterima. Silakan unduh LOA sebagai bukti penerimaan.</p>
                
                <div class="flex flex-wrap gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->acceptance_letter_path): ?>
                    <a href="<?php echo e(Storage::url($paper->acceptance_letter_path)); ?>" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download LOA (PDF)
                    </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->loa_number): ?>
                    <span class="inline-flex items-center px-3 py-2 bg-green-100 text-green-800 rounded-lg text-xs font-medium">
                        No: <?php echo e($paper->loa_number); ?>

                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php elseif($paper->loa_link): ?>
                    <a href="<?php echo e($paper->loa_link); ?>" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Buka LOA
                    </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    <h3 class="font-semibold text-gray-800 text-lg">Video Presentasi</h3>
                </div>
                <p class="text-sm text-gray-600 mb-4">Unggah link video presentasi materi Anda (YouTube, Google Drive, dll.) agar dapat ditampilkan kepada panitia.</p>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->video_presentation_url): ?>
                <?php
                    $vidUrl = $paper->video_presentation_url;
                    $embedUrl = null;
                    // Handle youtube.com/watch?v=ID, youtu.be/ID, youtube.com/embed/ID
                    if (preg_match('/(?:(?:www\.)?youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]+)/', $vidUrl, $m)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                    }
                ?>
                <div class="mb-4 rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($embedUrl): ?>
                        <div class="aspect-video">
                            <iframe src="<?php echo e($embedUrl); ?>" class="w-full h-full" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    <?php else: ?>
                        <div class="p-3 flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <a href="<?php echo e($vidUrl); ?>" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 hover:underline break-all"><?php echo e($vidUrl); ?></a>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form wire:submit.prevent="saveVideoUrl" class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link Video (YouTube / Google Drive / lainnya)</label>
                        <input type="url" wire:model="videoUrl"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400"
                            placeholder="https://youtu.be/..." />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['videoUrl'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span wire:loading.remove wire:target="saveVideoUrl">Simpan Link Video</span>
                        <span wire:loading wire:target="saveVideoUrl">Menyimpan...</span>
                    </button>
                </form>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($paper->status, ['deliverables_pending', 'completed'])): ?>
            <?php
                $adminSentDeliverables = $paper->deliverables->where('direction', 'admin_send');
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($adminSentDeliverables->count()): ?>
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h3 class="font-semibold text-purple-800 text-lg">Luaran dari Panitia</h3>
                </div>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $adminSentDeliverables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliverable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="bg-white rounded-lg border border-purple-100 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deliverable->type === 'prosiding_book'): ?>
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <?php else: ?>
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div>
                                    <p class="font-medium text-gray-800"><?php echo e(\App\Models\Deliverable::TYPE_LABELS[$deliverable->type] ?? $deliverable->type); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($deliverable->original_name); ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5">Dikirim: <?php echo e($deliverable->sent_at?->format('d M Y H:i')); ?></p>
                                </div>
                            </div>
                            <a href="<?php echo e(asset('storage/' . $deliverable->file_path)); ?>" target="_blank"
                               class="inline-flex items-center gap-1.5 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Download
                            </a>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Progress Paper
                </h3>
                
                <?php
                    $allSteps = [
                        'submitted' => ['label' => 'Paper Disubmit', 'icon' => '📤', 'desc' => 'Paper berhasil dikirim ke sistem'],
                        'screening' => ['label' => 'Screening Admin', 'icon' => '🔍', 'desc' => 'Sedang diperiksa kelengkapan dokumen'],
                        'in_review' => ['label' => 'Proses Review', 'icon' => '📝', 'desc' => 'Sedang direview oleh reviewer'],
                        'revision_required' => ['label' => 'Perlu Revisi', 'icon' => '🔄', 'desc' => 'Paper memerlukan perbaikan'],
                        'revised' => ['label' => 'Revisi Terkirim', 'icon' => '📨', 'desc' => 'Revisi sedang diperiksa ulang'],
                        'accepted' => ['label' => 'Diterima', 'icon' => '✅', 'desc' => 'Paper diterima untuk dipublikasikan'],
                        'rejected' => ['label' => 'Ditolak', 'icon' => '❌', 'desc' => 'Paper tidak lolos review'],
                        'deliverables_pending' => ['label' => 'Proses LOA', 'icon' => '📜', 'desc' => 'LOA & sertifikat sedang diproses'],
                        'completed' => ['label' => 'Selesai', 'icon' => '🏆', 'desc' => 'LOA sudah tersedia untuk diunduh'],
                    ];
                    
                    // Define the main flow steps (tanpa pembayaran karena sudah di abstract)
                    $mainFlow = ['submitted', 'screening', 'in_review', 'accepted', 'completed'];
                    $statusOrder = ['submitted','screening','in_review','revision_required','revised','accepted','rejected','payment_pending','payment_uploaded','payment_verified','deliverables_pending','completed'];
                    $currentIdx = array_search($paper->status, $statusOrder);
                    $currentStatus = $paper->status;
                    
                    // Map payment/deliverable statuses to simplified view
                    $displayStatus = $currentStatus;
                    if (in_array($currentStatus, ['payment_pending', 'payment_uploaded', 'payment_verified', 'deliverables_pending'])) {
                        $displayStatus = 'accepted'; // Show as accepted, waiting for LOA
                    }
                    
                    // Determine which steps to show based on current status
                    if ($currentStatus === 'rejected') {
                        $showSteps = ['submitted', 'screening', 'in_review', 'rejected'];
                    } elseif ($currentStatus === 'revision_required' || $currentStatus === 'revised') {
                        $showSteps = ['submitted', 'screening', 'in_review', 'revision_required', 'revised', 'accepted', 'completed'];
                    } else {
                        $showSteps = $mainFlow;
                    }
                ?>

                
                <div class="mb-5 p-3 rounded-xl <?php echo e($currentStatus === 'rejected' ? 'bg-red-50 border border-red-200' : ($currentStatus === 'completed' ? 'bg-green-50 border border-green-200' : 'bg-blue-50 border border-blue-200')); ?>">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl"><?php echo e($allSteps[$currentStatus]['icon'] ?? '📄'); ?></span>
                        <div>
                            <p class="text-xs <?php echo e($currentStatus === 'rejected' ? 'text-red-600' : ($currentStatus === 'completed' ? 'text-green-600' : 'text-blue-600')); ?> font-medium">Status Saat Ini</p>
                            <p class="font-bold <?php echo e($currentStatus === 'rejected' ? 'text-red-800' : ($currentStatus === 'completed' ? 'text-green-800' : 'text-blue-800')); ?>"><?php echo e($allSteps[$currentStatus]['label'] ?? $paper->status_label); ?></p>
                        </div>
                    </div>
                    <p class="text-xs <?php echo e($currentStatus === 'rejected' ? 'text-red-600' : ($currentStatus === 'completed' ? 'text-green-600' : 'text-blue-600')); ?> mt-2"><?php echo e($allSteps[$currentStatus]['desc'] ?? ''); ?></p>
                </div>

                
                <div class="relative">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $showSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $stepKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $step = $allSteps[$stepKey] ?? ['label' => $stepKey, 'icon' => '📄', 'desc' => ''];
                            $stepIdx = array_search($stepKey, $statusOrder);
                            $isCurrent = $stepKey === $currentStatus;
                            $isDone = $currentIdx > $stepIdx;
                            $isFuture = $currentIdx < $stepIdx;
                            $isLast = $idx === count($showSteps) - 1;
                        ?>
                        <div class="flex gap-3 <?php echo e(!$isLast ? 'pb-4' : ''); ?>">
                            
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shrink-0
                                    <?php echo e($isCurrent ? 'bg-blue-500 text-white ring-4 ring-blue-100' : ''); ?>

                                    <?php echo e($isDone ? 'bg-green-500 text-white' : ''); ?>

                                    <?php echo e($isFuture ? 'bg-gray-200 text-gray-400' : ''); ?>

                                    <?php echo e($stepKey === 'rejected' && $isCurrent ? 'bg-red-500 text-white ring-4 ring-red-100' : ''); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDone): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    <?php elseif($isCurrent): ?>
                                        <span class="text-xs"><?php echo e($step['icon']); ?></span>
                                    <?php else: ?>
                                        <?php echo e($idx + 1); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isLast): ?>
                                <div class="w-0.5 flex-1 min-h-[20px] <?php echo e($isDone ? 'bg-green-300' : 'bg-gray-200'); ?>"></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            
                            <div class="flex-1 pb-1">
                                <p class="text-sm font-medium <?php echo e($isCurrent ? 'text-blue-700' : ($isDone ? 'text-green-700' : 'text-gray-500')); ?>

                                    <?php echo e($stepKey === 'rejected' && $isCurrent ? 'text-red-700' : ''); ?>">
                                    <?php echo e($step['label']); ?>

                                </p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isCurrent): ?>
                                <p class="text-xs text-gray-500 mt-0.5"><?php echo e($step['desc']); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->submitted_at): ?>
                <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                    <p>📅 Disubmit: <?php echo e($paper->submitted_at->format('d M Y, H:i')); ?></p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->accepted_at): ?>
                    <p>✅ Diterima: <?php echo e($paper->accepted_at->format('d M Y, H:i')); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->acceptance_letter_sent_at): ?>
                    <p>📜 LOA Terbit: <?php echo e($paper->acceptance_letter_sent_at->format('d M Y, H:i')); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($paper->status, ['completed', 'rejected'])): ?>
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-5 text-white">
                <h4 class="font-semibold mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    Langkah Selanjutnya
                </h4>
                <p class="text-sm text-indigo-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($paper->status):
                        case ('submitted'): ?>
                            Paper Anda sedang menunggu screening oleh admin. Mohon tunggu 1-3 hari kerja.
                            <?php break; ?>
                        <?php case ('screening'): ?>
                            Admin sedang memeriksa kelengkapan dokumen. Paper akan segera ditugaskan ke reviewer.
                            <?php break; ?>
                        <?php case ('in_review'): ?>
                            Paper sedang direview. Proses ini memakan waktu 7-14 hari kerja.
                            <?php break; ?>
                        <?php case ('revision_required'): ?>
                            Silakan upload revisi paper sesuai catatan reviewer di bawah ini.
                            <?php break; ?>
                        <?php case ('revised'): ?>
                            Revisi Anda sedang diperiksa ulang oleh reviewer.
                            <?php break; ?>
                        <?php case ('accepted'): ?>
                            Selamat! Paper diterima. LOA akan segera diterbitkan oleh panitia.
                            <?php break; ?>
                        <?php case ('payment_pending'): ?>
                        <?php case ('payment_uploaded'): ?>
                        <?php case ('payment_verified'): ?>
                        <?php case ('deliverables_pending'): ?>
                            LOA dan sertifikat sedang diproses oleh panitia. Silakan cek secara berkala.
                            <?php break; ?>
                        <?php default: ?>
                            Pantau terus progress paper Anda di halaman ini.
                    <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->status === 'revision_required'): ?>
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

                <?php
                    $revisionFiles = $paper->files->where('type', 'revision');
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($revisionFiles->count() > 0): ?>
                <div class="mb-4">
                    <p class="text-sm font-semibold text-orange-800 mb-2">File Revisi yang Sudah Diupload:</p>
                    <div class="space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $revisionFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-orange-200">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-800"><?php echo e($file->original_name); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($file->file_size_human); ?> &bull; <?php echo e($file->created_at->format('d M Y H:i')); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($file->notes): ?> <p class="text-xs text-gray-600 mt-1">Catatan: <?php echo e($file->notes); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <a href="<?php echo e(asset('storage/' . $file->file_path)); ?>" target="_blank" class="text-orange-600 hover:text-orange-800 text-sm font-medium">Download</a>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                    <p class="text-xs text-orange-600 mt-2">💡 Anda dapat mengupload file revisi baru jika diperlukan</p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="bg-white rounded-lg p-4 border border-orange-200">
                    <h4 class="font-semibold text-gray-800 mb-3">Upload File Revisi Baru</h4>
                    <form wire:submit="submitRevision">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Revisi (.pdf, .doc, .docx, max 10MB)</label>
                            <input type="file" wire:model="revisionFile" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500" accept=".pdf,.doc,.docx">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['revisionFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paper->statusLogs && $paper->statusLogs->isNotEmpty()): ?>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">📋 Riwayat Status</h3>
                <div class="relative">
                    <div class="absolute left-3.5 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    <div class="space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $paper->statusLogs->sortByDesc('occurred_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $dotColor = match(true) {
                                str_contains($log->action ?? '', 'reject') => 'bg-red-500',
                                str_contains($log->action ?? '', 'payment') => 'bg-amber-500',
                                str_contains($log->action ?? '', 'review') => 'bg-blue-500',
                                $log->to_status === 'accepted' || $log->to_status === 'completed' => 'bg-green-500',
                                default => 'bg-indigo-500',
                            };
                            $actionLabel = \App\Models\PaperStatusLog::ACTIONS[$log->action] ?? ucwords(str_replace('_', ' ', $log->action ?? ''));
                        ?>
                        <div class="flex gap-4 relative">
                            <div class="relative z-10 w-7 h-7 rounded-full <?php echo e($dotColor); ?> flex items-center justify-center flex-shrink-0 mt-0.5">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0 pb-1">
                                <p class="text-sm font-semibold text-gray-800"><?php echo e($actionLabel); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->from_status && $log->to_status && $log->from_status !== $log->to_status): ?>
                                <p class="text-xs text-gray-500">
                                    <span class="font-mono"><?php echo e(\App\Models\Paper::STATUS_LABELS[$log->from_status] ?? $log->from_status); ?></span>
                                    → <span class="font-mono"><?php echo e(\App\Models\Paper::STATUS_LABELS[$log->to_status] ?? $log->to_status); ?></span>
                                </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->notes): ?><p class="text-xs text-gray-600 mt-0.5"><?php echo e($log->notes); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <p class="text-[10px] text-gray-400 mt-0.5"><?php echo e($log->occurred_at?->format('d M Y H:i')); ?></p>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Aksi</h3>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($paper->status, ['payment_pending','payment_uploaded'])): ?>
                        <a href="<?php echo e(route('author.paper.payment', $paper)); ?>" class="block w-full text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 text-sm font-medium">Upload Pembayaran</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($paper->status, ['payment_verified','deliverables_pending','completed'])): ?>
                        <a href="<?php echo e(route('author.paper.deliverables', $paper)); ?>" class="block w-full text-center bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 text-sm font-medium">Upload Luaran</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\LPKD-APJI\PROSIDING\resources\views/livewire/author/paper-detail.blade.php ENDPATH**/ ?>