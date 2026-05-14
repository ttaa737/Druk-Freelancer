<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalShortlistedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Proposal $proposal)
    {
        $this->proposal->load('job', 'freelancer');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⭐ You\'ve Been Shortlisted! - ' . $this->proposal->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposals.shortlisted',
            with: [
                'freelancer' => $this->proposal->freelancer,
                'job' => $this->proposal->job,
                'proposal' => $this->proposal,
                'bidAmount' => 'Nu. ' . number_format($this->proposal->bid_amount, 2),
                'proposalUrl' => route('proposals.show', $this->proposal),
            ]
        );
    }
}
