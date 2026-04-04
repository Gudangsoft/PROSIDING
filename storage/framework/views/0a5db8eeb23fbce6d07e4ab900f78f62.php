<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        $guestSiteName = \App\Models\Setting::getValue('site_name', config('app.name', 'Prosiding'));
        $guestSiteTagline = \App\Models\Setting::getValue('site_tagline', 'Sistem Manajemen Prosiding');
        $guestSiteLogo = \App\Models\Setting::getValue('site_logo');
        $guestFavicon = \App\Models\Setting::getValue('site_favicon');
    ?>
    <title><?php echo $__env->yieldContent('title', $guestSiteName); ?></title>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guestFavicon): ?>
        <link rel="icon" href="<?php echo e(asset('storage/' . $guestFavicon)); ?>" type="image/png">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guestSiteLogo): ?>
                <img src="<?php echo e(asset('storage/' . $guestSiteLogo)); ?>" alt="<?php echo e($guestSiteName); ?>" class="h-16 mx-auto mb-3 object-contain">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <h1 class="text-3xl font-bold text-blue-800"><?php echo e($guestSiteName); ?></h1>
            <p class="text-gray-500 mt-2"><?php echo e($guestSiteTagline); ?></p>
        </div>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH D:\LPKD-APJI\PROSIDING\resources\views/layouts/guest.blade.php ENDPATH**/ ?>