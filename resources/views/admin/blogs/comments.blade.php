@extends('layouts.admin')
@section('title', 'Comments: ' . $blog->title)

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.blogs.show', $blog->id) }}"
                    class="text-xs text-slate-500 hover:text-violet-400 no-underline">← Back to blog</a>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Comments on "{{ Str::limit($blog->title, 50) }}"</h1>
            <p class="text-slate-400 text-sm mt-1">
                {{ $blog->comments_count }} total — {{ $blog->approved_comments_count }} visible
            </p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-4 mb-6 backdrop-blur-sm">
        <div class="flex flex-col md:flex-row items-center gap-4" id="comments-filter">
            <input type="text" id="comments-search" value="{{ request('search') }}"
                class="w-full md:w-80 bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                placeholder="Search comments…">

            <select id="comments-filter-select"
                class="w-full md:w-48 bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2 outline-none focus:border-indigo-500">
                <option value="" @selected(!request('filter'))>All comments</option>
                <option value="approved" @selected(request('filter') === 'approved')>Visible only</option>
                <option value="pending" @selected(request('filter') === 'pending')>Hidden only</option>
            </select>

            <div class="ml-auto text-xs text-slate-500">
                Toggle the switch to show/hide a comment on the public site.
            </div>
        </div>
    </div>

    <div id="comments-wrapper">
        @include('admin.blogs._comments_table')
    </div>

    @push('scripts')
        <script>
            (function () {
                const wrapper = document.getElementById('comments-wrapper');
                const searchInput = document.getElementById('comments-search');
                const filterSelect = document.getElementById('comments-filter-select');
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                const blogId = {{ $blog->id }};

                const state = {
                    search: @json(request('search', '')),
                    filter: @json(request('filter', '')),
                    page: {{ $comments->currentPage() }},
                };

                function buildUrl() {
                    const url = new URL("{{ route('admin.blogs.comments.ajax', $blog->id) }}", window.location.origin);
                    if (state.search) url.searchParams.set('search', state.search);
                    if (state.filter) url.searchParams.set('filter', state.filter);
                    if (state.page) url.searchParams.set('page', state.page);
                    return url.toString();
                }

                async function load() {
                    wrapper.style.opacity = '0.5';
                    try {
                        const res = await fetch(buildUrl(), { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                        const json = await res.json();
                        wrapper.innerHTML = json.html;
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
                filterSelect?.addEventListener('change', () => {
                    state.filter = filterSelect.value;
                    state.page = 1;
                    load();
                });

                // Delegated handlers (work after AJAX reload)
                wrapper.addEventListener('change', async (e) => {
                    const toggle = e.target.closest('.comment-visibility-toggle');
                    if (!toggle) return;
                    const cid = toggle.dataset.commentId;
                    const row = toggle.closest('tr');
                    try {
                        const res = await fetch(`/admin/blogs/${blogId}/comments/${cid}/toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });
                        const json = await res.json();
                        const label = row.querySelector('.visibility-label');
                        if (json.is_approved) {
                            label.textContent = 'Visible';
                            label.classList.remove('text-slate-500');
                            label.classList.add('text-emerald-400');
                        } else {
                            label.textContent = 'Hidden';
                            label.classList.add('text-slate-500');
                            label.classList.remove('text-emerald-400');
                        }
                    } catch {
                        toggle.checked = !toggle.checked;
                    }
                });

                wrapper.addEventListener('click', async (e) => {
                    const del = e.target.closest('.comment-delete-btn');
                    if (del) {
                        e.preventDefault();
                        if (!confirm('Delete this comment? This cannot be undone.')) return;
                        const cid = del.dataset.commentId;
                        const res = await fetch(`/admin/blogs/${blogId}/comments/${cid}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });
                        if (res.ok) load();
                        return;
                    }

                    const pageLink = e.target.closest('#comments-pagination a');
                    if (pageLink) {
                        e.preventDefault();
                        const u = new URL(pageLink.href);
                        state.page = parseInt(u.searchParams.get('page') || '1', 10);
                        load();
                    }
                });
            })();
        </script>
    @endpush
@endsection
