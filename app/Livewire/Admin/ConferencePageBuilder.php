<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConferencePageBuilder extends Component
{
    use WithFileUploads;

    public int $conferenceId = 0;
    public array $blocks = [];

    public bool $showBlockModal = false;
    public ?int $editIndex = null;

    // Block form fields
    public string $blockType = 'text';
    public string $blockTitle = '';
    public string $blockContent = '';
    public string $blockBgColor = '#ffffff';
    public string $blockTextColor = '#1f2937';
    public bool $blockVisible = true;
    public string $blockButtonText = '';
    public string $blockButtonUrl = '';
    public string $blockImageUrl = '';
    public $blockImageUpload = null;

    const BLOCK_TYPES = [
        'hero'       => 'Hero Banner',
        'text'       => 'Blok Teks',
        'text_image' => 'Teks + Gambar',
        'cta'        => 'Call to Action',
        'highlight'  => 'Highlight Box',
        'divider'    => 'Pembatas / Divider',
        'html'       => 'HTML Custom',
    ];

    public function mount(): void
    {
        $this->loadBlocks();
    }

    public function updatedConferenceId(): void
    {
        $this->loadBlocks();
    }

    protected function loadBlocks(): void
    {
        if (!$this->conferenceId) { $this->blocks = []; return; }
        $conf = Conference::find($this->conferenceId);
        $this->blocks = $conf?->page_builder_blocks ?? [];
    }

    public function openAddBlock(string $type = 'text'): void
    {
        $this->resetBlockForm();
        $this->blockType     = $type;
        $this->showBlockModal = true;
    }

    public function openEditBlock(int $index): void
    {
        $b = $this->blocks[$index];
        $this->editIndex       = $index;
        $this->blockType       = $b['type'];
        $this->blockTitle      = $b['title'] ?? '';
        $this->blockContent    = $b['content'] ?? '';
        $this->blockBgColor    = $b['bg_color'] ?? '#ffffff';
        $this->blockTextColor  = $b['text_color'] ?? '#1f2937';
        $this->blockVisible    = $b['visible'] ?? true;
        $this->blockButtonText = $b['button_text'] ?? '';
        $this->blockButtonUrl  = $b['button_url'] ?? '';
        $this->blockImageUrl   = $b['image_url'] ?? '';
        $this->showBlockModal  = true;
    }

    public function saveBlock(): void
    {
        $this->validate([
            'blockType'  => 'required|in:hero,text,text_image,cta,highlight,divider,html',
            'blockTitle' => 'nullable|string|max:300',
            'blockImageUpload' => 'nullable|image|max:5120',
        ]);

        $imageUrl = $this->blockImageUrl;
        if ($this->blockImageUpload) {
            $path = $this->blockImageUpload->store('page-builder', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        $block = [
            'type'        => $this->blockType,
            'title'       => $this->blockTitle ?: null,
            'content'     => $this->blockContent ?: null,
            'bg_color'    => $this->blockBgColor,
            'text_color'  => $this->blockTextColor,
            'visible'     => $this->blockVisible,
            'button_text' => $this->blockButtonText ?: null,
            'button_url'  => $this->blockButtonUrl ?: null,
            'image_url'   => $imageUrl ?: null,
        ];

        if ($this->editIndex !== null) {
            $this->blocks[$this->editIndex] = $block;
        } else {
            $this->blocks[] = $block;
        }

        $this->persistBlocks();
        $this->showBlockModal = false;
        $this->resetBlockForm();
    }

    public function deleteBlock(int $index): void
    {
        array_splice($this->blocks, $index, 1);
        $this->persistBlocks();
    }

    public function toggleBlockVisible(int $index): void
    {
        $this->blocks[$index]['visible'] = !($this->blocks[$index]['visible'] ?? true);
        $this->persistBlocks();
    }

    public function moveUp(int $index): void
    {
        if ($index === 0) return;
        [$this->blocks[$index - 1], $this->blocks[$index]] = [$this->blocks[$index], $this->blocks[$index - 1]];
        $this->persistBlocks();
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->blocks) - 1) return;
        [$this->blocks[$index], $this->blocks[$index + 1]] = [$this->blocks[$index + 1], $this->blocks[$index]];
        $this->persistBlocks();
    }

    protected function persistBlocks(): void
    {
        if (!$this->conferenceId) return;
        Conference::where('id', $this->conferenceId)->update([
            'page_builder_blocks' => array_values($this->blocks),
        ]);
        session()->flash('success', 'Halaman berhasil disimpan!');
    }

    protected function resetBlockForm(): void
    {
        $this->editIndex       = null;
        $this->blockType       = 'text';
        $this->blockTitle      = '';
        $this->blockContent    = '';
        $this->blockBgColor    = '#ffffff';
        $this->blockTextColor  = '#1f2937';
        $this->blockVisible    = true;
        $this->blockButtonText = '';
        $this->blockButtonUrl  = '';
        $this->blockImageUrl   = '';
        $this->blockImageUpload = null;
        $this->resetValidation();
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        return view('livewire.admin.conference-page-builder', compact('conferences'))
            ->layout('layouts.app', ['title' => 'Conference Page Builder']);
    }
}
