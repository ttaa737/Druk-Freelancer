<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_number', 'job_id', 'proposal_id', 'poster_id', 'freelancer_id',
        'terms', 'total_amount', 'platform_fee', 'freelancer_amount',
        'status', 'start_date', 'deadline', 'completed_at', 'cancelled_at',
        'cancellation_reason', 'poster_signed', 'freelancer_signed',
    ];

    protected $casts = [
        'total_amount'     => 'decimal:2',
        'platform_fee'     => 'decimal:2',
        'freelancer_amount' => 'decimal:2',
        'start_date'       => 'datetime',
        'deadline'         => 'datetime',
        'completed_at'     => 'datetime',
        'cancelled_at'     => 'datetime',
        'poster_signed'    => 'boolean',
        'freelancer_signed' => 'boolean',
    ];

    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
    public function poster(): BelongsTo { return $this->belongsTo(User::class, 'poster_id'); }
    public function freelancer(): BelongsTo { return $this->belongsTo(User::class, 'freelancer_id'); }
    public function milestones(): HasMany { return $this->hasMany(Milestone::class); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function dispute(): HasOne { return $this->hasOne(DisputeCase::class); }
    public function invoice(): HasOne { return $this->hasOne(Invoice::class); }

    public function isActive(): bool { return $this->status === 'active'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($contract) {
            if (empty($contract->contract_number)) {
                $count = self::withTrashed()->count() + 1;
                $contract->contract_number = 'DF-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
