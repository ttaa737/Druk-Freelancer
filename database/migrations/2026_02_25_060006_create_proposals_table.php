<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->text('cover_letter');
            $table->decimal('bid_amount', 12, 2)->comment('Proposed amount in BTN');
            $table->integer('delivery_days')->comment('Proposed delivery in days');
            $table->enum('status', [
                'pending', 'shortlisted', 'accepted', 'rejected', 'withdrawn'
            ])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_shortlisted')->default(false);
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('awarded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['job_id', 'freelancer_id']);
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('status');
        });

        Schema::create('proposal_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2)->comment('Milestone amount in BTN');
            $table->integer('duration_days');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_milestones');
        Schema::dropIfExists('proposals');
    }
};
