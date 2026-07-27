<div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden backdrop-blur-sm shadow-xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900/40">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Asset</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Project Details</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Classification</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Visibility</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Featured</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Stats</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse($projects as $project)
                    <tr class="hover:bg-white/5 transition-all group">
                        <td class="px-6 py-4">
                            <div class="relative w-12 h-12 rounded-lg overflow-hidden border border-slate-600 group-hover:border-indigo-500 transition-colors shadow-inner bg-slate-900">
                                <img src="{{ asset('/' . $project->thumbnail) }}" alt="" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">
                                    {{ Str::limit($project->title, 35) }}
                                </span>
                                <span class="text-[10px] text-slate-500 font-mono mt-0.5">/{{ $project->slug }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $catColors = [
                                    'frontend' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                                    'backend' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'fullstack' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-md border text-[9px] font-black uppercase tracking-wider {{ $catColors[$project->category] ?? 'bg-slate-700 text-slate-300 border-slate-600' }}">
                                {{ $project->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'published' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'draft' => 'bg-slate-700/50 text-slate-400 border-slate-600/30',
                                    'archived' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full border text-[10px] font-bold uppercase {{ $statusColors[$project->status] ?? $statusColors['draft'] }}">
                                {{ $project->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="{{ route('admin.projects.toggle-featured', $project->id) }}">
                                @csrf
                                <button type="submit" class="transition-transform active:scale-125 focus:outline-none">
                                    @if ($project->is_featured)
                                        <span class="text-amber-400 text-xl drop-shadow-[0_0_8px_rgba(251,191,36,0.4)]">★</span>
                                    @else
                                        <span class="text-slate-600 hover:text-slate-400 text-xl transition-colors text-opacity-50">☆</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-300 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ number_format($project->view_count) }}
                                </span>
                                <span class="text-[10px] text-slate-500 mt-1 uppercase font-bold tracking-tighter">
                                    {{ $project->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.projects.show', $project->id) }}"
                                    class="p-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 rounded-lg transition-all" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.projects.edit', $project->id) }}"
                                    class="p-2 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white rounded-lg transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L20.172 4.172a2 2 0 010 2.828L13.414 13l-4 1 1-4 6.758-6.758z" />
                                    </svg>
                                </a>
                                <button onclick="confirmDelete('del-proj-{{ $project->id }}')"
                                    class="p-2 bg-rose-500/10 hover:bg-rose-600 text-rose-500 hover:text-white rounded-lg transition-all" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <form id="del-proj-{{ $project->id }}" method="POST"
                                action="{{ route('admin.projects.destroy', $project->id) }}" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 bg-slate-700/30 rounded-2xl flex items-center justify-center text-slate-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-white font-bold">No projects archived yet.</p>
                                <a href="{{ route('admin.projects.create') }}"
                                    class="text-indigo-400 hover:text-indigo-300 font-bold text-sm">Add your first project →</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($projects->hasPages())
        <div class="px-6 py-4 bg-slate-900/40 border-t border-slate-700" id="projects-pagination">
            {{ $projects->links() }}
        </div>
    @endif
</div>
