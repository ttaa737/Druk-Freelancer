<?php

namespace App\Mail;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $otpCode,
        public string $type = 'verification',
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'withdrawal'   => 'Your Druk Freelancer Withdrawal OTP',
            'phone_verify' => 'Verify Your Phone – Druk Freelancer',
            default        => 'Your Druk Freelancer Verification Code',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp', with: [
            'user'    => $this->user,
            'code'    => $this->otpCode,
            'type'    => $this->type,
            'expires' => config('platform.otp_expires_minutes', 10),
        ]);
    }
}
