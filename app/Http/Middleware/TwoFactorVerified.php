<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class TwoFactorVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = User::find(session('admin_user_id'));

        if ($user && $user->two_factor_enabled && ! session('two_factor_verified')) {
            return redirect()->route('admin.two-factor');
        }

        return $next($request);
    }
}
