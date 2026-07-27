<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageView;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $request->isMethod('GET')) {
            return $response;
        }

        if (
            $request->is('assets/*') ||
            $request->is('css/*') ||
            $request->is('js/*') ||
            str_contains($request->path(), '.')
        ) {
            return $response;
        }

        $key = 'pv_' . $request->path();

        if (session()->has($key)) {
            return $response;
        }

        session()->put($key, true);

        try {
            $ua = $request->userAgent() ?? '';

            PageView::insert([
                'page'        => $request->route()?->getName() ?? $request->path(),
                'url'         => $request->fullUrl(),
                'ip_address'  => $request->ip(),
                'user_agent'  => $ua,
                'referrer'    => $request->headers->get('referer'),
                'device_type' => $this->detectDevice($ua),
                'browser'     => $this->detectBrowser($ua),
                'session_id'  => session()->getId(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // silently fail so site never breaks
        }

        return $response;
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android')) {
            return 'mobile';
        }
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function detectBrowser(string $ua): string
    {
        if (str_contains($ua, 'Firefox'))  return 'Firefox';
        if (str_contains($ua, 'Chrome'))   return 'Chrome';
        if (str_contains($ua, 'Safari'))   return 'Safari';
        if (str_contains($ua, 'Edge'))     return 'Edge';
        if (str_contains($ua, 'Opera'))    return 'Opera';
        return 'Other';
    }
}
