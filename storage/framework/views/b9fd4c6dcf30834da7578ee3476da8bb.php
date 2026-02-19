
<?php
    $__navName = $siteName ?? \App\Models\Setting::getValue('site_name', 'Prosiding');
    $__navTagline = ($siteTagline ?? null) ?: \App\Models\Setting::getValue('site_tagline');
?>
<div class="min-w-0">
    <span class="text-lg font-bold text-gray-800 leading-tight block"><?php echo e($__navName); ?></span>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__navTagline): ?>
    <span class="text-xs text-gray-500 leading-tight block truncate max-w-[200px] sm:max-w-xs"><?php echo e($__navTagline); ?></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\LPKD-APJI\PROSIDING\resources\views/partials/navbar-brand.blade.php ENDPATH**/ ?>