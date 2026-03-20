<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    /**
     * Record an audit event.
     */
    public static function log(
        string $event,
        $model = null,
        array $oldValues = [],
        array $newValues = [],
        string $notes = null
    ): AuditLog {
        return AuditLog::create([
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
