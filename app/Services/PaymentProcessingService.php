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
        try {
            return DB::transaction(function () use ($submission) {
                $contract = $submission->contract;
                $freelancer = $submission->freelancer;
                $poster = $contract->poster;
                $adminUser = $this->getAdminUser();

                if (!$adminUser) {
                    throw new \RuntimeException('No admin user found to receive platform fee.');
                }

                Wallet::firstOrCreate(['user_id' => $poster->id], ['available_balance' => 0, 'escrow_balance' => 0]);
                Wallet::firstOrCreate(['user_id' => $freelancer->id], ['available_balance' => 0, 'escrow_balance' => 0]);
                Wallet::firstOrCreate(['user_id' => $adminUser->id], ['available_balance' => 0, 'escrow_balance' => 0]);

                $posterWallet = Wallet::where('user_id', $poster->id)->lockForUpdate()->firstOrFail();
                $freelancerWallet = Wallet::where('user_id', $freelancer->id)->lockForUpdate()->firstOrFail();
                $adminWallet = Wallet::where('user_id', $adminUser->id)->lockForUpdate()->firstOrFail();

                $totalAmount = (float) $contract->total_amount;
                $freelancerAmount = (float) $contract->freelancer_amount;
                $platformFee = (float) $contract->platform_fee;

                $source = 'escrow';
                $posterBalanceBefore = (float) $posterWallet->escrow_balance;

                if ($posterWallet->escrow_balance >= $totalAmount) {
                    $posterWallet->decrement('escrow_balance', $totalAmount);
                    $posterBalanceAfter = (float) $posterWallet->fresh()->escrow_balance;
                } elseif ($posterWallet->available_balance >= $totalAmount) {
                    // Legacy fallback if old contracts were not escrow funded.
                    $source = 'available_balance';
                    $posterBalanceBefore = (float) $posterWallet->available_balance;
                    $posterWallet->decrement('available_balance', $totalAmount);
                    $posterWallet->increment('total_spent', $totalAmount);
                    $posterBalanceAfter = (float) $posterWallet->fresh()->available_balance;
                } else {
                    throw new \RuntimeException('Insufficient poster funds to process completion payment.');
                }

                $freelancerBalanceBefore = (float) $freelancerWallet->available_balance;
                $freelancerWallet->increment('available_balance', $freelancerAmount);
                $freelancerWallet->increment('total_earned', $freelancerAmount);
                $freelancerBalanceAfter = (float) $freelancerWallet->fresh()->available_balance;

                $adminBalanceBefore = (float) $adminWallet->available_balance;
                $adminWallet->increment('available_balance', $platformFee);
                $adminWallet->increment('total_earned', $platformFee);
                $adminBalanceAfter = (float) $adminWallet->fresh()->available_balance;

                Transaction::create([
                    'user_id' => $poster->id,
                    'contract_id' => $contract->id,
                    'type' => 'completion_settlement',
                    'amount' => $totalAmount,
                    'fee' => 0,
                    'net_amount' => -$totalAmount,
                    'status' => 'completed',
                    'notes' => "Completion settlement for {$contract->contract_number} (source: {$source})",
                    'balance_before' => $posterBalanceBefore,
                    'balance_after' => $posterBalanceAfter,
                    'ip_address' => request()->ip(),
                ]);

                Transaction::create([
                    'user_id' => $freelancer->id,
                    'contract_id' => $contract->id,
                    'type' => 'completion_payment',
                    'amount' => $freelancerAmount,
                    'fee' => 0,
                    'net_amount' => $freelancerAmount,
                    'status' => 'completed',
                    'notes' => "Completion payment for {$contract->contract_number}",
                    'balance_before' => $freelancerBalanceBefore,
                    'balance_after' => $freelancerBalanceAfter,
                    'ip_address' => request()->ip(),
                ]);

                Transaction::create([
                    'user_id' => $adminUser->id,
                    'contract_id' => $contract->id,
                    'type' => 'platform_fee_earned',
                    'amount' => $platformFee,
                    'fee' => 0,
                    'net_amount' => $platformFee,
                    'status' => 'completed',
                    'notes' => "Platform fee for {$contract->contract_number}",
                    'balance_before' => $adminBalanceBefore,
                    'balance_after' => $adminBalanceAfter,
                    'ip_address' => request()->ip(),
                ]);

                $submission->status = CompletionSubmission::STATUS_PAYMENT_PROCESSED;
                $submission->payment_processed_at = now();
                $submission->save();

                $contract->completion_status = 'paid';
                $contract->status = 'completed';
                $contract->completed_at = now();
                $contract->save();

                Log::info("Payment processed successfully for completion submission #{$submission->id}", [
                    'contract_id' => $contract->id,
                    'freelancer_id' => $freelancer->id,
                    'poster_id' => $poster->id,
                    'source' => $source,
                    'freelancer_amount' => $freelancerAmount,
                    'platform_fee' => $platformFee,
                ]);

                return true;
            });
        } catch (\Exception $e) {
            Log::error("Payment processing failed for completion submission #{$submission->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
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
    private function getAdminUser(): ?User
    {
        return User::query()
            ->where('role', 'admin')
            ->orWhereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->first();
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
