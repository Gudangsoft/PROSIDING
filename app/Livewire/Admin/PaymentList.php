<?php

namespace App\Livewire\Admin;

use App\Mail\PaymentReminderMail;
use App\Mail\PaymentVerifiedMail;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    public string $typeFilter = '';
    public string $statusFilter = '';
    public string $adminNotes = '';
    public ?int $activePaymentId = null;
    public bool $showRejectModal = false;

    public function updatingTypeFilter() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function sendReminder(int $paymentId)
    {
        $payment = Payment::with('user')->findOrFail($paymentId);

        if (!$payment->user || !$payment->user->email) {
            session()->flash('error', 'User tidak memiliki email.');
            return;
        }

        $paymentUrl = $payment->type === 'participant'
            ? route('participant.payment')
            : route('author.paper.payment', $payment->paper);

        try {
            Mail::to($payment->user->email)->send(new PaymentReminderMail(
                $payment->user->name,
                $payment->invoice_number,
                $payment->amount,
                $paymentUrl
            ));

            \App\Models\Notification::createForUser(
                $payment->user_id,
                'warning',
                'Pengingat Pembayaran',
                'Segera upload bukti pembayaran (' . $payment->invoice_number . ') agar akun Anda dapat diaktifkan.',
                $paymentUrl,
                'Upload Sekarang'
            );

            session()->flash('success', 'Reminder terkirim ke ' . $payment->user->email);
        } catch (\Exception $e) {
            \Log::error('PaymentReminderMail gagal: ' . $e->getMessage());
            session()->flash('error', 'Gagal mengirim reminder: ' . $e->getMessage());
        }
    }

    public function quickVerify(int $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        if ($payment->type === 'paper' && $payment->paper) {
            $payment->paper->update(['status' => 'payment_verified']);
        }

        // Notify user
        $message = $payment->type === 'participant'
            ? 'Pembayaran registrasi Anda telah diverifikasi (Lunas).'
            : 'Pembayaran Anda (' . $payment->invoice_number . ') sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah diverifikasi (Lunas).';

        $actionUrl = $payment->type === 'participant'
            ? route('dashboard')
            : route('author.paper.detail', $payment->paper);

        \App\Models\Notification::createForUser(
            $payment->user_id,
            'success',
            'Pembayaran Terverifikasi ✓',
            $message,
            $actionUrl,
            'Lihat Detail'
        );

        // Kirim email notifikasi ke user
        try {
            Mail::to($payment->user->email)->send(new PaymentVerifiedMail(
                $payment->user->name,
                $payment->invoice_number,
                $payment->amount,
                $payment->type,
                $payment->paper?->title,
                $actionUrl
            ));
        } catch (\Exception $e) {
            \Log::error('PaymentVerifiedMail gagal: ' . $e->getMessage());
        }

        session()->flash('success', 'Pembayaran ' . $payment->invoice_number . ' → Lunas! Email notifikasi terkirim.');
    }

    public function openRejectModal(int $paymentId)
    {
        $this->activePaymentId = $paymentId;
        $this->adminNotes = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->activePaymentId = null;
        $this->adminNotes = '';
    }

    public function rejectPayment()
    {
        $payment = Payment::findOrFail($this->activePaymentId);
        $payment->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'admin_notes' => $this->adminNotes,
        ]);

        if ($payment->type === 'paper' && $payment->paper) {
            $payment->paper->update(['status' => 'payment_pending']);
        }

        // Notify user
        $message = 'Pembayaran Anda (' . $payment->invoice_number . ') ditolak.' . ($this->adminNotes ? ' Alasan: ' . $this->adminNotes : '');
        $actionUrl = $payment->type === 'participant'
            ? route('dashboard')
            : route('author.paper.payment', $payment->paper);
        $actionLabel = $payment->type === 'participant' ? 'Lihat Dashboard' : 'Upload Ulang';

        if ($payment->type === 'paper') {
            $message .= ' Silakan upload ulang bukti bayar.';
        }

        \App\Models\Notification::createForUser(
            $payment->user_id,
            'danger',
            'Pembayaran Ditolak',
            $message,
            $actionUrl,
            $actionLabel
        );

        $this->showRejectModal = false;
        $this->activePaymentId = null;
        $this->adminNotes = '';
        session()->flash('success', 'Pembayaran ' . $payment->invoice_number . ' → Ditolak.');
    }

    public function resetToPending(int $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->update([
            'status' => 'pending',
            'payment_proof' => null,
            'payment_method' => null,
            'paid_at' => null,
            'verified_by' => null,
            'verified_at' => null,
            'admin_notes' => null,
        ]);

        if ($payment->type === 'paper' && $payment->paper) {
            $payment->paper->update(['status' => 'payment_pending']);

            \App\Models\Notification::createForUser(
                $payment->user_id,
                'warning',
                'Status Pembayaran Direset',
                'Status pembayaran ' . $payment->invoice_number . ' dikembalikan ke Pending. Silakan upload bukti bayar.',
                route('author.paper.payment', $payment->paper),
                'Upload Bukti Bayar'
            );
        } else {
            \App\Models\Notification::createForUser(
                $payment->user_id,
                'warning',
                'Status Pembayaran Direset',
                'Status pembayaran registrasi Anda (' . $payment->invoice_number . ') dikembalikan ke Pending.',
                route('dashboard'),
                'Lihat Dashboard'
            );
        }

        session()->flash('success', 'Pembayaran ' . $payment->invoice_number . ' → Pending.');
    }

    public function render()
    {
        $payments = Payment::with(['paper.user', 'user'])
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        // Counts
        $allCount = Payment::count();
        $paperCount = Payment::where('type', 'paper')->count();
        $participantCount = Payment::where('type', 'participant')->count();

        return view('livewire.admin.payment-list', compact('payments', 'allCount', 'paperCount', 'participantCount'))->layout('layouts.app');
    }
}
