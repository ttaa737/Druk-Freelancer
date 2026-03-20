<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlatformNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $recipient,
        public string $mailSubject,
        public string $message,
        public ?string $actionUrl = null,
        public ?string $actionLabel = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[Druk Freelancer] ' . $this->mailSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.notification', with: [
            'userName'    => $this->recipient->name,
            'subject'     => $this->mailSubject,
            'body'        => $this->message,
            'actionUrl'   => $this->actionUrl,
            'actionLabel' => $this->actionLabel,
        ]);
    }
}
