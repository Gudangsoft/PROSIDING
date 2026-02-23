<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class MenuManager extends Component
{
    public string $location = 'header';

    // Form fields
    public ?int $editingId = null;
    public string $title = '';
    public string $url = '';
    public string $target = '_self';
    public string $icon = '';
    public ?int $parent_id = null;
    public bool $is_active = true;
    public int $sort_order = 0;

    public bool $showForm = false;

    // Default menu visibility toggles
    public array $defaultMenuVisibility = [];

    /**
     * List of all default (hardcoded) menu items
     */
    public static function getDefaultMenuItems(): array
    {
        return [
            ['key' => 'tentang',         'label' => 'Tentang',         'url' => '#conference',          'location' => 'header', 'conditional' => 'Tampil jika ada konferensi aktif'],
            ['key' => 'tanggal_penting', 'label' => 'Tanggal Penting', 'url' => '#dates',               'location' => 'header', 'conditional' => 'Tampil jika ada konferensi aktif'],
            ['key' => 'speaker',         'label' => 'Speaker',         'url' => '#speakers',            'location' => 'header', 'conditional' => 'Tampil jika ada keynote speaker'],
            ['key' => 'jurnal',          'label' => 'Jurnal',          'url' => '#journals',            'location' => 'header', 'conditional' => 'Tampil jika ada jurnal aktif'],
            ['key' => 'berita',          'label' => 'Berita',          'url' => '#news',                'location' => 'header', 'conditional' => 'Tampil jika ada berita'],
            ['key' => 'publikasi',       'label' => 'Publikasi',       'url' => '/publikasi',           'location' => 'header', 'conditional' => null],
            ['key' => 'arsip',           'label' => 'Arsip',           'url' => '/arsip',               'location' => 'header', 'conditional' => null],
            ['key' => 'login',           'label' => 'Login',           'url' => '/login',               'location' => 'header', 'conditional' => 'Tampil untuk pengunjung (belum login)'],
            ['key' => 'register',        'label' => 'Register',        'url' => '/register',            'location' => 'header', 'conditional' => 'Tampil untuk pengunjung (belum login)'],
            ['key' => 'dashboard',       'label' => 'Dashboard',       'url' => '/dashboard',           'location' => 'header', 'conditional' => 'Tampil untuk pengguna yang sudah login'],
            ['key' => 'footer_login',    'label' => 'Login',           'url' => '/login',               'location' => 'footer', 'conditional' => null],
            ['key' => 'footer_register', 'label' => 'Register',        'url' => '/register',            'location' => 'footer', 'conditional' => null],
        ];
    }

    public function mount(): void
    {
        $this->loadDefaultMenuVisibility();
    }

    public function loadDefaultMenuVisibility(): void
    {
        $saved = Setting::getValue('default_menu_visibility', []);
        if (is_string($saved)) {
            $saved = json_decode($saved, true) ?: [];
        }
        // Initialize all to visible (true) by default
        $this->defaultMenuVisibility = [];
        foreach (self::getDefaultMenuItems() as $item) {
            $this->defaultMenuVisibility[$item['key']] = $saved[$item['key']] ?? true;
        }
    }

    public function toggleDefaultMenu(string $key): void
    {
        $this->defaultMenuVisibility[$key] = !($this->defaultMenuVisibility[$key] ?? true);
        Setting::setValue('default_menu_visibility', json_encode($this->defaultMenuVisibility), 'menu', 'json', 'Visibilitas Menu Default');
        session()->flash('success', 'Visibilitas menu default diperbarui.');
    }

    /**
     * Helper: check if a default menu item is visible
     */
    public static function isDefaultMenuVisible(string $key): bool
    {
        $saved = Setting::getValue('default_menu_visibility', []);
        if (is_string($saved)) {
            $saved = json_decode($saved, true) ?: [];
        }
        return $saved[$key] ?? true;
    }

    protected function rules(): array
    {
        return [
            'title'     => 'required|string|max:255',
            'url'       => 'nullable|string|max:500',
            'target'    => 'required|in:_self,_blank',
            'icon'      => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:menus,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function switchLocation(string $location): void
    {
        $this->location = $location;
        $this->resetForm();
    }

    public function openForm(?int $parentId = null): void
    {
        $this->resetForm();
        if ($parentId) {
            $this->parent_id = $parentId;
        }
        $this->sort_order = Menu::where('location', $this->location)
            ->where('parent_id', $parentId)
            ->max('sort_order') + 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $this->editingId  = $menu->id;
        $this->title      = $menu->title;
        $this->url        = $menu->url ?? '';
        $this->target     = $menu->target;
        $this->icon       = $menu->icon ?? '';
        $this->parent_id  = $menu->parent_id;
        $this->is_active  = $menu->is_active;
        $this->sort_order = $menu->sort_order;
        $this->showForm   = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'      => $this->title,
            'url'        => $this->url ?: null,
            'target'     => $this->target,
            'icon'       => $this->icon ?: null,
            'location'   => $this->location,
            'parent_id'  => $this->parent_id,
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        // Prevent nesting beyond 3 levels
        if ($this->parent_id) {
            $parent = Menu::find($this->parent_id);
            if ($parent && $parent->depth >= 2) {
                $this->addError('parent_id', 'Maksimal 3 level kedalaman menu.');
                return;
            }
        }

        if ($this->editingId) {
            $menu = Menu::findOrFail($this->editingId);
            // Prevent setting self as parent
            if ($this->parent_id == $this->editingId) {
                $this->addError('parent_id', 'Menu tidak boleh menjadi parent dari dirinya sendiri.');
                return;
            }
            $menu->update($data);
            session()->flash('success', 'Menu berhasil diperbarui.');
        } else {
            Menu::create($data);
            session()->flash('success', 'Menu berhasil ditambahkan.');
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $menu->delete(); // cascade deletes children
        session()->flash('success', 'Menu berhasil dihapus.');
    }

    public function toggleActive(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_active' => !$menu->is_active]);
    }

    public function moveUp(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $sibling = Menu::where('location', $menu->location)
            ->where('parent_id', $menu->parent_id)
            ->where('sort_order', '<', $menu->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($sibling) {
            $tempOrder = $menu->sort_order;
            $menu->update(['sort_order' => $sibling->sort_order]);
            $sibling->update(['sort_order' => $tempOrder]);
        }
    }

    public function moveDown(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $sibling = Menu::where('location', $menu->location)
            ->where('parent_id', $menu->parent_id)
            ->where('sort_order', '>', $menu->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($sibling) {
            $tempOrder = $menu->sort_order;
            $menu->update(['sort_order' => $sibling->sort_order]);
            $sibling->update(['sort_order' => $tempOrder]);
        }
    }

    public function resetForm(): void
    {
        $this->editingId  = null;
        $this->title      = '';
        $this->url        = '';
        $this->target     = '_self';
        $this->icon       = '';
        $this->parent_id  = null;
        $this->is_active  = true;
        $this->sort_order = 0;
        $this->showForm   = false;
        $this->resetValidation();
    }

    /**
     * Get all menus for the current location as a flat list with depth
     */
    public function getMenuTreeProperty()
    {
        return Menu::where('location', $this->location)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with('allChildren')
            ->get();
    }

    /**
     * Get available parents for a menu item (excluding self & own children)
     */
    public function getAvailableParentsProperty()
    {
        $query = Menu::where('location', $this->location)
            ->orderBy('sort_order');

        if ($this->editingId) {
            // Exclude self and all descendants
            $excludeIds = $this->getDescendantIds($this->editingId);
            $excludeIds[] = $this->editingId;
            $query->whereNotIn('id', $excludeIds);
        }

        // Only allow up to depth 1 as parent (so children become depth 2 = level 3)
        $menus = $query->get()->filter(function ($menu) {
            return $menu->depth < 2;
        });

        return $menus;
    }

    private function getDescendantIds(int $menuId): array
    {
        $ids = [];
        $children = Menu::where('parent_id', $menuId)->get();
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child->id));
        }
        return $ids;
    }

    /**
     * Get default menus filtered by current location
     */
    public function getDefaultMenusProperty(): array
    {
        return collect(self::getDefaultMenuItems())
            ->filter(fn($item) => $item['location'] === $this->location)
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.menu-manager')
            ->title('Kelola Menu');
    }
}
