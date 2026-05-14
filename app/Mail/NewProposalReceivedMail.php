<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewProposalReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Proposal $proposal)
    {
        $this->proposal->load('job', 'freelancer.profile');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📧 New Proposal Received - ' . $this->proposal->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposals.new-proposal',
            with: [
                'poster' => $this->proposal->job->poster,
                'job' => $this->proposal->job,
                'proposal' => $this->proposal,
                'freelancer' => $this->proposal->freelancer,
                'bidAmount' => 'Nu. ' . number_format($this->proposal->bid_amount, 2),
                'proposalUrl' => route('proposals.show', $this->proposal),
                'listProposalsUrl' => route('proposals.index', $this->proposal->job),
            ]
        );
    }
}
