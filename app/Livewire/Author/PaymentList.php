<?php

namespace App\Livewire\Author;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class PaymentList extends Component
{
    use WithFileUploads;

    public $proofFile;
    public string $paymentMethod = '';
    public ?int $uploadingPaymentId = null;
    public bool $showUploadModal = false;

    public function openUploadModal(int $paymentId)
    {
        $payment = Payment::where('user_id', Auth::id())->findOrFail($paymentId);
        $this->uploadingPaymentId = $paymentId;
        $this->paymentMethod = $payment->payment_method ?? '';
        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->uploadingPaymentId = null;
        $this->proofFile = null;
        $this->paymentMethod = '';
    }

    public function uploadProof()
    {
        $this->validate([
            'proofFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'paymentMethod' => 'required|string',
        ], [
            'proofFile.required' => 'Bukti pembayaran wajib diunggah.',
            'proofFile.mimes' => 'File harus berformat JPG, PNG, atau PDF.',
            'proofFile.max' => 'Ukuran file maksimal 5MB.',
            'paymentMethod.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $payment = Payment::where('user_id', Auth::id())->findOrFail($this->uploadingPaymentId);

        $path = $this->proofFile->store('payments/abstract-' . $payment->abstract_id, 'public');

        $payment->update([
            'payment_proof' => $path,
            'payment_method' => $this->paymentMethod,
            'status' => 'uploaded',
            'paid_at' => now(),
        ]);

        $this->showUploadModal = false;
        $this->uploadingPaymentId = null;
        $this->proofFile = null;
        $this->paymentMethod = '';

        session()->flash('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
    }

    public function render()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->whereIn('type', ['abstract', 'participant'])
            ->with(['abstract.conference', 'registrationPackage.conference'])
            ->latest()
            ->get();

        return view('livewire.author.payment-list', compact('payments'))->layout('layouts.app');
    }
}
