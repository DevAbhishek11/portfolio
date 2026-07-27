<?php

return [
    'site_name'    => env('PORTFOLIO_SITE_NAME', 'My Portfolio'),
    'site_tagline' => env('PORTFOLIO_TAGLINE', 'Full Stack Developer'),
    'site_email'   => env('PORTFOLIO_EMAIL', 'hello@portfolio.local'),
    'site_phone'   => env('PORTFOLIO_PHONE', ''),
    'site_location' => env('PORTFOLIO_LOCATION', ''),

    'social' => [
        'github'   => env('PORTFOLIO_GITHUB', ''),
        'linkedin' => env('PORTFOLIO_LINKEDIN', ''),
        'twitter'  => env('PORTFOLIO_TWITTER', ''),
    ],

    'meta' => [
        'description' => env('PORTFOLIO_META_DESC', 'Full Stack Developer Portfolio'),
        'keywords'    => env('PORTFOLIO_META_KEYWORDS', 'developer, portfolio, laravel, react'),
        'og_image'    => env('PORTFOLIO_OG_IMAGE', '/assets/images/og/default.jpg'),
    ],

    'admin' => [
        'otp_expiry_minutes'   => 10,
        'reset_expiry_minutes' => 60,
        'max_login_attempts'   => 5,
        'lockout_minutes'      => 30,
    ],

    'upload' => [
        'max_image_size_kb' => 5120,
        'allowed_image_types' => ['jpg', 'jpeg', 'png', 'webp'],
        'max_pdf_size_kb'   => 10240,
    ],
];
