<?php

namespace App\Mail;

use App\Models\ContactQueries;
use App\Models\ContactReplies;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactQueries $query,
        public ContactReplies $reply
    ) {}

    public function build(): static
    {
        return $this->subject($this->reply->subject)
            ->view('emails.contact-reply');
    }
}
