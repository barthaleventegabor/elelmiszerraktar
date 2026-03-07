<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AuditLogger
{
    protected array $labels = [
        'User' => 'Felhasználó',
        'UserProfile' => 'Felhasználói profil',
        'Category' => 'Kategória',
        'Product' => 'Termék',
        'Supplier' => 'Beszállító',
    ];

    protected array $sensitiveKeys = [
        'password',
        'password_confirmation',
        'remember_token',
        'token',
        'access_token',
        'current_password',
    ];

    public function logCreated(Model $model): void
    {
        Log::channel('audit_create')->info(
            $this->resolveModelLabel($model).' létrehozva.',
            $this->buildModelContext($model, [
                'after' => $this->sanitizeData($model->attributesToArray()),
            ])
        );
    }

    public function logUpdated(Model $model): void
    {
        $changes = $this->sanitizeData($model->getChanges());
        unset($changes['updated_at']);

        if ($changes === []) {
            return;
        }

        $previous = Arr::only(
            $this->sanitizeData($model->getPrevious()),
            array_keys($changes)
        );

        unset($previous['updated_at']);

        Log::channel('audit_update')->info(
            $this->resolveModelLabel($model).' frissítve.',
            $this->buildModelContext($model, [
                'before' => $previous,
                'after' => $changes,
            ])
        );
    }

    public function logDeleted(Model $model): void
    {
        Log::channel('audit_delete')->info(
            $this->resolveModelLabel($model).' törölve.',
            $this->buildModelContext($model, [
                'before' => $this->sanitizeData($model->attributesToArray()),
            ])
        );
    }

    public function logAuthentication(
        string $event,
        bool $success,
        array $context = [],
        ?Authenticatable $actor = null
    ): void {
        $message = match ([$event, $success]) {
            ['login', true] => 'Sikeres bejelentkezési kísérlet.',
            ['login', false] => 'Sikertelen bejelentkezési kísérlet.',
            ['logout', true] => 'Sikeres kijelentkezés.',
            default => 'Hitelesítési esemény történt.',
        };

        $level = $success ? 'info' : 'warning';

        Log::channel('audit_auth')->{$level}(
            $message,
            array_merge(
                $this->buildBaseContext($actor),
                [
                    'event' => $event,
                    'success' => $success,
                ],
                $this->sanitizeData($context)
            )
        );
    }

    protected function buildModelContext(Model $model, array $context = []): array
    {
        return array_merge(
            $this->buildBaseContext(),
            [
                'model' => class_basename($model),
                'model_label' => $this->resolveModelLabel($model),
                'target_id' => $model->getKey(),
            ],
            $context
        );
    }

    protected function buildBaseContext(?Authenticatable $actor = null): array
    {
        $request = app()->bound('request') ? request() : null;
        $authenticatedUser = $actor ?? auth()->user();

        return array_filter([
            'timestamp' => now()->toDateTimeString(),
            'actor_id' => $authenticatedUser?->getAuthIdentifier(),
            'actor_email' => data_get($authenticatedUser, 'email'),
            'actor_role' => data_get($authenticatedUser, 'role'),
            'ip_address' => $request?->ip(),
            'http_method' => $request?->method(),
            'url' => $request?->fullUrl(),
            'route' => $request?->route()?->uri(),
            'user_agent' => $request?->userAgent(),
        ], static fn ($value) => ! is_null($value));
    }

    protected function resolveModelLabel(Model $model): string
    {
        return $this->labels[class_basename($model)] ?? class_basename($model);
    }

    protected function sanitizeData(mixed $data): mixed
    {
        if (! is_array($data)) {
            return $data;
        }

        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_string($key) && $this->isSensitiveKey($key)) {
                $sanitized[$key] = '***';
                continue;
            }

            $sanitized[$key] = is_array($value)
                ? $this->sanitizeData($value)
                : $value;
        }

        return $sanitized;
    }

    protected function isSensitiveKey(string $key): bool
    {
        return in_array(strtolower($key), $this->sensitiveKeys, true);
    }
}