<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique()->comment('e.g. DF-2026-00001');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('proposal_id')->nullable();
            $table->unsignedBigInteger('poster_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->text('terms')->nullable()->comment('Contract terms and conditions');
            $table->decimal('total_amount', 12, 2)->comment('Total contract value in BTN');
            $table->decimal('platform_fee', 10, 2)->comment('Platform service fee in BTN');
            $table->decimal('freelancer_amount', 12, 2)->comment('Amount freelancer receives in BTN');
            $table->enum('status', [
                'active', 'completed', 'cancelled', 'disputed', 'paused'
            ])->default('active');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->boolean('poster_signed')->default(false);
            $table->boolean('freelancer_signed')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('poster_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('status');
            $table->index('contract_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
