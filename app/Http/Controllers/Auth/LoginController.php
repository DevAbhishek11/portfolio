<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\User;
use App\Services\MailService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function __construct(
        private OtpService  $otpService,
        private MailService $mailService
    ) {}

    public function showLoginForm()
    {
        if (session('admin_user_id')) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $ip       = $request->ip();
        $email    = $request->email;
        $throttleKey = 'login:' . $ip;

        // Rate limit: 5 attempts per minute per IP
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Too many login attempts. Please wait {$seconds} seconds.");
        }

        $user = User::where('email', $email)->where('is_admin', true)->first();

        // Check lockout (10 failures → 30 min lock)
        if ($user && $this->isLockedOut($email, $ip)) {
            return back()->with('error', 'Account temporarily locked due to too many failed attempts. Try again in 30 minutes.');
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            LoginAttempt::create([
                'email'          => $email,
                'ip_address'     => $ip,
                'user_agent'     => $request->userAgent(),
                'successful'     => false,
                'failure_reason' => ! $user ? 'User not found' : 'Wrong password',
            ]);

            return back()->with('error', 'Invalid credentials.')->withInput(['email' => $email]);
        }

        // Successful credential check
        RateLimiter::clear($throttleKey);

        LoginAttempt::create([
            'email'      => $email,
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'successful' => true,
        ]);

        // Store user in session (not using Laravel's built-in Auth guard intentionally)
        session()->regenerate();
        session(['admin_user_id' => $user->id]);

        // Update last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);

        // Handle 2FA
        if ($user->two_factor_enabled) {
            if ($user->two_factor_method === 'email_otp') {
                $otp = $this->otpService->generate($user, 'login');
                $this->mailService->sendTwoFactorOtp($user, $otp);
            }
            // For auth_app, no email needed — user opens their app
            return redirect()->route('admin.two-factor');
        }

        session(['two_factor_verified' => true]);
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    private function isLockedOut(string $email, string $ip): bool
    {
        $failedCount = LoginAttempt::where('email', $email)
            ->where('ip_address', $ip)
            ->where('successful', false)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();

        return $failedCount >= 10;
    }
}
