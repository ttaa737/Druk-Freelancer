<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispute_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique()->comment('e.g. DIS-2026-00001');
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('milestone_id')->nullable();
            $table->unsignedBigInteger('raised_by');
            $table->unsignedBigInteger('against_user');
            $table->unsignedBigInteger('assigned_admin_id')->nullable();
            $table->string('subject');
            $table->text('description');
            $table->enum('reason', [
                'work_not_delivered', 'work_quality', 'payment_issue',
                'communication', 'contract_violation', 'fraud', 'other'
            ]);
            $table->enum('status', [
                'open', 'under_review', 'resolved_poster', 'resolved_freelancer',
                'resolved_split', 'closed', 'escalated'
            ])->default('open');
            $table->text('resolution_notes')->nullable();
            $table->decimal('poster_refund_amount', 12, 2)->nullable()->comment('Refund to poster in BTN');
            $table->decimal('freelancer_payout_amount', 12, 2)->nullable()->comment('Payout to freelancer in BTN');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('raised_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('against_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_admin_id')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
            $table->index('case_number');
        });

        Schema::create('dispute_evidence', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispute_id');
            $table->unsignedBigInteger('submitted_by');
            $table->enum('evidence_type', ['file', 'screenshot', 'message', 'statement']);
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_name')->nullable();
            $table->timestamps();

            $table->foreign('dispute_id')->references('id')->on('dispute_cases')->onDelete('cascade');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('dispute_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispute_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->boolean('is_admin_note')->default(false)->comment('Private admin note');
            $table->timestamps();

            $table->foreign('dispute_id')->references('id')->on('dispute_cases')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispute_comments');
        Schema::dropIfExists('dispute_evidence');
        Schema::dropIfExists('dispute_cases');
    }
};
