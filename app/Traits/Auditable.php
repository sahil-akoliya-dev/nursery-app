<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    /**
     * Boot the trait.
     */
    protected static function bootAuditable()
    {
        static::created(function (Model $model) {
            static::logAuditAction('created', $model);
        });

        static::updated(function (Model $model) {
            if ($model->wasChanged()) {
                static::logAuditAction('updated', $model, [
                    'old_values' => $model->getOriginal(),
                    'new_values' => $model->getChanges(),
                ]);
            }
        });

        static::deleted(function (Model $model) {
            static::logAuditAction('deleted', $model);
        });
    }

    /**
     * Log an audit action.
     */
    protected static function logAuditAction(string $action, Model $model, array $metadata = []): void
    {
        // Skip logging if no authenticated user
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return;
        }

        // Skip logging for audit logs themselves to prevent recursion
        if ($model instanceof AuditLog) {
            return;
        }

        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $metadata['old_values'] ?? null,
            'new_values' => $metadata['new_values'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'metadata' => array_merge($metadata, [
                'model_class' => get_class($model),
                'model_key' => $model->getKey(),
            ]),
        ]);
    }

    /**
     * Manually log an audit action.
     */
    public function logAction(string $action, array $metadata = []): void
    {
        static::logAuditAction($action, $this, $metadata);
    }

    /**
     * Get audit logs for this model.
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'model');
    }
}
