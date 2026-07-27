@extends('layouts.app')
@section('content')
    @php
        $metaTitle = 'About ' . (portfolio_owner()?->name ?? config('portfolio.site_name')) . ' — Laravel, React & Node.js Developer';
        $metaDesc = 'Background, skills, and experience of a full-stack developer working with Laravel, React, Node.js, and modern cloud infrastructure.';
    @endphp
    {{-- ── HERO ─────────────────────────────────────────────────────────────────── --}}
    <section class="pt-32 pb-16 relative overflow-hidden">

        <div class="orb w-[500px] h-[500px] bg-[rgba(139,92,246,0.1)] -top-[100px] -right-[100px]"></div>

        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center">

                {{-- Left copy --}}
                <div class="reveal-left">
                    <div class="section-tag">About Me</div>
                    <h1
                        class="font-display text-[clamp(2.5rem,5vw,4rem)] font-black text-[#1e1b4b] dark:text-[#e4e4e7] leading-[1.15] mb-5">
                        Crafting Digital<br><span class="grad-text">Experiences</span>
                    </h1>
                    <p class="text-[#6366f1] dark:text-[#a1a1aa] text-base leading-[1.8] mb-6">
                        {{ $admin?->bio ?: 'Passionate full stack developer with a love for clean code, thoughtful design, and building products that make a difference.' }}
                    </p>
                    <div class="flex gap-4 flex-wrap">
                        <a href="{{ route('contact') }}" class="btn-anime">Let's Talk</a>
                        @if ($admin?->resume_url)
                            <a href="{{ $admin->resume_url }}" target="_blank" class="btn-outline">Download CV ↓</a>
                        @endif
                    </div>
                </div>

                {{-- Right avatar --}}
                <div class="reveal-right text-center">
                    <div class="w-[280px] h-[280px] rounded-full mx-auto relative">
                        @if ($admin?->avatar)
                            <img src="{{ $admin->avatar }}" alt="{{ $admin->name }}"
                                class="w-full h-full object-cover rounded-full border-[3px] border-[#7c3aed]/20 dark:border-[#8b5cf6]/20">
                        @else
                            <div
                                class="w-full h-full rounded-full bg-gradient-to-br from-[#8b5cf6] to-[#06b6d4] flex items-center justify-center">
                                <span class="font-display text-[6rem] font-black text-white">
                                    {{ substr(config('portfolio.site_name'), 0, 1) }}
                                </span>
                            </div>
                        @endif
                        {{-- Rotating ring --}}
                        <div
                            class="absolute -inset-2 rounded-full border border-[#7c3aed]/20 dark:border-[#8b5cf6]/20 animate-spin-slow">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── TIMELINE ─────────────────────────────────────────────────────────────── --}}
    @if (count($timeline))
        <section class="section-pad bg-[#f3e8ff] dark:bg-[#12121a] relative">
            <div class="container">

                <div class="text-center mb-16">
                    <div class="section-tag reveal flex justify-center">My Journey</div>
                    <h2
                        class="font-display reveal delay-1 text-[clamp(1.8rem,3.5vw,2.8rem)] font-extrabold text-[#1e1b4b] dark:text-[#e4e4e7]">
                        Experience &amp; Education
                    </h2>
                </div>

                <div class="max-w-[700px] mx-auto relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-6 top-0 bottom-0 w-px bg-[#7c3aed]/20 dark:bg-[#8b5cf6]/20"></div>

                    @foreach ($timeline as $i => $item)
                        <div class="reveal delay-{{ $i + 1 }} flex gap-8 mb-10 relative">

                            {{-- Year badge --}}
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-[#7c3aed] to-[#0891b2] dark:from-[#8b5cf6] dark:to-[#06b6d4] flex items-center justify-center shrink-0 text-[0.7rem] font-bold text-white z-[1]">
                                {{ substr($item['year'], 2) }}
                            </div>

                            {{-- Card --}}
                            <div class="anime-card flex-1 p-5">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <h3 class="text-[#1e1b4b] dark:text-[#e4e4e7] text-base font-bold">
                                        {{ $item['title'] }}
                                    </h3>
                                    <span class="tech-badge">{{ $item['year'] }}</span>
                                </div>
                                <p class="text-[#7c3aed] dark:text-[#8b5cf6] text-[0.85rem] font-medium mb-2">
                                    {{ $item['place'] }}
                                </p>
                                <p class="text-[#6366f1] dark:text-[#a1a1aa] text-sm leading-[1.6]">
                                    {{ $item['desc'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── HOW I WORK ───────────────────────────────────────────────────────────── --}}
    <section class="section-pad relative">
        <div class="container">
            <div class="text-center mb-16">
                <div class="section-tag reveal flex justify-center">How I Work</div>
                <h2
                    class="font-display reveal delay-1 text-[clamp(1.8rem,3.5vw,2.8rem)] font-extrabold text-[#1e1b4b] dark:text-[#e4e4e7]">
                    My <span class="grad-text">Approach</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl mx-auto">
                @foreach ([
                    ['title' => 'Understand Before I Build', 'desc' => 'Every project starts with understanding the actual problem, not just the feature request — so what gets built solves the right thing.'],
                    ['title' => 'Test as I Go', 'desc' => "Edge cases and error states get handled during development, not discovered after launch. Testing isn't an afterthought bolted on at the end."],
                    ['title' => 'Document the Decisions', 'desc' => 'Code and comments explain the why behind non-obvious choices, so the codebase stays understandable long after a feature ships.'],
                    ['title' => 'Stay Current', 'desc' => 'The Laravel and React ecosystems move fast — keeping up with them means the code shipped today doesn\'t become tomorrow\'s legacy problem.'],
                ] as $i => $item)
                    <div class="anime-card reveal delay-{{ $i + 1 }} p-6">
                        <h3 class="text-[#1e1b4b] dark:text-[#e4e4e7] text-[0.95rem] font-bold mb-2">
                            {{ $item['title'] }}
                        </h3>
                        <p class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.85rem] leading-[1.7]">
                            {{ $item['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── SKILLS (radar / proficiency) ────────────────────────────────────────── --}}
    <section class="py-24 lg:py-32">
        <div class="max-w-6xl mx-auto px-6">

            <div class="text-center mb-16">
                <div class="section-tag flex justify-center">Tech Stack</div>
                <h2
                    class="font-display text-[clamp(2rem,4vw,3rem)] font-black text-[#1e1b4b] dark:text-[#e4e4e7] reveal delay-1">
                    Skills &amp; <span class="grad-text">Proficiency</span>
                </h2>
                <p class="text-[#6366f1] dark:text-[#a1a1aa] mt-4 max-w-md mx-auto reveal delay-2">
                    Hover the radar points to see exact proficiency levels. Filter by category below.
                </p>
            </div>

            <x-frontend.skills-radar :skills="$skills" :grouped="$grouped" />
        </div>
    </section>
@endsection
