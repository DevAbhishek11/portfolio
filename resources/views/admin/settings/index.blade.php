@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Site Settings</h1>
            <p class="text-slate-400 text-sm mt-1">Feature toggles and site-wide controls that don't need a
                redeploy.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- AI Chat --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">AI Live Chat</h2>
                <span
                    class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full {{ $openRouterConfigured ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-700 text-slate-400' }}">
                    {{ $openRouterConfigured ? 'API key configured' : 'No API key' }}
                </span>
            </div>

            @unless ($openRouterConfigured)
                <p class="text-xs text-amber-400 bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 mb-4">
                    Add <code class="font-mono">OPENROUTER_API_KEY</code> to your <code class="font-mono">.env</code>
                    to turn the chat widget on. Free account: openrouter.ai
                </p>
            @endunless

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                @csrf @method('PUT')
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="chat_enabled" value="1" @checked($settings['chat_enabled'])
                        class="w-4 h-4 rounded accent-indigo-500">
                    <span class="text-sm text-slate-300">Show the AI chat widget on the site</span>
                </label>
                <button type="submit"
                    class="py-2.5 px-5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition-all">
                    Save
                </button>
            </form>
        </div>

        {{-- Profile stats — shown on the Services page. Left blank until you
             fill them in, rather than shipping placeholder numbers. --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
            <h2 class="text-lg font-semibold text-white mb-1">Profile Stats</h2>
            <p class="text-xs text-slate-500 mb-4">Shown on the Services page. "Projects Completed", "Technologies",
                and "Articles Written" are calculated automatically from your real data — these two are the only
                ones that need your input, and stay hidden until you set them.</p>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                @csrf @method('PUT')
                <input type="hidden" name="chat_enabled" value="{{ $settings['chat_enabled'] ? '1' : '0' }}">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Years of Experience</label>
                        <input type="number" name="years_experience" min="0" max="80"
                            value="{{ old('years_experience', $settings['years_experience']) }}"
                            placeholder="e.g. 5"
                            class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-3 py-2 outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Clients Served</label>
                        <input type="number" name="client_count" min="0" max="100000"
                            value="{{ old('client_count', $settings['client_count']) }}" placeholder="e.g. 30"
                            class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-3 py-2 outline-none focus:border-indigo-500">
                    </div>
                </div>
                <button type="submit"
                    class="py-2.5 px-5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition-all">
                    Save
                </button>
            </form>
        </div>

        {{-- Career timeline — shown on the About page. Empty by default. --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm lg:col-span-2">
            <h2 class="text-lg font-semibold text-white mb-1">Career Timeline</h2>
            <p class="text-xs text-slate-500 mb-4">Shown on the About page under "Experience & Education". Hidden
                entirely until you add at least one entry.</p>

            <form method="POST" action="{{ route('admin.settings.timeline') }}" class="space-y-4">
                @csrf @method('PUT')
                <div id="timeline-rows" class="space-y-3"></div>
                <button type="button" id="addTimelineBtn"
                    class="py-2 px-4 border border-indigo-500/50 text-indigo-400 hover:bg-indigo-500 hover:text-white text-xs font-bold rounded-xl transition-all">
                    + Add Entry
                </button>
                <div>
                    <button type="submit"
                        class="py-2.5 px-5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition-all">
                        Save Timeline
                    </button>
                </div>
            </form>
        </div>

        {{-- Maintenance mode --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
            <h2 class="text-lg font-semibold text-white mb-4">Maintenance Mode</h2>

            @if (blank($maintenanceToken))
                <p class="text-xs text-amber-400 bg-amber-500/10 border border-amber-500/20 rounded-xl p-3">
                    Set <code class="font-mono">MAINTENANCE_TOKEN</code> in <code class="font-mono">.env</code> to
                    enable these controls.
                </p>
            @else
                <p class="text-xs text-slate-400 mb-4">Puts the whole site into maintenance mode. You'll need the
                    server maintenance token to bring it back up.</p>
                <div class="flex gap-3">
                    <a href="{{ url('/server/down/' . $maintenanceToken) }}"
                        class="py-2.5 px-5 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white text-sm font-bold rounded-xl transition-all">
                        Enable Maintenance Mode
                    </a>
                    <a href="{{ url('/server/up/' . $maintenanceToken) }}"
                        class="py-2.5 px-5 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white text-sm font-bold rounded-xl transition-all">
                        Bring Site Back Up
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const existing = @json($timeline);

                function addTimelineRow(entry) {
                    entry = entry || {};
                    const container = document.getElementById('timeline-rows');
                    const row = document.createElement('div');
                    row.className =
                        'flex flex-wrap md:flex-nowrap gap-3 items-start bg-slate-900/40 p-3 rounded-xl border border-slate-700/50';
                    row.innerHTML =
                        '<input type="text" name="timeline_year[]" placeholder="Year (e.g. 2024)" value="' +
                        (entry.year || '') +
                        '" class="w-24 bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-xs outline-none">' +
                        '<input type="text" name="timeline_title[]" placeholder="Title (e.g. Senior Developer)" value="' +
                        (entry.title || '') +
                        '" class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-xs outline-none">' +
                        '<input type="text" name="timeline_place[]" placeholder="Place (e.g. Company / Remote)" value="' +
                        (entry.place || '') +
                        '" class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-xs outline-none">' +
                        '<input type="text" name="timeline_desc[]" placeholder="Short description" value="' +
                        (entry.desc || '') +
                        '" class="flex-[2] bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-xs outline-none">' +
                        '<button type="button" class="remove-timeline p-2 text-rose-500 hover:bg-rose-500/10 rounded-lg transition-colors">' +
                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>' +
                        '</button>';

                    row.querySelector('.remove-timeline').addEventListener('click', function() {
                        row.remove();
                    });

                    container.appendChild(row);
                }

                document.getElementById('addTimelineBtn').addEventListener('click', function() {
                    addTimelineRow();
                });

                if (existing.length) {
                    existing.forEach(addTimelineRow);
                } else {
                    addTimelineRow();
                }
            });
        </script>
    @endpush
@endsection
