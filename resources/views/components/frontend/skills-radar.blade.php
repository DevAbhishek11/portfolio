@props(['skills', 'grouped'])

<div class="w-full">
    {{-- Category filter tabs --}}
    <div class="flex flex-wrap gap-2 justify-center mb-10" id="skillTabs">
        <button onclick="filterSkills('all')"
            class="skill-tab px-4 py-1.5 rounded-full text-sm font-medium border transition-all duration-200 border-accent-1 bg-accent-1/20 text-violet-400"
            data-cat="all">
            All
        </button>
        @foreach ($grouped as $category => $items)
            <button onclick="filterSkills('{{ Str::slug($category) }}')"
                class="skill-tab px-4 py-1.5 rounded-full text-sm font-medium border transition-all duration-200 border-admin-border text-txt-secondary hover:border-accent-1/40 hover:text-accent-1"
                data-cat="{{ Str::slug($category) }}">
                {{ $category }}
            </button>
        @endforeach
    </div>

    {{-- Radar + bar grid layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

        {{-- Radar chart --}}
        <div class="reveal">
            <div class="relative w-full max-w-sm mx-auto aspect-square">
                {{-- Glow backdrop --}}
                <div class="absolute inset-0 rounded-full"
                    style="background:radial-gradient(circle,rgba(139,92,246,0.08),transparent 70%);"></div>
                <canvas id="skillsRadar"></canvas>
            </div>
            <p class="text-center text-xs text-txt-secondary mt-4 opacity-60">
                Hover a point for details · Showing top featured skills
            </p>
        </div>

        {{-- Skill bars by category --}}
        <div id="skillBarsWrap" class="space-y-6 reveal delay-2">
            @foreach ($grouped as $category => $items)
                <div class="skill-category-group" data-cat="{{ Str::slug($category) }}">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-txt-secondary mb-3">
                        {{ $category }}
                    </h3>
                    <div class="space-y-3">
                        @foreach ($items as $skill)
                            <div class="skill-bar-item">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-sm font-medium text-txt-primary">{{ $skill['name'] }}</span>
                                    <span class="text-xs font-mono text-txt-secondary">{{ $skill['level'] }}%</span>
                                </div>
                                <div class="h-1.5 bg-bg-secondary rounded-full overflow-hidden">
                                    <div class="h-full rounded-full skill-fill transition-all duration-1000"
                                        style="width:0%;background:{{ $skill['color'] }};"
                                        data-target="{{ $skill['level'] }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // ── Radar data from server ────────────────────────────────────────────────
        const radarSkills = @json($skills->where('is_featured', true)->take(8)->values());

        let radarChart = null;

        function buildRadar(data) {
            const ctx = document.getElementById('skillsRadar').getContext('2d');
            if (radarChart) radarChart.destroy();

            radarChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: data.map(s => s.name),
                    datasets: [{
                        label: 'Proficiency',
                        data: data.map(s => s.level),
                        backgroundColor: 'rgba(139,92,246,0.12)',
                        borderColor: '#8b5cf6',
                        borderWidth: 2.5,
                        pointBackgroundColor: data.map(s => s.color),
                        pointBorderColor: '#0a0a0f',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        pointHoverBackgroundColor: data.map(s => s.color),
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutCubic',
                    },
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            ticks: {
                                stepSize: 25,
                                display: false,
                                backdropColor: 'transparent',
                            },
                            grid: {
                                color: 'rgba(139,92,246,0.1)',
                                lineWidth: 1,
                            },
                            angleLines: {
                                color: 'rgba(139,92,246,0.15)',
                                lineWidth: 1,
                            },
                            pointLabels: {
                                color: '#a1a1aa',
                                font: {
                                    size: 11,
                                    family: 'Inter',
                                    weight: '500'
                                },
                                padding: 12,
                            },
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1a1a2e',
                            borderColor: 'rgba(139,92,246,0.3)',
                            borderWidth: 1,
                            titleColor: '#e4e4e7',
                            bodyColor: '#a1a1aa',
                            padding: 10,
                            callbacks: {
                                label: ctx => ` ${ctx.raw}% proficiency`,
                            }
                        }
                    }
                }
            });
        }

        // Initial build
        buildRadar(radarSkills);

        // ── Skill bar animation on scroll ─────────────────────────────────────────
        const barObserver = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.querySelectorAll('.skill-fill').forEach(fill => {
                        setTimeout(() => {
                            fill.style.width = fill.dataset.target + '%';
                        }, 100);
                    });
                    barObserver.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.2
        });

        document.querySelectorAll('.skill-category-group').forEach(g => barObserver.observe(g));

        // ── Category filter ───────────────────────────────────────────────────────
        function filterSkills(cat) {
            // Update tab styles
            document.querySelectorAll('.skill-tab').forEach(t => {
                const isActive = t.dataset.cat === cat;
                t.className = t.className
                    .replace(/border-accent-1 bg-accent-1\/20 text-violet-400/g, '')
                    .replace(
                        /border-admin-border text-txt-secondary hover:border-accent-1\/40 hover:text-accent-1/g, ''
                        );
                t.className += isActive ?
                    ' border-accent-1 bg-accent-1/20 text-violet-400' :
                    ' border-admin-border text-txt-secondary hover:border-accent-1/40 hover:text-accent-1';
            });

            // Show/hide bar groups
            document.querySelectorAll('.skill-category-group').forEach(g => {
                const show = cat === 'all' || g.dataset.cat === cat;
                g.style.display = show ? 'block' : 'none';
            });

            // Update radar with filtered or all featured skills
            if (cat === 'all') {
                buildRadar(radarSkills);
            } else {
                const allSkills = @json($skills->values());
                const filtered = allSkills.filter(s => s.name.toLowerCase().includes(cat) ||
                    '{{ collect($grouped)->keys()->map(fn($k) => Str::slug($k))->implode(',') }}'.includes(cat));
                // Filter by category slug match
                const catName = document.querySelector(`[data-cat="${cat}"]`)?.textContent?.trim();
                const catFiltered = allSkills.filter(s => s.category === catName).slice(0, 8);
                if (catFiltered.length >= 3) buildRadar(catFiltered);
            }
        }
    </script>
@endpush
