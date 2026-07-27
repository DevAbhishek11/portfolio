@extends('layouts.auth')
@section('title', 'Admin Login')

@section('content')
    <div class="auth-card p-8" style="animation: glow 4s ease-in-out infinite alternate;">

        <div class="mb-6">
            <h2 class="text-white text-2xl font-bold">Welcome back</h2>
            <p class="text-zinc-400 text-sm mt-1">Sign in to your admin panel</p>
        </div>

        @if (session('success'))
            <div class="alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-error mb-4">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="mb-5">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                    class="anime-input @error('email') border-pink-500 @enderror" value="{{ old('email') }}"
                    placeholder="admin@example.com" autocomplete="email" autofocus>
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password">Password</label>
                <div style="position:relative;">
                    <input type="password" id="password" name="password"
                        class="anime-input @error('password') border-pink-500 @enderror" placeholder="••••••••"
                        autocomplete="current-password">
                    <button type="button" onclick="togglePassword()"
                        style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#71717a;">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-6">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;margin:0;">
                    <input type="checkbox" name="remember" style="accent-color:#8b5cf6;width:1rem;height:1rem;">
                    <span style="color:#a1a1aa;font-size:0.875rem;">Remember me</span>
                </label>
                <a href="{{ route('admin.forgot-password') }}" class="anime-link">Forgot password?</a>
            </div>

            <button type="submit" class="anime-btn">
                Sign In →
            </button>
        </form>

        {{-- Decorative Japanese text --}}
        <p class="text-center mt-6" style="color:rgba(139,92,246,0.3);font-size:0.75rem;letter-spacing:0.1em;">ようこそ —
            Welcome</p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection
