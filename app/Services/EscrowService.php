<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Milestone;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EscrowService
{
    protected float $platformFeePercent;

    public function __construct()
    {
        $this->platformFeePercent = (float) config('platform.service_fee_percent', 10);
    }

    /**
     * Fund the entire contract escrow (deposit from job poster's wallet).
     * Called when a job poster awards a contract.
     */
    public function fundContractEscrow(Contract $contract): Transaction
    {
        return DB::transaction(function () use ($contract) {
            $posterWallet = Wallet::where('user_id', $contract->poster_id)->lockForUpdate()->firstOrFail();

            if (!$posterWallet->hasSufficientFunds($contract->total_amount)) {
                throw new \Exception("Insufficient wallet balance to fund escrow. Required: Nu. " . number_format($contract->total_amount, 2));
            }

            $balanceBefore = $posterWallet->available_balance;

            // Deduct from available, add to escrow
            $posterWallet->decrement('available_balance', $contract->total_amount);
            $posterWallet->increment('escrow_balance', $contract->total_amount);
            $posterWallet->increment('total_spent', $contract->total_amount);

            $transaction = Transaction::create([
                'user_id'        => $contract->poster_id,
                'contract_id'    => $contract->id,
                'type'           => 'escrow_hold',
                'amount'         => $contract->total_amount,
                'fee'            => 0,
                'net_amount'     => $contract->total_amount,
                'status'         => 'completed',
                'payment_provider' => 'internal',
                'notes'          => "Escrow funded for Contract #{$contract->contract_number}",
                'balance_before' => $balanceBefore,
                'balance_after'  => $posterWallet->fresh()->available_balance,
                'ip_address'     => request()->ip(),
            ]);

            // Fund individual milestones if they exist
            $totalMilestones = $contract->milestones()->count();
            if ($totalMilestones > 0) {
                foreach ($contract->milestones as $milestone) {
                    $milestone->update(['escrow_held' => $milestone->amount, 'status' => 'pending']);
                }
            }

            AuditLogService::log('escrow.funded', $contract, notes: "Nu. {$contract->total_amount} held in escrow");

            return $transaction;
        });
    }

    /**
     * Release escrow funds for a specific milestone to the freelancer's wallet.
     * Called when a job poster approves a milestone.
     */
    public function releaseMilestonePayment(Milestone $milestone): Transaction
    {
        return DB::transaction(function () use ($milestone) {
            $contract = $milestone->contract;

            $platformFee = round($milestone->amount * ($this->platformFeePercent / 100), 2);
            $freelancerAmount = $milestone->amount - $platformFee;

            // Deduct from poster's escrow
            $posterWallet = Wallet::where('user_id', $contract->poster_id)->lockForUpdate()->firstOrFail();
            $posterWallet->decrement('escrow_balance', $milestone->amount);

            // Add to freelancer's available balance
            $freelancerWallet = Wallet::where('user_id', $contract->freelancer_id)->lockForUpdate()->firstOrFail();
            $freelancerBalanceBefore = $freelancerWallet->available_balance;
            $freelancerWallet->increment('available_balance', $freelancerAmount);
            $freelancerWallet->increment('total_earned', $freelancerAmount);

            // Mark milestone as paid
            $milestone->update([
                'status'     => 'paid',
                'paid_at'    => now(),
                'escrow_held' => 0,
            ]);

            // Record escrow release transaction
            $transaction = Transaction::create([
                'user_id'        => $contract->freelancer_id,
                'contract_id'    => $contract->id,
                'milestone_id'   => $milestone->id,
                'type'           => 'escrow_release',
                'amount'         => $milestone->amount,
                'fee'            => $platformFee,
                'net_amount'     => $freelancerAmount,
                'status'         => 'completed',
                'payment_provider' => 'internal',
                'notes'          => "Payment released for milestone: {$milestone->title}",
                'balance_before' => $freelancerBalanceBefore,
                'balance_after'  => $freelancerWallet->fresh()->available_balance,
                'ip_address'     => request()->ip(),
            ]);

            // Record platform fee transaction
            Transaction::create([
                'user_id'        => $contract->freelancer_id,
                'contract_id'    => $contract->id,
                'milestone_id'   => $milestone->id,
                'type'           => 'platform_fee',
                'amount'         => $platformFee,
                'fee'            => 0,
                'net_amount'     => $platformFee,
                'status'         => 'completed',
                'payment_provider' => 'internal',
                'notes'          => "Platform service fee ({$this->platformFeePercent}%) for milestone: {$milestone->title}",
                'ip_address'     => request()->ip(),
            ]);

            // Check if all milestones are paid → complete contract
            $unpaidMilestones = $contract->milestones()->whereNotIn('status', ['paid'])->count();
            if ($unpaidMilestones === 0) {
                $contract->update(['status' => 'completed', 'completed_at' => now()]);
                AuditLogService::log('contract.completed', $contract);
            }

            AuditLogService::log('escrow.released', $milestone, notes: "Nu. {$freelancerAmount} released to freelancer");

            return $transaction;
        });
    }

    /**
     * Refund escrow back to the job poster (on contract cancellation/dispute resolution).
     */
    public function refundEscrow(Contract $contract, float $refundAmount, string $reason): Transaction
    {
        return DB::transaction(function () use ($contract, $refundAmount, $reason) {
            $posterWallet = Wallet::where('user_id', $contract->poster_id)->lockForUpdate()->firstOrFail();
            $balanceBefore = $posterWallet->available_balance;

            $posterWallet->decrement('escrow_balance', $refundAmount);
            $posterWallet->increment('available_balance', $refundAmount);
            $posterWallet->decrement('total_spent', $refundAmount);

            $transaction = Transaction::create([
                'user_id'        => $contract->poster_id,
                'contract_id'    => $contract->id,
                'type'           => 'refund',
                'amount'         => $refundAmount,
                'fee'            => 0,
                'net_amount'     => $refundAmount,
                'status'         => 'completed',
                'payment_provider' => 'internal',
                'notes'          => "Escrow refund: {$reason}",
                'balance_before' => $balanceBefore,
                'balance_after'  => $posterWallet->fresh()->available_balance,
                'ip_address'     => request()->ip(),
            ]);

            AuditLogService::log('escrow.refunded', $contract, notes: "Nu. {$refundAmount} refunded - {$reason}");

            return $transaction;
        });
    }

    /**
     * Calculate platform fees.
     */
    public function calculateFee(float $amount): array
    {
        $fee = round($amount * ($this->platformFeePercent / 100), 2);
        return [
            'gross_amount'    => $amount,
            'platform_fee'    => $fee,
            'fee_percent'     => $this->platformFeePercent,
            'freelancer_gets' => $amount - $fee,
        ];
    }
}
