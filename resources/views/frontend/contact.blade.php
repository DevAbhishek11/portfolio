@extends('layouts.app')
@section('content')
    <section class="pt-32 pb-20 relative overflow-hidden">

        <div class="orb w-[500px] h-[500px] bg-[rgba(139,92,246,0.1)] -top-[100px] -right-[100px]"></div>
        <div class="orb w-[400px] h-[400px] bg-[rgba(6,182,212,0.07)] -bottom-[50px] -left-[80px]"></div>

        <div class="container relative z-[1]">

            {{-- Page heading --}}
            <div class="text-center mb-16">
                <div class="section-tag reveal flex justify-center">Contact</div>
                <h1
                    class="font-display reveal delay-1 text-[clamp(2.5rem,5vw,4rem)] font-black text-[#1e1b4b] dark:text-[#e4e4e7]">
                    Let's Work <span class="grad-text">Together</span>
                </h1>
                <p
                    class="reveal delay-2 text-[#6366f1] dark:text-[#a1a1aa] text-base max-w-[480px] mx-auto mt-4 leading-[1.7]">
                    Have a project in mind, or just want to say hi? Drop me a message and I'll get back to you as soon as
                    possible.
                </p>
                <p class="text-[rgba(139,92,246,0.4)] text-[0.8rem] tracking-[0.15em] mt-3">
                    一緒に素晴らしいものを作りましょう
                </p>
            </div>

            {{-- Two-column layout: form + sidebar --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-12 items-start max-w-[1000px] mx-auto">

                {{-- ── Contact Form ───────────────────────────────────────────────── --}}
                <div class="glass reveal-left p-8 md:p-10">
                    <form method="POST" action="{{ route('contact.store') }}" id="contactForm">
                        @csrf
                        {{-- Honeypot --}}
                        <div class="hidden">
                            <input type="text" name="honeypot" tabindex="-1" autocomplete="off">
                        </div>

                        {{-- Name + Email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label
                                    class="block text-[#6366f1] dark:text-[#a1a1aa] text-[0.82rem] font-medium mb-[0.4rem]">
                                    Full Name *
                                </label>
                                <input type="text" name="name"
                                    class="anime-input @error('name') input-error @enderror" value="{{ old('name') }}"
                                    placeholder="John Doe" required>
                                @error('name')
                                    <p class="error-msg">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[#6366f1] dark:text-[#a1a1aa] text-[0.82rem] font-medium mb-[0.4rem]">
                                    Email Address *
                                </label>
                                <input type="email" name="email"
                                    class="anime-input @error('email') input-error @enderror" value="{{ old('email') }}"
                                    placeholder="john@example.com" required>
                                @error('email')
                                    <p class="error-msg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Phone + Subject --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label
                                    class="block text-[#6366f1] dark:text-[#a1a1aa] text-[0.82rem] font-medium mb-[0.4rem]">
                                    Phone
                                </label>
                                <input type="text" name="phone"
                                    class="anime-input @error('phone') input-error @enderror" value="{{ old('phone') }}"
                                    placeholder="+1 234 567 890">
                                @error('phone')
                                    <p class="error-msg">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[#6366f1] dark:text-[#a1a1aa] text-[0.82rem] font-medium mb-[0.4rem]">
                                    Subject *
                                </label>
                                <input type="text" name="subject"
                                    class="anime-input @error('subject') input-error @enderror" value="{{ old('subject') }}"
                                    placeholder="Project inquiry" required>
                                @error('subject')
                                    <p class="error-msg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Message --}}
                        <div class="mb-6">
                            <label class="block text-[#6366f1] dark:text-[#a1a1aa] text-[0.82rem] font-medium mb-[0.4rem]">
                                Message *
                                <span id="char-count" class="float-right text-[#6366f1] dark:text-[#a1a1aa]">0 / 5000</span>
                            </label>
                            <textarea name="message" id="message-area" class="anime-input @error('message') input-error @enderror" rows="7"
                                placeholder="Tell me about your project, timeline, and budget…" required minlength="20" maxlength="5000"
                                oninput="document.getElementById('char-count').textContent = this.value.length + ' / 5000'">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" id="submit-btn" class="btn-anime w-full justify-center text-base py-4">
                            <span id="btn-text">Send Message ✉</span>
                            <span id="btn-loading" class="hidden">Sending…</span>
                        </button>

                        <p class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.78rem] text-center mt-3">
                            I'll reply within 24 hours.
                        </p>
                    </form>
                </div>

                {{-- ── Contact Info sidebar ────────────────────────────────────────── --}}
                <div class="reveal-right flex flex-col gap-5">

                    @foreach ([['📧', 'Email', config('portfolio.site_email'), 'mailto:' . config('portfolio.site_email')], ['📍', 'Location', config('portfolio.site_location'), null], ['📱', 'Phone', config('portfolio.site_phone'), 'tel:' . config('portfolio.site_phone')]] as [$icon, $label, $value, $href])
                        @if ($value)
                            <div class="anime-card p-5 flex items-center gap-4">
                                <div
                                    class="w-11 h-11 rounded-xl bg-[rgba(139,92,246,0.1)] flex items-center justify-center text-[1.2rem] shrink-0">
                                    {{ $icon }}
                                </div>
                                <div>
                                    <p class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.75rem] mb-[0.15rem]">
                                        {{ $label }}
                                    </p>
                                    @if ($href)
                                        <a href="{{ $href }}"
                                            class="text-[#1e1b4b] dark:text-[#e4e4e7] text-[0.9rem] font-medium no-underline">
                                            {{ $value }}
                                        </a>
                                    @else
                                        <p class="text-[#1e1b4b] dark:text-[#e4e4e7] text-[0.9rem] font-medium">
                                            {{ $value }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach

                    {{-- Social links --}}
                    <div class="anime-card p-5">
                        <p
                            class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.75rem] mb-[0.875rem] uppercase tracking-[0.08em]">
                            Find me online
                        </p>
                        <div class="flex gap-3 flex-wrap">
                            @if (config('portfolio.social.github'))
                                <a href="{{ config('portfolio.social.github') }}" target="_blank"
                                    class="social-icon !w-auto !px-4 !rounded-lg !text-[0.82rem]">GitHub</a>
                            @endif
                            @if (config('portfolio.social.linkedin'))
                                <a href="{{ config('portfolio.social.linkedin') }}" target="_blank"
                                    class="social-icon !w-auto !px-4 !rounded-lg !text-[0.82rem]">LinkedIn</a>
                            @endif
                            @if (config('portfolio.social.twitter'))
                                <a href="{{ config('portfolio.social.twitter') }}" target="_blank"
                                    class="social-icon !w-auto !px-4 !rounded-lg !text-[0.82rem]">Twitter</a>
                            @endif
                        </div>
                    </div>

                    {{-- Availability indicator --}}
                    <div class="anime-card p-5 !border-[rgba(34,197,94,0.3)]">
                        <div class="flex items-center gap-[0.625rem]">
                            <div class="w-[10px] h-[10px] rounded-full bg-[#22c55e] animate-pulse-dot"></div>
                            <p class="text-[#4ade80] text-sm font-semibold">Available for new projects</p>
                        </div>
                        <p class="text-[#6366f1] dark:text-[#a1a1aa] text-[0.8rem] mt-[0.375rem]">
                            Typical response time: within 24 hours
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.getElementById('contactForm').addEventListener('submit', function() {
                document.getElementById('btn-text').style.display = 'none';
                document.getElementById('btn-loading').style.display = 'inline';
                document.getElementById('submit-btn').disabled = true;
            });
        </script>
    @endpush
@endsection
