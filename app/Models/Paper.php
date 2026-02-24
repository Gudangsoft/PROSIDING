<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paper extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'conference_id', 'assigned_editor_id', 'title', 'abstract', 'keywords', 'topic',
        'authors_meta', 'status', 'editor_notes', 'loa_link', 'loa_number', 'article_link', 'submitted_at', 'accepted_at',
        'similarity_score', 'plagiarism_tool', 'plagiarism_note', 'plagiarism_checked_at',
        'doi', 'ojs_submission_id', 'ojs_status', 'ojs_submitted_at',
        'meeting_link', 'meeting_platform', 'meeting_scheduled_at',
        'extra_field_values', 'similarity_cross_score',
        'camera_ready_path', 'camera_ready_submitted_at', 'camera_ready_status', 'camera_ready_notes',
        'acceptance_letter_path', 'acceptance_letter_sent_at',
    ];

    protected $casts = [
        'authors_meta'        => 'array',
        'extra_field_values'  => 'array',
        'submitted_at'          => 'datetime',
        'accepted_at'           => 'datetime',
        'plagiarism_checked_at' => 'datetime',
        'ojs_submitted_at'      => 'datetime',
        'meeting_scheduled_at'         => 'datetime',
        'camera_ready_submitted_at'   => 'datetime',
        'acceptance_letter_sent_at'   => 'datetime',
    ];

    const STATUS_LABELS = [
        'submitted' => 'Submitted',
        'screening' => 'Screening',
        'in_review' => 'In Review',
        'revision_required' => 'Revision Required',
        'revised' => 'Revised',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
        'payment_pending' => 'Menunggu Pembayaran',
        'payment_uploaded' => 'Pembayaran Diupload',
        'payment_verified' => 'Pembayaran Terverifikasi',
        'deliverables_pending' => 'Menunggu Luaran',
        'completed' => 'Completed',
    ];

    const STATUS_COLORS = [
        'submitted' => 'blue',
        'screening' => 'yellow',
        'in_review' => 'indigo',
        'revision_required' => 'orange',
        'revised' => 'cyan',
        'accepted' => 'green',
        'rejected' => 'red',
        'payment_pending' => 'amber',
        'payment_uploaded' => 'purple',
        'payment_verified' => 'emerald',
        'deliverables_pending' => 'teal',
        'completed' => 'green',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function assignedEditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_editor_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(PaperFile::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function deliverables(): HasMany
    {
        return $this->hasMany(Deliverable::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function latestFullPaper()
    {
        return $this->files()->whereIn('type', ['full_paper', 'revision'])->latest()->first();
    }

    public function statusLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PaperStatusLog::class)->orderBy('occurred_at');
    }

    public function similarities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PaperSimilarity::class);
    }

    public function revisionRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RevisionRequest::class)->latest();
    }

    public function latestRevisionRequest(): ?RevisionRequest
    {
        return $this->revisionRequests()->first();
    }
}
