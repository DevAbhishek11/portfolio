@extends('layouts.admin')
@section('title', 'Project: ' . $project->title)

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.projects.index') }}"
                    class="text-xs text-slate-500 hover:text-violet-400 no-underline">← Back to projects</a>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">{{ $project->title }}</h1>
            <p class="text-slate-400 text-sm mt-1">
                Slug: <code class="text-violet-400">/{{ $project->slug }}</code>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.projects.edit', $project->id) }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl">
                Edit
            </a>
            <a href="{{ route('projects.show', $project->slug) }}" target="_blank"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-xl">
                View on site ↗
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden">
                <img src="{{ asset('/' . $project->thumbnail) }}" alt="{{ $project->title }}"
                    class="w-full h-72 object-cover">
            </div>

            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mb-2">Short description</h3>
                <p class="text-slate-300 text-sm mb-4">{{ $project->short_description }}</p>

                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mb-2">Description</h3>
                <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed">
                    {!! $project->description !!}
                </div>
            </div>

            @if ($project->images->count())
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                    <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mb-3">Gallery</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($project->images as $img)
                            <img src="{{ asset('/' . $img->image_path) }}" alt=""
                                class="w-full h-32 object-cover rounded-lg border border-slate-700">
                        @endforeach
                    </div>
                </div>
            @endif
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
                    $catColors = [
                        'frontend'  => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                        'backend'   => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                        'fullstack' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                    ];
                @endphp
                <span class="px-2.5 py-1 rounded-md border text-xs font-bold uppercase {{ $statusMap[$project->status] ?? '' }}">
                    {{ $project->status }}
                </span>

                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">Category</h3>
                <span class="px-2 py-1 rounded border text-xs font-bold uppercase {{ $catColors[$project->category] ?? '' }}">
                    {{ $project->category }}
                </span>

                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">Featured</h3>
                <span class="text-sm text-white">{{ $project->is_featured ? '★ Yes' : '☆ No' }}</span>

                <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">Timeline</h3>
                <p class="text-sm text-white">
                    {{ $project->start_date?->format('M d, Y') ?: '—' }}
                    →
                    {{ $project->end_date?->format('M d, Y') ?: 'Ongoing' }}
                </p>

                @if ($project->github_url)
                    <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">GitHub</h3>
                    <a href="{{ $project->github_url }}" target="_blank"
                        class="text-sm text-violet-400 hover:text-violet-300 break-all">{{ $project->github_url }}</a>
                @endif

                @if ($project->live_url)
                    <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mt-4 mb-2">Live URL</h3>
                    <a href="{{ $project->live_url }}" target="_blank"
                        class="text-sm text-violet-400 hover:text-violet-300 break-all">{{ $project->live_url }}</a>
                @endif
            </div>

            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-4 text-center">
                <p class="text-xs text-slate-500 uppercase tracking-wider">Views</p>
                <p class="text-2xl font-bold text-white mt-1">{{ number_format($project->view_count) }}</p>
            </div>

            @if ($project->techStacks->count())
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-5">
                    <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mb-3">Tech stack</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($project->techStacks as $tech)
                            <span class="px-2 py-1 bg-slate-700 text-slate-300 rounded text-xs">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
