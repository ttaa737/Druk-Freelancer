<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->decimal('available_balance', 15, 2)->default(0.00)->comment('Withdrawable balance in BTN');
            $table->decimal('escrow_balance', 15, 2)->default(0.00)->comment('Funds locked in escrow in BTN');
            $table->decimal('pending_withdrawal', 15, 2)->default(0.00)->comment('Pending withdrawal amount in BTN');
            $table->decimal('total_earned', 15, 2)->default(0.00)->comment('Total lifetime earnings in BTN');
            $table->decimal('total_spent', 15, 2)->default(0.00)->comment('Total lifetime spend in BTN');
            $table->boolean('is_frozen')->default(false);
            $table->string('freeze_reason')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('provider', ['mbob', 'mpay', 'tpay', 'epay', 'drukpay', 'dkpay']);
            $table->string('account_number', 50)->comment('Mobile number or account number');
            $table->string('account_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('wallets');
    }
};
