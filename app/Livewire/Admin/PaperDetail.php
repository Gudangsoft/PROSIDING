<?php

namespace App\Livewire\Admin;

use App\Models\Paper;
use App\Models\User;
use App\Models\Review;
use App\Models\Payment;
use App\Models\Deliverable;
use App\Models\Discussion;
use App\Models\DiscussionMessage;
use App\Models\PaperFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Mail\PaymentVerifiedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PaperDetail extends Component
{
    use WithFileUploads;

    public Paper $paper;
    public string $topTab = 'workflow';
    public string $workflowTab = 'submission';
    public string $editorNotes = '';
    public string $selectedReviewerId = '';
    public string $newStatus = '';

    // Payment
    public string $invoiceAmount = '';
    public string $invoiceDescription = 'Biaya publikasi prosiding';

    // Deliverables from admin
    public $prosidingBookFile;
    public $certificateFile;

    // Upload file
    public $uploadFile;
    public $copyeditFile;

    // Send to review modal
    public bool $showSendToReviewModal = false;
    public array $selectedFileIds = [];

    // Decline modal
    public bool $showDeclineModal = false;
    public string $declineReason = '';

    // Discussion
    public string $discussionSubject = '';
    public string $discussionMessage = '';
    public bool $showDiscussionModal = false;
    public string $discussionStage = 'submission';
    public ?int $activeDiscussionId = null;
    public string $replyMessage = '';

    // Accept modal
    public bool $showAcceptModal = false;
    public string $acceptLoaLink = '';
    public string $acceptInvoiceAmount = '';
    public string $acceptInvoiceDescription = 'Biaya publikasi prosiding';
    public string $acceptPackageId = '';
    public string $detectedPackageName = ''; // non-empty = auto-detected, skip dropdown
    public array $conferencePackages = [];
    public string $acceptSource = ''; // 'review' or 'skip'
    public bool $autoGenerateLoa = false;

    // Assign editor
    public bool $showAssignEditorModal = false;
    public string $selectedEditorId = '';

    // Publish modal
    public bool $showPublishModal = false;
    public string $publishArticleLink = '';

    public function mount(Paper $paper)
    {
        $this->paper = $paper;
        $this->editorNotes = $paper->editor_notes ?? '';
        $this->newStatus = $paper->status;
        $this->publishArticleLink = $paper->article_link ?? '';

        // Load conference packages for the accept-modal dropdown
        if ($paper->conference) {
            $this->conferencePackages = $paper->conference->registrationPackages()
                ->where('is_active', true)
                ->orderBy('sort_order')->orderBy('price')
                ->get()
                ->map(fn($pkg) => [
                    'id'      => $pkg->id,
                    'name'    => $pkg->name,
                    'price'   => $pkg->price,
                    'is_free' => (bool) $pkg->is_free,
                    'label'   => $pkg->name . ' (' . ($pkg->is_free ? 'Gratis' : 'Rp ' . number_format($pkg->price, 0, ',', '.')) . ')',
                ])
                ->toArray();
        }

        // Set initial workflow tab based on paper status
        $this->workflowTab = $this->getWorkflowTabForStatus($paper->status);
    }

    public function updatedAcceptPackageId($value)
    {
        if ($value) {
            $pkg = collect($this->conferencePackages)->firstWhere('id', (int) $value);
            if ($pkg) {
                $this->acceptInvoiceAmount = $pkg['is_free'] ? '0' : (string) intval($pkg['price']);
                $this->acceptInvoiceDescription = 'Biaya publikasi prosiding — ' . $pkg['name'];
            }
        } else {
            $this->acceptInvoiceAmount = '';
            $this->acceptInvoiceDescription = 'Biaya publikasi prosiding';
        }
    }

    protected function getWorkflowTabForStatus(string $status): string
    {
        return match($status) {
            'submitted', 'screening' => 'submission',
            'in_review', 'revision_required', 'revised' => 'review',
            'accepted' => 'copyediting',
            'payment_pending', 'payment_uploaded', 'payment_verified', 'deliverables_pending', 'completed' => 'production',
            'rejected' => 'submission',
            default => 'submission',
        };
    }

    public function setTopTab(string $tab)
    {
        $this->topTab = $tab;
    }

    public function setWorkflowTab(string $tab)
    {
        $this->workflowTab = $tab;
    }

    // ─── Assign Editor ───
    public function openAssignEditorModal()
    {
        $this->showAssignEditorModal = true;
        $this->selectedEditorId = $this->paper->assigned_editor_id ?? '';
    }

    public function closeAssignEditorModal()
    {
        $this->showAssignEditorModal = false;
    }

    public function assignEditor()
    {
        $this->validate(['selectedEditorId' => 'required|exists:users,id']);

        $editor = User::findOrFail($this->selectedEditorId);
        $this->paper->update(['assigned_editor_id' => $editor->id]);

        // Notify author
        if (class_exists(\App\Models\Notification::class)) {
            \App\Models\Notification::createForUser(
                $this->paper->user_id,
                'info',
                'Paper "' . \Illuminate\Support\Str::limit($this->paper->title, 50) . '" telah ditugaskan ke editor: ' . $editor->name,
                route('author.paper.detail', $this->paper),
                'Editor Ditugaskan'
            );
        }

        $this->paper->refresh();
        $this->showAssignEditorModal = false;
        session()->flash('success', "Editor {$editor->name} berhasil ditugaskan.");
    }

    public function unassignEditor()
    {
        $this->paper->update(['assigned_editor_id' => null]);
        $this->paper->refresh();
        session()->flash('success', 'Editor berhasil dihapus dari paper ini.');
    }

    // ─── Submission Stage Actions ───
    public function openSendToReview()
    {
        $this->selectedFileIds = $this->paper->files->pluck('id')->toArray();
        $this->showSendToReviewModal = true;
    }

    public function closeSendToReview()
    {
        $this->showSendToReviewModal = false;
    }

    public function sendToReview()
    {
        if ($this->paper->status === 'submitted' || $this->paper->status === 'screening') {
            $this->paper->update(['status' => 'in_review']);
        }
        $this->paper->refresh();
        $this->showSendToReviewModal = false;
        $this->workflowTab = 'review';
        session()->flash('success', 'Paper berhasil dikirim ke tahap Review.');
    }

    public function acceptAndSkipReview()
    {
        $this->acceptSource = 'skip';
        $this->showAcceptModal = true;
    }

    public function openDeclineModal()
    {
        $this->showDeclineModal = true;
    }

    public function closeDeclineModal()
    {
        $this->showDeclineModal = false;
        $this->declineReason = '';
    }

    public function declineSubmission()
    {
        $this->paper->update([
            'status' => 'rejected',
            'editor_notes' => $this->declineReason ?: $this->editorNotes,
        ]);
        $this->paper->refresh();
        $this->showDeclineModal = false;
        session()->flash('success', 'Submission telah ditolak.');
    }

    // ─── Upload File ───
    public function uploadSubmissionFile()
    {
        $this->validate(['uploadFile' => 'required|file|mimes:pdf,doc,docx|max:20480']);

        $path = $this->uploadFile->store('papers/' . $this->paper->id, 'public');
        PaperFile::create([
            'paper_id' => $this->paper->id,
            'type' => 'full_paper',
            'file_path' => $path,
            'original_name' => $this->uploadFile->getClientOriginalName(),
            'mime_type' => $this->uploadFile->getMimeType(),
            'file_size' => $this->uploadFile->getSize(),
        ]);

        $this->uploadFile = null;
        $this->paper->refresh();
        session()->flash('success', 'File berhasil diupload.');
    }

    public function uploadCopyeditFile()
    {
        $this->validate(['copyeditFile' => 'required|file|mimes:pdf,doc,docx|max:20480']);

        $path = $this->copyeditFile->store('papers/' . $this->paper->id . '/copyedit', 'public');
        PaperFile::create([
            'paper_id' => $this->paper->id,
            'type' => 'copyedit_draft',
            'file_path' => $path,
            'original_name' => $this->copyeditFile->getClientOriginalName(),
            'mime_type' => $this->copyeditFile->getMimeType(),
            'file_size' => $this->copyeditFile->getSize(),
        ]);

        $this->copyeditFile = null;
        $this->paper->refresh();
        session()->flash('success', 'File copyediting berhasil diupload.');
    }

    // ─── Review Stage Actions ───
    public function assignReviewer()
    {
        $this->validate(['selectedReviewerId' => 'required|exists:users,id']);

        $exists = Review::where('paper_id', $this->paper->id)
            ->where('reviewer_id', $this->selectedReviewerId)->exists();

        if ($exists) {
            session()->flash('error', 'Reviewer sudah ditugaskan untuk paper ini.');
            return;
        }

        Review::create([
            'paper_id' => $this->paper->id,
            'reviewer_id' => $this->selectedReviewerId,
            'assigned_by' => Auth::id(),
            'status' => 'pending',
        ]);

        if ($this->paper->status === 'submitted' || $this->paper->status === 'screening') {
            $this->paper->update(['status' => 'in_review']);
        }

        // Send notification to reviewer
        \App\Models\Notification::createForUser(
            $this->selectedReviewerId,
            'info',
            'Tugas Review Baru',
            'Anda ditugaskan untuk mereview paper "' . \Illuminate\Support\Str::limit($this->paper->title, 50) . '"',
            route('reviewer.reviews'),
            'Mulai Review'
        );

        $this->selectedReviewerId = '';
        $this->paper->refresh();
        session()->flash('success', 'Reviewer berhasil ditugaskan.');
    }

    public function acceptSubmission()
    {
        $this->acceptSource = 'review';
        $this->showAcceptModal = true;
    }

    public function openAcceptModal(string $source = 'review')
    {
        $this->acceptSource = $source;
        $this->acceptLoaLink = '';
        $this->acceptInvoiceAmount = '';
        $this->acceptInvoiceDescription = 'Biaya publikasi prosiding';
        $this->acceptPackageId = '';
        $this->detectedPackageName = '';

        // Auto pre-select package from author's participant payment for this conference
        $authorPayment = Payment::where('user_id', $this->paper->user_id)
            ->where('type', Payment::TYPE_PARTICIPANT)
            ->whereNotNull('registration_package_id')
            ->whereHas('registrationPackage', fn($q) => $q->where('conference_id', $this->paper->conference_id))
            ->with('registrationPackage')
            ->first();

        if ($authorPayment && $authorPayment->registrationPackage) {
            $pkg = $authorPayment->registrationPackage;
            $this->acceptPackageId      = (string) $pkg->id;
            $this->acceptInvoiceAmount  = $pkg->is_free ? '0' : (string) intval($pkg->price);
            $this->acceptInvoiceDescription = 'Biaya publikasi prosiding — ' . $pkg->name;
            $this->detectedPackageName  = $pkg->name . ' — ' . ($pkg->is_free ? 'Gratis' : 'Rp ' . number_format($pkg->price, 0, ',', '.'));
        }

        // Initialize based on conference mode
        $conference = $this->paper->conference;
        $this->autoGenerateLoa = ($conference && $conference->loa_generation_mode === 'auto');
        
        $this->showAcceptModal = true;
    }

    public function closeAcceptModal()
    {
        $this->showAcceptModal = false;
        $this->acceptLoaLink = '';
        $this->acceptInvoiceAmount = '';
        $this->acceptPackageId = '';
        $this->detectedPackageName = '';
        $this->acceptSource = '';
        $this->autoGenerateLoa = false;
    }

    public function confirmAccept()
    {
        try {
            // Validate based on mode
            $validationRules = [
                'acceptInvoiceAmount' => 'required|numeric|min:0',
            ];
            
            $validationMessages = [
                'acceptInvoiceAmount.required' => 'Jumlah tagihan wajib diisi.',
                'acceptInvoiceAmount.numeric' => 'Jumlah tagihan harus berupa angka.',
            ];
            
            // Only require LOA link if not auto-generating
            if (!$this->autoGenerateLoa) {
                $validationRules['acceptLoaLink'] = 'required|url';
                $validationMessages['acceptLoaLink.required'] = 'Link LOA wajib diisi.';
                $validationMessages['acceptLoaLink.url'] = 'Link LOA harus berupa URL yang valid.';
            }
            
            $this->validate($validationRules, $validationMessages);

            $loaLink = '';
            
            // Generate LOA automatically atau use manual link
            if ($this->autoGenerateLoa) {
                $generator = new \App\Services\DocumentGenerator();
                $loaPath = $generator->generateLOA($this->paper);
                $loaLink = asset('storage/' . $loaPath);
            } else {
                $loaLink = $this->acceptLoaLink;
            }

            // Update paper status
            $this->paper->update([
                'status' => 'payment_pending',
                'accepted_at' => now(),
                'loa_link' => $loaLink,
            ]);

            // Create invoice automatically
            if (!$this->paper->payment) {
                Payment::create([
                    'paper_id' => $this->paper->id,
                    'user_id' => $this->paper->user_id,
                    'invoice_number' => Payment::generateInvoiceNumber(),
                    'amount' => $this->acceptInvoiceAmount,
                    'description' => $this->acceptInvoiceDescription,
                    'status' => 'pending',
                ]);
            }

            // Send notification to author with LOA
            \App\Models\Notification::createForUser(
                $this->paper->user_id,
                'success',
                'Paper Diterima! 🎉 LOA Tersedia',
                'Selamat! Paper Anda "' . \Illuminate\Support\Str::limit($this->paper->title, 50) . '" telah diterima. LOA dan tagihan pembayaran telah tersedia.',
                route('author.paper.detail', $this->paper),
                'Lihat LOA & Bayar'
            );

            $this->showAcceptModal = false;
            $this->paper->refresh();
            $this->newStatus = $this->paper->status;
            $this->workflowTab = 'production';

            $message = $this->autoGenerateLoa 
                ? 'Paper diterima! LOA auto-generated dan tagihan berhasil dikirim ke author.'
                : 'Paper diterima! LOA dan tagihan berhasil dikirim ke author.';
            session()->flash('success', $message);
            $this->dispatch('paperStatusUpdated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error in confirmAccept: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function requestRevision()
    {
        try {
            $this->paper->update([
                'status' => 'revision_required',
                'editor_notes' => $this->editorNotes,
            ]);
            
            // Send notification to author
            \App\Models\Notification::createForUser(
                $this->paper->user_id,
                'warning',
                'Revisi Diperlukan',
                'Paper "' . \Illuminate\Support\Str::limit($this->paper->title, 50) . '" memerlukan revisi. Silakan upload file revisi terbaru.',
                route('author.paper.detail', $this->paper),
                'Upload Revisi'
            );
            
            $this->paper->refresh();
            
            session()->flash('success', 'Revisi berhasil diminta. Notifikasi telah dikirim ke author.');
            
            $this->dispatch('paperStatusUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectSubmission()
    {
        try {
            $this->paper->update([
                'status' => 'rejected',
                'editor_notes' => $this->editorNotes,
            ]);
            
            // Send notification to author
            \App\Models\Notification::createForUser(
                $this->paper->user_id,
                'danger',
                'Paper Ditolak',
                'Paper Anda "' . \Illuminate\Support\Str::limit($this->paper->title, 50) . '" tidak dapat diterima untuk publikasi.',
                route('author.paper.detail', $this->paper),
                'Lihat Detail'
            );
            
            $this->paper->refresh();
            
            session()->flash('success', 'Paper berhasil ditolak. Notifikasi telah dikirim ke author.');
            
            $this->dispatch('paperStatusUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus()
    {
        $this->paper->update([
            'status' => $this->newStatus,
            'editor_notes' => $this->editorNotes,
        ]);

        if ($this->newStatus === 'accepted') {
            $this->paper->update(['accepted_at' => now()]);
        }

        $this->paper->refresh();
        session()->flash('success', 'Status paper berhasil diperbarui!');
    }

    // ─── Production Stage Actions ───
    public function createInvoice()
    {
        $this->validate([
            'invoiceAmount' => 'required|numeric|min:0',
        ]);

        if ($this->paper->payment) {
            session()->flash('error', 'Invoice sudah dibuat sebelumnya.');
            return;
        }

        $payment = Payment::create([
            'paper_id' => $this->paper->id,
            'user_id' => $this->paper->user_id,
            'invoice_number' => Payment::generateInvoiceNumber(),
            'amount' => $this->invoiceAmount,
            'description' => $this->invoiceDescription,
            'status' => 'pending',
        ]);

        $this->paper->update(['status' => 'payment_pending']);
        $this->paper->refresh();

        // Send notification to author about new invoice
        \App\Models\Notification::createForUser(
            userId: $this->paper->user_id,
            type: 'info',
            title: 'Tagihan Pembayaran Dibuat 💳',
            message: 'Tagihan pembayaran untuk paper "' . $this->paper->title . '" telah dibuat. Invoice: ' . $payment->invoice_number . ' sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . '. Silakan lakukan pembayaran sesuai nominal.',
            actionUrl: route('author.paper.payment', $this->paper),
            actionText: 'Lihat & Bayar'
        );

        // Send invoice email
        try {
            \Illuminate\Support\Facades\Mail::to($this->paper->user->email)->send(
                new \App\Mail\InvoiceCreatedMail(
                    $this->paper->user->name,
                    $this->paper->title,
                    $payment->invoice_number,
                    $payment->amount,
                    route('author.paper.payment', $this->paper)
                )
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send invoice email: ' . $e->getMessage());
        }

        session()->flash('success', 'Invoice berhasil dibuat!');
    }

    public function verifyPayment(string $action)
    {
        $payment = $this->paper->payment;
        if (!$payment) return;

        if ($action === 'verify') {
            $payment->update([
                'status' => 'verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
            $this->paper->update(['status' => 'payment_verified']);

            \App\Models\Notification::createForUser(
                $this->paper->user_id,
                'success',
                'Pembayaran Lunas ✓',
                'Pembayaran Anda (' . $payment->invoice_number . ') sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah diverifikasi (Lunas).',
                route('author.paper.detail', $this->paper),
                'Lihat Detail'
            );

            // Kirim email notifikasi ke author
            try {
                Mail::to($this->paper->user->email)->send(new PaymentVerifiedMail(
                    $this->paper->user->name,
                    $payment->invoice_number,
                    $payment->amount,
                    'paper',
                    $this->paper->title,
                    route('author.paper.detail', $this->paper)
                ));
            } catch (\Exception $e) {
                \Log::error('PaymentVerifiedMail gagal: ' . $e->getMessage());
            }

            session()->flash('success', 'Pembayaran → Lunas! Email notifikasi terkirim.');
        } else {
            $payment->update([
                'status' => 'rejected',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'admin_notes' => $this->editorNotes,
            ]);
            $this->paper->update(['status' => 'payment_pending']);

            \App\Models\Notification::createForUser(
                $this->paper->user_id,
                'danger',
                'Pembayaran Ditolak',
                'Pembayaran Anda (' . $payment->invoice_number . ') ditolak.' . ($this->editorNotes ? ' Alasan: ' . $this->editorNotes : '') . ' Silakan upload ulang.',
                route('author.paper.payment', $this->paper),
                'Upload Ulang'
            );

            session()->flash('success', 'Pembayaran → Ditolak.');
        }

        $this->paper->refresh();
    }

    public function sendDeliverable(string $type)
    {
        $fileProperty = match($type) {
            'prosiding_book' => 'prosidingBookFile',
            'certificate' => 'certificateFile',
        };

        $this->validate([$fileProperty => 'required|file|mimes:pdf|max:20480']);

        $file = $this->$fileProperty;
        $path = $file->store('deliverables/' . $this->paper->id . '/admin/' . $type, 'public');

        Deliverable::updateOrCreate(
            ['paper_id' => $this->paper->id, 'type' => $type, 'direction' => 'admin_send'],
            [
                'user_id' => Auth::id(),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'sent_at' => now(),
            ]
        );

        $this->$fileProperty = null;
        $this->paper->refresh();
        session()->flash('success', Deliverable::TYPE_LABELS[$type] . ' berhasil dikirim!');
    }

    // ─── Publish Modal ───
    public function openPublishModal()
    {
        $this->publishArticleLink = $this->paper->article_link ?? '';
        $this->showPublishModal = true;
    }

    public function closePublishModal()
    {
        $this->showPublishModal = false;
        $this->publishArticleLink = '';
    }

    // ─── Mark as Completed ───
    public function markAsCompleted()
    {
        try {
            $this->validate([
                'publishArticleLink' => 'nullable|url|max:500',
            ], [
                'publishArticleLink.url' => 'Link artikel harus berupa URL yang valid.',
            ]);

            $this->paper->update([
                'status' => 'completed',
                'article_link' => $this->publishArticleLink ?: null,
            ]);

            \App\Models\Notification::createForUser(
                $this->paper->user_id,
                'success',
                'Paper Selesai Dipublikasi! 🎉',
                'Paper Anda "' . \Illuminate\Support\Str::limit($this->paper->title, 50) . '" telah selesai dan dipublikasikan.',
                route('author.paper.detail', $this->paper),
                'Lihat Detail'
            );

            $this->paper->refresh();
            $this->newStatus = 'completed';
            $this->showPublishModal = false;
            $this->publishArticleLink = '';
            session()->flash('success', 'Paper berhasil dipublikasikan!');
            $this->dispatch('paperStatusUpdated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error in markAsCompleted: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateArticleLink()
    {
        $this->validate([
            'publishArticleLink' => 'nullable|url|max:500',
        ], [
            'publishArticleLink.url' => 'Link artikel harus berupa URL yang valid.',
        ]);

        $this->paper->update([
            'article_link' => $this->publishArticleLink ?: null,
        ]);

        $this->paper->refresh();
        session()->flash('success', 'Link artikel berhasil diperbarui!');
    }

    // ─── Discussion Actions ───
    public function openDiscussionModal(string $stage)
    {
        $this->discussionStage = $stage;
        $this->discussionSubject = '';
        $this->discussionMessage = '';
        $this->showDiscussionModal = true;
    }

    public function closeDiscussionModal()
    {
        $this->showDiscussionModal = false;
        $this->discussionSubject = '';
        $this->discussionMessage = '';
    }

    public function createDiscussion()
    {
        $this->validate([
            'discussionSubject' => 'required|min:3|max:255',
            'discussionMessage' => 'required|min:3',
        ]);

        $discussion = Discussion::create([
            'paper_id' => $this->paper->id,
            'user_id' => Auth::id(),
            'subject' => $this->discussionSubject,
            'stage' => $this->discussionStage,
        ]);

        DiscussionMessage::create([
            'discussion_id' => $discussion->id,
            'user_id' => Auth::id(),
            'message' => $this->discussionMessage,
        ]);

        $this->showDiscussionModal = false;
        $this->discussionSubject = '';
        $this->discussionMessage = '';
        $this->paper->refresh();
        session()->flash('success', 'Diskusi berhasil dibuat.');
    }

    public function openDiscussion(int $id)
    {
        $this->activeDiscussionId = ($this->activeDiscussionId === $id) ? null : $id;
        $this->replyMessage = '';
    }

    public function sendReply()
    {
        $this->validate([
            'replyMessage' => 'required|min:1',
            'activeDiscussionId' => 'required|exists:discussions,id',
        ]);

        DiscussionMessage::create([
            'discussion_id' => $this->activeDiscussionId,
            'user_id' => Auth::id(),
            'message' => $this->replyMessage,
        ]);

        $this->replyMessage = '';
        $this->paper->refresh();
        session()->flash('success', 'Balasan berhasil dikirim.');
    }

    public function toggleCloseDiscussion(int $id)
    {
        $discussion = Discussion::findOrFail($id);
        $discussion->update(['is_closed' => !$discussion->is_closed]);
        $this->paper->refresh();
        session()->flash('success', $discussion->is_closed ? 'Diskusi ditutup.' : 'Diskusi dibuka kembali.');
    }

    // ─── Helpers ───
    public function getWorkflowStageProperty(): string
    {
        return match($this->paper->status) {
            'submitted', 'screening' => 'submission',
            'in_review', 'revision_required', 'revised' => 'review',
            'accepted' => 'copyediting',
            'payment_pending', 'payment_uploaded', 'payment_verified', 'deliverables_pending', 'completed' => 'production',
            'rejected' => 'declined',
            default => 'submission',
        };
    }

    public function render()
    {
        $this->paper->load(['user', 'assignedEditor', 'files', 'reviews.reviewer', 'payment', 'deliverables', 'discussions.user', 'discussions.messages.user', 'discussions.latestMessage']);
        $reviewers = User::where('role', 'reviewer')->get();
        $editors = User::whereIn('role', ['admin', 'editor'])->get();
        $authorDeliverables = $this->paper->deliverables->where('direction', 'author_upload');
        $adminDeliverables = $this->paper->deliverables->where('direction', 'admin_send');

        return view('livewire.admin.paper-detail', compact('reviewers', 'editors', 'authorDeliverables', 'adminDeliverables'))->layout('layouts.app');
    }
}
