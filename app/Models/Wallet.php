<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'available_balance', 'escrow_balance',
        'pending_withdrawal', 'total_earned', 'total_spent',
        'is_frozen', 'freeze_reason',
    ];

    protected $casts = [
        'available_balance'  => 'decimal:2',
        'escrow_balance'     => 'decimal:2',
        'pending_withdrawal' => 'decimal:2',
        'total_earned'       => 'decimal:2',
        'total_spent'        => 'decimal:2',
        'is_frozen'          => 'boolean',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class, 'user_id', 'user_id'); }

    public function getTotalBalanceAttribute(): float
    {
        return (float) $this->available_balance + (float) $this->escrow_balance;
    }

    public function hasSufficientFunds(float $amount): bool
    {
        return $this->available_balance >= $amount;
    }

    public function canWithdraw(float $amount): bool
    {
        $minWithdrawal = (float) config('platform.min_withdrawal', 500);
        return !$this->is_frozen && $this->available_balance >= $amount && $amount >= $minWithdrawal;
    }
}
