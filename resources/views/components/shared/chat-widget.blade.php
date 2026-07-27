@php
    $chatEnabled = app(\App\Services\OpenRouterService::class)->isConfigured()
        && \App\Models\Setting::bool('chat_enabled', true);
@endphp
@if ($chatEnabled)
    <div id="ai-chat-widget">
        <button id="chat-toggle" type="button" aria-label="Open AI chat"
            class="w-14 h-14 rounded-full flex items-center justify-center text-white cursor-pointer border-0 shadow-glow-purple"
            style="background:linear-gradient(135deg,var(--accent-1),var(--accent-2));">
            <svg id="chat-toggle-icon-open" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <svg id="chat-toggle-icon-close" width="22" height="22" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8" class="hidden">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div id="chat-panel" class="hidden anime-card flex flex-col overflow-hidden"
            style="width:340px;max-width:90vw;height:480px;max-height:70vh;">
            <div data-fw-handle class="flex items-center justify-between px-4 py-3"
                style="cursor:move;border-bottom:1px solid var(--border-color);">
                <div>
                    <p class="font-semibold" style="color:var(--text-primary);font-size:0.9rem;">Ask me anything</p>
                    <p style="color:var(--text-secondary);font-size:0.7rem;">AI assistant · trained on this portfolio</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="chat-reset" type="button" title="Reset conversation" aria-label="Reset conversation"
                        style="background:none;border:none;cursor:pointer;color:var(--text-secondary);">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                    <button id="chat-close" type="button" aria-label="Close chat"
                        style="background:none;border:none;cursor:pointer;color:var(--text-secondary);">✕</button>
                </div>
            </div>

            <div id="chat-messages" class="flex-1 overflow-y-auto" style="padding:0.75rem;display:flex;flex-direction:column;gap:0.6rem;">
                <div class="chat-msg chat-msg-assistant">
                    Hi! Ask me about {{ portfolio_owner()?->name ?? config('portfolio.site_name') }}'s projects, skills,
                    or experience — or let me know if you'd like to get in touch.
                </div>
            </div>

            <form id="chat-form" class="flex items-center gap-2" style="padding:0.65rem;border-top:1px solid var(--border-color);">
                @csrf
                <input id="chat-input" type="text" placeholder="Type a message…" autocomplete="off" maxlength="1000"
                    class="anime-input" style="flex:1;padding:0.6rem 0.85rem;font-size:0.85rem;">
                <button type="submit" class="btn-anime" style="padding:0.6rem 1rem;font-size:0.85rem;" aria-label="Send message">→</button>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .chat-msg {
                max-width: 85%;
                padding: 0.55rem 0.8rem;
                border-radius: 0.9rem;
                font-size: 0.83rem;
                line-height: 1.5;
                white-space: pre-wrap;
            }

            .chat-msg-user {
                align-self: flex-end;
                background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
                color: #fff;
                border-bottom-right-radius: 0.25rem;
            }

            .chat-msg-assistant {
                align-self: flex-start;
                background: var(--bg-tertiary);
                border: 1px solid var(--border-color);
                color: var(--text-primary);
                border-bottom-left-radius: 0.25rem;
            }

            .chat-msg-typing {
                align-self: flex-start;
                color: var(--text-secondary);
                font-size: 0.8rem;
                font-style: italic;
            }
        </style>
    @endpush
@endif
