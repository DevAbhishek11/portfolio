<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Services\TwoFactorService;
use App\Services\MailService;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function __construct(
        private OtpService        $otpService,
        private TwoFactorService  $tfService,
        private MailService       $mailService
    ) {}

    public function show()
    {
        $user = User::find(session('admin_user_id'));
        if (! $user) return redirect()->route('admin.login');

        return view('auth.two-factor', ['method' => $user->two_factor_method]);
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string|min:6|max:6']);

        $user = User::find(session('admin_user_id'));
        if (! $user) return redirect()->route('admin.login');

        $verified = false;

        if ($user->two_factor_method === 'email_otp') {
            $verified = $this->otpService->verify($user, $request->code, 'login');
        } elseif ($user->two_factor_method === 'auth_app') {
            $secret   = decrypt($user->two_factor_secret);
            $verified = $this->tfService->verify($secret, $request->code);
        }

        if (! $verified) {
            return back()->with('error', 'Invalid or expired verification code.');
        }

        session(['two_factor_verified' => true]);
        $user->update(['two_factor_verified_at' => now()]);

        return redirect()->route('admin.dashboard');
    }

    public function resend(Request $request)
    {
        $user = User::find(session('admin_user_id'));

        if (! $user || $user->two_factor_method !== 'email_otp') {
            return back()->with('error', 'Cannot resend code.');
        }

        $otp = $this->otpService->generate($user, 'login');
        $this->mailService->sendTwoFactorOtp($user, $otp);

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
