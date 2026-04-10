<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Proposal;
use App\Services\AuditLogService;
use App\Services\EscrowService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function __construct(private EscrowService $escrow)
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Submit a new proposal (freelancer only).
     */
    public function store(Request $request, Job $job)
    {
        abort_unless(Auth::user()->isFreelancer(), 403, 'Only freelancers can submit proposals.');
        abort_if($job->status !== 'open', 422, 'This job is no longer accepting proposals.');
        abort_if(Proposal::where('job_id', $job->id)->where('freelancer_id', Auth::id())->exists(), 422, 'You have already submitted a proposal for this job.');

        $validated = $request->validate([
            'cover_letter'           => 'required|string|min:50|max:3000',
            'bid_amount'             => 'required|numeric|min:100|max:9999999',
            'delivery_days'          => 'required|integer|min:1|max:365',
            'milestones'             => 'nullable|array|min:1|max:10',
            'milestones.*.title'     => 'required_with:milestones|string|max:200',
            'milestones.*.amount'    => 'required_with:milestones|numeric|min:100',
            'milestones.*.duration'  => 'required_with:milestones|integer|min:1',
        ]);

        $proposal = DB::transaction(function () use ($job, $validated) {
            $proposal = Proposal::create([
                'job_id'        => $job->id,
                'freelancer_id' => Auth::id(),
                'cover_letter'  => $validated['cover_letter'],
                'bid_amount'    => $validated['bid_amount'],
                'delivery_days' => $validated['delivery_days'],
            ]);

            if (!empty($validated['milestones'])) {
                $i = 1;
                foreach ($validated['milestones'] as $ms) {
                    $proposal->milestones()->create([
                        'title'        => $ms['title'],
                        'amount'       => $ms['amount'],
                        'duration_days' => $ms['duration'],
                        'sort_order'   => $i++,
                    ]);
                }
            }

            $job->increment('proposals_count');

            return $proposal;
        });

        NotificationService::newProposalReceived($job->poster, $proposal->load('freelancer', 'job'));
        AuditLogService::log('proposal.submitted', $proposal, notes: "Bid: Nu.{$validated['bid_amount']} for job #{$job->id}");

        return redirect()->route('jobs.show', $job->slug)
                         ->with('success', 'Your proposal has been submitted successfully!');
    }

    /**
     * List proposals for a job (poster only).
     */
    public function index(Job $job)
    {
        $this->authorize('update', $job); // Only poster

        $proposals = Proposal::with(['freelancer.profile', 'freelancer.skills', 'milestones'])
                             ->where('job_id', $job->id)
                             ->orderByDesc('is_shortlisted')
                             ->latest()
                             ->paginate(10);

        return view('proposals.index', compact('job', 'proposals'));
    }

    /**
     * View a proposal (poster and the freelancer).
     */
    public function show(Proposal $proposal)
    {
        $this->authorize('view', $proposal);
        $proposal->load(['job', 'freelancer.profile', 'freelancer.reviews', 'freelancer.certifications', 'milestones']);

        return view('proposals.show', compact('proposal'));
    }

    /**
     * Shortlist a proposal (poster only).
     */
    public function shortlist(Proposal $proposal)
    {
        $this->authorize('update', $proposal->job);

        $proposal->update(['is_shortlisted' => !$proposal->is_shortlisted, 'shortlisted_at' => now()]);
        $proposal->update(['status' => $proposal->is_shortlisted ? 'shortlisted' : 'pending']);

        if ($proposal->is_shortlisted) {
            NotificationService::proposalStatusChanged($proposal->freelancer, $proposal);
        }

        return back()->with('success', $proposal->is_shortlisted ? 'Proposal shortlisted.' : 'Proposal removed from shortlist.');
    }

    /**
     * Award a contract to a freelancer (accept proposal).
     */
    public function award(Request $request, Proposal $proposal)
    {
        $this->authorize('award', $proposal);

        abort_if($proposal->job->contracts()->where('status', 'active')->exists(), 422, 'An active contract already exists for this job.');

        $validated = $request->validate(['terms' => 'nullable|string|max:5000']);

        DB::transaction(function () use ($proposal, $validated) {
            $job = $proposal->job;
            $feeCalc = $this->escrow->calculateFee($proposal->bid_amount);

            /** @var Contract $contract */
            $contract = Contract::create([
                'job_id'            => $job->id,
                'proposal_id'       => $proposal->id,
                'poster_id'         => Auth::id(),
                'freelancer_id'     => $proposal->freelancer_id,
                'terms'             => $validated['terms'] ?? null,
                'total_amount'      => $proposal->bid_amount,
                'platform_fee'      => $feeCalc['platform_fee'],
                'freelancer_amount' => $feeCalc['freelancer_gets'],
                'start_date'        => now(),
                'deadline'          => now()->addDays($proposal->delivery_days),
            ]);

            // Create milestones from proposal milestones
            if ($proposal->milestones()->count() > 0) {
                $due = now();
                foreach ($proposal->milestones()->orderBy('sort_order')->get() as $ms) {
                    $due->addDays($ms->duration_days);
                    $contract->milestones()->create([
                        'title'       => $ms->title,
                        'description' => $ms->description,
                        'amount'      => $ms->amount,
                        'due_date'    => $due->copy(),
                        'sort_order'  => $ms->sort_order,
                        'status'      => 'pending',
                    ]);
                }
            } else {
                // Single milestone for the entire project
                $contract->milestones()->create([
                    'title'  => 'Project Completion',
                    'amount' => $proposal->bid_amount,
                    'due_date' => now()->addDays($proposal->delivery_days),
                    'sort_order' => 1,
                ]);
            }

            // Update proposal and job status
            $proposal->update(['status' => 'accepted', 'awarded_at' => now()]);
            Proposal::where('job_id', $job->id)->where('id', '!=', $proposal->id)->update(['status' => 'rejected']);
            $job->update(['status' => 'in_progress', 'awarded_at' => now()]);

            NotificationService::contractCreated($proposal->freelancer, $contract);
            AuditLogService::log('contract.awarded', $contract, notes: "Contract #{$contract->contract_number} created");
        });

        return redirect()->route('contracts.show', $proposal->job->contracts()->latest()->first()->id)
                         ->with('success', 'Contract created! Please fund the escrow to begin the project.');
    }

    /**
     * Reject a proposal (poster only).
     */
    public function reject(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal->job);

        $proposal->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        NotificationService::proposalStatusChanged($proposal->freelancer, $proposal);

        return back()->with('success', 'Proposal rejected.');
    }

    /**
     * Withdraw own proposal (freelancer only).
     */
    public function withdraw(Proposal $proposal)
    {
        abort_unless($proposal->freelancer_id === Auth::id(), 403);
        abort_if(in_array($proposal->status, ['accepted']), 422, 'Cannot withdraw an accepted proposal.');

        $proposal->update(['status' => 'withdrawn']);

        return redirect()->route('dashboard')->with('success', 'Proposal withdrawn.');
    }

    /**
     * My submitted proposals (freelancer dashboard).
     */
    public function myProposals()
    {
        $proposals = Proposal::with(['job.category', 'job.poster.profile', 'milestones'])
                             ->where('freelancer_id', Auth::id())
                             ->latest()
                             ->paginate(10);

        return view('proposals.my-proposals', compact('proposals'));
    }
}

