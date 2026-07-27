<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private OtpService  $otpService,
        private MailService $mailService
    ) {}

    public function show()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->where('is_admin', true)->first();

        // Always show success to prevent email enumeration
        if (! $user) {
            return back()->with('success', 'If that email exists, a reset link has been sent.');
        }

        // Generate token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email'      => $user->email,
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Generate OTP for extra verification
        $otp = $this->otpService->generate($user, 'password_reset');

        $this->mailService->sendPasswordReset($user, $token, $otp);

        return back()->with('success', 'Password reset instructions have been sent to your email.');
    }
}
