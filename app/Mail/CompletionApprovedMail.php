<?php

namespace App\Mail;

use App\Models\CompletionSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompletionApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CompletionSubmission $submission)
    {
        $this->submission->load('contract.job', 'freelancer');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Work Approved & Payment Processing - ' . $this->submission->contract->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.completion.approved',
            with: [
                'freelancer' => $this->submission->freelancer,
                'contract' => $this->submission->contract,
                'submission' => $this->submission,
                'paymentAmount' => 'Nu. ' . number_format($this->submission->contract->freelancer_amount, 2),
                'walletUrl' => route('wallet.index'),
            ]
        );
    }
}
