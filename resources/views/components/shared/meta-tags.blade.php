@props([
    'title' => config('portfolio.site_name'),
    'description' => config('portfolio.meta.description'),
    'keywords' => config('portfolio.meta.keywords'),
    'ogImage' => config('portfolio.meta.og_image'),
    'ogType' => 'website',
    'canonical' => null,
])

<title>{{ $title === config('portfolio.site_name') ? $title : $title . ' — ' . config('portfolio.site_name') }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $canonical ?? request()->url() }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ asset($ogImage) }}">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:site_name" content="{{ config('portfolio.site_name') }}">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ asset($ogImage) }}">
