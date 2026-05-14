<?php

namespace App\Mail;

use App\Models\CompletionSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompletionSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CompletionSubmission $submission)
    {
        $this->submission->load('contract.poster', 'contract.job', 'freelancer');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Work Submitted for Review - ' . $this->submission->contract->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.completion.submitted',
            with: [
                'poster' => $this->submission->contract->poster,
                'contract' => $this->submission->contract,
                'submission' => $this->submission,
                'freelancer' => $this->submission->freelancer,
                'adminUrl' => route('admin.completions.index'),
            ]
        );
    }
}
