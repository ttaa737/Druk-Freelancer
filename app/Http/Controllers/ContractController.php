<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\AuditLogService;
use App\Services\EscrowService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function __construct(private EscrowService $escrow)
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index()
    {
        $user = Auth::user();
        $query = Contract::with(['job', 'poster.profile', 'freelancer.profile', 'milestones']);

        if ($user->isFreelancer()) {
            $contracts = $query->where('freelancer_id', $user->id)->latest()->paginate(10);
        } else {
            $contracts = $query->where('poster_id', $user->id)->latest()->paginate(10);
        }

        return view('contracts.index', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $this->authorize('view', $contract);
        $contract->load([
            'job', 'poster.profile', 'freelancer.profile',
            'milestones.attachments', 'reviews', 'dispute', 'invoice',
        ]);

        return view('contracts.show', compact('contract'));
    }

    /**
     * Fund the escrow for a contract (poster only).
     */
    public function fundEscrow(Contract $contract)
    {
        $this->authorize('update', $contract);
        abort_unless(Auth::id() === $contract->poster_id, 403);

        try {
            $transaction = $this->escrow->fundContractEscrow($contract);
            NotificationService::send(
                $contract->freelancer,
                'escrow_funded',
                'Escrow Funded - Project Ready to Start!',
                "The job poster has funded Nu. " . number_format($contract->total_amount, 2) . " in escrow for contract #{$contract->contract_number}. You can now begin working!",
                ['contract_id' => $contract->id],
                true
            );

            return redirect()->route('contracts.show', $contract->id)
                             ->with('success', 'Escrow funded successfully! The freelancer has been notified to begin work.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Sign the contract (both parties must sign before work begins).
     */
    public function sign(Contract $contract)
    {
        $this->authorize('view', $contract);
        $user = Auth::user();

        if ($user->id === $contract->poster_id) {
            $contract->update(['poster_signed' => true]);
        } elseif ($user->id === $contract->freelancer_id) {
            $contract->update(['freelancer_signed' => true]);
        } else {
            abort(403);
        }

        AuditLogService::log('contract.signed', $contract, notes: "Signed by user #{$user->id}");

        return back()->with('success', 'Contract signed successfully.');
    }

    /**
     * Cancel a contract.
     */
    public function cancel(Request $request, Contract $contract)
    {
        $this->authorize('view', $contract);

        $request->validate(['reason' => 'required|string|max:1000']);

        try {
            // Refund any remaining escrow to poster
            $escrowBalance = $contract->poster->wallet->escrow_balance;
            if ($escrowBalance > 0) {
                $this->escrow->refundEscrow($contract, $escrowBalance, "Contract cancelled: {$request->reason}");
            }

            $contract->update([
                'status'              => 'cancelled',
                'cancelled_at'        => now(),
                'cancellation_reason' => $request->reason,
            ]);

            AuditLogService::log('contract.cancelled', $contract, notes: $request->reason);

            NotificationService::send(
                $contract->poster_id === Auth::id() ? $contract->freelancer : $contract->poster,
                'contract_cancelled',
                'Contract Cancelled',
                "Contract #{$contract->contract_number} has been cancelled. Reason: {$request->reason}",
                ['contract_id' => $contract->id],
                true
            );

            return redirect()->route('dashboard')
                             ->with('success', 'Contract cancelled. Escrow funds have been refunded.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

