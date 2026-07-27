<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Services\ImageUploadService;
use App\Services\TwoFactorService;
use App\Services\OtpService;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct(
        private ImageUploadService $imageService,
        private TwoFactorService   $tfService,
        private OtpService         $otpService,
        private MailService        $mailService
    ) {}

    public function index()
    {
        $user = User::findOrFail(session('admin_user_id'));
        return view('admin.profile.index', compact('user'));
    }

    public function edit()
    {
        $user = User::findOrFail(session('admin_user_id'));
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(session('admin_user_id'));

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'title'       => 'nullable|string|max:255',
            'bio'         => 'nullable|string|max:1000',
            'phone'       => 'nullable|string|max:30',
            'location'    => 'nullable|string|max:255',
            'website'     => 'nullable|url',
            'github_url'  => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'resume'      => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('avatar')) {
            $this->imageService->delete($user->avatar);
            $data['avatar'] = $this->imageService->upload($request->file('avatar'), 'profile');
        } else {
            unset($data['avatar']);
        }

        if ($request->hasFile('resume')) {
            $file     = $request->file('resume');
            $filename = 'resume_' . time() . '.pdf';
            $file->storeAs('public/profile', $filename);
            $data['resume_url'] = '/storage/profile/' . $filename;
        }

        $user->update($data);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile updated successfully.');
    }

    public function security()
    {
        $user         = User::findOrFail(session('admin_user_id'));
        $loginHistory = LoginAttempt::where('email', $user->email)
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.profile.security', compact('user', 'loginHistory'));
    }

    public function updatePassword(Request $request)
    {
        $user = User::findOrFail(session('admin_user_id'));

        $request->validate([
            'current_password' => 'required|string',
            'password'         => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function enableTwoFactor(Request $request)
    {
        $request->validate(['method' => 'required|in:email_otp,auth_app']);
        $user = User::findOrFail(session('admin_user_id'));

        if ($request->method === 'auth_app') {
            $secret = $this->tfService->generateSecret();
            $user->update([
                'two_factor_secret' => encrypt($secret),
                'two_factor_method' => 'auth_app',
            ]);

            $uri    = $this->tfService->getQrCodeUri($user, $secret);
            $qrUrl  = $this->tfService->generateQrSvg($uri);

            return back()->with([
                'success'    => 'Scan the QR code with your authenticator app, then verify.',
                'qr_url'     => $qrUrl,
                'tf_secret'  => $secret,
                'setup_mode' => 'auth_app',
            ]);
        }

        $user->update(['two_factor_method' => 'email_otp']);
        $otp = $this->otpService->generate($user, 'login');
        $this->mailService->sendTwoFactorOtp($user, $otp);

        return back()->with([
            'success'    => 'A test OTP has been sent to your email. Enter it below to activate.',
            'setup_mode' => 'email_otp',
        ]);
    }

    public function verifyTwoFactorSetup(Request $request)
    {
        $request->validate(['code' => 'required|string|min:6|max:6']);
        $user = User::findOrFail(session('admin_user_id'));

        $verified = false;

        if ($user->two_factor_method === 'auth_app' && $user->two_factor_secret) {
            $secret   = decrypt($user->two_factor_secret);
            $verified = $this->tfService->verify($secret, $request->code);
        } elseif ($user->two_factor_method === 'email_otp') {
            $verified = $this->otpService->verify($user, $request->code, 'login');
        }

        if (! $verified) {
            return back()->with('error', 'Verification failed. Please try again.');
        }

        $user->update(['two_factor_enabled' => true]);

        return back()->with('success', 'Two-factor authentication enabled successfully.');
    }

    public function disableTwoFactor(Request $request)
    {
        $request->validate(['password' => 'required|string']);
        $user = User::findOrFail(session('admin_user_id'));

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret'  => null,
        ]);

        return back()->with('success', 'Two-factor authentication disabled.');
    }
}
