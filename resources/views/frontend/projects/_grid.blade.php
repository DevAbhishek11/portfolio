<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.75rem;" id="project-grid">
    @forelse($projects as $i => $project)
        <article class="anime-card reveal visible delay-{{ ($i % 3) + 1 }}">
            <a href="{{ route('projects.show', $project->slug) }}"
                style="display:block;text-decoration:none;color:inherit;position:relative;z-index:1;">
                <div style="height:200px;overflow:hidden;border-radius:0.875rem 0.875rem 0 0;position:relative;">
                    <img src="{{ asset('/' . ($project->thumbnail ?: $project->getFirstMediaUrl('thumbnail', 'card'))) }}"
                        srcset="{{ $project->getThumbnailSrcset() }}"
                        sizes="(max-width:640px) 100vw, (max-width:1024px) 50vw, 33vw"
                        alt="{{ $project->title }}" loading="lazy" decoding="async"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div
                        style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 50%,rgba(10,10,15,0.85));">
                    </div>
                    <span class="badge-{{ $project->category }}"
                        style="position:absolute;top:0.75rem;left:0.75rem;padding:3px 10px;border-radius:9999px;font-size:0.7rem;font-weight:600;backdrop-filter:blur(8px);background:rgba(0,0,0,0.5);">
                        {{ ucfirst($project->category) }}
                    </span>
                    @if ($project->is_featured)
                        <span style="position:absolute;top:0.75rem;right:0.75rem;">⭐</span>
                    @endif
                </div>
            </a>
            <div style="padding:1.5rem;position:relative;z-index:1;">
                <h2
                    style="color:var(--text-primary);font-size:1.05rem;font-weight:700;margin-bottom:0.4rem;font-family:'Playfair Display',serif;">
                    <a href="{{ route('projects.show', $project->slug) }}"
                        style="color:inherit;text-decoration:none;">{{ $project->title }}</a>
                </h2>
                <p style="color:var(--text-secondary);font-size:0.85rem;line-height:1.6;margin-bottom:1rem;">
                    {{ Str::limit($project->short_description, 90) }}
                </p>
                <div style="display:flex;flex-wrap:wrap;gap:0.35rem;margin-bottom:1.25rem;">
                    @foreach ($project->techStacks->take(4) as $tech)
                        <span class="tech-badge">{{ $tech->name }}</span>
                    @endforeach
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <a href="{{ route('projects.show', $project->slug) }}" class="btn-anime"
                        style="padding:0.5rem 1rem;font-size:0.82rem;flex:1;justify-content:center;position:relative;z-index:2;">View
                        →</a>
                    @if ($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn-outline"
                            style="padding:0.5rem 0.75rem;font-size:0.8rem;position:relative;z-index:2;">GH</a>
                    @endif
                    @if ($project->live_url)
                        <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn-outline"
                            style="padding:0.5rem 0.75rem;font-size:0.8rem;position:relative;z-index:2;">↗</a>
                    @endif
                </div>
            </div>
        </article>
    @empty
        <div style="grid-column:1/-1;text-align:center;padding:5rem 0;color:var(--text-secondary);">
            No projects found.
        </div>
    @endforelse
</div>
