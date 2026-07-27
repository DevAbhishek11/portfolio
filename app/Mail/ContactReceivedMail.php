<?php

namespace App\Mail;

use App\Models\ContactQueries;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User         $admin,
        public ContactQueries $query
    ) {}

    public function build(): static
    {
        return $this->subject('New Contact Query from ' . $this->query->name)
            ->view('emails.contact-received');
    }
}
