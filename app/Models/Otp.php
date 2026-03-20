<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'identifier', 'type', 'code',
        'is_used', 'attempts', 'expires_at', 'used_at',
    ];

    protected $casts = [
        'is_used'    => 'boolean',
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired() && $this->attempts < 5;
    }

    public static function generate(string $identifier, string $type, ?int $userId = null, int $expiresInMinutes = 10): self
    {
        // Invalidate previous OTPs
        self::where('identifier', $identifier)->where('type', $type)->where('is_used', false)->update(['is_used' => true]);

        return self::create([
            'user_id'    => $userId,
            'identifier' => $identifier,
            'type'       => $type,
            'code'       => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);
    }
}
