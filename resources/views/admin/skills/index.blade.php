@extends('layouts.admin')
@section('title', 'Skills')
@section('breadcrumb', 'Skills')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-txt-primary">Skills & Proficiency</h1>
            <p class="text-txt-secondary text-sm mt-0.5">Manage your skills radar chart. Featured skills appear on the chart
                (max 8).</p>
        </div>
        <a href="{{ route('admin.skills.create') }}" class="btn-anime">+ Add Skill</a>
    </div>

    {{-- Radar preview --}}
    <div class="admin-card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-txt-primary">Radar Preview</h2>
            <span class="text-xs text-txt-secondary">Shows first 8 featured skills</span>
        </div>
        <div class="flex justify-center">
            <canvas id="radarPreview" class="max-w-xs w-full"></canvas>
        </div>
    </div>

    {{-- Skills by category --}}
    @foreach ($skills as $category => $categorySkills)
        <div class="admin-card mb-4 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-admin-border">
                <h2 class="text-sm font-semibold text-txt-primary">{{ $category }}</h2>
                <span class="text-xs text-txt-secondary">{{ $categorySkills->count() }} skills</span>
            </div>
            <div id="sortable-{{ Str::slug($category) }}" class="divide-y divide-admin-border/40">
                @foreach ($categorySkills as $skill)
                    <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-admin-surface/50 transition-colors group"
                        data-id="{{ $skill->id }}">
                        {{-- Drag handle --}}
                        <div class="text-admin-border group-hover:text-txt-secondary cursor-grab transition-colors flex-shrink-0"
                            title="Drag to reorder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
                            </svg>
                        </div>

                        {{-- Colour dot --}}
                        <div class="w-3 h-3 rounded-full flex-shrink-0" style="background:{{ $skill->color }};"></div>

                        {{-- Name --}}
                        <div class="flex-1 min-w-0">
                            <span class="text-sm font-medium text-txt-primary">{{ $skill->name }}</span>
                            @if ($skill->is_featured)
                                <span
                                    class="ml-2 text-xs px-1.5 py-0.5 rounded bg-accent-1/10 text-violet-400 border border-accent-1/20">
                                    radar
                                </span>
                            @endif
                        </div>

                        {{-- Progress bar --}}
                        <div class="w-32 hidden sm:block">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-txt-secondary">{{ $skill->level }}%</span>
                            </div>
                            <div class="h-1.5 bg-admin-border rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500"
                                    style="width:{{ $skill->level }}%;background:{{ $skill->color }};"></div>
                            </div>
                        </div>

                        {{-- Level badge --}}
                        <span
                            class="hidden md:block text-xs font-mono text-txt-secondary w-8 text-right">{{ $skill->level }}</span>

                        {{-- Actions --}}
                        <div class="flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.skills.edit', $skill->id) }}"
                                class="px-2.5 py-1.5 text-xs rounded-lg bg-admin-surface border border-admin-border text-txt-secondary hover:text-txt-primary hover:border-accent-1/40 transition-all">
                                Edit
                            </a>
                            <form id="del-skill-{{ $skill->id }}" method="POST"
                                action="{{ route('admin.skills.destroy', $skill->id) }}">
                                @csrf @method('DELETE')
                            </form>
                            <button onclick="confirmDelete('del-skill-{{ $skill->id }}')"
                                class="px-2.5 py-1.5 text-xs rounded-lg bg-accent-3/10 border border-accent-3/25 text-red-400 hover:bg-accent-3/20 transition-all">
                                Del
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endpush

    @push('scripts')
        <script>
            // ── Radar preview ─────────────────────────────────────────────────────────
            fetch('{{ route('api.skills.radar') }}')
                .then(r => r.json())
                .then(data => {
                    if (!data.length) return;
                    const ctx = document.getElementById('radarPreview').getContext('2d');
                    new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: data.map(s => s.name),
                            datasets: [{
                                label: 'Proficiency',
                                data: data.map(s => s.level),
                                backgroundColor: 'rgba(139,92,246,0.15)',
                                borderColor: '#8b5cf6',
                                borderWidth: 2,
                                pointBackgroundColor: data.map(s => s.color),
                                pointBorderColor: '#fff',
                                pointBorderWidth: 1.5,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                r: {
                                    min: 0,
                                    max: 100,
                                    ticks: {
                                        stepSize: 25,
                                        display: false
                                    },
                                    grid: {
                                        color: 'rgba(139,92,246,0.1)'
                                    },
                                    angleLines: {
                                        color: 'rgba(139,92,246,0.1)'
                                    },
                                    pointLabels: {
                                        color: '#a1a1aa',
                                        font: {
                                            size: 11,
                                            family: 'Inter'
                                        }
                                    },
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => ` ${ctx.raw}%`
                                    }
                                }
                            }
                        }
                    });
                });

            // ── Drag-to-reorder ───────────────────────────────────────────────────────
            document.querySelectorAll('[id^="sortable-"]').forEach(el => {
                Sortable.create(el, {
                    animation: 150,
                    handle: '[title="Drag to reorder"]',
                    ghostClass: 'bg-accent-1/5',
                    onEnd() {
                        const ids = [...el.querySelectorAll('[data-id]')].map(r => r.dataset.id);
                        fetch('{{ route('admin.skills.reorder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                            },
                            body: JSON.stringify({
                                order: ids
                            }),
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
