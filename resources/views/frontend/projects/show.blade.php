@extends('layouts.app')
@section('content')

    @php
        $jsonLdType = 'project';
        $jsonLdData = [
            'title' => $project->title,
            'description' => $project->short_description,
            'image' => asset($project->thumbnail),
            'url' => route('projects.show', $project->slug),
        ];
    @endphp

    <article style="padding-top:5rem;">
        {{-- Hero image --}}
        <div style="height:420px;position:relative;overflow:hidden;">
            <img src="{{ asset('/' . $project->thumbnail ) }}" alt="{{ $project->title }}" style="width:100%;height:100%;object-fit:cover;">
            <div
                style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(10,10,15,0.3),var(--bg-primary));">
            </div>
        </div>

        <div class="container" style="margin-top:-3rem;position:relative;z-index:1;">
            <div style="display:grid;grid-template-columns:1fr 320px;gap:2.5rem;align-items:start;">

                {{-- Main content --}}
                <div>
                    <div style="display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap;">
                        <span class="badge-{{ $project->category }}"
                            style="padding:4px 12px;border-radius:9999px;font-size:0.78rem;font-weight:600;">{{ ucfirst($project->category) }}</span>
                        @if ($project->is_featured)
                            <span class="tech-badge">⭐ Featured</span>
                        @endif
                    </div>

                    <h1 class="font-display"
                        style="font-size:clamp(2rem,4vw,3rem);font-weight:900;color:var(--text-primary);margin-bottom:1rem;line-height:1.2;">
                        {{ $project->title }}
                    </h1>

                    <p
                        style="color:var(--text-secondary);font-size:1.05rem;line-height:1.7;margin-bottom:2rem;border-left:3px solid var(--accent-1);padding-left:1rem;">
                        {{ $project->short_description }}
                    </p>

                    {{-- Image Gallery --}}
                    @if ($project->images->count())
                        <div
                            style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:0.75rem;margin-bottom:2rem;">
                            @foreach ($project->images as $img)
                                <img src="{{ asset('/' .  $img->image_path ) }}" alt="{{ $img->alt_text }}"
                                    style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:0.75rem;border:1px solid var(--border-color);cursor:zoom-in;"
                                    onclick="openLightbox(this.src)">
                            @endforeach
                        </div>
                    @endif

                    {{-- Description --}}
                    <div class="prose-content" style="color:var(--text-secondary);line-height:1.85;font-size:0.95rem;">
                        {!! $project->description !!}
                    </div>

                    {{-- Prev / Next --}}
                    <div
                        style="display:flex;gap:1rem;margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border-color);flex-wrap:wrap;">
                        @if ($prev)
                            <a href="{{ route('projects.show', $prev->slug) }}" class="btn-outline"
                                style="flex:1;justify-content:center;">← {{ Str::limit($prev->title, 25) }}</a>
                        @endif
                        @if ($next)
                            <a href="{{ route('projects.show', $next->slug) }}" class="btn-outline"
                                style="flex:1;justify-content:center;">{{ Str::limit($next->title, 25) }} →</a>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div style="position:sticky;top:6rem;">
                    <div class="anime-card" style="padding:1.5rem;margin-bottom:1.5rem;">
                        <h3
                            style="color:var(--text-primary);font-weight:700;margin-bottom:1.25rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Project Info</h3>

                        @foreach ([['Status', ucfirst($project->status)], ['Category', ucfirst($project->category)], ['Start', $project->start_date?->format('M Y') ?? '—'], ['End', $project->end_date?->format('M Y') ?? 'Ongoing'], ['Views', number_format($project->view_count)]] as [$label, $val])
                            <div
                                style="display:flex;justify-content:space-between;padding:0.6rem 0;border-bottom:1px solid var(--border-color);font-size:0.85rem;">
                                <span style="color:var(--text-secondary);">{{ $label }}</span>
                                <span style="color:var(--text-primary);font-weight:500;">{{ $val }}</span>
                            </div>
                        @endforeach

                        <div style="display:flex;gap:0.75rem;margin-top:1.25rem;flex-wrap:wrap;">
                            @if ($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank" class="btn-outline"
                                    style="flex:1;justify-content:center;font-size:0.85rem;">GitHub</a>
                            @endif
                            @if ($project->live_url)
                                <a href="{{ $project->live_url }}" target="_blank" class="btn-anime"
                                    style="flex:1;justify-content:center;font-size:0.85rem;">Live Demo ↗</a>
                            @endif
                        </div>
                    </div>

                    {{-- Tech Stack --}}
                    <div class="anime-card" style="padding:1.5rem;">
                        <h3
                            style="color:var(--text-primary);font-weight:700;margin-bottom:1rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Tech Stack</h3>
                        @foreach ($project->techStacks->groupBy('category') as $cat => $techs)
                            <div style="margin-bottom:0.875rem;">
                                <p
                                    style="color:var(--text-secondary);font-size:0.72rem;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.4rem;">
                                    {{ $cat }}</p>
                                <div style="display:flex;flex-wrap:wrap;gap:0.35rem;">
                                    @foreach ($techs as $tech)
                                        <span class="tech-badge">{{ $tech->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Related Projects --}}
            @if ($related->count())
                <div style="margin-top:5rem;padding-top:3rem;border-top:1px solid var(--border-color);">
                    <h2 class="font-display reveal"
                        style="font-size:1.75rem;font-weight:800;color:var(--text-primary);margin-bottom:2rem;">Related
                        Projects</h2>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem;">
                        @foreach ($related as $i => $rel)
                            <a href="{{ route('projects.show', $rel->slug) }}"
                                class="anime-card reveal delay-{{ $i + 1 }}"
                                style="text-decoration:none;display:block;">
                                <img src="{{ asset('/' . $rel->thumbnail) }}"
                                    style="width:100%;height:150px;object-fit:cover;border-radius:0.875rem 0.875rem 0 0;">
                                <div style="padding:1rem;">
                                    <h3 style="color:var(--text-primary);font-size:0.95rem;font-weight:600;">
                                        {{ $rel->title }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>

    {{-- Lightbox --}}
    <div id="lightbox" onclick="this.style.display='none'"
        style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.9);display:none;align-items:center;justify-content:center;cursor:zoom-out;">
        <img id="lightbox-img" src="" style="max-width:90%;max-height:90%;border-radius:0.75rem;">
    </div>

    <script>
        function openLightbox(src) {
            const lb = document.getElementById('lightbox');
            document.getElementById('lightbox-img').src = src;
            lb.style.display = 'flex';
        }
    </script>

    <style>
        .prose-content h1,
        .prose-content h2,
        .prose-content h3 {
            color: var(--text-primary);
            font-family: 'Playfair Display', serif;
            margin: 1.5rem 0 0.75rem;
        }

        .prose-content p {
            margin-bottom: 1rem;
        }

        .prose-content a {
            color: var(--accent-1);
        }

        .prose-content ul,
        .prose-content ol {
            margin: 1rem 0 1rem 1.5rem;
        }

        .prose-content li {
            margin-bottom: 0.4rem;
        }

        .prose-content code {
            background: var(--bg-secondary);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.85em;
        }

        .prose-content pre {
            background: var(--bg-secondary);
            padding: 1rem;
            border-radius: 0.75rem;
            overflow-x: auto;
            margin: 1rem 0;
        }
    </style>
@endsection
