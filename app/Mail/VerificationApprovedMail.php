<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $documentType
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Verification Approved - Your Account is Now Verified!',
        );
    }

    public function content(): Content
    {
        $docTypeLabel = match($this->documentType) {
            'cid' => 'CID',
            'license' => 'Professional License',
            'brn' => 'Business Registration',
            'education' => 'Education Certificate',
            'tax_certificate' => 'Tax Clearance Certificate',
            default => ucfirst($this->documentType)
        };

        return new Content(
            view: 'emails.verification.approved',
            with: [
                'user' => $this->user,
                'documentType' => $docTypeLabel,
                'verificationStatus' => $this->user->verification_status,
                'dashboardUrl' => route('dashboard'),
            ]
        );
    }
}
