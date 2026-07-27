<div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden backdrop-blur-sm shadow-xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900/40">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Post Title</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Category</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Engagement</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Comments</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Date</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse($blogs as $blog)
                    <tr class="hover:bg-white/5 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">
                                        {{ Str::limit($blog->title, 45) }}
                                    </span>
                                    @if ($blog->is_featured)
                                        <span class="px-2 py-0.5 bg-amber-500/10 text-amber-500 border border-amber-500/20 rounded text-[9px] font-black uppercase tracking-tighter">
                                            Featured
                                        </span>
                                    @endif
                                </div>
                                <span class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $blog->read_time }} min read
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-slate-400 bg-slate-900/50 px-2 py-1 rounded-lg border border-slate-700">
                                {{ $blog->category ?: 'General' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusMap = [
                                    'published' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'draft' => 'bg-slate-700/50 text-slate-400 border-slate-600/30',
                                    'archived' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-md border text-[10px] font-bold uppercase tracking-tight {{ $statusMap[$blog->status] ?? $statusMap['draft'] }}">
                                {{ $blog->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-slate-300">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="text-sm font-semibold">{{ number_format($blog->view_count) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.blogs.comments', $blog->id) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-900/50 hover:bg-violet-500/10 hover:border-violet-500/30 border border-slate-700 rounded-lg text-xs no-underline transition-all">
                                <span class="text-violet-400">💬</span>
                                <span class="text-white font-semibold">{{ $blog->comments_count }}</span>
                                @if ($blog->comments_count !== $blog->approved_comments_count)
                                    <span class="text-rose-400 text-[10px]">
                                        ({{ $blog->comments_count - $blog->approved_comments_count }} hidden)
                                    </span>
                                @endif
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-500 whitespace-nowrap">
                                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                                <a href="{{ route('admin.blogs.show', $blog->id) }}"
                                    class="p-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                    class="p-2 bg-slate-700 hover:bg-indigo-600 text-white rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L20.172 4.172a2 2 0 010 2.828L13.414 13l-4 1 1-4 6.758-6.758z" />
                                    </svg>
                                </a>
                                <button onclick="confirmDelete('del-blog-{{ $blog->id }}')"
                                    class="p-2 bg-slate-700 hover:bg-rose-600 text-white rounded-lg transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <form id="del-blog-{{ $blog->id }}" method="POST"
                                action="{{ route('admin.blogs.destroy', $blog->id) }}" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="p-4 bg-slate-700/30 rounded-full text-slate-500">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-lg">No posts found</p>
                                    <p class="text-slate-500 text-sm">Start sharing your thoughts with the world.</p>
                                </div>
                                <a href="{{ route('admin.blogs.create') }}"
                                    class="text-indigo-400 hover:text-indigo-300 font-bold text-sm">Create your first post →</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($blogs->hasPages())
        <div class="px-6 py-4 bg-slate-900/40 border-t border-slate-700" id="blogs-pagination">
            {{ $blogs->links() }}
        </div>
    @endif
</div>
