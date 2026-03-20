<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id', 'contract_id', 'poster_id', 'freelancer_id',
        'last_message_at', 'poster_archived', 'freelancer_archived',
    ];

    protected $casts = [
        'last_message_at'    => 'datetime',
        'poster_archived'    => 'boolean',
        'freelancer_archived' => 'boolean',
    ];

    public function poster(): BelongsTo { return $this->belongsTo(User::class, 'poster_id'); }
    public function freelancer(): BelongsTo { return $this->belongsTo(User::class, 'freelancer_id'); }
    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function messages(): HasMany { return $this->hasMany(Message::class)->orderBy('created_at'); }
    public function latestMessage() { return $this->hasOne(Message::class)->latest(); }

    public function getOtherUser(int $currentUserId): User
    {
        return $this->poster_id === $currentUserId ? $this->freelancer : $this->poster;
    }

    public function getUnreadCountFor(int $userId): int
    {
        return $this->messages()->where('sender_id', '!=', $userId)->where('is_read', false)->count();
    }
}
