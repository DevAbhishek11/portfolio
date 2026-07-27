<?php

namespace App\Services;

use App\Models\TwoFactorToken;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function generate(User $user, string $type): string
    {
        // Invalidate previous unused tokens of same type
        TwoFactorToken::where('user_id', $user->id)
            ->where('type', $type)
            ->where('used', false)
            ->delete();

        $otp    = (string) random_int(100000, 999999);
        $expiry = $type === 'login'
            ? config('portfolio.admin.otp_expiry_minutes', 10)
            : config('portfolio.admin.reset_expiry_minutes', 60);

        TwoFactorToken::create([
            'user_id'    => $user->id,
            'token'      => Hash::make($otp),
            'type'       => $type,
            'used'       => false,
            'expires_at' => now()->addMinutes($expiry),
        ]);

        return $otp;
    }

    public function verify(User $user, string $otp, string $type): bool
    {
        $token = TwoFactorToken::where('user_id', $user->id)
            ->where('type', $type)
            ->where('used', false)
            ->latest()
            ->first();

        if (! $token || ! $token->isValid()) {
            return false;
        }

        if (! Hash::check($otp, $token->token)) {
            return false;
        }

        $token->update(['used' => true]);

        return true;
    }
}
