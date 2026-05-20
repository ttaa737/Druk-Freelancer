<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_id', 'freelancer_id', 'cover_letter', 'bid_amount',
        'delivery_days', 'status', 'rejection_reason', 'is_shortlisted',
        'cv_file_path', 'cv_file_name', 'cv_from_verification', 'cv_document_id',
    ];

    protected $casts = [
        'bid_amount'        => 'decimal:2',
        'is_shortlisted'    => 'boolean',
        'shortlisted_at'    => 'datetime',
        'awarded_at'        => 'datetime',
        'cv_from_verification' => 'boolean', // Indicates CV is from verified documents
    ];

    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
    public function freelancer(): BelongsTo { return $this->belongsTo(User::class, 'freelancer_id'); }
    public function cvDocument(): BelongsTo { return $this->belongsTo(VerificationDocument::class, 'cv_document_id'); }
    public function milestones(): HasMany { return $this->hasMany(ProposalMilestone::class); }

    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeShortlisted($query) { return $query->where('is_shortlisted', true); }

    public function getBidFormattedAttribute(): string
    {
        return 'Nu. ' . number_format($this->bid_amount, 2);
    }

    /**
     * Check if this proposal uses the freelancer's verified CV
     */
    public function isUsingVerifiedCV(): bool
    {
        return $this->cv_from_verification === true && $this->cv_document_id !== null;
    }

    /**
     * Get the CV document info for display
     */
    public function getCVInfo(): array
    {
        if ($this->isUsingVerifiedCV() && $this->cvDocument) {
            return [
                'name' => $this->cvDocument->original_name,
                'date' => $this->cvDocument->reviewed_at?->format('d M Y'),
                'from_verification' => true,
            ];
        }

        return [
            'name' => $this->cv_file_name,
            'date' => $this->created_at->format('d M Y'),
            'from_verification' => false,
        ];
    }
}
