@extends('layouts.app')
@section('content')
    <section class="pt-32 pb-16">
        <div class="container">

            {{-- ── Page heading ─────────────────────────────────────────────────── --}}
            <div class="text-center mb-16">
                <div class="section-tag reveal flex justify-center">Services</div>
                <h1
                    class="font-display reveal delay-1 text-[clamp(2.5rem,5vw,4rem)] font-black text-[#1e1b4b] dark:text-[#e4e4e7]">
                    What I Can <span class="grad-text">Do For You</span>
                </h1>
                <p class="reveal delay-2 text-[#6366f1] dark:text-[#a1a1aa] text-base max-w-[520px] mx-auto mt-4">
                    Full-spectrum digital solutions — from pixel-perfect UIs to scalable backend systems.
                </p>
            </div>

            {{-- ── Services cards ───────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7 mb-20">
                @foreach ($services as $i => $service)
                    <div class="anime-card reveal delay-{{ ($i % 3) + 1 }} p-8">

                        {{-- Icon --}}
                        <div class="w-[52px] h-[52px] rounded-[0.875rem] flex items-center justify-center mb-5"
                            style="background:{{ $service['color'] }}20; border:1px solid {{ $service['color'] }}40;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                                viewBox="0 0 24 24" stroke="{{ $service['color'] }}" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $service['icon'] }}" />
                            </svg>
                        </div>

                        <h3 class="font-display text-[#1e1b4b] dark:text-[#e4e4e7] text-[1.15rem] font-bold mb-3">
                            {{ $service['title'] }}
                        </h3>
                        <p class="text-[#6366f1] dark:text-[#a1a1aa] text-sm leading-[1.7] mb-4">
                            {{ $service['desc'] }}
                        </p>
                        <div class="flex flex-wrap gap-[0.35rem]">
                            @foreach ($service['stack'] as $tech)
                                <span class="tech-badge">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── Process ───────────────────────────────────────────────────────── --}}
            <div class="mb-20">
                <div class="text-center mb-12">
                    <div class="section-tag reveal flex justify-center">How I Work</div>
                    <h2
                        class="font-display reveal delay-1 text-[clamp(1.8rem,3.5vw,2.8rem)] font-extrabold text-[#1e1b4b] dark:text-[#e4e4e7]">
                        My Process
                    </h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach ($process as $i => $step)
                        <div class="anime-card reveal delay-{{ ($i % 4) + 1 }} p-6 text-center">
                            <div class="grad-text font-display text-[2.5rem] font-black mb-3">
                                {{ $step['step'] }}
                            </div>
                            <h3 class="text-[#1e1b4b] dark:text-[#e4e4e7] text-[0.95rem] font-bold mb-2">
                                {{ $step['title'] }}
                            </h3>
                            <p class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.82rem] leading-[1.6]">
                                {{ $step['desc'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Stats banner ──────────────────────────────────────────────────── --}}
            <div
                class="bg-[#f3e8ff] dark:bg-[#12121a] rounded-3xl border border-[#7c3aed]/20 dark:border-[#8b5cf6]/20 p-8 md:p-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                @foreach ($stats as $i => $stat)
                    <div class="reveal delay-{{ $i + 1 }}">
                        <div class="grad-text font-display text-[2.5rem] font-black" data-counter="{{ $stat['value'] }}"
                            data-suffix="+">
                            {{ $stat['value'] }}+
                        </div>
                        <p class="text-[#6366f1] dark:text-[#a1a1aa] text-sm mt-1">
                            {{ $stat['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- ── CTA button ────────────────────────────────────────────────────── --}}
            <div class="text-center mt-14">
                <a href="{{ route('contact') }}" class="btn-anime !text-[1.05rem] !py-4 !px-10">
                    Start a Project →
                </a>
            </div>
        </div>
    </section>
@endsection
