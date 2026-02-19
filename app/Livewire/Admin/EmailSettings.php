<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmailSettings extends Component
{
    use WithFileUploads;

    public array $settings = [];
    public array $settingModels = [];
    public string $testEmail = '';

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Only load notification settings (boolean type settings)
        $models = Setting::byGroup('email')->where('type', 'boolean')->get();
        $this->settingModels = $models->toArray();
        
        \Log::info('Loading email notification settings from database');
        
        foreach ($models as $setting) {
            $this->settings[$setting->key] = $setting->value === '1' || $setting->value === 1 || $setting->value === true;
            \Log::info("Loaded notification setting: {$setting->key} = " . ($this->settings[$setting->key] ? 'true' : 'false'));
        }
    }

    public function save()
    {
        try {
            // Get only notification settings (boolean type)
            $notificationSettings = Setting::where('group', 'email')->where('type', 'boolean')->get();
            
            $savedCount = 0;
            \Log::info('Starting to save email notification settings', ['settings_keys' => array_keys($this->settings)]);
            
            foreach ($notificationSettings as $settingModel) {
                $key = $settingModel->key;
                
                // For boolean: check if key exists in settings and is truthy
                $value = isset($this->settings[$key]) && $this->settings[$key] ? '1' : '0';
                
                $oldValue = $settingModel->value;
                $settingModel->value = $value;
                $settingModel->save();
                $savedCount++;
                
                \Log::info("Saved notification setting: {$key} = {$value} (was: {$oldValue})");
            }

            session()->flash('success', "Pengaturan notifikasi email berhasil disimpan ({$savedCount} item).");
            
            // Reload settings to show updated values
            $this->loadSettings();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
            \Log::error('Failed to save email notification settings: ' . $e->getMessage());
        }
    }

    public function sendTestEmail()
    {
        $this->validate(['testEmail' => 'required|email']);

        try {
            // Use config from .env file directly (no need to update)
            \Illuminate\Support\Facades\Mail::raw('Ini adalah email percobaan dari Prosiding LPKD-APJI. Jika Anda menerima email ini, konfigurasi SMTP sudah benar.', function ($message) {
                $message->to($this->testEmail)
                        ->subject('Test Email - Prosiding LPKD-APJI');
            });

            session()->flash('test_success', 'Email percobaan berhasil dikirim ke ' . $this->testEmail);
        } catch (\Exception $e) {
            session()->flash('test_error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.email-settings')->layout('layouts.app');
    }
}
