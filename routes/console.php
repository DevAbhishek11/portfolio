<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    \App\Models\TwoFactorToken::where('expires_at', '<', now()->subDay())
        ->orWhere(function ($q) {
            $q->where('used', true)->where('updated_at', '<', now()->subDays(7));
        })
        ->delete();
})->daily()->name('cleanup:tokens');

Schedule::call(function () {
    \App\Models\LoginAttempt::where('created_at', '<', now()->subDays(90))->delete();
})->weekly()->name('cleanup:login-attempts');

Schedule::call(function () {
    \App\Models\PageView::where('created_at', '<', now()->subYear())->delete();
})->monthly()->name('cleanup:page-views');
