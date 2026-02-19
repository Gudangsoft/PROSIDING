<?php

namespace App\Livewire\Admin;

use App\Models\Slider;
use Livewire\Component;
use Livewire\WithPagination;

class SliderList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleActive(int $id)
    {
        $slider = Slider::findOrFail($id);
        $slider->update(['is_active' => !$slider->is_active]);
    }

    public function updateOrder(int $id, string $direction)
    {
        $slider = Slider::findOrFail($id);
        $newOrder = $direction === 'up' ? $slider->sort_order - 1 : $slider->sort_order + 1;
        if ($newOrder < 0) $newOrder = 0;

        // Swap with adjacent
        $adjacent = Slider::where('sort_order', $direction === 'up' ? $slider->sort_order - 1 : $slider->sort_order + 1)->first();
        if ($adjacent) {
            $adjacent->update(['sort_order' => $slider->sort_order]);
        }
        $slider->update(['sort_order' => $newOrder]);
    }

    public function delete(int $id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->image && \Storage::disk('public')->exists($slider->image)) {
            \Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();
        session()->flash('success', 'Slider berhasil dihapus.');
    }

    public function render()
    {
        $sliders = Slider::when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->orderBy('sort_order')
            ->paginate(10);

        return view('livewire.admin.slider-list', compact('sliders'))->layout('layouts.app');
    }
}
