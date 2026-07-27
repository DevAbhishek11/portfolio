@props(['videoPath', 'title' => 'Demo video'])

<div id="video-mini-player" data-video-src="{{ asset('/' . $videoPath) }}" data-video-title="{{ $title }}">
    <button id="video-toggle" type="button" aria-label="Open video player"
        class="w-14 h-14 rounded-full flex items-center justify-center text-white cursor-pointer border-0 shadow-glow-cyan"
        style="background:linear-gradient(135deg,var(--accent-2),var(--accent-1));">
        <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
            <path d="M8 5v14l11-7z" />
        </svg>
    </button>

    <div id="video-panel" class="hidden anime-card overflow-hidden" style="width:360px;max-width:90vw;">
        <div data-fw-handle class="flex items-center justify-between px-3 py-2"
            style="cursor:move;border-bottom:1px solid var(--border-color);">
            <span style="color:var(--text-primary);font-size:0.8rem;font-weight:600;">{{ $title }}</span>
            <button id="video-close" type="button" aria-label="Close video"
                style="background:none;border:none;cursor:pointer;color:var(--text-secondary);">✕</button>
        </div>
        <video id="video-player-el" controls playsinline style="width:100%;display:block;aspect-ratio:16/9;background:#000;">
            <source src="{{ asset('/' . $videoPath) }}" type="video/mp4">
            Your browser doesn't support embedded video.
        </video>
    </div>
</div>
