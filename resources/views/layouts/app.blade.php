<!DOCTYPE html>
<html lang="en" class="dark" id="html-root">

<head>
    <script>
        (function() {
            var t = localStorage.getItem('theme') || 'dark';
            document.documentElement.classList.remove('light', 'dark');
            document.documentElement.classList.add(t === 'light' ? 'light' : 'dark');
        })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">

    <x-shared.meta-tags :title="$metaTitle ?? config('portfolio.site_name')" :description="$metaDesc ?? config('portfolio.meta.description')" :ogImage="$ogImage ?? config('portfolio.meta.og_image')" />

    <x-shared.json-ld type="{{ $jsonLdType ?? 'website' }}" :data="$jsonLdData ?? []" />

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    @stack('styles')
</head>

<body class="grid-bg">
    {{-- Reading progress --}}
    <div id="read-progress"></div>

    {{-- Preloader --}}
    <div id="preloader">
        <div>
            <h1 class="font-display grad-text" style="font-size:2rem;font-weight:800;">
                {{ config('portfolio.site_name') }}
            </h1>
            <p
                style="color:var(--text-secondary);font-size:0.8rem;text-align:center;margin-top:0.25rem;letter-spacing:0.1em;">
                <span id="loader-msg">読み込み中…</span>
            </p>
        </div>
        <div class="loader-bar">
            <div class="loader-fill" id="loader-fill"></div>
        </div>
        <p id="loader-pct" style="color:var(--accent-1);font-size:0.75rem;font-weight:600;">0%</p>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu">
        <button id="close-menu" type="button" aria-label="Close navigation menu"
            style="position:absolute;top:1.5rem;right:1.5rem;background:none;border:none;cursor:pointer;color:var(--text-secondary);">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        @foreach ([['home', 'Home'], ['about', 'About'], ['projects.index', 'Projects'], ['services', 'Services'], ['blogs.index', 'Blog'], ['contact', 'Contact']] as [$r, $l])
            <a href="{{ route($r) }}" class="font-display mobile-nav-link"
                style="font-size:2.5rem;color:var(--text-primary);text-decoration:none;margin:0.5rem 0;opacity:0;transform:translateY(20px);transition:all 0.3s ease;">
                {{ $l }}
            </a>
        @endforeach
    </div>

    {{-- Navbar --}}
    <nav id="navbar">
        <div class="container navbar-inner">
            <a href="{{ route('home') }}" style="text-decoration:none;">
                <img src="https://devabhi.site/storage/profile/Logo.png" alt="Site Logo" class="h-[48px]" />
            </a>

            {{-- Desktop nav --}}
            <div class="desktop-nav">
                @foreach ([['home', 'Home'], ['about', 'About'], ['projects.index', 'Projects'], ['services', 'Services'], ['blogs.index', 'Blog'], ['contact', 'Contact']] as [$r, $l])
                    <a href="{{ route($r) }}"
                        class="nav-link {{ request()->routeIs($r) || ($r === 'projects.index' && request()->routeIs('projects.*')) || ($r === 'blogs.index' && request()->routeIs('blogs.*')) ? 'active' : '' }}">
                        {{ $l }}
                    </a>
                @endforeach
            </div>

            <div class="navbar-actions">
                {{-- Theme toggle --}}
                <button id="theme-toggle" type="button" aria-label="Toggle dark and light theme"
                    style="background:none;border:1px solid var(--border-color);padding:0.5rem;border-radius:0.5rem;cursor:pointer;color:var(--text-secondary);line-height:0;">
                    <svg id="sun-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg id="moon-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                {{-- Mobile hamburger --}}
                <button id="open-menu" type="button" class="mobile-menu-toggle" aria-label="Open navigation menu"
                    aria-expanded="false" aria-controls="mobile-menu"
                    style="background:none;border:1px solid var(--border-color);padding:0.5rem;border-radius:0.5rem;cursor:pointer;color:var(--text-secondary);line-height:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    <x-shared.flash-message />

    {{-- Main content --}}
    <div>
        <x-shared.skip-link />

        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer id="site-footer">
            <div class="container" style="padding-top:3rem;padding-bottom:3rem;">
                <div
                    style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:2rem;margin-bottom:3rem;">
                    <div>
                        <h3 class="font-display grad-text"
                            style="font-size:1.4rem;font-weight:800;margin-bottom:0.75rem;">
                            {{ config('portfolio.site_name') }}
                        </h3>
                        <p style="color:var(--text-secondary);font-size:0.875rem;line-height:1.7;max-width:260px;">
                            {{ config('portfolio.meta.description') }}
                        </p>

                        <div style="display:flex;gap:0.75rem;margin-top:1rem;">
                            @if (config('portfolio.social.github'))
                                <a href="{{ config('portfolio.social.github') }}" target="_blank"
                                    class="social-icon">GH</a>
                            @endif

                            @if (config('portfolio.social.linkedin'))
                                <a href="{{ config('portfolio.social.linkedin') }}" target="_blank"
                                    class="social-icon">in</a>
                            @endif

                            @if (config('portfolio.social.twitter'))
                                <a href="{{ config('portfolio.social.twitter') }}" target="_blank"
                                    class="social-icon">X</a>
                            @endif

                            @if (portfolio_owner()?->upwork_url)
                                <a href="{{ portfolio_owner()->upwork_url }}" target="_blank" rel="noopener"
                                    class="social-icon" title="Hire me on Upwork">Up</a>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4
                            style="color:var(--text-primary);font-size:0.875rem;font-weight:600;margin-bottom:1rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Navigation
                        </h4>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            @foreach ([['home', 'Home'], ['about', 'About'], ['projects.index', 'Projects'], ['services', 'Services'], ['blogs.index', 'Blog'], ['contact', 'Contact']] as [$r, $l])
                                <a href="{{ route($r) }}"
                                    style="color:var(--text-secondary);text-decoration:none;font-size:0.875rem;transition:color 0.2s;"
                                    onmouseover="this.style.color='var(--accent-1)'"
                                    onmouseout="this.style.color='var(--text-secondary)'">
                                    {{ $l }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h4
                            style="color:var(--text-primary);font-size:0.875rem;font-weight:600;margin-bottom:1rem;text-transform:uppercase;letter-spacing:0.05em;">
                            Contact
                        </h4>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            @if (config('portfolio.site_email'))
                                <span style="color:var(--text-secondary);font-size:0.875rem;">
                                    📧 {{ config('portfolio.site_email') }}
                                </span>
                            @endif

                            @if (config('portfolio.site_location'))
                                <span style="color:var(--text-secondary);font-size:0.875rem;">
                                    📍 {{ config('portfolio.site_location') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div
                    style="border-top:1px solid var(--border-color);padding-top:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                    <p style="color:var(--text-secondary);font-size:0.8rem;">
                        &copy; {{ date('Y') }} {{ config('portfolio.site_name') }}. All rights reserved.
                    </p>
                    <p style="color:rgba(139,92,246,0.4);font-size:0.75rem;letter-spacing:0.1em;">
                        作られた愛を込めて · Made with love
                    </p>
                </div>
            </div>
        </footer>
    </div>

    {{-- Decorative canvases --}}
    <canvas id="sparkle-canvas" style="position:fixed;inset:0;pointer-events:none;z-index:1;"></canvas>
    <canvas id="sakura-canvas" style="position:fixed;inset:0;pointer-events:none;z-index:2;"></canvas>

    {{-- Floating AI chat widget --}}
    <x-shared.chat-widget />

    {{-- CDN Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>

    {{-- Site JS --}}
    <script src="{{ versioned_asset('assets/js/preloader.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/cursor.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/scroll-animations.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/mouse-interactions.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/particle-system.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/floating-widgets.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/chat-widget.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/video-widget.js') }}"></script>
    <script src="{{ versioned_asset('assets/js/app.js') }}"></script>

    <script>
        // ── Preloader ────────────────────────────────────────────────────────────────
        (function() {
            const preloader = document.getElementById('preloader');
            const fill = document.getElementById('loader-fill');
            const pctEl = document.getElementById('loader-pct');

            if (!preloader || !fill || !pctEl) return;

            if (sessionStorage.getItem('preloaded')) {
                preloader.classList.add('hidden');
                return;
            }

            let pct = 0;
            const timer = setInterval(() => {
                pct += Math.random() * 15;
                if (pct > 100) pct = 100;

                fill.style.width = pct + '%';
                pctEl.textContent = Math.floor(pct) + '%';

                if (pct >= 100) {
                    clearInterval(timer);
                    setTimeout(() => {
                        preloader.classList.add('hidden');
                        sessionStorage.setItem('preloaded', '1');
                    }, 300);
                }
            }, 80);
        })();

        // ── Theme toggle ──────────────────────────────────────────────────────────────
        (function() {
            const html = document.getElementById('html-root');
            const btn = document.getElementById('theme-toggle');
            const sun = document.getElementById('sun-icon');
            const moon = document.getElementById('moon-icon');
            const saved = localStorage.getItem('theme') || 'dark';

            if (!html || !btn || !sun || !moon) return;

            function apply(t) {
                if (t === 'light') {
                    html.classList.remove('dark');
                    html.classList.add('light');
                    sun.style.display = 'block';
                    moon.style.display = 'none';
                } else {
                    html.classList.remove('light');
                    html.classList.add('dark');
                    sun.style.display = 'none';
                    moon.style.display = 'block';
                }
            }

            apply(saved);

            btn.addEventListener('click', () => {
                const next = html.classList.contains('dark') ? 'light' : 'dark';
                localStorage.setItem('theme', next);
                apply(next);
            });
        })();

        // ── Navbar scroll effect ──────────────────────────────────────────────────────
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (navbar) {
                navbar.classList.toggle('scrolled', window.scrollY > 60);
            }
        }, {
            passive: true
        });

        // ── Mobile menu ───────────────────────────────────────────────────────────────
        (function() {
            const menu = document.getElementById('mobile-menu');
            const openBtn = document.getElementById('open-menu');
            const closeBtn = document.getElementById('close-menu');

            if (!menu || !openBtn || !closeBtn) return;

            const links = menu.querySelectorAll('.mobile-nav-link');

            function openMenu() {
                menu.classList.add('open');
                document.body.classList.add('menu-open');
                openBtn.setAttribute('aria-expanded', 'true');

                links.forEach((link, i) => {
                    link.style.opacity = '0';
                    link.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        link.style.opacity = '1';
                        link.style.transform = 'translateY(0)';
                    }, 80 * i);
                });
            }

            function closeMenu() {
                menu.classList.remove('open');
                document.body.classList.remove('menu-open');
                openBtn.setAttribute('aria-expanded', 'false');

                links.forEach((link) => {
                    link.style.opacity = '0';
                    link.style.transform = 'translateY(20px)';
                });
            }

            openBtn.addEventListener('click', openMenu);
            closeBtn.addEventListener('click', closeMenu);

            links.forEach(link => {
                link.addEventListener('click', closeMenu);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && menu.classList.contains('open')) {
                    closeMenu();
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    closeMenu();
                }
            });
        })();

        // ── Reading progress ──────────────────────────────────────────────────────────
        window.addEventListener('scroll', () => {
            const doc = document.documentElement;
            const progress = document.getElementById('read-progress');
            if (!progress) return;

            const pct = (doc.scrollTop / (doc.scrollHeight - doc.clientHeight)) * 100;
            progress.style.width = pct + '%';
        }, {
            passive: true
        });

        // ── Scroll reveal ─────────────────────────────────────────────────────────────
        (function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.12
            });

            document.querySelectorAll('.reveal,.reveal-left,.reveal-right').forEach(el => observer.observe(el));
        })();

        // ── GSAP + ScrollTrigger init ─────────────────────────────────────────────────
        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);
        }
    </script>

    @stack('scripts')
</body>

</html>
