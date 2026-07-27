@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
    <div class="auth-card p-8">

        <div class="mb-6">
            <a href="{{ route('admin.login') }}" class="anime-link text-xs mb-4 inline-block">← Back to login</a>
            <h2 class="text-white text-2xl font-bold">Reset Password</h2>
            <p class="text-zinc-400 text-sm mt-1">Enter your email and we'll send reset instructions.</p>
        </div>

        @if (session('success'))
            <div class="alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-error mb-4">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.forgot-password.send') }}">
            @csrf
            <div class="mb-5">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                    class="anime-input @error('email') border-pink-500 @enderror" value="{{ old('email') }}"
                    placeholder="admin@example.com" autofocus>
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="anime-btn">
                Send Reset Instructions →
            </button>
        </form>

        <p class="text-center mt-6" style="color:rgba(139,92,246,0.3);font-size:0.75rem;letter-spacing:0.1em;">パスワードリセット</p>
    </div>
@endsection
