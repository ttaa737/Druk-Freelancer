<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\DisputeCase;
use App\Models\DisputeComment;
use App\Models\DisputeEvidence;
use App\Models\Milestone;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisputeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List disputes for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        $disputes = DisputeCase::where('raised_by', $user->id)
            ->orWhere(function ($q) use ($user) {
                $q->whereHas('contract', function ($cq) use ($user) {
                    $cq->where('job_poster_id', $user->id)
                       ->orWhere('freelancer_id', $user->id);
                });
            })
            ->with(['contract.job', 'raisedBy.profile', 'assignedAdmin.profile'])
            ->latest()
            ->paginate(15);

        return view('disputes.index', compact('disputes'));
    }

    /**
     * Show a dispute + its evidence and comments.
     */
    public function show(DisputeCase $dispute)
    {
        $this->authorizeDispute($dispute);

        $dispute->load([
            'contract.job',
            'raisedBy.profile',
            'assignedAdmin.profile',
            'evidence.uploadedBy.profile',
            'comments.user.profile',
        ]);

        return view('disputes.show', compact('dispute'));
    }

    /**
     * Show the raise-dispute form.
     */
    public function create(Contract $contract)
    {
        $user = Auth::user();

        abort_if(
            $contract->job_poster_id !== $user->id && $contract->freelancer_id !== $user->id,
            403
        );
        abort_unless(in_array($contract->status, ['active', 'completed']), 403);

        $milestones = $contract->milestones()->get();

        return view('disputes.create', compact('contract', 'milestones'));
    }

    /**
     * Raise a new dispute.
     */
    public function store(Request $request, Contract $contract)
    {
        $user = Auth::user();

        abort_if(
            $contract->job_poster_id !== $user->id && $contract->freelancer_id !== $user->id,
            403
        );

        $validated = $request->validate([
            'subject'      => 'required|string|max:255',
            'description'  => 'required|string|max:5000',
            'milestone_id' => 'nullable|exists:milestones,id',
            'evidence.*'   => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $dispute = DisputeCase::create([
            'contract_id'  => $contract->id,
            'raised_by'    => $user->id,
            'subject'      => $validated['subject'],
            'description'  => $validated['description'],
            'milestone_id' => $validated['milestone_id'] ?? null,
            'status'       => 'open',
        ]);

        // Mark the contract as disputed
        $contract->update(['status' => 'disputed']);
        if ($validated['milestone_id']) {
            Milestone::find($validated['milestone_id'])?->update(['status' => 'disputed']);
        }

        // Upload any initial evidence
        foreach ($request->file('evidence', []) as $file) {
            $path = $file->store('dispute-evidence', 'public');
            $dispute->evidence()->create([
                'uploaded_by'   => $user->id,
                'file_path'      => $path,
                'original_name'  => $file->getClientOriginalName(),
                'file_type'      => $file->getMimeType(),
            ]);
        }

        NotificationService::disputeUpdate($dispute, 'opened');
        AuditLogService::log('dispute.raised', $dispute, notes: $validated['subject']);

        return redirect()->route('disputes.show', $dispute)
            ->with('success', 'Dispute raised. Our admin team will review it shortly.');
    }

    /**
     * Add evidence to an open dispute.
     */
    public function addEvidence(Request $request, DisputeCase $dispute)
    {
        $this->authorizeDispute($dispute);
        abort_unless(in_array($dispute->status, ['open', 'under_review']), 403, 'Cannot add evidence to a closed dispute.');

        $request->validate([
            'description' => 'nullable|string|max:1000',
            'files.*'     => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        foreach ($request->file('files', []) as $file) {
            $path = $file->store('dispute-evidence', 'public');
            $dispute->evidence()->create([
                'uploaded_by'   => Auth::id(),
                'file_path'      => $path,
                'original_name'  => $file->getClientOriginalName(),
                'file_type'      => $file->getMimeType(),
                'description'    => $request->description,
            ]);
        }

        return back()->with('success', 'Evidence uploaded successfully.');
    }

    /**
     * Add a comment/message to a dispute.
     */
    public function addComment(Request $request, DisputeCase $dispute)
    {
        $this->authorizeDispute($dispute);
        abort_unless(in_array($dispute->status, ['open', 'under_review']), 403);

        $request->validate(['comment' => 'required|string|max:2000']);

        $dispute->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        NotificationService::disputeUpdate($dispute, 'new_comment');

        return back()->with('success', 'Comment added.');
    }

    // ─── Private ─────────────────────────────────────────────────────────────

    private function authorizeDispute(DisputeCase $dispute): void
    {
        $user = Auth::user();
        $contract = $dispute->contract;

        $isParty = $dispute->raised_by === $user->id
            || $contract->job_poster_id === $user->id
            || $contract->freelancer_id === $user->id;

        abort_if(!$isParty && !$user->isAdmin(), 403);
    }
}

