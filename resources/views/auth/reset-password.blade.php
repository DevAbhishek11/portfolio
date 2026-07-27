@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
    <div class="auth-card p-8">

        <div class="mb-6">
            <h2 class="text-white text-2xl font-bold">Set New Password</h2>
            <p class="text-zinc-400 text-sm mt-1">Enter the OTP from your email and your new password.</p>
        </div>

        @if (session('error'))
            <div class="alert-error mb-4">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.reset-password.submit') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email (needed to look up user) --}}
            <div class="mb-4">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                    class="anime-input @error('email') border-pink-500 @enderror" value="{{ old('email') }}"
                    placeholder="admin@example.com" autofocus>
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- OTP --}}
            <div class="mb-4">
                <label>OTP Code (from email)</label>
                <div class="flex gap-2 mb-1">
                    @for ($i = 1; $i <= 6; $i++)
                        <input type="text" maxlength="1" class="otp-input" id="r-otp-{{ $i }}"
                            inputmode="numeric" autocomplete="off">
                    @endfor
                </div>
                <input type="hidden" name="otp" id="reset-otp">
                @error('otp')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div class="mb-4">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password"
                    class="anime-input @error('password') border-pink-500 @enderror"
                    placeholder="Min 8 chars, upper, lower, number, symbol" oninput="checkStrength(this.value)">
                <div
                    style="margin-top:0.5rem;height:4px;background:rgba(255,255,255,0.08);border-radius:9999px;overflow:hidden;">
                    <div id="strength-bar" class="strength-bar" style="width:0%;background:#f43f5e;"></div>
                </div>
                <p id="strength-text" style="font-size:0.75rem;color:#52525b;margin-top:0.25rem;"></p>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="anime-input"
                    placeholder="Repeat your new password">
            </div>

            <button type="submit" class="anime-btn">Reset Password →</button>
        </form>
    </div>

    <script>
        // OTP boxes
        const otpInputs = document.querySelectorAll('[id^="r-otp-"]');
        const resetOtp = document.getElementById('reset-otp');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '').slice(-1);
                if (input.value && index < otpInputs.length - 1) otpInputs[index + 1].focus();
                resetOtp.value = Array.from(otpInputs).map(i => i.value).join('');
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) otpInputs[index - 1].focus();
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasted = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                pasted.split('').forEach((char, i) => {
                    if (otpInputs[i]) otpInputs[i].value = char;
                });
                resetOtp.value = pasted.padEnd(6, '').slice(0, 6);
                otpInputs[Math.min(pasted.length, 5)].focus();
            });
        });

        // Password strength meter
        function checkStrength(val) {
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            const pcts = ['0%', '20%', '40%', '60%', '80%', '100%'];
            const colors = ['#f43f5e', '#f43f5e', '#f97316', '#eab308', '#22c55e', '#22c55e'];
            const labels = ['', 'Very weak', 'Weak', 'Fair', 'Strong', 'Very strong'];

            bar.style.width = pcts[score];
            bar.style.background = colors[score];
            text.textContent = labels[score];
            text.style.color = colors[score];
        }
    </script>
@endsection
