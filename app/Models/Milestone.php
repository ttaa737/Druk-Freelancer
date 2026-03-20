<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id', 'title', 'description', 'amount', 'due_date',
        'sort_order', 'status', 'work_description', 'escrow_held',
        'submitted_at', 'approved_at', 'paid_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'escrow_held'  => 'decimal:2',
        'due_date'     => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
        'paid_at'      => 'datetime',
    ];

    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function attachments(): HasMany { return $this->hasMany(MilestoneAttachment::class); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isSubmitted(): bool  { return $this->status === 'submitted'; }
    public function isApproved(): bool   { return $this->status === 'approved'; }
    public function isPaid(): bool       { return $this->status === 'paid'; }
    public function isDisputed(): bool   { return $this->status === 'disputed'; }

    public function getAmountFormattedAttribute(): string
    {
        return 'Nu. ' . number_format($this->amount, 2);
    }
}
