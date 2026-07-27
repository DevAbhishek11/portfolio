<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.75rem;" id="blog-grid">
    @forelse($blogs as $i => $blog)
        <article class="anime-card reveal visible delay-{{ ($i % 3) + 1 }}">
            @if ($blog->featured_image)
                <a href="{{ route('blogs.show', $blog->slug) }}"
                    style="display:block;position:relative;z-index:1;text-decoration:none;">
                    <div style="height:190px;overflow:hidden;border-radius:0.875rem 0.875rem 0 0;">
                        <img src="{{ asset('/' . $blog->featured_image) }}" alt="{{ $blog->title }}"
                            style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s;"
                            onmouseover="this.style.transform='scale(1.05)'"
                            onmouseout="this.style.transform='scale(1)'">
                    </div>
                </a>
            @endif
            <div style="padding:1.5rem;position:relative;z-index:1;">
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.75rem;">
                    @if ($blog->category)
                        <a href="{{ route('blogs.category', $blog->category) }}" class="tech-badge filter-link"
                            data-filter-type="category" data-filter-value="{{ $blog->category }}"
                            style="text-decoration:none;cursor:pointer;">
                            {{ $blog->category }}
                        </a>
                    @endif
                    @foreach (array_slice($blog->tags ?? [], 0, 2) as $tag)
                        <a href="{{ route('blogs.tag', $tag) }}" class="tech-badge filter-link"
                            data-filter-type="tag" data-filter-value="{{ $tag }}"
                            style="opacity:0.7;text-decoration:none;cursor:pointer;">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
                <h2 class="font-display"
                    style="color:var(--text-primary);font-size:1.05rem;font-weight:700;line-height:1.4;margin-bottom:0.6rem;">
                    <a href="{{ route('blogs.show', $blog->slug) }}"
                        style="color:inherit;text-decoration:none;">{{ Str::limit($blog->title, 65) }}</a>
                </h2>
                <p style="color:var(--text-secondary);font-size:0.85rem;line-height:1.6;margin-bottom:1rem;">
                    {{ Str::limit($blog->excerpt ?: strip_tags($blog->content), 100) }}
                </p>
                <div
                    style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;flex-wrap:wrap;">
                    <span style="color:var(--text-secondary);font-size:0.78rem;">
                        {{ $blog->published_at?->format('M d, Y') }} · {{ $blog->read_time }}
                        @if (isset($blog->approved_comments_count))
                            · 💬 {{ $blog->approved_comments_count }}
                        @endif
                    </span>
                    <a href="{{ route('blogs.show', $blog->slug) }}"
                        style="color:var(--accent-1);text-decoration:none;font-size:0.85rem;font-weight:500;position:relative;z-index:2;">
                        Read →
                    </a>
                </div>
            </div>
        </article>
    @empty
        <div style="grid-column:1/-1;text-align:center;padding:5rem 0;color:var(--text-secondary);">
            No blog posts found.
        </div>
    @endforelse
</div>
