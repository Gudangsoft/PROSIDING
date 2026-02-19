<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    // Profile fields
    public string $name = '';
    public string $email = '';
    public string $gender = '';
    public string $institution = '';
    public string $country = '';
    public string $phone = '';
    public string $research_interest = '';
    public string $other_info = '';

    // File uploads
    public $photo;
    public $signatureFile;

    // Password change
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    // State
    public ?string $existingPhoto = null;
    public ?string $existingSignature = null;
    public ?string $existingProofOfPayment = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->gender = $user->gender ?? '';
        $this->institution = $user->institution ?? '';
        $this->country = $user->country ?? '';
        $this->phone = $user->phone ?? '';
        $this->research_interest = $user->research_interest ?? '';
        $this->other_info = $user->other_info ?? '';
        $this->existingPhoto = $user->photo;
        $this->existingSignature = $user->signature;
        $this->existingProofOfPayment = $user->proof_of_payment;
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'nullable|in:male,female',
            'institution' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'research_interest' => 'nullable|string|max:255',
            'other_info' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'signatureFile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender ?: null,
            'institution' => $this->institution ?: null,
            'country' => $this->country ?: null,
            'phone' => $this->phone ?: null,
            'research_interest' => $this->research_interest ?: null,
            'other_info' => $this->other_info ?: null,
        ];

        // Handle photo upload
        if ($this->photo) {
            // Delete old photo
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $this->photo->store('user-photos', 'public');
            $this->existingPhoto = $data['photo'];
            $this->photo = null;
        }

        // Handle signature upload
        if ($this->signatureFile) {
            if ($user->signature) {
                Storage::disk('public')->delete($user->signature);
            }
            $data['signature'] = $this->signatureFile->store('signatures', 'public');
            $this->existingSignature = $data['signature'];
            $this->signatureFile = null;
        }

        $user->update($data);

        session()->flash('profile-success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini salah.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        session()->flash('password-success', 'Password berhasil diubah.');
    }

    public function removePhoto(): void
    {
        $user = Auth::user();
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->update(['photo' => null]);
            $this->existingPhoto = null;
        }
    }

    public function removeSignature(): void
    {
        $user = Auth::user();
        if ($user->signature) {
            Storage::disk('public')->delete($user->signature);
            $user->update(['signature' => null]);
            $this->existingSignature = null;
        }
    }

    public function render()
    {
        return view('livewire.profile')->layout('layouts.app');
    }
}
