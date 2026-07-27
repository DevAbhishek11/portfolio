@extends('layouts.app')
@section('content')

    <section style="padding:8rem 0 4rem;">
        <div class="container">
            <div style="text-align:center;margin-bottom:3rem;">
                <div class="section-tag reveal" style="justify-content:center;">Articles</div>
                <h1 class="font-display reveal delay-1"
                    style="font-size:clamp(2.5rem,5vw,4rem);font-weight:900;color:var(--text-primary);">
                    The <span class="grad-text">Blog</span>
                </h1>
            </div>

            {{-- Search + Filters --}}
            <form id="blog-filter-form"
                style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:center;margin-bottom:1.25rem;"
                class="reveal delay-2">
                <input type="text" name="search" value="{{ $search }}" class="anime-input" id="blog-search"
                    style="max-width:300px;" placeholder="Search articles…">

                <select name="sort" id="blog-sort" class="anime-input" style="max-width:160px;">
                    <option value="newest" @selected($sort === 'newest')>Newest</option>
                    <option value="oldest" @selected($sort === 'oldest')>Oldest</option>
                    <option value="popular" @selected($sort === 'popular')>Most viewed</option>
                </select>

                <a href="{{ route('blogs.index') }}" class="filter-link tech-badge"
                    data-filter-type="category" data-filter-value=""
                    style="padding:0.5rem 1rem;border-radius:9999px;font-size:0.82rem;text-decoration:none;cursor:pointer;
                    border:1px solid {{ !$activeCategory && !$activeTag ? 'var(--accent-1)' : 'var(--border-color)' }};
                    background:{{ !$activeCategory && !$activeTag ? 'rgba(139,92,246,0.2)' : 'transparent' }};
                    color:{{ !$activeCategory && !$activeTag ? '#a78bfa' : 'var(--text-secondary)' }};">
                    All
                </a>

                @foreach ($categories as $cat)
                    <a href="{{ route('blogs.category', $cat) }}" class="filter-link tech-badge"
                        data-filter-type="category" data-filter-value="{{ $cat }}"
                        style="padding:0.5rem 1rem;border-radius:9999px;font-size:0.82rem;text-decoration:none;cursor:pointer;
                        border:1px solid {{ $activeCategory === $cat ? 'var(--accent-1)' : 'var(--border-color)' }};
                        background:{{ $activeCategory === $cat ? 'rgba(139,92,246,0.2)' : 'transparent' }};
                        color:{{ $activeCategory === $cat ? '#a78bfa' : 'var(--text-secondary)' }};">
                        {{ $cat }}
                    </a>
                @endforeach
            </form>

            @if ($allTags->count())
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;justify-content:center;margin-bottom:2.5rem;">
                    @foreach ($allTags as $tag)
                        <a href="{{ route('blogs.tag', $tag) }}" class="filter-link tech-badge"
                            data-filter-type="tag" data-filter-value="{{ $tag }}"
                            style="font-size:0.75rem;text-decoration:none;cursor:pointer;
                            {{ $activeTag === $tag ? 'background:rgba(139,92,246,0.25);color:#a78bfa;border-color:var(--accent-1);' : '' }}">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Featured Post (only when no filter) --}}
            @if ($featured && !$activeCategory && !$activeTag && !$search)
                <div class="anime-card reveal" id="featured-blog"
                    style="display:grid;grid-template-columns:1fr 1fr;gap:0;margin-bottom:2.5rem;overflow:hidden;">
                    @if ($featured->featured_image)
                        <a href="{{ route('blogs.show', $featured->slug) }}" style="position:relative;z-index:1;">
                            <img src="{{ asset('/' . $featured->featured_image) }}" alt="{{ $featured->title }}"
                                style="width:100%;height:300px;object-fit:cover;">
                        </a>
                    @endif
                    <div style="padding:2.5rem;display:flex;flex-direction:column;justify-content:center;position:relative;z-index:1;">
                        <span class="tech-badge" style="width:fit-content;margin-bottom:1rem;">⭐ Featured</span>
                        <h2 class="font-display"
                            style="color:var(--text-primary);font-size:1.5rem;font-weight:800;line-height:1.3;margin-bottom:0.75rem;">
                            <a href="{{ route('blogs.show', $featured->slug) }}"
                                style="color:inherit;text-decoration:none;">{{ $featured->title }}</a>
                        </h2>
                        <p style="color:var(--text-secondary);font-size:0.9rem;line-height:1.6;margin-bottom:1.5rem;">
                            {{ Str::limit($featured->excerpt ?: strip_tags($featured->content), 150) }}
                        </p>
                        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;">
                            <span style="color:var(--text-secondary);font-size:0.8rem;">
                                {{ $featured->published_at?->format('M d, Y') }} · {{ $featured->read_time }}
                            </span>
                            <a href="{{ route('blogs.show', $featured->slug) }}" class="btn-anime"
                                style="padding:0.625rem 1.25rem;font-size:0.875rem;position:relative;z-index:2;">Read Article →</a>
                        </div>
                    </div>
                </div>
            @endif

            <div id="blog-results-wrapper">
                @include('frontend.blogs._grid')
                @include('frontend.blogs._pagination')
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            (function () {
                const wrapper = document.getElementById('blog-results-wrapper');
                const form = document.getElementById('blog-filter-form');
                const searchInput = document.getElementById('blog-search');
                const sortSelect = document.getElementById('blog-sort');
                if (!wrapper) return;

                const state = {
                    category: @json($activeCategory),
                    tag: @json($activeTag),
                    search: @json($search),
                    sort: @json($sort),
                    page: 1,
                };

                function buildUrl() {
                    let base = "{{ url('/blogs') }}";
                    if (state.tag)      base += '/tag/' + encodeURIComponent(state.tag);
                    else if (state.category) base += '/category/' + encodeURIComponent(state.category);

                    const qs = new URLSearchParams();
                    if (state.search) qs.set('search', state.search);
                    if (state.sort && state.sort !== 'newest') qs.set('sort', state.sort);
                    if (state.page && state.page > 1) qs.set('page', state.page);
                    const s = qs.toString();
                    return s ? base + '?' + s : base;
                }

                function buildAjaxUrl() {
                    const url = new URL("{{ route('blogs.ajax') }}", window.location.origin);
                    if (state.tag) url.searchParams.set('tag', state.tag);
                    if (state.category) url.searchParams.set('category', state.category);
                    if (state.search) url.searchParams.set('search', state.search);
                    if (state.sort) url.searchParams.set('sort', state.sort);
                    if (state.page) url.searchParams.set('page', state.page);
                    return url.toString();
                }

                async function load(push = true) {
                    wrapper.style.opacity = '0.5';
                    try {
                        const res = await fetch(buildAjaxUrl(), { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                        const json = await res.json();
                        wrapper.innerHTML = json.html + (json.pagination || '');
                        if (push) history.pushState(state, '', buildUrl());
                        wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } catch (e) {
                        console.error('Blog AJAX failed', e);
                    } finally {
                        wrapper.style.opacity = '1';
                    }
                }

                // Filter chip clicks (category & tag)
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('.filter-link');
                    if (link) {
                        e.preventDefault();
                        const type = link.dataset.filterType;
                        const val  = link.dataset.filterValue;
                        if (type === 'category') {
                            state.category = val || null;
                            state.tag = null;
                        } else if (type === 'tag') {
                            state.tag = val || null;
                            state.category = null;
                        }
                        state.page = 1;
                        // visual update of chips
                        document.querySelectorAll('.filter-link').forEach(el => {
                            const t = el.dataset.filterType, v = el.dataset.filterValue;
                            const active = (t === 'category' && v === (state.category || '') && !state.tag) ||
                                           (t === 'tag' && v === state.tag);
                            el.style.borderColor = active ? 'var(--accent-1)' : 'var(--border-color)';
                            el.style.background  = active ? 'rgba(139,92,246,0.2)' : 'transparent';
                            el.style.color       = active ? '#a78bfa' : 'var(--text-secondary)';
                        });
                        load();
                        return;
                    }

                    // AJAX pagination
                    const pageLink = e.target.closest('#blog-pagination a');
                    if (pageLink) {
                        e.preventDefault();
                        const u = new URL(pageLink.href);
                        state.page = parseInt(u.searchParams.get('page') || '1', 10);
                        load();
                    }
                });

                // Search (debounced)
                let searchTimer;
                searchInput?.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        state.search = searchInput.value.trim();
                        state.page = 1;
                        load();
                    }, 350);
                });

                // Sort change
                sortSelect?.addEventListener('change', () => {
                    state.sort = sortSelect.value;
                    state.page = 1;
                    load();
                });

                form?.addEventListener('submit', (e) => { e.preventDefault(); });

                window.addEventListener('popstate', (e) => {
                    if (e.state) {
                        Object.assign(state, e.state);
                        load(false);
                    }
                });
            })();
        </script>
    @endpush
@endsection
