<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\RememberToken;
use App\Models\User;
use App\Services\MailService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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

        if ($request->boolean('remember')) {
            $this->rememberUser($user, $request);
        }

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
        $this->forgetRememberCookie($request);
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

    /**
     * Issue a 30-day "remember me" cookie backed by a hashed token row,
     * independent of the normal session lifetime.
     */
    private function rememberUser(User $user, Request $request): void
    {
        $rawToken = bin2hex(random_bytes(32));

        RememberToken::create([
            'user_id'    => $user->id,
            'token'      => hash('sha256', $rawToken),
            'expires_at' => now()->addDays(30),
        ]);

        Cookie::queue(Cookie::make(
            'admin_remember',
            $user->id . '|' . $rawToken,
            60 * 24 * 30,
            null,
            null,
            $request->secure(),
            true,
            false,
            'lax'
        ));
    }

    private function forgetRememberCookie(Request $request): void
    {
        $cookie = $request->cookie('admin_remember');

        if ($cookie && str_contains($cookie, '|')) {
            [$userId, $rawToken] = explode('|', $cookie, 2);

            RememberToken::where('user_id', $userId)
                ->get()
                ->each(function (RememberToken $t) use ($rawToken) {
                    if (hash_equals($t->token, hash('sha256', $rawToken))) {
                        $t->delete();
                    }
                });
        }

        Cookie::queue(Cookie::forget('admin_remember'));
    }
}
