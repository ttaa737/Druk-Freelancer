<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'contract_id', 'milestone_id',
        'billed_to', 'billed_by', 'subtotal', 'platform_fee',
        'tax_amount', 'total_amount', 'amount_paid',
        'status', 'due_date', 'paid_at', 'pdf_path', 'notes',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'platform_fee'  => 'decimal:2',
        'tax_amount'    => 'decimal:2',
        'total_amount'  => 'decimal:2',
        'amount_paid'   => 'decimal:2',
        'due_date'      => 'datetime',
        'paid_at'       => 'datetime',
    ];

    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function milestone(): BelongsTo { return $this->belongsTo(Milestone::class); }
    public function billedTo(): BelongsTo { return $this->belongsTo(User::class, 'billed_to'); }
    public function billedBy(): BelongsTo { return $this->belongsTo(User::class, 'billed_by'); }

    public function isPaid(): bool { return $this->status === 'paid'; }

    public function getBalanceDueAttribute(): float
    {
        return max(0, (float)$this->total_amount - (float)$this->amount_paid);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $count = self::count() + 1;
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
