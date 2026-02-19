<?php

namespace App\Livewire\Admin;

use App\Models\Slider;
use Livewire\Component;
use Livewire\WithFileUploads;

class SliderForm extends Component
{
    use WithFileUploads;

    public ?Slider $slider = null;
    public bool $editMode = false;

    public string $title = '';
    public string $subtitle = '';
    public string $description = '';
    public $image = null;
    public ?string $existingImage = null;
    public string $button_text = '';
    public string $button_url = '';
    public string $button_text_2 = '';
    public string $button_url_2 = '';
    public string $text_position = 'center';
    public string $text_color = 'white';
    public string $overlay_color = 'rgba(0,0,0,0.4)';
    public bool $is_active = true;
    public int $sort_order = 0;
    public ?string $start_date = null;
    public ?string $end_date = null;

    public function mount(?Slider $slider = null)
    {
        if ($slider && $slider->exists) {
            $this->slider = $slider;
            $this->editMode = true;
            $this->title = $this->slider->title ?? '';
            $this->subtitle = $this->slider->subtitle ?? '';
            $this->description = $this->slider->description ?? '';
            $this->existingImage = $this->slider->image;
            $this->button_text = $this->slider->button_text ?? '';
            $this->button_url = $this->slider->button_url ?? '';
            $this->button_text_2 = $this->slider->button_text_2 ?? '';
            $this->button_url_2 = $this->slider->button_url_2 ?? '';
            $this->text_position = $this->slider->text_position;
            $this->text_color = $this->slider->text_color;
            $this->overlay_color = $this->slider->overlay_color;
            $this->is_active = $this->slider->is_active;
            $this->sort_order = $this->slider->sort_order;
            $this->start_date = $this->slider->start_date?->format('Y-m-d');
            $this->end_date = $this->slider->end_date?->format('Y-m-d');
        } else {
            $this->sort_order = Slider::max('sort_order') + 1;
        }
    }

    public function save()
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'button_text_2' => 'nullable|string|max:100',
            'button_url_2' => 'nullable|string|max:500',
            'text_position' => 'required|in:left,center,right',
            'text_color' => 'required|in:white,dark',
            'overlay_color' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        if (!$this->editMode) {
            $rules['image'] = 'required|image|max:5120';
        } else {
            $rules['image'] = 'nullable|image|max:5120';
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'button_text' => $this->button_text,
            'button_url' => $this->button_url,
            'button_text_2' => $this->button_text_2,
            'button_url_2' => $this->button_url_2,
            'text_position' => $this->text_position,
            'text_color' => $this->text_color,
            'overlay_color' => $this->overlay_color,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
        ];

        if ($this->image) {
            // Delete old image
            if ($this->editMode && $this->existingImage && \Storage::disk('public')->exists($this->existingImage)) {
                \Storage::disk('public')->delete($this->existingImage);
            }
            $data['image'] = $this->image->store('sliders', 'public');
        }

        if ($this->editMode) {
            $this->slider->update($data);
            session()->flash('success', 'Slider berhasil diperbarui.');
        } else {
            Slider::create($data);
            session()->flash('success', 'Slider berhasil ditambahkan.');
        }

        return redirect()->route('admin.sliders');
    }

    public function render()
    {
        return view('livewire.admin.slider-form')->layout('layouts.app');
    }
}
