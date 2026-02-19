    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeConference && $activeConference->keynoteSpeakers->count()): ?>
    <section id="speakers" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider"><?php echo e(__('welcome.speakers.badge')); ?></span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900"><?php echo e(__('welcome.speakers.title')); ?></h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto"><?php echo e(__('welcome.speakers.description')); ?></p>
            </div>

            <?php
                $speakerTypes = \App\Models\KeynoteSpeaker::TYPE_LABELS;
                $typeColors = [
                    'opening_speech' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'badge' => 'bg-purple-100 text-purple-700', 'ring' => 'border-purple-200 group-hover:border-purple-400', 'gradient' => 'from-purple-400 to-purple-600', 'text' => 'text-purple-600'],
                    'keynote_speaker' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'badge' => 'bg-blue-100 text-blue-700', 'ring' => 'border-blue-200 group-hover:border-blue-400', 'gradient' => 'from-blue-400 to-indigo-500', 'text' => 'text-blue-600'],
                    'narasumber' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'badge' => 'bg-green-100 text-green-700', 'ring' => 'border-green-200 group-hover:border-green-400', 'gradient' => 'from-green-400 to-emerald-500', 'text' => 'text-green-600'],
                    'moderator_host' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'badge' => 'bg-orange-100 text-orange-700', 'ring' => 'border-orange-200 group-hover:border-orange-400', 'gradient' => 'from-orange-400 to-amber-500', 'text' => 'text-orange-600'],
                ];
                $typeIcons = \App\Models\KeynoteSpeaker::TYPE_ICONS;
                $allSpeakers = $activeConference->keynoteSpeakers->sortBy('sort_order');
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $speakerTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeKey => $typeLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <?php
                    $filtered = $allSpeakers->where('type', $typeKey);
                    $colors = $typeColors[$typeKey] ?? $typeColors['keynote_speaker'];
                    $icon = $typeIcons[$typeKey] ?? '';
                    $colCount = min($filtered->count(), 4);
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filtered->count()): ?>
                <div class="mb-16 last:mb-0">
                    
                    <div class="flex items-center justify-center gap-3 mb-8">
                        <div class="h-px flex-1 max-w-[80px] bg-gradient-to-r from-transparent <?php echo e(str_replace('bg-', 'to-', explode(' ', $colors['badge'])[0])); ?>"></div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 <?php echo e($colors['badge']); ?> text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($icon); ?>"/></svg>
                                <?php echo e($typeLabel); ?>

                            </span>
                        </div>
                        <div class="h-px flex-1 max-w-[80px] bg-gradient-to-l from-transparent <?php echo e(str_replace('bg-', 'to-', explode(' ', $colors['badge'])[0])); ?>"></div>
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-<?php echo e($colCount); ?> gap-8 max-w-5xl mx-auto">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $filtered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speaker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="group text-center">
                            <div class="relative w-36 h-36 mx-auto mb-5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($speaker->photo): ?>
                                <img src="<?php echo e(asset('storage/' . $speaker->photo)); ?>" alt="<?php echo e($speaker->name); ?>"
                                     class="w-36 h-36 rounded-full object-cover border-4 <?php echo e($colors['ring']); ?> transition shadow-lg">
                                <?php else: ?>
                                <div class="w-36 h-36 rounded-full bg-gradient-to-br <?php echo e($colors['gradient']); ?> flex items-center justify-center border-4 <?php echo e($colors['ring']); ?> transition shadow-lg">
                                    <span class="text-4xl font-bold text-white"><?php echo e(strtoupper(substr($speaker->name, 0, 1))); ?></span>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900"><?php echo e($speaker->name); ?></h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($speaker->title): ?>
                            <p class="text-sm <?php echo e($colors['text']); ?> font-medium"><?php echo e($speaker->title); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($speaker->institution): ?>
                            <p class="text-sm text-gray-500 mt-0.5"><?php echo e($speaker->institution); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($speaker->topic): ?>
                            <div class="mt-3 inline-block bg-gray-100 text-gray-600 text-xs font-medium px-3 py-1.5 rounded-full max-w-xs">
                                <svg class="w-3 h-3 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($icon); ?>"/></svg>
                                <?php echo e($speaker->topic); ?>

                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        </div>
    </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\LPKD-APJI\PROSIDING\resources\views/templates/default/sections/speakers.blade.php ENDPATH**/ ?>