<?php

namespace App\Http\Middleware;

use App\Models\RememberToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class RememberMe
{
    /**
     * Restores an admin session from the long-lived "remember me" cookie
     * when the normal session has expired or been lost. Skips the 2FA
     * challenge on the trusted device, mirroring standard "remember this
     * device" behavior.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! session('admin_user_id')) {
            $this->restoreFromCookie($request);
        }

        return $next($request);
    }

    private function restoreFromCookie(Request $request): void
    {
        $cookie = $request->cookie('admin_remember');

        if (! $cookie || ! str_contains($cookie, '|')) {
            return;
        }

        [$userId, $rawToken] = explode('|', $cookie, 2);

        $record = RememberToken::where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->get()
            ->first(fn (RememberToken $t) => hash_equals($t->token, hash('sha256', $rawToken)));

        if (! $record) {
            return;
        }

        $user = User::find($userId);

        if (! $user || ! $user->is_admin) {
            return;
        }

        session()->regenerate();
        session([
            'admin_user_id'        => $user->id,
            'two_factor_verified'  => true,
        ]);

        // Rotate the token value on use (defense-in-depth against a leaked
        // cookie) while keeping the original 30-day expiry fixed.
        $newRawToken = bin2hex(random_bytes(32));
        $record->update(['token' => hash('sha256', $newRawToken)]);

        $minutesRemaining = max(1, (int) ceil(($record->expires_at->timestamp - now()->timestamp) / 60));

        cookie()->queue(cookie(
            'admin_remember',
            $user->id . '|' . $newRawToken,
            $minutesRemaining,
            null,
            null,
            $request->secure(),
            true,
            false,
            'lax'
        ));
    }
}
