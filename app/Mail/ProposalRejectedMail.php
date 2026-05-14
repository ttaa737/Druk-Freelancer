<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Proposal $proposal)
    {
        $this->proposal->load('job', 'freelancer');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Proposal Update - ' . $this->proposal->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposals.rejected',
            with: [
                'freelancer' => $this->proposal->freelancer,
                'job' => $this->proposal->job,
                'proposal' => $this->proposal,
                'rejectionReason' => $this->proposal->rejection_reason ?? 'No reason provided',
                'browsJobsUrl' => route('jobs.index'),
            ]
        );
    }
}
