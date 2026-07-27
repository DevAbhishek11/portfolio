<?php

namespace App\Services;

use App\Mail\TwoFactorOtpMail;
use App\Mail\PasswordResetMail;
use App\Mail\ContactReplyMail;
use App\Mail\ContactReceivedMail;
use App\Models\User;
use App\Models\ContactQueries as ContactQuery;
use App\Models\ContactReplies as ContactReply;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendTwoFactorOtp(User $user, string $otp): void
    {
        Mail::to($user->email)->send(new TwoFactorOtpMail($user, $otp));
    }

    public function sendPasswordReset(User $user, string $token, string $otp): void
    {
        Mail::to($user->email)->send(new PasswordResetMail($user, $token, $otp));
    }

    public function sendContactReceived(User $admin, ContactQuery $query): void
    {
        Mail::to($admin->email)->send(new ContactReceivedMail($admin, $query));
    }

    public function sendContactReply(ContactQuery $query, ContactReply $reply): void
    {
        Mail::to($query->email)->send(new ContactReplyMail($query, $reply));
    }
}
