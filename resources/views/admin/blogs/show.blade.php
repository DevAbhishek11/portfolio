@extends('layouts.admin')
@section('title', 'Blog Post: ' . $blog->title)

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.blogs.index') }}"
                    class="text-xs text-slate-500 hover:text-violet-400 no-underline">← Back to blogs</a>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">{{ $blog->title }}</h1>
            <p class="text-slate-400 text-sm mt-1">
                Slug: <code class="text-violet-400">/{{ $blog->slug }}</code>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.blogs.comments', $blog->id) }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-xl inline-flex items-center gap-2">
                💬 Comments ({{ $blog->comments_count }})
            </a>
            <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl">
                Edit
            </a>
            <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-xl">
                View on site ↗
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
            @if ($blog->featured_image)
                <img src="{{ asset('/' . $blog->featured_image) }}" alt="{{ $blog->title }}"
                    class="w-full h-64 object-cover rounded-xl mb-4">
            @endif

            <div class="flex items-center gap-2 mb-4 flex-wrap">
                @if ($blog->category)
                    <span class="px-2 py-1 bg-violet-500/10 text-violet-400 rounded text-xs font-bold">
                        {{ $blog->category }}
                    </span>
                @endif
                @foreach ($blog->tags ?? [] as $tag)
                    <span class="px-2 py-1 bg-slate-700 text-slate-300 rounded text-xs">#{{ $tag }}</span>
                @endforeach
            </div>

            @if ($blog->excerpt)
                <div class="mb-4 p-3 bg-slate-900/50 border border-slate-700 rounded-lg text-slate-300 text-sm italic">
                    {{ $blog->excerpt }}
                </div>
            @endif

            <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed">
                {!! $blog->content !!}
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-5">
                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mb-3">Status</h3>
                @php
                    $statusMap = [
                        'published' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                        'draft'     => 'bg-slate-700/50 text-slate-400 border-slate-600/30',
                        'archived'  => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                    ];
                @endphp
                <span class="px-2.5 py-1 rounded-md border text-xs font-bold uppercase {{ $statusMap[$blog->status] ?? '' }}">
                    {{ $blog->status }}
                </span>

                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">Featured</h3>
                <span class="text-sm text-white">{{ $blog->is_featured ? '★ Yes' : '☆ No' }}</span>

                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">Published</h3>
                <p class="text-sm text-white">
                    {{ $blog->published_at?->format('M d, Y H:i') ?: '—' }}
                </p>

                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">Author</h3>
                <p class="text-sm text-white">{{ $blog->user->name ?? '—' }}</p>

                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">Read time</h3>
                <p class="text-sm text-white">{{ $blog->read_time }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-4 text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Views</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($blog->view_count) }}</p>
                </div>
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-4 text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Comments</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $blog->comments_count }}</p>
                    <p class="text-[10px] text-slate-500 mt-1">
                        {{ $blog->approved_comments_count }} visible
                    </p>
                </div>
            </div>

            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider">Recent comments</h3>
                    <a href="{{ route('admin.blogs.comments', $blog->id) }}"
                        class="text-xs text-violet-400 hover:text-violet-300 no-underline">View all →</a>
                </div>
                @forelse($recentComments as $c)
                    <div class="py-2 border-b border-slate-700/50 last:border-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-white">{{ $c->name }}</span>
                            <span class="text-[10px] {{ $c->is_approved ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $c->is_approved ? '✓ visible' : '✕ hidden' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400">{{ Str::limit($c->comment, 80) }}</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
