<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisputeCase;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\EscrowService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDisputeController extends Controller
{
    public function __construct(private EscrowService $escrow)
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = DisputeCase::with(['contract.job', 'raisedBy.profile', 'assignedAdmin.profile']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('assigned')) {
            $request->assigned === 'me'
                ? $query->where('assigned_admin_id', Auth::id())
                : $query->whereNull('assigned_admin_id');
        }

        $disputes = $query->latest()->paginate(20)->withQueryString();

        return view('admin.disputes.index', compact('disputes'));
    }

    public function show(DisputeCase $dispute)
    {
        $dispute->load([
            'contract.job', 'contract.poster.profile', 'contract.freelancer.profile',
            'raisedBy.profile', 'assignedAdmin.profile',
            'evidence.submittedBy.profile',
            'comments.user.profile',
            'contract.milestones',
        ]);

        return view('admin.disputes.show', compact('dispute'));
    }

    public function assign(DisputeCase $dispute)
    {
        $dispute->update([
            'assigned_admin_id' => Auth::id(),
            'status'            => 'under_review',
        ]);

        AuditLogService::log('dispute.assigned', $dispute);

        return back()->with('success', 'Dispute assigned to you and marked Under Review.');
    }

    public function resolve(Request $request, DisputeCase $dispute)
    {
        $request->validate([
            'resolution'      => 'required|in:favour_poster,favour_freelancer,split',
            'resolution_note' => 'required|string|max:2000',
            'freelancer_percent' => 'required_if:resolution,split|nullable|integer|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $dispute) {
            $contract  = $dispute->contract;
            $escrowBal = $contract->escrow_held ?? 0;

            if ($request->resolution === 'favour_poster') {
                // Full refund to poster
                $this->escrow->refundEscrow($contract, $escrowBal, 'Dispute resolved in favour of job poster.');
                $contract->freelancer->wallet?->update(['is_frozen' => false]);
            } elseif ($request->resolution === 'favour_freelancer') {
                // Release to freelancer
                foreach ($contract->milestones()->where('status', 'disputed')->get() as $ms) {
                    $this->escrow->releaseMilestonePayment($ms);
                }
            } elseif ($request->resolution === 'split') {
                $freelancerPct = (int) $request->freelancer_percent;
                $posterPct = 100 - $freelancerPct;

                $posterAmt    = bcmul($escrowBal, $posterPct / 100, 2);
                $freelancerAmt = bcmul($escrowBal, $freelancerPct / 100, 2);

                if ($posterAmt > 0) {
                    $this->escrow->refundEscrow($contract, $posterAmt, "Dispute split: {$posterPct}% to poster.");
                }
                if ($freelancerAmt > 0) {
                    // Direct credit to freelancer wallet
                    $contract->freelancer->wallet()->increment('available_balance', $freelancerAmt);
                    $contract->freelancer->wallet()->increment('total_earned', $freelancerAmt);
                }
            }

            $resolvedStatus = match ($request->resolution) {
                'favour_poster' => 'resolved_poster',
                'favour_freelancer' => 'resolved_freelancer',
                default => 'resolved_split',
            };

            $dispute->update([
                'status'                 => $resolvedStatus,
                'resolution_notes'       => $request->resolution_note,
                'poster_refund_amount'   => $request->resolution === 'favour_poster' ? $escrowBal : ($request->resolution === 'split' ? $posterAmt : 0),
                'freelancer_payout_amount' => $request->resolution === 'favour_freelancer' ? $escrowBal : ($request->resolution === 'split' ? $freelancerAmt : 0),
                'resolved_at'            => now(),
            ]);

            $contract->update(['status' => 'cancelled']);

            // Unfreeze wallets
            $contract->poster->wallet?->update(['is_frozen' => false]);
            $contract->freelancer->wallet?->update(['is_frozen' => false]);

            NotificationService::disputeUpdate($contract->poster, $dispute, 'Your dispute case has been resolved by admin.');
            NotificationService::disputeUpdate($contract->freelancer, $dispute, 'Your dispute case has been resolved by admin.');
            AuditLogService::log('dispute.resolved', $dispute, notes: $request->resolution);
        });

        return redirect()->route('admin.disputes.index')->with('success', 'Dispute resolved successfully.');
    }
}

