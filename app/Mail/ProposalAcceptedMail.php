<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Proposal $proposal)
    {
        $this->proposal->load('job', 'freelancer');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Your Proposal Has Been Accepted! - ' . $this->proposal->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposals.accepted',
            with: [
                'freelancer' => $this->proposal->freelancer,
                'job' => $this->proposal->job,
                'proposal' => $this->proposal,
                'bidAmount' => 'Nu. ' . number_format($this->proposal->bid_amount, 2),
                'contractUrl' => route('contracts.index'),
            ]
        );
    }
}
