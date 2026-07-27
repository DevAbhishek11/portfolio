@extends('layouts.admin')
@section('title', 'Contact Queries')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Contact Queries</h1>
            @if ($unreadCount > 0)
                <div class="flex items-center gap-2 mt-1">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    <p class="text-sm font-medium text-rose-400">{{ $unreadCount }} unread messages</p>
                </div>
            @else
                <p class="text-slate-400 text-sm mt-1">Your inbox is up to date.</p>
            @endif
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-4 mb-6 backdrop-blur-sm">
        <div class="flex flex-col md:flex-row items-center gap-4" id="contacts-filter">
            <div class="relative w-full md:w-72">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" id="contacts-search" value="{{ request('search') }}"
                    class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                    placeholder="Search name, email...">
            </div>

            <div class="w-full md:w-44">
                <select id="contacts-status"
                    class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2 outline-none focus:border-indigo-500 appearance-none">
                    <option value="">All Statuses</option>
                    @foreach (['unread', 'read', 'replied', 'archived'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto ml-auto">
                <button type="button" id="contacts-reset"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-xl transition-all text-center">
                    Clear
                </button>
            </div>
        </div>
    </div>

    <div id="contacts-wrapper">
        @include('admin.contacts._table')
    </div>

    @push('scripts')
        <script>
            (function () {
                const wrapper = document.getElementById('contacts-wrapper');
                const searchInput = document.getElementById('contacts-search');
                const statusSelect = document.getElementById('contacts-status');
                const resetBtn = document.getElementById('contacts-reset');
                if (!wrapper) return;

                const state = {
                    search: @json(request('search', '')),
                    status: @json(request('status', '')),
                    page: 1,
                };

                function buildAjaxUrl() {
                    const url = new URL("{{ route('admin.contacts.ajax') }}", window.location.origin);
                    if (state.search) url.searchParams.set('search', state.search);
                    if (state.status) url.searchParams.set('status', state.status);
                    if (state.page) url.searchParams.set('page', state.page);
                    return url.toString();
                }

                function buildPublicUrl() {
                    const url = new URL("{{ route('admin.contacts.index') }}", window.location.origin);
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
                        wireSelectAll();
                    } finally {
                        wrapper.style.opacity = '1';
                    }
                }

                function wireSelectAll() {
                    const selectAll = document.getElementById('selectAll');
                    const rowChecks = document.querySelectorAll('.row-check');
                    if (!selectAll) return;
                    selectAll.addEventListener('change', function () {
                        rowChecks.forEach(cb => {
                            cb.checked = this.checked;
                            updateRowStyle(cb);
                        });
                    });
                    rowChecks.forEach(cb => cb.addEventListener('change', () => updateRowStyle(cb)));
                }
                function updateRowStyle(cb) {
                    const tr = cb.closest('tr');
                    if (cb.checked) tr.classList.add('bg-indigo-600/5');
                    else tr.classList.remove('bg-indigo-600/5');
                }
                wireSelectAll();

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
                    const pageLink = e.target.closest('#contacts-pagination a');
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
