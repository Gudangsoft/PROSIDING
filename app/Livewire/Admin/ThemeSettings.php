<?php

namespace App\Livewire\Admin;

use App\Models\ThemePreset;
use App\Models\Setting;
use App\Helpers\Template;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ThemeSettings extends Component
{
    use WithFileUploads;

    // Current editing state
    public array $form = [];
    public ?int $editingPresetId = null;
    public string $presetName = '';
    public string $presetDescription = '';
    public string $linkedTemplate = '';

    // File upload
    public $loginBgImage = null;

    // Sections config
    public array $sectionsConfig = [];

    // UI state
    public string $activeTab = 'colors';
    public bool $showSaveAsModal = false;
    public string $saveAsName = '';
    public string $saveAsDescription = '';
    public string $saveAsLinkedTemplate = '';

    // Create new template modal
    public bool $showCreateTemplateModal = false;
    public string $newTemplateName = '';
    public string $newTemplateSlug = '';
    public string $newTemplateDescription = '';
    public string $newTemplateBase = 'default';

    // Import template
    public bool $showImportTemplateModal = false;
    public $importTemplateZip = null;
    public string $importTemplateSlug = '';
    public bool $importOverwrite = false;

    public function mount()
    {
        $this->loadActivePreset();
    }

    public function loadActivePreset()
    {
        $active = ThemePreset::getActive();
        if ($active) {
            $this->editingPresetId = $active->id;
            $this->presetName = $active->name;
            $this->presetDescription = $active->description ?? '';
            $this->linkedTemplate = $active->linked_template ?? '';
            $this->form = $active->only(
                array_merge(ThemePreset::COLOR_FIELDS, ThemePreset::TYPO_FIELDS, ThemePreset::LAYOUT_FIELDS, ['custom_css', 'login_bg_image'])
            );
            $this->sectionsConfig = $active->getSectionsConfig();
        } else {
            $this->editingPresetId = null;
            $this->presetName = 'Default';
            $this->presetDescription = '';
            $this->linkedTemplate = 'default';
            $this->form = ThemePreset::DEFAULTS;
            $this->sectionsConfig = ThemePreset::DEFAULT_SECTIONS_CONFIG;
        }
    }

    public function loadPreset(int $id)
    {
        $preset = ThemePreset::findOrFail($id);
        $this->editingPresetId = $preset->id;
        $this->presetName = $preset->name;
        $this->presetDescription = $preset->description ?? '';
        $this->linkedTemplate = $preset->linked_template ?? '';
        $this->form = $preset->only(
            array_merge(ThemePreset::COLOR_FIELDS, ThemePreset::TYPO_FIELDS, ThemePreset::LAYOUT_FIELDS, ['custom_css', 'login_bg_image'])
        );
        $this->sectionsConfig = $preset->getSectionsConfig();
        $this->loginBgImage = null;
    }

    public function activatePreset(int $id)
    {
        $preset = ThemePreset::findOrFail($id);
        $preset->is_active = true;
        $preset->save(); // booted() deactivates others

        // Sync linked template to active_template setting
        if ($preset->linked_template) {
            Setting::setValue('active_template', $preset->linked_template);
        }

        // Sync old settings table for backward compatibility
        $this->syncToSettings($preset);

        $this->loadPreset($id);
        session()->flash('success', "Tema \"{$preset->name}\" berhasil diaktifkan.");
    }

    public function save()
    {
        $this->validate([
            'presetName' => 'required|string|max:100',
        ]);

        // Auto-generate template slug from preset name
        $templateSlug = Str::slug($this->presetName);
        $this->linkedTemplate = $this->ensureTemplateFolder($templateSlug, $this->linkedTemplate ?: 'default');

        // Handle file upload
        $loginBgPath = $this->form['login_bg_image'] ?? null;
        if ($this->loginBgImage) {
            if ($loginBgPath && \Storage::disk('public')->exists($loginBgPath)) {
                \Storage::disk('public')->delete($loginBgPath);
            }
            $loginBgPath = $this->loginBgImage->store('settings', 'public');
            $this->loginBgImage = null;
        }

        $data = array_merge($this->form, [
            'name' => $this->presetName,
            'description' => $this->presetDescription,
            'linked_template' => $this->linkedTemplate,
            'login_bg_image' => $loginBgPath,
            'sections_config' => $this->sectionsConfig,
        ]);

        if ($this->editingPresetId) {
            $preset = ThemePreset::findOrFail($this->editingPresetId);
            $preset->update($data);
        } else {
            $data['slug'] = Str::slug($this->presetName);
            $data['is_active'] = true;
            $preset = ThemePreset::create($data);
            $this->editingPresetId = $preset->id;
        }

        $this->form['login_bg_image'] = $loginBgPath;

        // Sync to old settings if active
        if ($preset->is_active) {
            $this->syncToSettings($preset);
            if ($preset->linked_template) {
                Setting::setValue('active_template', $preset->linked_template);
            }
        }

        session()->flash('success', "Tema \"{$preset->name}\" berhasil disimpan.");
    }

    public function openSaveAs()
    {
        $this->saveAsName = $this->presetName . ' (Copy)';
        $this->saveAsDescription = $this->presetDescription;
        $this->saveAsLinkedTemplate = Str::slug($this->saveAsName);
        $this->showSaveAsModal = true;
    }

    public function updatedSaveAsName($value)
    {
        // Auto-update template slug when name changes
        $this->saveAsLinkedTemplate = Str::slug($value);
    }

    public function openCreateTemplate()
    {
        $this->newTemplateName = '';
        $this->newTemplateSlug = '';
        $this->newTemplateDescription = '';
        $this->newTemplateBase = 'default';
        $this->showCreateTemplateModal = true;
    }

    public function updatedNewTemplateName($value)
    {
        $this->newTemplateSlug = Str::slug($value);
    }

    public function createTemplate()
    {
        $this->validate([
            'newTemplateName' => 'required|string|max:100',
            'newTemplateSlug' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                function ($attribute, $value, $fail) {
                    // Must be unique (not used by another preset)
                    $exists = ThemePreset::where('linked_template', $value)->exists();
                    if ($exists) {
                        $fail('Nama template "' . $value . '" sudah digunakan oleh tema lain.');
                    }
                    // Must not already exist as a folder with a different owner
                    $existsBySlug = ThemePreset::where('slug', $value)->exists();
                    if ($existsBySlug) {
                        $fail('Slug "' . $value . '" sudah digunakan.');
                    }
                },
            ],
            'newTemplateBase' => 'required|string',
        ], [
            'newTemplateSlug.regex' => 'Nama template hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
        ]);

        // Create template folder by copying from base template
        $templateSlug = $this->ensureTemplateFolder($this->newTemplateSlug, $this->newTemplateBase);

        // Create preset with default values
        $data = array_merge(ThemePreset::DEFAULTS, [
            'name' => $this->newTemplateName,
            'slug' => $this->newTemplateSlug,
            'description' => $this->newTemplateDescription,
            'linked_template' => $templateSlug,
            'is_active' => false,
            'is_default' => false,
            'sections_config' => ThemePreset::DEFAULT_SECTIONS_CONFIG,
        ]);

        $preset = ThemePreset::create($data);

        // Load the newly created preset for editing
        $this->loadPreset($preset->id);
        $this->showCreateTemplateModal = false;

        session()->flash('success', "Template baru \"{$preset->name}\" berhasil dibuat. Silakan kustomisasi sesuai kebutuhan.");
    }

    public function saveAs()
    {
        $this->validate([
            'saveAsName' => 'required|string|max:100',
            'saveAsLinkedTemplate' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                function ($attribute, $value, $fail) {
                    // Must be different from current linked template
                    if ($value === $this->linkedTemplate) {
                        $fail('Nama template harus berbeda dari template yang sedang diedit.');
                    }
                    // Must be unique (not already used by another preset)
                    $exists = ThemePreset::where('linked_template', $value)
                        ->when($this->editingPresetId, fn($q) => $q->where('id', '!=', $this->editingPresetId))
                        ->exists();
                    if ($exists) {
                        $fail('Nama template "' . $value . '" sudah digunakan oleh tema lain.');
                    }
                },
            ],
        ], [
            'saveAsLinkedTemplate.regex' => 'Nama template hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
        ]);

        // Use the user-specified template slug
        $templateSlug = $this->saveAsLinkedTemplate;
        $newLinkedTemplate = $this->ensureTemplateFolder($templateSlug, $this->linkedTemplate ?: 'default');

        // Handle file upload
        $loginBgPath = $this->form['login_bg_image'] ?? null;
        if ($this->loginBgImage) {
            if ($loginBgPath && \Storage::disk('public')->exists($loginBgPath)) {
                \Storage::disk('public')->delete($loginBgPath);
            }
            $loginBgPath = $this->loginBgImage->store('settings', 'public');
            $this->loginBgImage = null;
        }

        $data = array_merge($this->form, [
            'name' => $this->saveAsName,
            'description' => $this->saveAsDescription,
            'slug' => $templateSlug,
            'linked_template' => $newLinkedTemplate,
            'is_active' => false,
            'login_bg_image' => $loginBgPath,
            'sections_config' => $this->sectionsConfig,
        ]);

        $preset = ThemePreset::create($data);
        $this->editingPresetId = $preset->id;
        $this->presetName = $preset->name;
        $this->presetDescription = $preset->description ?? '';
        $this->linkedTemplate = $preset->linked_template;
        $this->form['login_bg_image'] = $loginBgPath;
        $this->showSaveAsModal = false;

        session()->flash('success', "Tema baru \"{$preset->name}\" berhasil dibuat.");
    }

    public function deletePreset(int $id)
    {
        $preset = ThemePreset::findOrFail($id);
        if ($preset->is_default) {
            session()->flash('error', 'Tema bawaan tidak bisa dihapus.');
            return;
        }
        $wasActive = $preset->is_active;
        $name = $preset->name;

        // Delete login bg image
        if ($preset->login_bg_image && \Storage::disk('public')->exists($preset->login_bg_image)) {
            \Storage::disk('public')->delete($preset->login_bg_image);
        }

        $preset->delete();

        if ($wasActive) {
            // Activate default or first available
            $fallback = ThemePreset::where('is_default', true)->first() ?? ThemePreset::first();
            if ($fallback) {
                $fallback->is_active = true;
                $fallback->save();
                $this->syncToSettings($fallback);
                $this->loadPreset($fallback->id);
            } else {
                $this->editingPresetId = null;
                $this->form = ThemePreset::DEFAULTS;
                $this->presetName = 'Default';
            }
        } elseif ($this->editingPresetId === $id) {
            $this->loadActivePreset();
        }

        session()->flash('success', "Tema \"{$name}\" berhasil dihapus.");
    }

    public function duplicatePreset(int $id)
    {
        $source = ThemePreset::findOrFail($id);
        $data = $source->toArray();
        unset($data['id'], $data['created_at'], $data['updated_at']);
        $data['name'] = $source->name . ' (Copy)';
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        $data['is_active'] = false;
        $data['is_default'] = false;

        // Create template folder for the copy
        $templateSlug = Str::slug($data['name']);
        $data['linked_template'] = $this->ensureTemplateFolder($templateSlug, $source->linked_template ?: 'default');

        $new = ThemePreset::create($data);
        session()->flash('success', "Tema berhasil diduplikasi sebagai \"{$new->name}\".");
    }

    public function resetToDefaults()
    {
        $this->form = ThemePreset::DEFAULTS;
        session()->flash('success', 'Warna dan pengaturan direset ke default.');
    }

    /**
     * Sync preset values to old Settings table for backward compat.
     */
    protected function syncToSettings(ThemePreset $preset): void
    {
        $map = [
            'primary_color' => 'theme_primary_color',
            'secondary_color' => 'theme_secondary_color',
            'accent_color' => 'theme_accent_color',
            'sidebar_bg' => 'theme_sidebar_bg',
            'sidebar_text' => 'theme_sidebar_text',
            'header_bg' => 'theme_header_bg',
            'body_bg' => 'theme_body_bg',
            'font_family' => 'theme_font_family',
            'border_radius' => 'theme_border_radius',
            'custom_css' => 'theme_custom_css',
            'login_bg_image' => 'theme_login_bg_image',
        ];

        foreach ($map as $presetField => $settingKey) {
            Setting::setValue($settingKey, $preset->$presetField ?? '');
        }
    }

    /**
     * Ensure a template folder exists for the given slug.
     * Copies from a base template (default) if the folder doesn't exist yet.
     * Returns the slug used as the folder name.
     */
    protected function ensureTemplateFolder(string $slug, ?string $baseTemplate = null): string
    {
        $templatesPath = resource_path('views/templates');
        $targetPath = $templatesPath . '/' . $slug;

        // Already exists — no need to create
        if (File::isDirectory($targetPath)) {
            return $slug;
        }

        // Pick base template to copy from
        $base = $baseTemplate && File::isDirectory($templatesPath . '/' . $baseTemplate)
            ? $baseTemplate
            : 'default';
        $basePath = $templatesPath . '/' . $base;

        // Copy entire template directory
        File::copyDirectory($basePath, $targetPath);

        // Update the @include paths inside all blade files to point to the new template
        $bladeFiles = File::allFiles($targetPath);
        foreach ($bladeFiles as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                $updated = str_replace(
                    "templates.{$base}.",
                    "templates.{$slug}.",
                    $content
                );
                File::put($file->getPathname(), $updated);
            }
        }

        return $slug;
    }

    // ─── Import Template ───────────────────────────────────────────

    public function openImportTemplate()
    {
        $this->importTemplateZip = null;
        $this->importTemplateSlug = '';
        $this->importOverwrite = false;
        $this->showImportTemplateModal = true;
    }

    public function importTemplate()
    {
        $this->validate([
            'importTemplateZip' => 'required|file|mimes:zip|max:51200', // max 50MB
        ], [
            'importTemplateZip.required' => 'File ZIP template wajib diunggah.',
            'importTemplateZip.mimes' => 'File harus berformat .zip.',
            'importTemplateZip.max' => 'Ukuran file maksimal 50MB.',
        ]);

        $templatesPath = resource_path('views/templates');
        $tempPath = storage_path('app/temp_template_' . Str::random(8));

        try {
            // Store uploaded file temporarily
            $zipPath = $this->importTemplateZip->getRealPath();

            // Open and extract ZIP
            $zip = new \ZipArchive();
            $result = $zip->open($zipPath);
            if ($result !== true) {
                throw new \Exception('Gagal membuka file ZIP. Pastikan file tidak rusak.');
            }

            // Extract to temp directory
            File::ensureDirectoryExists($tempPath);
            $zip->extractTo($tempPath);
            $zip->close();

            // Detect template root: might be extracted directly or inside a subfolder
            $templateRoot = $this->detectTemplateRoot($tempPath);
            if (!$templateRoot) {
                throw new \Exception('Struktur template tidak valid. ZIP harus berisi file blade (.blade.php) dan minimal file welcome.blade.php.');
            }

            // Determine slug from folder name or override
            $detectedSlug = basename($templateRoot);
            // If root is the temp path itself, use the zip filename as slug
            if (realpath($templateRoot) === realpath($tempPath)) {
                $originalName = pathinfo($this->importTemplateZip->getClientOriginalName(), PATHINFO_FILENAME);
                $detectedSlug = Str::slug($originalName);
            }
            $slug = $this->importTemplateSlug ?: $detectedSlug;
            $slug = Str::slug($slug);

            if (empty($slug)) {
                throw new \Exception('Tidak dapat menentukan nama folder template. Isi kolom "Nama Folder" secara manual.');
            }

            $targetPath = $templatesPath . '/' . $slug;

            // Check if already exists
            if (File::isDirectory($targetPath) && !$this->importOverwrite) {
                throw new \Exception('Template "' . $slug . '" sudah ada. Centang opsi "Timpa jika sudah ada" atau gunakan nama folder berbeda.');
            }

            // Remove existing if overwrite
            if (File::isDirectory($targetPath) && $this->importOverwrite) {
                File::deleteDirectory($targetPath);
            }

            // Copy template files to target
            File::copyDirectory($templateRoot, $targetPath);

            // Update @include paths inside blade files to match new slug
            $this->updateBladeIncludes($targetPath, $slug);

            // Check if theme.json / manifest exists for preset data
            $presetData = $this->readTemplateManifest($targetPath);

            // Create a ThemePreset record
            $presetName = $presetData['name'] ?? ucfirst(str_replace('-', ' ', $slug));
            $existingPreset = ThemePreset::where('linked_template', $slug)->first();

            if ($existingPreset && $this->importOverwrite) {
                // Update existing preset
                $existingPreset->update(array_merge(
                    ['name' => $presetName],
                    ['description' => $presetData['description'] ?? $existingPreset->description],
                    $this->extractPresetColors($presetData)
                ));
                $preset = $existingPreset;
            } else {
                // Create new preset
                $data = array_merge(ThemePreset::DEFAULTS, $this->extractPresetColors($presetData), [
                    'name' => $presetName,
                    'slug' => $slug . '-' . Str::random(4),
                    'description' => $presetData['description'] ?? 'Diimpor dari file ZIP',
                    'linked_template' => $slug,
                    'is_active' => false,
                    'is_default' => false,
                    'sections_config' => $presetData['sections_config'] ?? ThemePreset::DEFAULT_SECTIONS_CONFIG,
                ]);
                $preset = ThemePreset::create($data);
            }

            // Load the imported preset
            $this->loadPreset($preset->id);
            $this->showImportTemplateModal = false;
            $this->importTemplateZip = null;

            session()->flash('success', "Template \"{$presetName}\" berhasil diimpor ke folder templates/{$slug}/.");

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        } finally {
            // Clean up temp directory
            if (File::isDirectory($tempPath)) {
                File::deleteDirectory($tempPath);
            }
        }
    }

    /**
     * Detect the actual template root inside extracted ZIP.
     * The ZIP might contain files directly or inside a single subfolder.
     */
    protected function detectTemplateRoot(string $path): ?string
    {
        // Check if welcome.blade.php exists at root level
        if (File::exists($path . '/welcome.blade.php')) {
            return $path;
        }

        // Check one level deep (common: zip contains a single folder)
        $dirs = File::directories($path);
        foreach ($dirs as $dir) {
            if (File::exists($dir . '/welcome.blade.php')) {
                return $dir;
            }
        }

        // Check if any .blade.php files exist at root (partial template)
        $bladeFiles = File::glob($path . '/*.blade.php');
        if (!empty($bladeFiles)) {
            return $path;
        }

        // Check one level deep for blade files
        foreach ($dirs as $dir) {
            $bladeFiles = File::glob($dir . '/*.blade.php');
            if (!empty($bladeFiles)) {
                return $dir;
            }
        }

        return null;
    }

    /**
     * Update @include / @extends paths in all blade files to match the new template slug.
     */
    protected function updateBladeIncludes(string $targetPath, string $slug): void
    {
        $bladeFiles = File::allFiles($targetPath);
        foreach ($bladeFiles as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                // Replace templates.{anything}. with templates.{slug}.
                $updated = preg_replace(
                    '/templates\.([a-z0-9\-]+)\./',
                    'templates.' . $slug . '.',
                    $content
                );
                if ($updated !== $content) {
                    File::put($file->getPathname(), $updated);
                }
            }
        }
    }

    /**
     * Read theme.json manifest from imported template if it exists.
     */
    protected function readTemplateManifest(string $templatePath): array
    {
        $manifestPath = $templatePath . '/theme.json';
        if (!File::exists($manifestPath)) {
            return [];
        }

        try {
            $data = json_decode(File::get($manifestPath), true);
            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Extract color/typo/layout fields from manifest data.
     */
    protected function extractPresetColors(array $manifest): array
    {
        $fields = array_merge(ThemePreset::COLOR_FIELDS, ThemePreset::TYPO_FIELDS, ThemePreset::LAYOUT_FIELDS);
        $result = [];
        foreach ($fields as $field) {
            if (isset($manifest[$field])) {
                $result[$field] = $manifest[$field];
            }
        }
        return $result;
    }

    /**
     * Toggle visibility of a section by its ID.
     */
    public function toggleSectionVisibility(string $sectionId)
    {
        foreach ($this->sectionsConfig as &$section) {
            if ($section['id'] === $sectionId) {
                $section['visible'] = !$section['visible'];
                break;
            }
        }
    }

    /**
     * Update section order from drag-drop reorder (receives array of section IDs in new order).
     */
    public function updateSectionOrder(array $orderedIds)
    {
        $orderMap = array_flip($orderedIds);
        foreach ($this->sectionsConfig as &$section) {
            if (isset($orderMap[$section['id']])) {
                $section['order'] = $orderMap[$section['id']];
            }
        }
        // Sort by new order
        usort($this->sectionsConfig, fn($a, $b) => $a['order'] <=> $b['order']);
    }

    /**
     * Move a section up in order.
     */
    public function moveSectionUp(string $sectionId)
    {
        $index = null;
        foreach ($this->sectionsConfig as $i => $section) {
            if ($section['id'] === $sectionId) {
                $index = $i;
                break;
            }
        }
        if ($index !== null && $index > 0) {
            // Swap with previous
            $temp = $this->sectionsConfig[$index - 1];
            $this->sectionsConfig[$index - 1] = $this->sectionsConfig[$index];
            $this->sectionsConfig[$index] = $temp;
            // Re-number orders
            foreach ($this->sectionsConfig as $i => &$s) {
                $s['order'] = $i;
            }
        }
    }

    /**
     * Move a section down in order.
     */
    public function moveSectionDown(string $sectionId)
    {
        $index = null;
        foreach ($this->sectionsConfig as $i => $section) {
            if ($section['id'] === $sectionId) {
                $index = $i;
                break;
            }
        }
        if ($index !== null && $index < count($this->sectionsConfig) - 1) {
            // Swap with next
            $temp = $this->sectionsConfig[$index + 1];
            $this->sectionsConfig[$index + 1] = $this->sectionsConfig[$index];
            $this->sectionsConfig[$index] = $temp;
            // Re-number orders
            foreach ($this->sectionsConfig as $i => &$s) {
                $s['order'] = $i;
            }
        }
    }

    /**
     * Reset sections to default config.
     */
    public function resetSections()
    {
        $this->sectionsConfig = ThemePreset::DEFAULT_SECTIONS_CONFIG;
    }

    public function render()
    {
        return view('livewire.admin.theme-settings', [
            'presets' => ThemePreset::orderBy('is_default', 'desc')->orderBy('name')->get(),
            'availableTemplates' => Template::available(),
            'currentActiveTemplate' => Template::active(),
            'fontOptions' => ThemePreset::FONT_OPTIONS,
            'borderRadiusOptions' => ThemePreset::BORDER_RADIUS_OPTIONS,
            'shadowOptions' => ThemePreset::SHADOW_OPTIONS,
            'navbarStyles' => ThemePreset::NAVBAR_STYLES,
            'heroStyles' => ThemePreset::HERO_STYLES,
            'footerStyles' => ThemePreset::FOOTER_STYLES,
            'cardStyles' => ThemePreset::CARD_STYLES,
            'containerWidthOptions' => ThemePreset::CONTAINER_WIDTH_OPTIONS,
            'sectionLabels' => collect(ThemePreset::SECTIONS)->pluck('label', 'id')->toArray(),
        ])->layout('layouts.app');
    }
}

