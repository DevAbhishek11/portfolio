<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User   $user,
        public string $otp
    ) {}

    public function build(): static
    {
        return $this->subject('Your Login Verification Code — ' . config('portfolio.site_name'))
                    ->view('emails.two-factor-otp');
    }
}