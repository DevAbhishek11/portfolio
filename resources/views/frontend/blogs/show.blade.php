@extends('layouts.app')
@section('content')

    @php
        $jsonLdType = 'article';
        $jsonLdData = [
            'title' => $blog->meta_title ?: $blog->title,
            'description' => $blog->meta_description ?: $blog->excerpt,
            'image' => $blog->featured_image ? asset($blog->featured_image) : '',
            'published_at' => $blog->published_at?->toIso8601String(),
            'updated_at' => $blog->updated_at->toIso8601String(),
        ];
    @endphp

    <article style="padding:5rem 0;">
        {{-- Featured image --}}
        @if ($blog->featured_image)
            <div style="height:420px;position:relative;overflow:hidden;">
                <img src="{{ asset('/' . $blog->featured_image) }}" alt="{{ $blog->title }}"
                    style="width:100%;height:100%;object-fit:cover;">
                <div
                    style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 30%,var(--bg-primary));">
                </div>
            </div>
        @else
            <div style="height:80px;"></div>
        @endif

        <div class="container"
            style="max-width:860px;{{ $blog->featured_image ? 'margin-top:-3rem;' : 'margin-top:2rem;' }}position:relative;z-index:1;">

            {{-- Meta --}}
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1.25rem;">
                @if ($blog->category)
                    <span class="tech-badge">{{ $blog->category }}</span>
                @endif
                @foreach ($blog->tags ?? [] as $tag)
                    <a href="{{ route('blogs.tag', $tag) }}" class="tech-badge"
                        style="text-decoration:none;">#{{ $tag }}</a>
                @endforeach
            </div>

            <h1 class="font-display"
                style="font-size:clamp(2rem,4vw,3rem);font-weight:900;color:var(--text-primary);line-height:1.2;margin-bottom:1rem;">
                {{ $blog->title }}
            </h1>

            <div
                style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;padding-bottom:1.5rem;border-bottom:1px solid var(--border-color);margin-bottom:2.5rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div
                        style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--accent-1),var(--accent-2));display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:0.85rem;">
                        {{ substr($blog->user->name ?? 'A', 0, 1) }}
                    </div>
                    <span
                        style="color:var(--text-primary);font-weight:500;font-size:0.9rem;">{{ $blog->user->name ?? 'Admin' }}</span>
                </div>
                <span
                    style="color:var(--text-secondary);font-size:0.85rem;">{{ $blog->published_at?->format('M d, Y') }}</span>
                <span style="color:var(--text-secondary);font-size:0.85rem;">{{ $blog->read_time }}</span>
                <span style="color:var(--text-secondary);font-size:0.85rem;">{{ number_format($blog->view_count) }}
                    views</span>
            </div>

            {{-- Content --}}
            <div class="prose-content" style="color:var(--text-secondary);line-height:1.9;font-size:1rem;">
                {!! $blog->content !!}
            </div>

            {{-- Share --}}
            <div
                style="display:flex;align-items:center;gap:1rem;margin-top:3rem;padding:1.5rem;background:var(--bg-secondary);border-radius:1rem;border:1px solid var(--border-color);">
                <span style="color:var(--text-secondary);font-size:0.875rem;font-weight:500;">Share:</span>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}"
                    target="_blank" class="btn-outline" style="padding:0.4rem 0.875rem;font-size:0.8rem;">Twitter</a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                    target="_blank" class="btn-outline" style="padding:0.4rem 0.875rem;font-size:0.8rem;">LinkedIn</a>
            </div>

            {{-- Prev/Next --}}
            <div
                style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border-color);">
                @if ($prev)
                    <a href="{{ route('blogs.show', $prev->slug) }}" class="anime-card"
                        style="padding:1.25rem;text-decoration:none;display:block;">
                        <p style="color:var(--text-secondary);font-size:0.75rem;margin-bottom:0.4rem;">← Previous</p>
                        <p style="color:var(--text-primary);font-size:0.875rem;font-weight:600;line-height:1.4;">
                            {{ Str::limit($prev->title, 50) }}</p>
                    </a>
                @else
                    <div></div>
                @endif
                @if ($next)
                    <a href="{{ route('blogs.show', $next->slug) }}" class="anime-card"
                        style="padding:1.25rem;text-decoration:none;display:block;text-align:right;">
                        <p style="color:var(--text-secondary);font-size:0.75rem;margin-bottom:0.4rem;">Next →</p>
                        <p style="color:var(--text-primary);font-size:0.875rem;font-weight:600;line-height:1.4;">
                            {{ Str::limit($next->title, 50) }}</p>
                    </a>
                @endif
            </div>

            {{-- Comments --}}
            <div style="margin-top:4rem;padding-top:3rem;border-top:1px solid var(--border-color);">
                <h2 class="font-display reveal"
                    style="font-size:1.75rem;font-weight:800;color:var(--text-primary);margin-bottom:2rem;">
                    Comments ({{ $blog->approvedComments->count() }})
                </h2>

                @if (session('success'))
                    <div
                        style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);color:#4ade80;border-radius:0.75rem;padding:0.875rem 1rem;margin-bottom:1.5rem;font-size:0.875rem;">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Comment list --}}
                @forelse($blog->approvedComments as $comment)
                    <div style="margin-bottom:2rem;">
                        <div class="anime-card" style="padding:1.5rem;">
                            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.875rem;">
                                <div
                                    style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent-1),var(--accent-2));display:flex;align-items:center;justify-content:center;color:white;font-size:0.85rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($comment->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p style="color:var(--text-primary);font-weight:600;font-size:0.9rem;">
                                        {{ $comment->name }}</p>
                                    <p style="color:var(--text-secondary);font-size:0.75rem;">
                                        {{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <p style="color:var(--text-secondary);font-size:0.9rem;line-height:1.7;">
                                {{ $comment->comment }}</p>
                        </div>

                        {{-- Nested replies --}}
                        @foreach ($comment->replies as $reply)
                            <div style="margin-left:2rem;margin-top:0.75rem;" class="anime-card" style="padding:1.25rem;">
                                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.625rem;">
                                    <div
                                        style="width:28px;height:28px;border-radius:50%;background:var(--accent-2);opacity:0.6;display:flex;align-items:center;justify-content:center;color:white;font-size:0.75rem;font-weight:700;">
                                        {{ strtoupper(substr($reply->name, 0, 1)) }}
                                    </div>
                                    <span
                                        style="color:var(--text-primary);font-size:0.85rem;font-weight:600;">{{ $reply->name }}</span>
                                    <span
                                        style="color:var(--text-secondary);font-size:0.75rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p style="color:var(--text-secondary);font-size:0.875rem;line-height:1.6;">
                                    {{ $reply->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <p style="color:var(--text-secondary);text-align:center;padding:2rem 0;">No comments yet. Be the first!
                    </p>
                @endforelse

                {{-- Comment form --}}
                <div style="margin-top:3rem;">
                    <h3 class="font-display"
                        style="color:var(--text-primary);font-size:1.25rem;font-weight:700;margin-bottom:1.5rem;">Leave a
                        Comment</h3>
                    <form method="POST" action="{{ route('blogs.comment', $blog->slug) }}">
                        @csrf
                        {{-- Honeypot --}}
                        <div style="display:none;"><input type="text" name="honeypot" tabindex="-1" autocomplete="off">
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                            <div>
                                <input type="text" name="name"
                                    class="anime-input @error('name') input-error @enderror" value="{{ old('name') }}"
                                    placeholder="Your name *" required>
                                @error('name')
                                    <p class="error-msg">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="email" name="email"
                                    class="anime-input @error('email') input-error @enderror" value="{{ old('email') }}"
                                    placeholder="Your email *" required>
                                @error('email')
                                    <p class="error-msg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div style="margin-bottom:1rem;">
                            <textarea name="comment" class="anime-input @error('comment') input-error @enderror" rows="5"
                                placeholder="Write your comment…" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn-anime">Post Comment →</button>
                        {{-- <p style="color:var(--text-secondary);font-size:0.78rem;margin-top:0.75rem;">Comments are moderated
                            and appear after approval.</p> --}}
                    </form>
                </div>
            </div>
        </div>
    </article>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => Prism.highlightAll());
    </script>

    <style>
        .prose-content h1,
        .prose-content h2,
        .prose-content h3 {
            color: var(--text-primary);
            font-family: 'Playfair Display', serif;
            margin: 2rem 0 0.75rem;
            line-height: 1.3;
        }

        .prose-content h2 {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .prose-content h3 {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .prose-content p {
            margin-bottom: 1.25rem;
        }

        .prose-content a {
            color: var(--accent-1);
            text-decoration: underline;
        }

        .prose-content ul,
        .prose-content ol {
            margin: 1rem 0 1rem 1.5rem;
        }

        .prose-content li {
            margin-bottom: 0.5rem;
        }

        .prose-content blockquote {
            border-left: 3px solid var(--accent-1);
            padding-left: 1.25rem;
            color: var(--text-secondary);
            font-style: italic;
            margin: 1.5rem 0;
        }

        .prose-content code {
            background: var(--bg-secondary);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.85em;
            color: var(--accent-1);
        }

        .prose-content pre {
            background: var(--bg-secondary);
            padding: 1.25rem;
            border-radius: 0.75rem;
            overflow-x: auto;
            margin: 1.5rem 0;
        }

        .prose-content pre code {
            background: none;
            padding: 0;
            color: inherit;
        }

        .prose-content img {
            max-width: 100%;
            border-radius: 0.75rem;
            margin: 1rem 0;
        }
    </style>
@endsection
