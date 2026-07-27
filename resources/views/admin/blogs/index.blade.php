@extends('layouts.admin')
@section('title', 'Blog Posts')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Content Management</h1>
            <p class="text-slate-400 text-sm mt-1">Manage, edit, and monitor your blog performance.</p>
        </div>
        <a href="{{ route('admin.blogs.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all transform active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create New Post
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-4 mb-6 backdrop-blur-sm">
        <div class="flex flex-col md:flex-row items-center gap-4" id="blogs-filter">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" id="blogs-search" value="{{ request('search') }}"
                    class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                    placeholder="Search by title or category...">
            </div>

            <div class="w-full md:w-48">
                <select id="blogs-status"
                    class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2 outline-none focus:border-indigo-500 appearance-none cursor-pointer">
                    <option value="">All Statuses</option>
                    @foreach (['published', 'draft', 'archived'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto ml-auto">
                <button type="button" id="blogs-reset"
                    class="px-4 py-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 text-sm font-bold rounded-xl transition-all">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <div id="blogs-wrapper">
        @include('admin.blogs._table')
    </div>

    @push('scripts')
        <script>
            (function () {
                const wrapper = document.getElementById('blogs-wrapper');
                const searchInput = document.getElementById('blogs-search');
                const statusSelect = document.getElementById('blogs-status');
                const resetBtn = document.getElementById('blogs-reset');
                if (!wrapper) return;

                const state = {
                    search: @json(request('search', '')),
                    status: @json(request('status', '')),
                    page: 1,
                };

                function buildAjaxUrl() {
                    const url = new URL("{{ route('admin.blogs.ajax') }}", window.location.origin);
                    if (state.search) url.searchParams.set('search', state.search);
                    if (state.status) url.searchParams.set('status', state.status);
                    if (state.page) url.searchParams.set('page', state.page);
                    return url.toString();
                }

                function buildPublicUrl() {
                    const url = new URL("{{ route('admin.blogs.index') }}", window.location.origin);
                    if (state.search) url.searchParams.set('search', state.search);
                    if (state.status) url.searchParams.set('status', state.status);
                    if (state.page && state.page > 1) url.searchParams.set('page', state.page);
                    return url.pathname + (url.search || '');
                }

                async function load(push = true) {
                    wrapper.style.opacity = '0.5';
                    try {
                        const res = await fetch(buildAjaxUrl(), { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                        const json = await res.json();
                        wrapper.innerHTML = json.html;
                        if (push) history.pushState(state, '', buildPublicUrl());
                    } finally {
                        wrapper.style.opacity = '1';
                    }
                }

                let st;
                searchInput?.addEventListener('input', () => {
                    clearTimeout(st);
                    st = setTimeout(() => {
                        state.search = searchInput.value.trim();
                        state.page = 1;
                        load();
                    }, 350);
                });
                statusSelect?.addEventListener('change', () => {
                    state.status = statusSelect.value;
                    state.page = 1;
                    load();
                });
                resetBtn?.addEventListener('click', () => {
                    state.search = '';
                    state.status = '';
                    state.page = 1;
                    searchInput.value = '';
                    statusSelect.value = '';
                    load();
                });

                wrapper.addEventListener('click', (e) => {
                    const pageLink = e.target.closest('#blogs-pagination a');
                    if (pageLink) {
                        e.preventDefault();
                        const u = new URL(pageLink.href);
                        state.page = parseInt(u.searchParams.get('page') || '1', 10);
                        load();
                    }
                });

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
