<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;

    public function __construct(
        public User   $user,
        public string $token,
        public string $otp
    ) {
        $this->resetUrl = route('admin.reset-password', ['token' => $token]);
    }

    public function build(): static
    {
        return $this->subject('Password Reset Request — ' . config('portfolio.site_name'))
                    ->view('emails.password-reset');
    }
}