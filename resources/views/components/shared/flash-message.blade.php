@if (session('success'))
    <div class="flash-msg flash-success" data-flash>
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2" style="flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()"
            style="background:none;border:none;cursor:pointer;color:inherit;margin-left:auto;opacity:0.7;">✕</button>
    </div>
@endif
@if (session('error'))
    <div class="flash-msg flash-error" data-flash style="bottom:5.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2" style="flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()"
            style="background:none;border:none;cursor:pointer;color:inherit;margin-left:auto;opacity:0.7;">✕</button>
    </div>
@endif

<style>
    .flash-msg {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1.25rem;
        border-radius: 0.875rem;
        max-width: 380px;
        font-size: 0.875rem;
        font-weight: 500;
        backdrop-filter: blur(12px);
        animation: slideUp 0.4s ease;
    }

    .flash-success {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #4ade80;
    }

    .flash-error {
        background: rgba(244, 63, 94, 0.15);
        border: 1px solid rgba(244, 63, 94, 0.3);
        color: #fb7185;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<script>
    setTimeout(() => {
        document.querySelectorAll('[data-flash]').forEach(el => {
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 5000);
</script>
