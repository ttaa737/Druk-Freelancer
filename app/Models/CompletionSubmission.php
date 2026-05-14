<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompletionSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_id', 'freelancer_id', 'submission_notes', 'status',
        'submitted_at', 'verified_at', 'verified_by', 'rejection_reason',
        'rejected_at', 'payment_processed_at'
    ];

    protected $casts = [
        'submitted_at'        => 'datetime',
        'verified_at'         => 'datetime',
        'rejected_at'         => 'datetime',
        'payment_processed_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PAYMENT_PROCESSED = 'payment_processed';

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CompletionSubmissionAttachment::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isPaymentProcessed(): bool
    {
        return $this->status === self::STATUS_PAYMENT_PROCESSED;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($submission) {
            if (empty($submission->submitted_at)) {
                $submission->submitted_at = now();
            }
            if (empty($submission->status)) {
                $submission->status = self::STATUS_PENDING;
            }
        });
    }
}
