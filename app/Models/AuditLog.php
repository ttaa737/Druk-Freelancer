<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'event', 'auditable_type', 'auditable_id',
        'old_values', 'new_values', 'ip_address', 'user_agent', 'url', 'notes',
        'action', 'entity_type', 'entity_id', 'module',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public $timestamps = true;
    const UPDATED_AT = null; // Audit logs are immutable

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public static function record(string $event, $model = null, array $oldValues = [], array $newValues = [], string $notes = null): self
    {
        return self::create([
            'user_id'        => auth()->id(),
            'event'          => $event,
            'action'         => $event,
            'auditable_type' => $model ? get_class($model) : null,
            'entity_type'    => $model ? get_class($model) : null,
            'auditable_id'   => $model?->id,
            'entity_id'      => $model?->id,
            'old_values'     => $oldValues ?: null,
            'new_values'     => $newValues ?: null,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'url'            => request()->fullUrl(),
            'module'         => request()->route()?->getName() ?? request()->path(),
            'notes'          => $notes,
        ]);
    }
}
