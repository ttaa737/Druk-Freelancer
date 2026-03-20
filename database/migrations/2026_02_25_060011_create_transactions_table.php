<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_ref')->unique()->comment('Unique reference e.g. TXN-2026-00001');
            $table->unsignedBigInteger('user_id')->comment('Who initiated this transaction');
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->unsignedBigInteger('milestone_id')->nullable();
            $table->enum('type', [
                'deposit',        // money deposited into wallet from payment gateway
                'escrow_hold',    // funds moved to escrow
                'escrow_release', // funds released from escrow to freelancer
                'withdrawal',     // freelancer withdraws to payment provider
                'refund',         // refund back to job poster
                'platform_fee',   // platform commission
                'penalty'         // dispute penalty
            ]);
            $table->decimal('amount', 15, 2)->comment('Amount in BTN');
            $table->decimal('fee', 10, 2)->default(0.00)->comment('Transaction fee in BTN');
            $table->decimal('net_amount', 15, 2)->comment('Amount after fees in BTN');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'reversed'])->default('pending');
            $table->enum('payment_provider', ['mbob', 'mpay', 'tpay', 'epay', 'drukpay', 'dkpay', 'internal'])->default('internal');
            $table->string('payment_provider_ref')->nullable()->comment('Payment gateway reference number');
            $table->string('payment_provider_response')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('balance_before', 15, 2)->nullable()->comment('Wallet balance before transaction');
            $table->decimal('balance_after', 15, 2)->nullable()->comment('Wallet balance after transaction');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('set null');
            $table->foreign('milestone_id')->references('id')->on('milestones')->onDelete('set null');
            $table->index('type');
            $table->index('status');
            $table->index('transaction_ref');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
