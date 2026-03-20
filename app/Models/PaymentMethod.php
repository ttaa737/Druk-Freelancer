<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'provider', 'account_number', 'account_name', 'is_default', 'is_verified', 'verified_at'];
    protected $casts = ['is_default' => 'boolean', 'is_verified' => 'boolean', 'verified_at' => 'datetime'];

    const PROVIDERS = [
        'mbob'    => 'mBoB (Bank of Bhutan)',
        'mpay'    => 'mPay (Bhutan National Bank)',
        'tpay'    => 'TPay (T Bank)',
        'epay'    => 'ePay (Bhutan Development Bank)',
        'drukpay' => 'DrukPay (Druk PNB Bank)',
        'dkpay'   => 'DK Pay (Digital Kidu)',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function getProviderLabelAttribute(): string
    {
        return self::PROVIDERS[$this->provider] ?? $this->provider;
    }
}
