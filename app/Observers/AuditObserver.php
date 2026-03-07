<?php

namespace App\Observers;

use App\Support\AuditLogger;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        app(AuditLogger::class)->logCreated($model);
    }

    public function updated(Model $model): void
    {
        app(AuditLogger::class)->logUpdated($model);
    }

    public function deleted(Model $model): void
    {
        app(AuditLogger::class)->logDeleted($model);
    }
}