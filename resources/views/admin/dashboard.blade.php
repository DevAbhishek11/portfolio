@extends('layouts.admin')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
    {{-- Using Chart.js for data visualization --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .stat-card {
            @apply bg-zinc-900/50 border border-zinc-800 p-5 rounded-2xl hover:border-zinc-700 transition-all duration-300;
        }

        .admin-table th {
            @apply text-[10px] uppercase tracking-wider text-zinc-500 font-bold px-4 py-3 border-b border-zinc-800 bg-zinc-950/50;
        }

        .admin-table td {
            @apply px-4 py-3 text-sm border-b border-zinc-800/50;
        }
    </style>
@endpush

@section('content')
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard Overview</h1>
            <p class="text-zinc-400 text-sm">Welcome back, <span
                    class="text-violet-400 font-medium">{{ $adminUser->name }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.projects.create') }}"
                class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold rounded-lg shadow-lg shadow-violet-600/20 transition-all">
                + New Project
            </a>
            <a href="{{ route('admin.blogs.create') }}"
                class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-bold rounded-lg border border-zinc-700 transition-all">
                + New Post
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $statCards = [
                [
                    'label' => 'Page Views Today',
                    'value' => number_format($stats['page_views']['today']),
                    'sub' => 'Month: ' . number_format($stats['page_views']['this_month']),
                    'color' => '#8b5cf6',
                    'icon' =>
                        'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                ],
                [
                    'label' => 'Total Projects',
                    'value' => $stats['projects']['total'],
                    'sub' => $stats['projects']['published'] . ' published',
                    'color' => '#06b6d4',
                    'icon' =>
                        'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                ],
                [
                    'label' => 'Blog Posts',
                    'value' => $stats['blogs']['total'],
                    'sub' => $stats['blogs']['published'] . ' published',
                    'color' => '#f43f5e',
                    'icon' =>
                        'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                ],
                [
                    'label' => 'Unread Messages',
                    'value' => $stats['contacts']['unread'],
                    'sub' => $stats['contacts']['total'] . ' total queries',
                    'color' => '#eab308',
                    'icon' =>
                        'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                ],
            ];
        @endphp

        @foreach ($statCards as $card)
            <div class="stat-card group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110"
                        style="background: {{ $card['color'] }}15;">
                        <svg class="w-6 h-6" fill="none" stroke="{{ $card['color'] }}" viewBox="0 0 24 24"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-bold px-2 py-1 rounded bg-zinc-950 text-zinc-500 uppercase tracking-tighter">Live</span>
                </div>
                <h3 class="text-2xl font-bold text-white tracking-tight">{{ $card['value'] }}</h3>
                <p class="text-xs text-zinc-500 font-medium mb-2">{{ $card['label'] }}</p>
                <div class="h-1 w-full bg-zinc-800 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-1000"
                        style="background:{{ $card['color'] }}; width: 65%;"></div>
                </div>
                <p class="text-[10px] mt-2 font-bold uppercase tracking-wider" style="color:{{ $card['color'] }};">
                    {{ $card['sub'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Main Traffic Chart --}}
        <div class="lg:col-span-2 bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-sm font-bold text-white uppercase tracking-widest">Traffic Analytics</h2>
                    <p class="text-[10px] text-zinc-500 uppercase font-bold mt-1">Website engagement over time</p>
                </div>
                <div class="flex bg-zinc-950 p-1 rounded-lg border border-zinc-800">
                    @foreach ([7, 14, 30] as $d)
                        <button onclick="loadChart({{ $d }})" id="chart-btn-{{ $d }}"
                            class="px-3 py-1 rounded-md text-[10px] font-bold transition-all {{ $d === 30 ? 'bg-violet-600 text-white' : 'text-zinc-500 hover:text-zinc-300' }}">
                            {{ $d }}D
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>

        {{-- Device Distribution --}}
        <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6">
            <h2 class="text-sm font-bold text-white uppercase tracking-widest mb-1">Device Usage</h2>
            <p class="text-[10px] text-zinc-500 uppercase font-bold mb-6">Last 30 days breakdown</p>

            <div class="relative flex justify-center items-center mb-8">
                <canvas id="deviceChart"></canvas>
                <div class="absolute text-center pointer-events-none">
                    <p class="text-2xl font-bold text-white">100%</p>
                    <p class="text-[10px] text-zinc-500 uppercase font-bold">Total</p>
                </div>
            </div>

            <div class="space-y-3">
                @php
                    $colors = ['#8b5cf6', '#06b6d4', '#f43f5e'];
                    $i = 0;
                @endphp
                @foreach ($stats['devices'] as $device => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full" style="background: {{ $colors[$i++] }}"></div>
                            <span class="text-xs text-zinc-400 capitalize">{{ $device }}</span>
                        </div>
                        <span class="text-xs font-bold text-white">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Contacts Table --}}
        <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/20">
                <h2 class="text-xs font-bold text-white uppercase tracking-widest">Recent Inquiries</h2>
                <a href="{{ route('admin.contacts.index') }}"
                    class="text-[10px] font-bold text-violet-400 uppercase hover:text-violet-300">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left admin-table">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentContacts as $contact)
                            <tr class="hover:bg-zinc-800/30 transition-colors">
                                <td class="font-medium text-zinc-200">
                                    <div class="flex flex-col">
                                        <span>{{ Str::limit($contact->name, 20) }}</span>
                                        <span class="text-[10px] text-zinc-500 font-normal">{{ $contact->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $contact->status === 'unread' ? 'bg-rose-500/10 text-rose-500' : 'bg-emerald-500/10 text-emerald-500' }}">
                                        {{ $contact->status }}
                                    </span>
                                </td>
                                <td class="text-zinc-500 text-[11px]">{{ $contact->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-12 text-zinc-600">No recent messages</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Projects --}}
        <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/20">
                <h2 class="text-xs font-bold text-white uppercase tracking-widest">Popular Projects</h2>
                <a href="{{ route('admin.projects.index') }}"
                    class="text-[10px] font-bold text-violet-400 uppercase hover:text-violet-300">Manage →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left admin-table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Views</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['top_projects'] as $project)
                            <tr class="hover:bg-zinc-800/30 transition-colors">
                                <td class="text-zinc-200 font-medium">{{ Str::limit($project->title, 25) }}</td>
                                <td class="text-zinc-400 text-xs font-mono">{{ number_format($project->view_count) }}</td>
                                <td>
                                    {{-- <span
                                        class="w-2 h-2 rounded-full inline-block {{ $project->is_published ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-zinc-600' }}"></span> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-12 text-zinc-600">No projects found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const viewsData = @json($stats['views_chart']);
            const devicesData = @json($stats['devices']);
            let viewsChart;

            function buildViewsChart(data) {
                const ctx = document.getElementById('viewsChart').getContext('2d');
                if (viewsChart) viewsChart.destroy();

                viewsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(data).map(d => new Date(d).toLocaleDateString('en', {
                            month: 'short',
                            day: 'numeric'
                        })),
                        datasets: [{
                            data: Object.values(data),
                            borderColor: '#8b5cf6',
                            backgroundColor: (context) => {
                                const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                                gradient.addColorStop(0, 'rgba(139, 92, 246, 0.2)');
                                gradient.addColorStop(1, 'rgba(139, 92, 246, 0)');
                                return gradient;
                            },
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHitRadius: 30,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#52525b',
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: '#18181b'
                                },
                                ticks: {
                                    color: '#52525b',
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function loadChart(days) {
                [7, 14, 30].forEach(d => {
                    const btn = document.getElementById('chart-btn-' + d);
                    if (d === days) {
                        btn.classList.add('bg-violet-600', 'text-white');
                        btn.classList.remove('text-zinc-500');
                    } else {
                        btn.classList.remove('bg-violet-600', 'text-white');
                        btn.classList.add('text-zinc-500');
                    }
                });

                fetch(`{{ route('admin.dashboard.stats') }}?days=${days}`)
                    .then(r => r.json())
                    .then(json => buildViewsChart(json.views_chart));
            }

            buildViewsChart(viewsData);

            // Devices Doughnut
            new Chart(document.getElementById('deviceChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(devicesData),
                    datasets: [{
                        data: Object.values(devicesData),
                        backgroundColor: ['#8b5cf6', '#06b6d4', '#f43f5e'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    cutout: '80%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
