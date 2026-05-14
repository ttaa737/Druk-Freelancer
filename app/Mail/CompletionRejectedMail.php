<?php

namespace App\Mail;

use App\Models\CompletionSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompletionRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CompletionSubmission $submission)
    {
        $this->submission->load('contract.job', 'freelancer');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resubmission Required - ' . $this->submission->contract->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.completion.rejected',
            with: [
                'freelancer' => $this->submission->freelancer,
                'contract' => $this->submission->contract,
                'submission' => $this->submission,
                'rejectionReason' => $this->submission->rejection_reason ?? 'No specific reason provided',
                'resubmitUrl' => route('completion.create', $this->submission->contract),
            ]
        );
    }
}
