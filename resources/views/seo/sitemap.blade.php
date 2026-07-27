<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @foreach ($static as $page)
        <url>
            <loc>{{ $page['url'] }}</loc>
            <changefreq>{{ $page['freq'] }}</changefreq>
            <priority>{{ $page['priority'] }}</priority>
            <lastmod>{{ now()->toAtomString() }}</lastmod>
        </url>
    @endforeach

    @foreach ($projects as $project)
        <url>
            <loc>{{ route('projects.show', $project->slug) }}</loc>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
            <lastmod>{{ $project->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach

    @foreach ($blogs as $blog)
        <url>
            <loc>{{ route('blogs.show', $blog->slug) }}</loc>
            <changefreq>monthly</changefreq>
            <priority>0.75</priority>
            <lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach

</urlset>
