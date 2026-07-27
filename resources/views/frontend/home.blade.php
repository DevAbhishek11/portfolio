@extends('layouts.app')
@section('content')

    {{-- ── HERO ─────────────────────────────────────────────────────────────────── --}}
    <section class="min-h-screen flex items-center relative overflow-hidden pt-20">

        {{-- Orbs --}}
        <div class="orb w-[600px] h-[600px] bg-[rgba(139,92,246,0.12)] -top-[100px] -right-[100px]"></div>
        <div class="orb w-[400px] h-[400px] bg-[rgba(6,182,212,0.08)] -bottom-[50px] -left-[50px]"></div>

        <div class="container relative z-[1]">
            <div class="max-w-[700px]">

                <div class="section-tag reveal" data-anime="fade-up">
                    <span>Full Stack Developer</span>
                </div>

                <h1 class="hero-title font-display text-[clamp(3rem,7vw,5.5rem)] font-black leading-[1.1] mb-6"
                    data-split="chars">
                    Hi, I'm <span class="grad-text">{{ config('portfolio.site_name') }}</span>
                </h1>

                <p class="reveal delay-2 text-[1.1rem] text-[#6366f1] dark:text-[#a1a1aa] max-w-[520px] mb-10 leading-[1.8]"
                    data-anime="fade-up" data-delay="200">
                    I craft beautiful, performant web experiences — from pixel-perfect frontends to bulletproof backends.
                    <span class="text-[#7c3aed] dark:text-[#8b5cf6]">{{ $projectCount }}+ projects</span> shipped.
                </p>

                <div class="flex gap-4 flex-wrap reveal delay-3" data-anime="fade-up" data-delay="300">
                    <a href="{{ route('projects.index') }}" class="btn-anime">
                        View My Work
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}" class="btn-outline">Get In Touch</a>
                </div>

                {{-- Social links --}}
                <div class="flex gap-3 mt-10 reveal delay-4">
                    @if (config('portfolio.social.github'))
                        <a href="{{ config('portfolio.social.github') }}" target="_blank" class="social-icon">GH</a>
                    @endif
                    @if (config('portfolio.social.linkedin'))
                        <a href="{{ config('portfolio.social.linkedin') }}" target="_blank" class="social-icon">in</a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-center z-[1] hidden sm:block">
            <p class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.7rem] tracking-[0.15em] mb-2">
                スクロール · SCROLL
            </p>
            <div
                class="w-px h-[50px] bg-gradient-to-b from-[#7c3aed] dark:from-[#8b5cf6] to-transparent mx-auto animate-scroll-pulse">
            </div>
        </div>
    </section>

    {{-- ── FEATURED PROJECTS ────────────────────────────────────────────────────── --}}
    <section class="stagger-grid section-pad relative">
        <div class="container">

            <div class="text-center mb-16">
                <div class="section-tag reveal" style="justify-content:center;">Selected Work</div>
                <h2
                    class="font-display reveal delay-1 text-[clamp(2rem,4vw,3rem)] font-extrabold text-[#1e1b4b] dark:text-[#e4e4e7]">
                    Featured Projects
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
                @forelse($featuredProjects as $i => $project)
                    <article class="anime-card reveal delay-{{ (int) $i + 1 }} relative">

                        {{-- Thumbnail --}}
                        <div class="h-[220px] overflow-hidden rounded-t-[0.875rem] relative">
                            <img src="{{ asset('/' . $project->thumbnail) }}" alt="{{ $project->title }}"
                                class="w-full h-full object-cover transition-transform duration-500">
                            <div
                                class="absolute inset-0 bg-[linear-gradient(to_bottom,transparent_40%,rgba(10,10,15,0.9))]">
                            </div>
                            <span
                                class="badge-{{ $project->category }} absolute top-3 left-3 px-3 py-[3px] rounded-full text-[0.72rem] font-semibold bg-black/50 backdrop-blur-[8px]">
                                {{ ucfirst($project->category) }}
                            </span>
                            @if ($project->is_featured)
                                <span class="absolute top-3 right-3 text-[0.8rem]">⭐</span>
                            @endif
                        </div>

                        <div class="p-6 relative z-[1]">
                            <h3 class="font-display text-[#1e1b4b] dark:text-[#e4e4e7] text-[1.1rem] font-bold mb-2">
                                {{ $project->title }}
                            </h3>
                            <p class="text-[#6366f1] dark:text-[#a1a1aa] text-sm leading-[1.6] mb-4">
                                {{ Str::limit($project->short_description, 100) }}
                            </p>

                            {{-- Tech stack --}}
                            <div class="flex flex-wrap gap-[0.4rem] mb-5">
                                @foreach ($project->techStacks->take(4) as $tech)
                                    <span class="tech-badge">{{ $tech->name }}</span>
                                @endforeach
                                @if ($project->techStacks->count() > 4)
                                    <span class="tech-badge">+{{ $project->techStacks->count() - 4 }}</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between">
                                <a href="{{ route('projects.show', $project->slug) }}"
                                    class="btn-anime !py-2 !px-5 !text-[0.85rem]">
                                    View Project →
                                </a>
                                <div class="flex gap-2">
                                    @if ($project->github_url)
                                        <a href="{{ $project->github_url }}" target="_blank"
                                            class="btn-outline !py-2 !px-3 !text-[0.8rem]">GH</a>
                                    @endif
                                    @if ($project->live_url)
                                        <a href="{{ $project->live_url }}" target="_blank"
                                            class="btn-outline !py-2 !px-3 !text-[0.8rem]">↗</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-[#6366f1] dark:text-[#a1a1aa] text-center col-span-full py-16">
                        No featured projects yet.
                    </p>
                @endforelse
            </div>

            <div class="text-center my-12 reveal">
                <a href="{{ route('projects.index') }}" class="btn-outline">View All Projects →</a>
            </div>
        </div>
    </section>

    {{-- ── SERVICES PREVIEW ─────────────────────────────────────────────────────── --}}
    <section data-section-burst class="section-pad bg-[#f3e8ff] dark:bg-[#12121a] relative overflow-hidden">

        <div class="orb w-[500px] h-[500px] bg-[rgba(6,182,212,0.06)] top-1/2 -left-[100px] -translate-y-1/2"></div>

        <div class="container relative z-[1]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center">

                <div>
                    <div class="section-tag reveal">What I Do</div>
                    <h2
                        class="font-display reveal delay-1 text-[clamp(1.8rem,3.5vw,2.8rem)] font-extrabold text-[#1e1b4b] dark:text-[#e4e4e7] mb-4">
                        Full Stack<br><span class="grad-text">Development</span>
                    </h2>
                    <p class="reveal delay-2 text-[#6366f1] dark:text-[#a1a1aa] leading-[1.8] mb-6">
                        From concept to deployment, I build complete digital products that solve real problems.
                        Frontend finesse meets backend power.
                    </p>
                    <a href="{{ route('services') }}" class="btn-anime reveal delay-3">
                        See All Services →
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @foreach ([['Frontend', 'React · Next.js · Tailwind', '#06b6d4'], ['Backend', 'Laravel · Node.js · APIs', '#8b5cf6'], ['Mobile', 'React Native · Expo', '#f43f5e'], ['Database', 'MySQL · MongoDB · Redis', '#eab308']] as $i => [$title, $sub, $color])
                        <div class="anime-card reveal delay-{{ $i + 1 }} p-5">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-3"
                                style="background:{{ $color }}20;">
                                <div class="w-[14px] h-[14px] rounded-[3px]" style="background:{{ $color }};"></div>
                            </div>
                            <h3 class="text-[#1e1b4b] dark:text-[#e4e4e7] text-[0.95rem] font-semibold mb-1">
                                {{ $title }}
                            </h3>
                            <p class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.78rem]">{{ $sub }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ── LATEST BLOGS ─────────────────────────────────────────────────────────── --}}
    @if ($latestBlogs->count())
        <section class="section-pad">
            <div class="container">

                <div class="flex items-center justify-between mb-12 flex-wrap gap-4">
                    <div>
                        <div class="section-tag reveal">Latest Writing</div>
                        <h2
                            class="font-display reveal delay-1 text-[clamp(1.8rem,3.5vw,2.8rem)] font-extrabold text-[#1e1b4b] dark:text-[#e4e4e7]">
                            From the Blog
                        </h2>
                    </div>
                    <a href="{{ route('blogs.index') }}" class="btn-outline reveal">Read All →</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
                    @foreach ($latestBlogs as $i => $blog)
                        <article class="anime-card reveal delay-{{ $i + 1 }}">
                            @if ($blog->featured_image)
                                <div class="h-[180px] overflow-hidden rounded-t-[0.875rem]">
                                    <img src="{{ asset('/' . $blog->featured_image) }}" alt="{{ $blog->title }}"
                                        class="w-full h-full object-cover transition-transform duration-[400ms]">
                                </div>
                            @endif
                            <div class="p-6">
                                @if ($blog->category)
                                    <span class="tech-badge mb-3 inline-block">{{ $blog->category }}</span>
                                @endif
                                <h3
                                    class="font-display text-[#1e1b4b] dark:text-[#e4e4e7] text-base font-bold mb-2 leading-[1.4]">
                                    {{ Str::limit($blog->title, 60) }}
                                </h3>
                                <p class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.85rem] leading-[1.6] mb-4">
                                    {{ Str::limit($blog->excerpt ?: strip_tags($blog->content), 100) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.78rem]">
                                        {{ $blog->published_at?->format('M d, Y') }} · {{ $blog->read_time }}
                                    </span>
                                    <a href="{{ route('blogs.show', $blog->slug) }}"
                                        class="text-[#7c3aed] dark:text-[#8b5cf6] text-[0.85rem] no-underline font-medium">
                                        Read →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── CONTACT CTA ──────────────────────────────────────────────────────────── --}}
    <section class="py-20 relative overflow-hidden bg-[#f3e8ff] dark:bg-[#12121a]">

        <div class="orb w-[700px] h-[700px] bg-[rgba(139,92,246,0.1)] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        </div>

        <div class="container text-center relative z-[1]">
            <p class="text-[rgba(139,92,246,0.5)] text-[0.8rem] tracking-[0.2em] mb-4">一緒に作りましょう</p>
            <h2
                class="font-display reveal text-[clamp(2rem,5vw,3.5rem)] font-black text-[#1e1b4b] dark:text-[#e4e4e7] mb-4">
                Let's Create Something<br><span class="grad-text">Amazing Together</span>
            </h2>
            <p class="reveal delay-1 text-[#6366f1] dark:text-[#a1a1aa] text-base max-w-[500px] mx-auto mb-8">
                Have a project in mind? I'd love to hear about it. Let's talk and make it real.
            </p>
            <a href="{{ route('contact') }}" class="btn-anime reveal delay-2 !text-[1.05rem] !py-4 !px-10">
                Get In Touch ✉
            </a>
        </div>
    </section>

@endsection
