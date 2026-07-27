<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecurityHeaders
{
    private array $allowedScriptDomains = [
        "'self'",
        "'unsafe-inline'",
        "'unsafe-eval'",
        'https://cdn.tailwindcss.com',
        'https://cdnjs.cloudflare.com',
        'https://cdn.jsdelivr.net',
    ];

    private array $allowedStyleDomains = [
        "'self'",
        "'unsafe-inline'",
        'https://fonts.googleapis.com',
        'https://cdn.tailwindcss.com',
        'https://cdn.jsdelivr.net',
    ];

    private array $allowedImgDomains = [
        "'self'",
        'data:',
        'blob:',
        'https://api.qrserver.com',
    ];

    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if (! method_exists($response, 'headers')) {
            return $response;
        }

        $scripts = implode(' ', $this->allowedScriptDomains);
        $styles  = implode(' ', $this->allowedStyleDomains);
        $imgs    = implode(' ', $this->allowedImgDomains);

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src {$scripts}",
            "style-src {$styles}",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src {$imgs}",
            "connect-src 'self'",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ]);

        $h = $response->headers;
        $h->set('X-Content-Type-Options',    'nosniff');
        $h->set('X-Frame-Options',           'DENY');
        $h->set('X-XSS-Protection',          '1; mode=block');
        $h->set('Referrer-Policy',           'strict-origin-when-cross-origin');
        $h->set('Permissions-Policy',        'camera=(), microphone=(), geolocation=(), payment=()');
        $h->set('Content-Security-Policy',   $csp);
        $h->remove('X-Powered-By');
        $h->remove('Server');

        // HSTS only in production over HTTPS
        if (app()->isProduction() && $request->secure()) {
            $h->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
