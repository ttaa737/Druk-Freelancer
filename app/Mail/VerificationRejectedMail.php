<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $documentType,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Document Verification Update - Action Required',
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
            view: 'emails.verification.rejected',
            with: [
                'user' => $this->user,
                'documentType' => $docTypeLabel,
                'rejectionReason' => $this->reason,
                'resubmitUrl' => route('profile.edit'),
            ]
        );
    }
}
