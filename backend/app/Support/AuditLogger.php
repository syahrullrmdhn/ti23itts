<?php

namespace App\Support;

use App\Models\AuditLog;
use App\Models\User;

class AuditLogger
{
    public static function record(
        ?User $user,
        string $action,
        string $targetType,
        ?int $targetId,
        ?string $targetLabel,
        string $summary,
        ?array $properties = null,
    ): AuditLog {
        return AuditLog::query()->create([
            'user_id' => $user?->id,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'target_label' => $targetLabel,
            'summary' => $summary,
            'properties' => $properties,
        ]);
    }
}
