<div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900/40">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Author</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Comment</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Visibility</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Date</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50" id="comments-tbody">
                @forelse($comments as $comment)
                    <tr class="hover:bg-white/5 transition-all" data-comment-id="{{ $comment->id }}">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white">{{ $comment->name }}</span>
                                <span class="text-[11px] text-slate-500">{{ $comment->email }}</span>
                                @if ($comment->parent_id)
                                    <span class="text-[10px] text-violet-400 mt-1">↳ Reply</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-md">
                            <p class="text-sm text-slate-300 leading-relaxed">{{ $comment->comment }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                    class="comment-visibility-toggle sr-only peer"
                                    data-blog-id="{{ $blog->id }}"
                                    data-comment-id="{{ $comment->id }}"
                                    {{ $comment->is_approved ? 'checked' : '' }}>
                                <div class="relative w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                    peer-checked:bg-emerald-500"></div>
                                <span class="ml-2 text-xs font-medium {{ $comment->is_approved ? 'text-emerald-400' : 'text-slate-500' }} visibility-label">
                                    {{ $comment->is_approved ? 'Visible' : 'Hidden' }}
                                </span>
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-500 whitespace-nowrap">
                                {{ $comment->created_at->format('M d, Y H:i') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <button class="comment-delete-btn p-2 bg-rose-500/10 hover:bg-rose-600 text-rose-500 hover:text-white rounded-lg transition-all"
                                    data-blog-id="{{ $blog->id }}"
                                    data-comment-id="{{ $comment->id }}"
                                    title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-500 text-sm">
                            No comments to review.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($comments->hasPages())
        <div class="px-6 py-4 bg-slate-900/40 border-t border-slate-700" id="comments-pagination">
            {{ $comments->links() }}
        </div>
    @endif
</div>
