


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->allActiveChildren && $item->allActiveChildren->count()): ?>
        
        <div x-data="{ expanded: false }">
            <div class="flex items-center">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->url): ?>
                <a href="<?php echo e($item->url); ?>" target="<?php echo e($item->target); ?>" @click="mobileOpen=false"
                   class="flex-1 block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg <?php echo e($depth > 0 ? 'pl-' . (3 + $depth * 4) : ''); ?>">
                    <?php echo e($item->title); ?>

                </a>
                <?php else: ?>
                <span class="flex-1 block px-3 py-2 text-sm text-gray-500 <?php echo e($depth > 0 ? 'pl-' . (3 + $depth * 4) : ''); ?>">
                    <?php echo e($item->title); ?>

                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <button @click="expanded = !expanded" class="p-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4 transition" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
            <div x-show="expanded" x-cloak x-collapse>
                <?php echo $__env->make(\App\Helpers\Template::view('partials.menu-mobile'), ['items' => $item->allActiveChildren->sortBy('sort_order'), 'depth' => $depth + 1], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    <?php else: ?>
        
        <a href="<?php echo e($item->url ?: '#'); ?>" target="<?php echo e($item->target); ?>" @click="mobileOpen=false"
           class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg" style="<?php echo e($depth > 0 ? 'padding-left: ' . (0.75 + $depth * 1) . 'rem;' : ''); ?>">
            <?php echo e($item->title); ?>

        </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
<?php /**PATH D:\LPKD-APJI\PROSIDING\resources\views/templates/default/partials/menu-mobile.blade.php ENDPATH**/ ?>