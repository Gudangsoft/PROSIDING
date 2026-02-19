<?php

namespace App\Livewire\Author;

use App\Models\Paper;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class PaymentUpload extends Component
{
    use WithFileUploads;

    public Paper $paper;
    public Payment $payment;
    public $proofFile;
    public string $paymentMethod = '';

    public function mount(Paper $paper)
    {
        if ($paper->user_id !== Auth::id()) abort(403);
        $this->paper = $paper;
        $this->payment = $paper->payment ?? new Payment();
    }

    public function uploadProof()
    {
        $this->validate([
            'proofFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'paymentMethod' => 'required|string',
        ]);

        $path = $this->proofFile->store('payments/' . $this->paper->id, 'public');

        $this->payment->update([
            'payment_proof' => $path,
            'payment_method' => $this->paymentMethod,
            'status' => 'uploaded',
            'paid_at' => now(),
        ]);

        $this->paper->update(['status' => 'payment_uploaded']);
        $this->paper->refresh();
        $this->payment->refresh();
        $this->proofFile = null;

        session()->flash('success', 'Bukti pembayaran berhasil diunggah!');
    }

    public function render()
    {
        return view('livewire.author.payment-upload')->layout('layouts.app');
    }
}
