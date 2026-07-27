@extends('layouts.auth')
@section('title', 'Two-Factor Verification')

@section('content')
    <div class="auth-card p-8">

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                style="background:rgba(139,92,246,0.15);border:1px solid rgba(139,92,246,0.3);">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24"
                    stroke="#8b5cf6" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </div>
            <h2 class="text-white text-2xl font-bold">Verify Your Identity</h2>
            <p class="text-zinc-400 text-sm mt-1">
                @if ($method === 'email_otp')
                    Enter the 6-digit code sent to your email
                @else
                    Enter the code from your authenticator app
                @endif
            </p>
        </div>

        @if (session('success'))
            <div class="alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-error mb-4">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.two-factor.verify') }}">
            @csrf

            {{-- OTP digit boxes --}}
            <div class="flex justify-center gap-3 mb-6">
                @for ($i = 1; $i <= 6; $i++)
                    <input type="text" maxlength="1" class="otp-input" id="otp-{{ $i }}"
                        data-index="{{ $i }}" inputmode="numeric" autocomplete="off">
                @endfor
            </div>
            <input type="hidden" name="code" id="full-code">

            @error('code')
                <p class="field-error text-center mb-4">{{ $message }}</p>
            @enderror

            <button type="submit" class="anime-btn mb-4" id="verify-btn" disabled style="opacity:0.5;">
                Verify Code →
            </button>
        </form>

        @if ($method === 'email_otp')
            <form method="POST" action="{{ route('admin.two-factor.resend') }}" class="text-center">
                @csrf
                <button type="submit" class="anime-link" style="background:none;border:none;cursor:pointer;">
                    Didn't receive the code? Resend
                </button>
            </form>
        @else
            <p class="text-center text-zinc-500 text-sm">Open your authenticator app to get the current code.</p>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('admin.login') }}" class="anime-link" style="font-size:0.8rem;color:#52525b;">
                ← Back to login
            </a>
        </div>
    </div>

    <script>
        // OTP box auto-advance
        const inputs = document.querySelectorAll('.otp-input');
        const fullCode = document.getElementById('full-code');
        const verifyBtn = document.getElementById('verify-btn');

        function updateCode() {
            const code = Array.from(inputs).map(i => i.value).join('');
            fullCode.value = code;
            if (code.length === 6) {
                verifyBtn.disabled = false;
                verifyBtn.style.opacity = '1';
            } else {
                verifyBtn.disabled = true;
                verifyBtn.style.opacity = '0.5';
            }
        }

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Only digits
                input.value = input.value.replace(/[^0-9]/g, '').slice(-1);

                if (input.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateCode();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasted = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                pasted.split('').forEach((char, i) => {
                    if (inputs[i]) inputs[i].value = char;
                });
                updateCode();
                inputs[Math.min(pasted.length, 5)].focus();
            });
        });

        // Focus first input
        inputs[0].focus();
    </script>
@endsection
