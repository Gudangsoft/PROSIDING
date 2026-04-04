<?php

namespace App\Livewire\Admin;

use App\Models\AbstractSubmission;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\RegistrationPackage;
use Livewire\Component;
use Livewire\WithPagination;

class AbstractManagement extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $statusFilter = '';
    public string $confFilter   = '';

    public ?int $reviewingId    = null;
    public ?AbstractSubmission $reviewingAbstract = null;
    public string $reviewStatus = '';
    public string $reviewNotes  = '';

    protected $queryString = ['search', 'statusFilter', 'confFilter'];

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function openReview(int $id): void
    {
        $abstract = AbstractSubmission::findOrFail($id);
        $this->reviewingId    = $id;
        $this->reviewingAbstract = $abstract;
        $this->reviewStatus   = $abstract->status;
        $this->reviewNotes    = $abstract->reviewer_notes ?? '';
    }

    public function submitReview(): void
    {
        $this->validate([
            'reviewStatus' => 'required|in:approved,rejected,revision_required,pending',
            'reviewNotes'  => 'nullable|string|max:2000',
        ]);

        $abstract = AbstractSubmission::findOrFail($this->reviewingId);
        $oldStatus = $abstract->status;
        
        $abstract->update([
            'status'        => $this->reviewStatus,
            'reviewer_notes'=> $this->reviewNotes,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
        ]);

        // Create payment/invoice when abstract is approved (and doesn't already have one)
        if ($this->reviewStatus === 'approved' && $oldStatus !== 'approved') {
            $this->createPaymentForAbstract($abstract);
        }

        // Notifikasi ke author
        $labelMap = [
            'approved'          => 'Abstrak Anda Disetujui',
            'rejected'          => 'Abstrak Anda Ditolak',
            'revision_required' => 'Abstrak Perlu Revisi',
        ];
        
        $notifMessage = 'Status abstrak "' . $abstract->title . '" diperbarui menjadi: ' . ($labelMap[$this->reviewStatus] ?? $this->reviewStatus);
        
        // Add payment info to notification if approved
        if ($this->reviewStatus === 'approved') {
            $payment = $abstract->fresh()->payment;
            if ($payment) {
                $notifMessage .= '. Silakan selesaikan pembayaran sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . '.';
            }
        }
        
        Notification::create([
            'user_id' => $abstract->user_id,
            'type'    => 'abstract_review',
            'title'   => $labelMap[$this->reviewStatus] ?? 'Update Status Abstrak',
            'message' => $notifMessage,
            'data'    => json_encode(['abstract_id' => $abstract->id]),
        ]);

        $this->reset(['reviewingId', 'reviewingAbstract', 'reviewStatus', 'reviewNotes']);
        session()->flash('success', 'Review abstrak berhasil disimpan.');
    }

    private function createPaymentForAbstract(AbstractSubmission $abstract): void
    {
        // Check if payment already exists
        if ($abstract->payment) {
            return;
        }

        // Get presenter registration package from conference
        // Look for package with "pemakalah" or "presenter" in name, or use first package
        $package = RegistrationPackage::where('conference_id', $abstract->conference_id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('name', 'like', '%pemakalah%')
                  ->orWhere('name', 'like', '%presenter%')
                  ->orWhere('name', 'like', '%author%');
            })
            ->first();
        
        // Fallback to first active package if no presenter package found
        if (!$package) {
            $package = RegistrationPackage::where('conference_id', $abstract->conference_id)
                ->where('is_active', true)
                ->orderBy('price', 'desc') // Get highest price (usually presenter)
                ->first();
        }

        $amount = $package?->price ?? 500000; // Default 500k if no package

        Payment::create([
            'type' => Payment::TYPE_ABSTRACT,
            'abstract_id' => $abstract->id,
            'user_id' => $abstract->user_id,
            'registration_package_id' => $package?->id,
            'invoice_number' => Payment::generateInvoiceNumber(),
            'amount' => $amount,
            'description' => 'Biaya registrasi pemakalah untuk abstrak: ' . $abstract->title,
            'status' => 'pending',
        ]);
    }

    public function cancelReview(): void
    {
        $this->reset(['reviewingId', 'reviewingAbstract', 'reviewStatus', 'reviewNotes']);
    }

    public function render()
    {
        $abstracts = AbstractSubmission::with(['user', 'conference'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            }))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->confFilter,   fn($q) => $q->where('conference_id', $this->confFilter))
            ->latest()
            ->paginate(15);

        $conferences = \App\Models\Conference::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.abstract-management', compact('abstracts', 'conferences'))
            ->layout('layouts.app', ['title' => 'Manajemen Abstrak']);
    }
}
