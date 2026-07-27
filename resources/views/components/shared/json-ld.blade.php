@props(['type' => 'website', 'data' => []])

@php
    $admin = \App\Models\User::where('is_admin', true)->first();

    $base = [
        '@context' => 'https://schema.org',
    ];

    if ($type === 'website') {
        $schema = array_merge($base, [
            '@type' => 'WebSite',
            'name' => config('portfolio.site_name'),
            'url' => config('app.url'),
            'description' => config('portfolio.meta.description'),
            'author' => [
                '@type' => 'Person',
                'name' => $admin?->name ?? config('portfolio.site_name'),
                'url' => config('app.url'),
            ],
        ]);
    } elseif ($type === 'person') {
        $schema = array_merge($base, [
            '@type' => 'Person',
            'name' => $admin?->name ?? config('portfolio.site_name'),
            'url' => config('app.url'),
            'description' => $admin?->bio ?? config('portfolio.meta.description'),
            'email' => config('portfolio.site_email'),
            'sameAs' => array_filter([
                config('portfolio.social.github'),
                config('portfolio.social.linkedin'),
                config('portfolio.social.twitter'),
            ]),
        ]);
    } elseif ($type === 'article') {
        $schema = array_merge($base, [
            '@type' => 'Article',
            'headline' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? '',
            'datePublished' => $data['published_at'] ?? '',
            'dateModified' => $data['updated_at'] ?? '',
            'author' => [
                '@type' => 'Person',
                'name' => $admin?->name ?? config('portfolio.site_name'),
            ],
        ]);
    } elseif ($type === 'project') {
        $schema = array_merge($base, [
            '@type' => 'CreativeWork',
            'name' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? '',
            'url' => $data['url'] ?? '',
            'creator' => [
                '@type' => 'Person',
                'name' => $admin?->name ?? config('portfolio.site_name'),
            ],
        ]);
    } else {
        $schema = $base;
    }
@endphp

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
