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

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'anime-purple': '#8b5cf6',
                        'anime-cyan': '#06b6d4',
                        'anime-pink': '#f43f5e',
                        'anime-dark': '#0a0a0f',
                    },
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/anime-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

    <style>
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-tertiary: #1a1a2e;
            --bg-glass: rgba(26, 26, 46, 0.7);
            --text-primary: #e4e4e7;
            --text-secondary: #a1a1aa;
            --text-accent: #c084fc;
            --accent-1: #8b5cf6;
            --accent-2: #06b6d4;
            --accent-3: #f43f5e;
            --border-color: rgba(139, 92, 246, 0.2);
            --shadow-anime: 0 0 30px rgba(139, 92, 246, 0.15);
            --gradient: linear-gradient(135deg, #8b5cf6, #06b6d4, #f43f5e);
        }

        .light {
            --bg-primary: #faf5ff;
            --bg-secondary: #f3e8ff;
            --bg-tertiary: #ffffff;
            --text-primary: #1e1b4b;
            --text-secondary: #6366f1;
            --accent-1: #7c3aed;
            --accent-2: #0891b2;
            --accent-3: #e11d48;
            --border-color: rgba(124, 58, 237, 0.2);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            overflow-x: hidden;
        }

        body.loco-ready {
            overflow: hidden;
        }

        body.menu-open {
            overflow: hidden;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-1);
            border-radius: 3px;
        }

        /* Typography */
        .font-display {
            font-family: 'Playfair Display', serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Playfair Display', serif;
        }

        /* Gradient text */
        .grad-text {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glass card */
        .glass {
            background: var(--bg-glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 1.25rem;
        }

        /* Anime card */
        .anime-card {
            background: var(--bg-tertiary);
            border: 2px solid var(--border-color);
            border-radius: 1rem;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .anime-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 0;
            border-radius: inherit;
            pointer-events: none;
        }

        .anime-card:hover {
            border-color: var(--accent-1);
            transform: translateY(-4px);
            box-shadow: var(--shadow-anime);
        }

        .anime-card:hover::before {
            opacity: 0.04;
        }

        /* Section padding */
        .section-pad {
            padding: 6rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Buttons */
        .btn-anime {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-anime:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.4);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            border-color: var(--accent-1);
            color: var(--accent-1);
        }

        /* Badge */
        .tech-badge {
            display: inline-block;
            padding: 3px 12px;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-accent);
        }

        .badge-frontend {
            background: rgba(6, 182, 212, 0.1);
            border-color: rgba(6, 182, 212, 0.25);
            color: #22d3ee;
        }

        .badge-backend {
            background: rgba(139, 92, 246, 0.1);
            border-color: rgba(139, 92, 246, 0.25);
            color: #a78bfa;
        }

        .badge-fullstack {
            background: rgba(244, 63, 94, 0.1);
            border-color: rgba(244, 63, 94, 0.25);
            color: #fb7185;
        }

        /* Section header */
        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--accent-1);
            margin-bottom: 1rem;
        }

        .section-tag::before,
        .section-tag::after {
            content: '';
            display: inline-block;
            width: 24px;
            height: 1px;
            background: var(--accent-1);
            opacity: 0.5;
        }

        /* Navbar */
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1.25rem 0;
            transition: all 0.4s ease;
        }

        #navbar.scrolled {
            background: rgba(10, 10, 15, 0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 0;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mobile-menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
            position: relative;
            padding-bottom: 4px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent-1);
            transition: width 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--text-primary);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* Anime form inputs */
        .anime-input {
            width: 100%;
            background: rgba(10, 10, 15, 0.6);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 0.75rem;
            padding: 0.875rem 1.125rem;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.3s;
        }

        .anime-input:focus {
            border-color: var(--accent-1);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
        }

        .anime-input::placeholder {
            color: rgba(161, 161, 170, 0.5);
        }

        .input-error {
            border-color: var(--accent-3) !important;
        }

        .error-msg {
            color: #fb7185;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        /* Glow orb */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        /* Grid pattern */
        .grid-bg {
            background-image:
                linear-gradient(rgba(139, 92, 246, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139, 92, 246, 0.04) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* Anime halftone dots */
        .halftone {
            background-image: radial-gradient(circle, rgba(139, 92, 246, 0.15) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal-left.visible,
        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Stagger delay helpers */
        .delay-1 {
            transition-delay: 0.1s;
        }

        .delay-2 {
            transition-delay: 0.2s;
        }

        .delay-3 {
            transition-delay: 0.3s;
        }

        .delay-4 {
            transition-delay: 0.4s;
        }

        .delay-5 {
            transition-delay: 0.5s;
        }

        /* Mobile menu */
        #mobile-menu {
            position: fixed;
            inset: 0;
            z-index: 1100;
            background: rgba(10, 10, 15, 0.97);
            backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: translateX(100%);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: transform 0.4s ease, opacity 0.4s ease, visibility 0.4s ease;
        }

        #mobile-menu.open {
            transform: translateX(0);
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: var(--bg-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1.5rem;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }

        #preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-bar {
            width: 200px;
            height: 2px;
            background: rgba(139, 92, 246, 0.2);
            border-radius: 9999px;
            overflow: hidden;
        }

        .loader-fill {
            height: 100%;
            background: var(--gradient);
            border-radius: 9999px;
            width: 0%;
            transition: width 0.1s linear;
        }

        /* Anime speed lines */
        .speed-lines::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(90deg,
                    transparent,
                    transparent 4px,
                    rgba(139, 92, 246, 0.03) 4px,
                    rgba(139, 92, 246, 0.03) 5px);
            pointer-events: none;
        }

        /* Footer */
        footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
        }

        /* Reading progress bar */
        #read-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 2px;
            background: var(--gradient);
            z-index: 9998;
            width: 0%;
            transition: width 0.1s linear;
        }

        /* Social icon */
        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .social-icon:hover {
            background: rgba(139, 92, 246, 0.2);
            color: var(--accent-1);
            border-color: var(--accent-1);
        }

        /* Responsive */
        @media (max-width: 767px) {
            .section-pad {
                padding: 4rem 0;
            }

            .container {
                padding: 0 1rem;
            }

            h1.hero-title {
                font-size: clamp(2.5rem, 8vw, 4rem) !important;
            }

            .desktop-nav {
                display: none !important;
            }

            .mobile-menu-toggle {
                display: inline-flex !important;
            }
        }

        @media (min-width: 768px) {
            #mobile-menu {
                display: none !important;
            }
        }
    </style>

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
    <div data-scroll-container id="scroll-container">
        <x-shared.skip-link />

        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer>
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

    {{-- CDN Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>

    {{-- 3D System --}}
    <script src="{{ asset('assets/js/three-scene.js') }}"></script>
    <script src="{{ asset('assets/js/anime-character.js') }}"></script>
    <script src="{{ asset('assets/js/floating-objects.js') }}"></script>
    <script src="{{ asset('assets/js/scene-environment.js') }}"></script>
    <script src="{{ asset('assets/js/section-scenes.js') }}"></script>
    <script src="{{ asset('assets/js/scene-quality.js') }}"></script>
    <script src="{{ asset('assets/js/three-boot.js') }}"></script>

    {{-- Site JS --}}
    <script src="{{ asset('assets/js/preloader.js') }}"></script>
    <script src="{{ asset('assets/js/cursor.js') }}"></script>
    <script src="{{ asset('assets/js/locomotive-init.js') }}"></script>
    <script src="{{ asset('assets/js/scroll-animations.js') }}"></script>
    <script src="{{ asset('assets/js/mouse-interactions.js') }}"></script>
    <script src="{{ asset('assets/js/particle-system.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

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
