<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id', 'sender_id', 'body', 'type',
        'attachment_path', 'attachment_name', 'is_read', 'read_at',
        'is_deleted_by_sender', 'is_deleted_by_receiver',
    ];

    protected $casts = [
        'is_read'               => 'boolean',
        'is_deleted_by_sender'  => 'boolean',
        'is_deleted_by_receiver' => 'boolean',
        'read_at'               => 'datetime',
    ];

    public function conversation(): BelongsTo { return $this->belongsTo(Conversation::class); }
    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'sender_id'); }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }
}
