<?php

namespace App\Mail;

use App\Models\ChatLead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $admin,
        public ChatLead $lead
    ) {}

    public function build(): static
    {
        return $this->subject('New Lead from AI Chat — ' . $this->lead->name)
            ->view('emails.chat-lead');
    }
}
