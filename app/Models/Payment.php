<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'type', 'paper_id', 'registration_package_id', 'user_id', 'invoice_number', 'amount',
        'description', 'status', 'payment_proof', 'payment_method',
        'paid_at', 'verified_by', 'verified_at', 'admin_notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Type constants
    const TYPE_PAPER = 'paper';
    const TYPE_PARTICIPANT = 'participant';

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function registrationPackage(): BelongsTo
    {
        return $this->belongsTo(RegistrationPackage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Ymd');
        $last = self::where('invoice_number', 'like', $prefix . '%')->count();
        return $prefix . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
