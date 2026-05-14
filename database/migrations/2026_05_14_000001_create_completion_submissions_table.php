<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('completion_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->text('submission_notes')->nullable()->comment('Notes from freelancer about completion');
            $table->enum('status', [
                'pending', 'verified', 'rejected', 'payment_processed'
            ])->default('pending')->index();
            $table->timestamp('submitted_at')->nullable()->comment('When freelancer submitted');
            $table->timestamp('verified_at')->nullable()->comment('When admin verified');
            $table->unsignedBigInteger('verified_by')->nullable()->comment('Admin who verified');
            $table->text('rejection_reason')->nullable()->comment('Reason if rejected');
            $table->timestamp('rejected_at')->nullable()->comment('When rejected');
            $table->timestamp('payment_processed_at')->nullable()->comment('When payment was processed');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('completion_submissions');
    }
};
