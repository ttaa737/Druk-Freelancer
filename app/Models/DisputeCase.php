<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisputeCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'case_number', 'contract_id', 'milestone_id', 'raised_by', 'against_user',
        'assigned_admin_id', 'subject', 'description', 'reason', 'status',
        'resolution_notes', 'poster_refund_amount', 'freelancer_payout_amount', 'resolved_at',
    ];

    protected $casts = [
        'poster_refund_amount'    => 'decimal:2',
        'freelancer_payout_amount' => 'decimal:2',
        'resolved_at'             => 'datetime',
    ];

    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function milestone(): BelongsTo { return $this->belongsTo(Milestone::class); }
    public function raisedBy(): BelongsTo { return $this->belongsTo(User::class, 'raised_by'); }
    public function againstUser(): BelongsTo { return $this->belongsTo(User::class, 'against_user'); }
    public function assignedAdmin(): BelongsTo { return $this->belongsTo(User::class, 'assigned_admin_id'); }
    public function evidence(): HasMany { return $this->hasMany(DisputeEvidence::class, 'dispute_id'); }
    public function comments(): HasMany { return $this->hasMany(DisputeComment::class, 'dispute_id'); }

    public function isOpen(): bool { return $this->status === 'open'; }
    public function isResolved(): bool { return in_array($this->status, ['resolved_poster', 'resolved_freelancer', 'resolved_split', 'closed']); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($dispute) {
            if (empty($dispute->case_number)) {
                $count = self::withTrashed()->count() + 1;
                $dispute->case_number = 'DIS-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
