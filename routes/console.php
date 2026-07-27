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

// ── Cache maintenance ────────────────────────────────────────────────────
// Off-peak weekly refresh: clears stale application cache rows, then
// rebuilds the config/route/view caches so they never silently drift from
// what's actually on disk between manual deploys.
Schedule::call(function () {
    try {
        Artisan::call('cache:clear');
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Scheduled cache:clear failed', ['error' => $e->getMessage()]);
    }
})->weekly()->sundays()->at('03:00')->name('cache:auto-clear');

Schedule::call(function () {
    try {
        Artisan::call('optimize:clear');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Scheduled optimize refresh failed', ['error' => $e->getMessage()]);
    }
})->weekly()->sundays()->at('03:15')->name('optimize:auto-refresh');
