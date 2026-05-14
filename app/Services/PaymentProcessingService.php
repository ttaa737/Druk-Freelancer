<?php

namespace App\Services;

use App\Models\CompletionSubmission;
use App\Models\Contract;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentProcessingService
{
    /**
     * Process payment when completion is verified
     * Transfers funds from job poster to freelancer and admin
     */
    public function processCompletionPayment(CompletionSubmission $submission): bool
    {
        return DB::transaction(function () use ($submission) {
            try {
                $contract = $submission->contract;
                $freelancer = $submission->freelancer;
                $poster = $contract->poster;
                $adminUser = $this->getAdminUser();

                // Get or create wallets
                $freelancerWallet = Wallet::firstOrCreate(
                    ['user_id' => $freelancer->id],
                    ['available_balance' => 0, 'escrow_balance' => 0]
                );
                $adminWallet = Wallet::firstOrCreate(
                    ['user_id' => $adminUser->id],
                    ['available_balance' => 0, 'escrow_balance' => 0]
                );

                $freelancerAmount = $contract->freelancer_amount;
                $platformFee = $contract->platform_fee;

                // Transaction 1: Transfer to Freelancer
                $this->createTransaction(
                    user: $freelancer,
                    contract: $contract,
                    type: 'completion_payment',
                    amount: $freelancerAmount,
                    fee: 0,
                    netAmount: $freelancerAmount,
                    notes: "Completion payment for {$contract->contract_number}"
                );

                // Update freelancer wallet
                $freelancerWallet->available_balance += $freelancerAmount;
                $freelancerWallet->total_earned += $freelancerAmount;
                $freelancerWallet->save();

                // Transaction 2: Transfer admin fee
                $this->createTransaction(
                    user: $adminUser,
                    contract: $contract,
                    type: 'platform_fee_earned',
                    amount: $platformFee,
                    fee: 0,
                    netAmount: $platformFee,
                    notes: "Platform fee for {$contract->contract_number}"
                );

                // Update admin wallet
                $adminWallet->available_balance += $platformFee;
                $adminWallet->total_earned += $platformFee;
                $adminWallet->save();

                // Transaction 3: Deduction from Poster
                $posterWallet = Wallet::firstOrCreate(
                    ['user_id' => $poster->id],
                    ['available_balance' => 0, 'escrow_balance' => 0]
                );

                $this->createTransaction(
                    user: $poster,
                    contract: $contract,
                    type: 'job_payment',
                    amount: $contract->total_amount,
                    fee: 0,
                    netAmount: -$contract->total_amount,
                    notes: "Payment for job {$contract->contract_number}"
                );

                // Update poster wallet
                $posterWallet->available_balance -= $contract->total_amount;
                $posterWallet->total_spent += $contract->total_amount;
                $posterWallet->save();

                // Update completion submission status
                $submission->status = CompletionSubmission::STATUS_PAYMENT_PROCESSED;
                $submission->payment_processed_at = now();
                $submission->save();

                // Update contract status
                $contract->completion_status = 'paid';
                $contract->save();

                Log::info("Payment processed successfully for completion submission #{$submission->id}", [
                    'contract_id' => $contract->id,
                    'freelancer_id' => $freelancer->id,
                    'freelancer_amount' => $freelancerAmount,
                    'platform_fee' => $platformFee,
                ]);

                return true;
            } catch (\Exception $e) {
                Log::error("Payment processing failed for completion submission #{$submission->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return false;
            }
        });
    }

    /**
     * Create a transaction record
     */
    private function createTransaction(
        User $user,
        Contract $contract,
        string $type,
        float $amount,
        float $fee,
        float $netAmount,
        string $notes
    ): Transaction {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['available_balance' => 0, 'escrow_balance' => 0]
        );

        $balanceBefore = $wallet->available_balance;
        $balanceAfter = $balanceBefore + $netAmount;

        return Transaction::create([
            'user_id' => $user->id,
            'contract_id' => $contract->id,
            'type' => $type,
            'amount' => $amount,
            'fee' => $fee,
            'net_amount' => $netAmount,
            'status' => 'completed',
            'notes' => $notes,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Get admin user (typically first super admin)
     */
    private function getAdminUser(): User
    {
        return User::where('is_admin', true)
            ->orWhereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->first() ?? User::where('is_super_admin', true)->first();
    }

    /**
     * Reverse payment (in case of rejection or cancellation)
     */
    public function reversePayment(CompletionSubmission $submission): bool
    {
        return DB::transaction(function () use ($submission) {
            try {
                $contract = $submission->contract;
                $freelancer = $submission->freelancer;
                $adminUser = $this->getAdminUser();

                $freelancerWallet = $freelancer->wallet;
                $adminWallet = $adminUser->wallet;

                $freelancerAmount = $contract->freelancer_amount;
                $platformFee = $contract->platform_fee;

                // Reverse freelancer payment
                $freelancerWallet->available_balance -= $freelancerAmount;
                $freelancerWallet->total_earned -= $freelancerAmount;
                $freelancerWallet->save();

                // Reverse admin fee
                $adminWallet->available_balance -= $platformFee;
                $adminWallet->total_earned -= $platformFee;
                $adminWallet->save();

                // Reverse poster deduction
                $posterWallet = $contract->poster->wallet;
                $posterWallet->available_balance += $contract->total_amount;
                $posterWallet->total_spent -= $contract->total_amount;
                $posterWallet->save();

                Log::info("Payment reversed for completion submission #{$submission->id}");

                return true;
            } catch (\Exception $e) {
                Log::error("Payment reversal failed for submission #{$submission->id}", [
                    'error' => $e->getMessage(),
                ]);

                return false;
            }
        });
    }
}
