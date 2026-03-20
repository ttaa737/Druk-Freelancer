<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2)->comment('Milestone amount in BTN');
            $table->timestamp('due_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->enum('status', [
                'pending',       // Awaiting work
                'in_progress',   // Freelancer working
                'submitted',     // Freelancer submitted work
                'revision',      // Poster requested revision
                'approved',      // Poster approved
                'paid',          // Payment released
                'disputed'       // Under dispute
            ])->default('pending');
            $table->text('work_description')->nullable()->comment('Freelancer submission notes');
            $table->decimal('escrow_held', 12, 2)->default(0)->comment('Amount in escrow for this milestone');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->index('status');
        });

        Schema::create('milestone_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('milestone_id');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->enum('uploaded_by_role', ['freelancer', 'poster'])->default('freelancer');
            $table->timestamps();

            $table->foreign('milestone_id')->references('id')->on('milestones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestone_attachments');
        Schema::dropIfExists('milestones');
    }
};
