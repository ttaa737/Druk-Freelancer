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
    ];

    protected $casts = [
        'bid_amount'     => 'decimal:2',
        'is_shortlisted' => 'boolean',
        'shortlisted_at' => 'datetime',
        'awarded_at'     => 'datetime',
    ];

    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
    public function freelancer(): BelongsTo { return $this->belongsTo(User::class, 'freelancer_id'); }
    public function milestones(): HasMany { return $this->hasMany(ProposalMilestone::class); }

    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeShortlisted($query) { return $query->where('is_shortlisted', true); }

    public function getBidFormattedAttribute(): string
    {
        return 'Nu. ' . number_format($this->bid_amount, 2);
    }
}
