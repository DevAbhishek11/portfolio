@props(['type' => 'website', 'data' => []])

@php
    $admin = portfolio_owner();

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
                $admin?->upwork_url,
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
    } elseif ($type === 'service') {
        $schema = array_merge($base, [
            '@type' => 'Service',
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'serviceType' => $data['serviceType'] ?? ($data['name'] ?? ''),
            'provider' => [
                '@type' => 'Person',
                'name' => $admin?->name ?? config('portfolio.site_name'),
                'url' => config('app.url'),
            ],
            'areaServed' => $data['areaServed'] ?? 'Worldwide',
        ]);
    } elseif ($type === 'faq') {
        $schema = array_merge($base, [
            '@type' => 'FAQPage',
            'mainEntity' => collect($data['items'] ?? [])->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'] ?? '',
                ],
            ])->all(),
        ]);
    } elseif ($type === 'breadcrumb') {
        $schema = array_merge($base, [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($data['items'] ?? [])->values()->map(fn ($item, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'] ?? '',
                'item' => $item['url'] ?? '',
            ])->all(),
        ]);
    } else {
        $schema = $base;
    }
@endphp

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
