
<script>
(function() {
    if (!tailwind.config) tailwind.config = {};
    if (!tailwind.config.theme) tailwind.config.theme = {};
    if (!tailwind.config.theme.extend) tailwind.config.theme.extend = {};
    if (!tailwind.config.theme.extend.colors) tailwind.config.theme.extend.colors = {};
    var c = tailwind.config.theme.extend.colors;

    function palette(v) {
        return {
            50:  'color-mix(in srgb, ' + v + ' 5%, white)',
            100: 'color-mix(in srgb, ' + v + ' 10%, white)',
            200: 'color-mix(in srgb, ' + v + ' 25%, white)',
            300: 'color-mix(in srgb, ' + v + ' 40%, white)',
            400: 'color-mix(in srgb, ' + v + ' 60%, white)',
            500: 'color-mix(in srgb, ' + v + ' 80%, white)',
            600: v,
            700: 'color-mix(in srgb, ' + v + ', black 15%)',
            800: 'color-mix(in srgb, ' + v + ', black 30%)',
            900: 'color-mix(in srgb, ' + v + ', black 45%)',
            950: 'color-mix(in srgb, ' + v + ', black 60%)',
        };
    }

    // Primary color → blue, teal
    var p = 'var(--theme-primary-color)';
    c.blue = palette(p);
    c.teal = palette(p);

    // Secondary color → indigo, emerald
    var s = 'var(--theme-secondary-color)';
    c.indigo = palette(s);
    c.emerald = palette(s);

    // Accent color → cyan
    var a = 'var(--theme-accent-color)';
    c.cyan = palette(a);
})();
</script>

<style>
    /* ══════════════════════════════════════════════════
       CSS Variables from Active ThemePreset
    ══════════════════════════════════════════════════ */
    <?php echo \App\Models\ThemePreset::getActive()?->toCssVariables() ?? \App\Models\ThemePreset::defaultCssVariables(); ?>


    /* ══════════════════════════════════════════════════
       Structural Background Overrides
    ══════════════════════════════════════════════════ */
    body {
        background-color: var(--theme-body-bg) !important;
        font-family: var(--theme-font-family) !important;
    }

    /* Navigation */
    nav {
        background-color: var(--theme-nav-bg) !important;
    }
    nav.shadow-md, nav[class*="shadow"] {
        box-shadow: 0 4px 6px -1px color-mix(in srgb, var(--theme-nav-text) 10%, transparent) !important;
    }

    /* Footer */
    footer {
        background-color: var(--theme-footer-bg) !important;
        color: var(--theme-footer-text-color) !important;
    }
    footer a { color: var(--theme-footer-text-color) !important; }
    footer a:hover { color: white !important; opacity: 1; }

    /* White backgrounds → card-bg (cards, sections, modals) */
    .bg-white {
        background-color: var(--theme-card-bg) !important;
    }

    /* Gray backgrounds → theme-aware */
    .bg-gray-50 {
        background-color: var(--theme-body-bg) !important;
    }
    .bg-gray-100 {
        background-color: var(--theme-section-alt-bg) !important;
    }
    .bg-gray-200 {
        background-color: var(--theme-card-border) !important;
    }
    .bg-gray-800 {
        background-color: color-mix(in srgb, var(--theme-footer-bg), var(--theme-card-bg) 20%) !important;
    }
    .bg-gray-900 {
        background-color: var(--theme-footer-bg) !important;
    }

    /* ══════════════════════════════════════════════════
       Text Color Overrides (auto-adapts light/dark)
       Ramps from body-bg → nav-text for proper contrast
    ══════════════════════════════════════════════════ */
    .text-gray-900 { color: var(--theme-nav-text) !important; }
    .text-gray-800 { color: color-mix(in srgb, var(--theme-nav-text) 95%, var(--theme-body-bg)) !important; }
    .text-gray-700 { color: color-mix(in srgb, var(--theme-nav-text) 88%, var(--theme-body-bg)) !important; }
    .text-gray-600 { color: color-mix(in srgb, var(--theme-nav-text) 78%, var(--theme-body-bg)) !important; }
    .text-gray-500 { color: color-mix(in srgb, var(--theme-nav-text) 65%, var(--theme-body-bg)) !important; }
    .text-gray-400 { color: color-mix(in srgb, var(--theme-nav-text) 50%, var(--theme-body-bg)) !important; }
    .text-gray-300 { color: color-mix(in srgb, var(--theme-nav-text) 35%, var(--theme-body-bg)) !important; }

    /* Footer-specific text: always use footer text color */
    footer .text-gray-400,
    footer .text-gray-500 {
        color: var(--theme-footer-text-color) !important;
    }
    footer .text-white {
        color: white !important;
    }

    /* ══════════════════════════════════════════════════
       Border & Divider Overrides
    ══════════════════════════════════════════════════ */
    .border-gray-100, .border-gray-200, .border-gray-300 {
        border-color: var(--theme-card-border) !important;
    }
    .divide-gray-100 > * + *,
    .divide-gray-200 > * + *,
    .divide-gray-300 > * + * {
        border-color: var(--theme-card-border) !important;
    }
    .border-b, .border-t {
        border-color: var(--theme-card-border);
    }

    /* ══════════════════════════════════════════════════
       Navigation Links & Interactions
    ══════════════════════════════════════════════════ */
    nav a:hover, nav button:hover {
        color: var(--theme-link-hover-color) !important;
    }
    /* Nav hover backgrounds */
    nav .hover\:bg-blue-50:hover,
    nav .hover\:bg-teal-50:hover,
    nav .hover\:bg-gray-50:hover {
        background-color: color-mix(in srgb, var(--theme-primary-color) 10%, var(--theme-nav-bg)) !important;
    }

    /* Nav backdrop-blur overlay — respect nav-bg with opacity */
    nav.backdrop-blur, nav[class*="backdrop-blur"] {
        background-color: color-mix(in srgb, var(--theme-nav-bg) 90%, transparent) !important;
    }

    /* ══════════════════════════════════════════════════
       Typography
    ══════════════════════════════════════════════════ */
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--theme-heading-font) !important;
    }

    /* ══════════════════════════════════════════════════
       Layout: Container Width
    ══════════════════════════════════════════════════ */
    .max-w-5xl, .max-w-6xl, .max-w-7xl {
        max-width: var(--theme-container-width) !important;
    }

    /* ══════════════════════════════════════════════════
       Layout: Border Radius
    ══════════════════════════════════════════════════ */
    .rounded-lg { border-radius: var(--theme-border-radius) !important; }
    .rounded-xl { border-radius: calc(var(--theme-border-radius) * 1.5) !important; }
    .rounded-2xl { border-radius: calc(var(--theme-border-radius) * 2) !important; }

    /* ══════════════════════════════════════════════════
       Layout: Shadow Style
    ══════════════════════════════════════════════════ */
    .shadow, .shadow-md, .shadow-lg, .shadow-xl {
        box-shadow: var(--theme-shadow) !important;
    }

    /* ══════════════════════════════════════════════════
       Layout: Card Style
    ══════════════════════════════════════════════════ */
    <?php $cardStyle = \App\Models\ThemePreset::getActive()?->card_style ?? 'bordered'; ?>
    <?php if($cardStyle === 'bordered'): ?>
    [data-card], .theme-card { border: 1px solid var(--theme-card-border); box-shadow: none; }
    <?php elseif($cardStyle === 'shadow'): ?>
    [data-card], .theme-card { border: none; box-shadow: var(--theme-shadow); }
    <?php elseif($cardStyle === 'flat'): ?>
    [data-card], .theme-card { border: none; box-shadow: none; }
    <?php elseif($cardStyle === 'elevated'): ?>
    [data-card], .theme-card { border: none; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); transform: translateY(-1px); }
    <?php endif; ?>

    /* ══════════════════════════════════════════════════
       Layout: Navbar Style
    ══════════════════════════════════════════════════ */
    <?php $navStyle = \App\Models\ThemePreset::getActive()?->navbar_style ?? 'glass'; ?>
    <?php if($navStyle === 'glass'): ?>
    nav { backdrop-filter: blur(12px) saturate(180%) !important; background-color: color-mix(in srgb, var(--theme-nav-bg) 80%, transparent) !important; }
    <?php elseif($navStyle === 'solid'): ?>
    nav { backdrop-filter: none !important; background-color: var(--theme-nav-bg) !important; }
    <?php elseif($navStyle === 'transparent'): ?>
    nav:not(.scrolled) { background-color: transparent !important; backdrop-filter: none !important; }
    nav.scrolled, nav[data-scrolled="true"] { background-color: var(--theme-nav-bg) !important; }
    <?php elseif($navStyle === 'bordered'): ?>
    nav { backdrop-filter: none !important; background-color: var(--theme-nav-bg) !important; box-shadow: none !important; border-bottom: 2px solid var(--theme-card-border) !important; }
    <?php endif; ?>

    /* ══════════════════════════════════════════════════
       Layout: Footer Style
    ══════════════════════════════════════════════════ */
    <?php $footerStyle = \App\Models\ThemePreset::getActive()?->footer_style ?? 'dark'; ?>
    <?php if($footerStyle === 'light'): ?>
    footer { background-color: var(--theme-section-alt-bg) !important; color: var(--theme-nav-text) !important; }
    footer a { color: var(--theme-nav-text) !important; }
    footer a:hover { color: var(--theme-primary-color) !important; }
    footer .text-white { color: var(--theme-nav-text) !important; }
    <?php elseif($footerStyle === 'colored'): ?>
    footer { background-color: color-mix(in srgb, var(--theme-primary-color), black 30%) !important; color: white !important; }
    footer a { color: rgba(255,255,255,0.8) !important; }
    footer a:hover { color: white !important; }
    <?php elseif($footerStyle === 'minimal'): ?>
    footer { background-color: var(--theme-body-bg) !important; color: var(--theme-nav-text) !important; border-top: 1px solid var(--theme-card-border) !important; }
    footer a { color: var(--theme-nav-text) !important; }
    footer a:hover { color: var(--theme-primary-color) !important; }
    footer .text-white { color: var(--theme-nav-text) !important; }
    <?php endif; ?>
    

    /* ══════════════════════════════════════════════════
       Card & Section Borders
    ══════════════════════════════════════════════════ */
    .border { border-color: var(--theme-card-border); }
    section.bg-white { background-color: var(--theme-card-bg) !important; }

    /* ══════════════════════════════════════════════════
       Mobile Menu
    ══════════════════════════════════════════════════ */
    nav [x-show] {
        background-color: var(--theme-nav-bg) !important;
        border-color: var(--theme-card-border) !important;
    }
    nav [x-show] a {
        color: color-mix(in srgb, var(--theme-nav-text) 80%, var(--theme-body-bg)) !important;
    }
    nav [x-show] a:hover {
        background-color: color-mix(in srgb, var(--theme-primary-color) 10%, var(--theme-nav-bg)) !important;
        color: var(--theme-link-hover-color) !important;
    }
</style>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\App\Models\ThemePreset::getActive()?->custom_css): ?>
<style><?php echo \App\Models\ThemePreset::getActive()->custom_css; ?></style>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\LPKD-APJI\PROSIDING\resources\views/templates/default/partials/theme-config.blade.php ENDPATH**/ ?>