<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Supported Bhutanese payment providers configuration.
     */
    public const PROVIDERS = [
        'mbob' => [
            'name'    => 'mBoB - Bank of Bhutan',
            'api_url' => 'MBOB_API_URL',
            'api_key' => 'MBOB_API_KEY',
            'type'    => 'mobile_banking',
        ],
        'mpay' => [
            'name'    => 'mPay - Bhutan National Bank',
            'api_url' => 'MPAY_API_URL',
            'api_key' => 'MPAY_API_KEY',
            'type'    => 'mobile_banking',
        ],
        'tpay' => [
            'name'    => 'TPay - T Bank Limited',
            'api_url' => 'TPAY_API_URL',
            'api_key' => 'TPAY_API_KEY',
            'type'    => 'mobile_banking',
        ],
        'epay' => [
            'name'    => 'ePay - Bhutan Development Bank',
            'api_url' => 'EPAY_API_URL',
            'api_key' => 'EPAY_API_KEY',
            'type'    => 'mobile_banking',
        ],
        'drukpay' => [
            'name'    => 'DrukPay - Druk PNB Bank',
            'api_url' => 'DRUKPAY_API_URL',
            'api_key' => 'DRUKPAY_API_KEY',
            'type'    => 'mobile_banking',
        ],
        'dkpay' => [
            'name'    => 'DK Pay - Digital Kidu',
            'api_url' => 'DKPAY_API_URL',
            'api_key' => 'DKPAY_API_KEY',
            'type'    => 'digital_wallet',
        ],
    ];

    /**
     * Deposit funds to a user's platform wallet from a Bhutanese payment provider.
     * In production, this would integrate with the actual payment gateway APIs.
     */
    public function deposit(User $user, float $amount, string $provider, string $providerRef): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $provider, $providerRef) {
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->firstOrFail();
            $balanceBefore = $wallet->available_balance;

            // Verify payment with provider (stub - replace with real API call)
            $verified = $this->verifyWithProvider($provider, $providerRef, $amount);

            if (!$verified) {
                throw new \Exception("Payment verification failed with {$provider}. Reference: {$providerRef}");
            }

            $wallet->increment('available_balance', $amount);

            $transaction = Transaction::create([
                'user_id'                  => $user->id,
                'type'                     => 'deposit',
                'amount'                   => $amount,
                'fee'                      => 0,
                'net_amount'               => $amount,
                'status'                   => 'completed',
                'payment_provider'         => $provider,
                'payment_provider_ref'     => $providerRef,
                'notes'                    => "Wallet deposit via " . self::PROVIDERS[$provider]['name'],
                'balance_before'           => $balanceBefore,
                'balance_after'            => $wallet->fresh()->available_balance,
                'ip_address'               => request()->ip(),
            ]);

            AuditLogService::log('wallet.deposit', $user, notes: "Nu. {$amount} deposited via {$provider}");

            return $transaction;
        });
    }

    /**
     * Process a withdrawal request from the platform wallet to a Bhutanese payment provider.
     */
    public function withdraw(User $user, float $amount, string $provider, string $accountNumber): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $provider, $accountNumber) {
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->firstOrFail();

            $minWithdrawal = (float) config('platform.min_withdrawal', 500);

            if ($amount < $minWithdrawal) {
                throw new \Exception("Minimum withdrawal amount is Nu. " . number_format($minWithdrawal, 2));
            }

            if (!$wallet->canWithdraw($amount)) {
                if ($wallet->is_frozen) {
                    throw new \Exception("Your wallet is currently frozen. Please contact support.");
                }
                throw new \Exception("Insufficient available balance. Available: Nu. " . number_format($wallet->available_balance, 2));
            }

            $balanceBefore = $wallet->available_balance;
            $wallet->decrement('available_balance', $amount);
            $wallet->increment('pending_withdrawal', $amount);

            $transaction = Transaction::create([
                'user_id'          => $user->id,
                'type'             => 'withdrawal',
                'amount'           => $amount,
                'fee'              => 0,
                'net_amount'       => $amount,
                'status'           => 'processing',
                'payment_provider' => $provider,
                'notes'            => "Withdrawal to {$accountNumber} via " . self::PROVIDERS[$provider]['name'],
                'balance_before'   => $balanceBefore,
                'balance_after'    => $wallet->fresh()->available_balance,
                'ip_address'       => request()->ip(),
            ]);

            // Process actual withdrawal (async/queued in production)
            $this->processWithdrawalAsync($transaction, $provider, $accountNumber, $amount);

            AuditLogService::log('wallet.withdrawal', $user, notes: "Nu. {$amount} withdrawal requested via {$provider}");

            return $transaction;
        });
    }

    /**
     * Verify payment with the external provider API.
     * In production this would make an HTTP call to the payment gateway.
     */
    private function verifyWithProvider(string $provider, string $reference, float $amount): bool
    {
        $apiKey = env(self::PROVIDERS[$provider]['api_key'] ?? '');

        if (!$apiKey) {
            // No API key configured - simulate success for development
            Log::info("Payment verification simulated for {$provider} ref: {$reference} amount: Nu.{$amount}");
            return true;
        }

        // TODO: Implement actual API call to Bhutanese payment provider
        // $apiUrl = env(self::PROVIDERS[$provider]['api_url']);
        // HTTP call to verify transaction reference...

        return true;
    }

    /**
     * Process withdrawal asynchronously (would use Laravel Queue in production).
     */
    private function processWithdrawalAsync(Transaction $txn, string $provider, string $accountNumber, float $amount): void
    {
        $apiKey = env(self::PROVIDERS[$provider]['api_key'] ?? '');

        if (!$apiKey) {
            // Simulate completion for development
            $txn->update(['status' => 'completed']);
            $wallet = Wallet::where('user_id', $txn->user_id)->first();
            $wallet->decrement('pending_withdrawal', $amount);
            return;
        }

        // In production: dispatch a job to the queue
        // ProcessWithdrawalJob::dispatch($txn, $provider, $accountNumber, $amount);
    }
}
