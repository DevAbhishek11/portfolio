@extends('layouts.app')
@section('content')

    <section style="padding:8rem 0 4rem;">
        <div class="container">
            <div style="text-align:center;margin-bottom:3rem;">
                <div class="section-tag reveal" style="justify-content:center;">Portfolio</div>
                <h1 class="font-display reveal delay-1"
                    style="font-size:clamp(2.5rem,5vw,4rem);font-weight:900;color:var(--text-primary);">
                    My <span class="grad-text">Projects</span>
                </h1>
            </div>

            {{-- Search + Sort + Category Filters --}}
            <form id="project-filter-form"
                style="display:flex;justify-content:center;gap:0.75rem;flex-wrap:wrap;margin-bottom:2rem;"
                class="reveal delay-2">
                <input type="text" name="search" value="{{ $search }}" class="anime-input" id="project-search"
                    style="max-width:300px;" placeholder="Search projects…">
                <select name="sort" id="project-sort" class="anime-input" style="max-width:160px;">
                    <option value="order" @selected($sort === 'order')>Default</option>
                    <option value="newest" @selected($sort === 'newest')>Newest</option>
                    <option value="popular" @selected($sort === 'popular')>Most viewed</option>
                </select>
            </form>

            {{-- Category Filter --}}
            <div style="display:flex;justify-content:center;gap:0.75rem;flex-wrap:wrap;margin-bottom:3rem;"
                class="reveal delay-2">
                @foreach ($categories as $cat)
                    <a href="{{ $cat === 'all' ? route('projects.index') : route('projects.category', $cat) }}"
                        class="filter-link"
                        data-filter-type="category" data-filter-value="{{ $cat === 'all' ? '' : $cat }}"
                        style="padding:0.5rem 1.25rem;border-radius:9999px;font-size:0.85rem;font-weight:500;text-decoration:none;cursor:pointer;transition:all 0.2s;
                        border:1px solid {{ $active === $cat ? 'var(--accent-1)' : 'var(--border-color)' }};
                        background:{{ $active === $cat ? 'rgba(139,92,246,0.2)' : 'transparent' }};
                        color:{{ $active === $cat ? '#a78bfa' : 'var(--text-secondary)' }};">
                        {{ ucfirst($cat) }}
                    </a>
                @endforeach
            </div>

            <div id="project-results-wrapper">
                @include('frontend.projects._grid')
                @include('frontend.projects._pagination')
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            (function () {
                const wrapper = document.getElementById('project-results-wrapper');
                const searchInput = document.getElementById('project-search');
                const sortSelect = document.getElementById('project-sort');
                if (!wrapper) return;

                const state = {
                    category: @json($active === 'all' ? null : $active),
                    search: @json($search),
                    sort: @json($sort),
                    page: 1,
                };

                function buildUrl() {
                    let base = "{{ url('/projects') }}";
                    if (state.category) base += '/category/' + encodeURIComponent(state.category);
                    const qs = new URLSearchParams();
                    if (state.search) qs.set('search', state.search);
                    if (state.sort && state.sort !== 'order') qs.set('sort', state.sort);
                    if (state.page && state.page > 1) qs.set('page', state.page);
                    const s = qs.toString();
                    return s ? base + '?' + s : base;
                }

                function buildAjaxUrl() {
                    const url = new URL("{{ route('projects.ajax') }}", window.location.origin);
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
                        console.error('Projects AJAX failed', e);
                    } finally {
                        wrapper.style.opacity = '1';
                    }
                }

                document.addEventListener('click', (e) => {
                    const link = e.target.closest('.filter-link');
                    if (link) {
                        e.preventDefault();
                        const val = link.dataset.filterValue;
                        state.category = val || null;
                        state.page = 1;
                        document.querySelectorAll('.filter-link').forEach(el => {
                            const active = (el.dataset.filterValue || null) === (state.category || null);
                            el.style.borderColor = active ? 'var(--accent-1)' : 'var(--border-color)';
                            el.style.background  = active ? 'rgba(139,92,246,0.2)' : 'transparent';
                            el.style.color       = active ? '#a78bfa' : 'var(--text-secondary)';
                        });
                        load();
                        return;
                    }
                    const pageLink = e.target.closest('#project-pagination a');
                    if (pageLink) {
                        e.preventDefault();
                        const u = new URL(pageLink.href);
                        state.page = parseInt(u.searchParams.get('page') || '1', 10);
                        load();
                    }
                });

                let searchTimer;
                searchInput?.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        state.search = searchInput.value.trim();
                        state.page = 1;
                        load();
                    }, 350);
                });

                sortSelect?.addEventListener('change', () => {
                    state.sort = sortSelect.value;
                    state.page = 1;
                    load();
                });

                document.getElementById('project-filter-form')?.addEventListener('submit', (e) => e.preventDefault());

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
