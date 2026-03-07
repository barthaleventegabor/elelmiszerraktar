<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserProfile;
use App\Observers\AuditObserver;
use App\Support\AuditLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Category::observe(AuditObserver::class);
        Product::observe(AuditObserver::class);
        Supplier::observe(AuditObserver::class);
        User::observe(AuditObserver::class);
        UserProfile::observe(AuditObserver::class);

        Event::listen(Login::class, function (Login $event): void {
            app(AuditLogger::class)->logAuthentication(
                'login',
                true,
                [
                    'guard' => $event->guard,
                    'user_id' => $event->user->getAuthIdentifier(),
                    'email' => $event->user->email,
                ],
                $event->user
            );
        });

        Event::listen(Failed::class, function (Failed $event): void {
            app(AuditLogger::class)->logAuthentication(
                'login',
                false,
                [
                    'guard' => $event->guard,
                    'user_id' => $event->user?->getAuthIdentifier(),
                    'email' => $event->credentials['email'] ?? null,
                ],
                $event->user
            );
        });

        Event::listen(Logout::class, function (Logout $event): void {
            app(AuditLogger::class)->logAuthentication(
                'logout',
                true,
                [
                    'guard' => $event->guard,
                    'user_id' => $event->user?->getAuthIdentifier(),
                    'email' => $event->user?->email,
                ],
                $event->user
            );
        });
    }
}
