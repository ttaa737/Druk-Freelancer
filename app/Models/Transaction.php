<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_ref', 'user_id', 'contract_id', 'milestone_id',
        'type', 'amount', 'fee', 'net_amount', 'status',
        'payment_provider', 'payment_provider_ref', 'payment_provider_response',
        'notes', 'balance_before', 'balance_after', 'ip_address',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'fee'            => 'decimal:2',
        'net_amount'     => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after'  => 'decimal:2',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function milestone(): BelongsTo { return $this->belongsTo(Milestone::class); }
    public function wallet(): HasOneThrough
    {
        return $this->hasOneThrough(
            Wallet::class,
            User::class,
            'id',
            'user_id',
            'user_id',
            'id'
        );
    }

    public function scopeCompleted($query) { return $query->where('status', 'completed'); }
    public function scopeByType($query, string $type) { return $query->where('type', $type); }

    public function getAmountFormattedAttribute(): string
    {
        return 'Nu. ' . number_format($this->amount, 2);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($txn) {
            if (empty($txn->transaction_ref)) {
                $count = self::count() + 1;
                $txn->transaction_ref = 'TXN-' . date('Y') . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
