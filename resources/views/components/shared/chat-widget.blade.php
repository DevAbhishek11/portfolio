@php
    $chatEnabled = app(\App\Services\OpenRouterService::class)->isConfigured()
        && \App\Models\Setting::bool('chat_enabled', true);
@endphp
@if ($chatEnabled)
    <div id="ai-chat-widget" style="display:flex;flex-direction:column-reverse;align-items:flex-end;gap:0.85rem;">
        <button id="chat-toggle" type="button" aria-label="Open AI chat" aria-expanded="false"
            class="relative w-16 h-16 rounded-full flex items-center justify-center text-white cursor-pointer border-0 shadow-glow-purple"
            style="background:linear-gradient(135deg,var(--accent-1),var(--accent-2));">
            <span class="absolute inset-0 rounded-full animate-ping"
                style="background:linear-gradient(135deg,var(--accent-1),var(--accent-2));opacity:0.35;"></span>
            <svg class="relative" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.7">
                <rect x="4" y="8" width="16" height="12" rx="3" />
                <path stroke-linecap="round" d="M12 8V4" />
                <circle cx="12" cy="3" r="1.1" fill="currentColor" stroke="none" />
                <circle cx="9" cy="13.5" r="1.3" fill="currentColor" stroke="none" />
                <circle cx="15" cy="13.5" r="1.3" fill="currentColor" stroke="none" />
                <path stroke-linecap="round" d="M9 17.5h6" />
                <path stroke-linecap="round" d="M2 12h2M20 12h2" />
            </svg>
            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#22c55e] border-2"
                style="border-color:var(--bg-primary);"></span>
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

            <div id="chat-messages" class="flex-1 overflow-y-auto" style="padding:0.75rem;display:flex;flex-direction:column;gap:0.7rem;">
                <div class="chat-row chat-row-assistant">
                    <div class="chat-avatar">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="4" y="8" width="16" height="12" rx="3" />
                            <path stroke-linecap="round" d="M12 8V4" />
                            <circle cx="12" cy="3" r="1.1" fill="currentColor" stroke="none" />
                            <circle cx="9" cy="13.5" r="1.3" fill="currentColor" stroke="none" />
                            <circle cx="15" cy="13.5" r="1.3" fill="currentColor" stroke="none" />
                            <path stroke-linecap="round" d="M9 17.5h6" />
                        </svg>
                    </div>
                    <div class="chat-msg chat-msg-assistant">
                        Hi! Ask me about {{ portfolio_owner()?->name ?? config('portfolio.site_name') }}'s projects, skills,
                        or experience — or let me know if you'd like to get in touch.
                    </div>
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

    <style>
            .chat-row {
                display: flex;
                align-items: flex-end;
                gap: 0.45rem;
                max-width: 92%;
            }

            .chat-row-user {
                align-self: flex-end;
                flex-direction: row-reverse;
            }

            .chat-row-assistant {
                align-self: flex-start;
            }

            .chat-avatar {
                flex-shrink: 0;
                width: 24px;
                height: 24px;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            }

            .chat-msg {
                padding: 0.55rem 0.8rem;
                border-radius: 0.9rem;
                font-size: 0.83rem;
                line-height: 1.5;
                white-space: pre-wrap;
            }

            .chat-msg-user {
                background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
                color: #fff;
                font-weight: 500;
                border-bottom-right-radius: 0.25rem;
            }

            .chat-msg-assistant {
                background: var(--bg-tertiary);
                border: 1px solid var(--border-color);
                color: var(--text-primary);
                border-bottom-left-radius: 0.25rem;
            }

            .chat-msg-assistant ul {
                list-style: disc;
                margin: 0.35rem 0 0;
                padding-left: 1.1rem;
            }

            .chat-msg-assistant li {
                margin-bottom: 0.2rem;
            }

            .chat-msg-assistant li::marker {
                color: var(--accent-1);
            }

            .chat-msg-typing {
                align-self: flex-start;
                color: var(--text-secondary);
                font-size: 0.8rem;
                font-style: italic;
                padding-left: 1.85rem;
            }
        </style>
@endif
