@if ($paginator->hasPages())
    <nav style="display:flex;align-items:center;justify-content:center;gap:0.375rem;flex-wrap:wrap;">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span
                style="padding:0.5rem 0.875rem;border-radius:0.5rem;border:1px solid var(--border-color);color:var(--text-secondary);font-size:0.82rem;opacity:0.4;">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                style="padding:0.5rem 0.875rem;border-radius:0.5rem;border:1px solid var(--border-color);color:var(--text-secondary);font-size:0.82rem;text-decoration:none;transition:all 0.2s;"
                onmouseover="this.style.borderColor='var(--accent-1)';this.style.color='var(--accent-1)'"
                onmouseout="this.style.borderColor='var(--border-color)';this.style.color='var(--text-secondary)'">←</a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span
                    style="padding:0.5rem 0.75rem;color:var(--text-secondary);font-size:0.82rem;">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            style="padding:0.5rem 0.875rem;border-radius:0.5rem;background:rgba(139,92,246,0.2);border:1px solid var(--accent-1);color:#a78bfa;font-size:0.82rem;font-weight:600;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            style="padding:0.5rem 0.875rem;border-radius:0.5rem;border:1px solid var(--border-color);color:var(--text-secondary);font-size:0.82rem;text-decoration:none;transition:all 0.2s;"
                            onmouseover="this.style.borderColor='var(--accent-1)';this.style.color='var(--accent-1)'"
                            onmouseout="this.style.borderColor='var(--border-color)';this.style.color='var(--text-secondary)'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                style="padding:0.5rem 0.875rem;border-radius:0.5rem;border:1px solid var(--border-color);color:var(--text-secondary);font-size:0.82rem;text-decoration:none;transition:all 0.2s;"
                onmouseover="this.style.borderColor='var(--accent-1)';this.style.color='var(--accent-1)'"
                onmouseout="this.style.borderColor='var(--border-color)';this.style.color='var(--text-secondary)'">→</a>
        @else
            <span
                style="padding:0.5rem 0.875rem;border-radius:0.5rem;border:1px solid var(--border-color);color:var(--text-secondary);font-size:0.82rem;opacity:0.4;">→</span>
        @endif
    </nav>
@endif
