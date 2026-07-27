<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter as LaravelRateLimiter;

class RateLimiter
{
    public function handle(Request $request, Closure $next, string $key = 'default', int $maxAttempts = 60, int $decaySeconds = 60): mixed
    {
        $identifier = $key . ':' . $request->ip();

        if (LaravelRateLimiter::tooManyAttempts($identifier, $maxAttempts)) {
            $retryAfter = LaravelRateLimiter::availableIn($identifier);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message'     => 'Too many requests.',
                    'retry_after' => $retryAfter,
                ], 429);
            }

            return back()->with('error', "Too many requests. Please try again in {$retryAfter} seconds.");
        }

        LaravelRateLimiter::hit($identifier, $decaySeconds);

        return $next($request);
    }
}
