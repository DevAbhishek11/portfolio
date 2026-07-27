<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('admin_user_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Please log in to access the admin panel.');
        }

        $user = \App\Models\User::find(session('admin_user_id'));

        if (! $user || ! $user->is_admin) {
            session()->flush();
            return redirect()->route('admin.login')
                ->with('error', 'Unauthorized access.');
        }

        // Share user with all views
        view()->share('adminUser', $user);

        return $next($request);
    }
}
