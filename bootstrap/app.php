<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            'admin.auth'         => \App\Http\Middleware\AdminAuthenticated::class,
            'two-factor.verified' => \App\Http\Middleware\TwoFactorVerified::class,
            'track.pageview'     => \App\Http\Middleware\TrackPageView::class,
            'rate.limit' => \App\Http\Middleware\RateLimiter::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
