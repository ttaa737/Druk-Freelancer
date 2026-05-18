<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('completion_submission_attachments')) {
            return;
        }

        Schema::create('completion_submission_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('completion_submission_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->text('description')->nullable();
            $table->enum('document_type', ['evidence', 'report', 'deliverable', 'screenshot', 'video', 'other'])->default('evidence');
            $table->timestamps();
            $table->softDeletes();

            if (Schema::hasTable('completion_submissions')) {
                $table->foreign('completion_submission_id')
                    ->name('comp_sub_att_sub_id_fk')
                    ->references('id')
                    ->on('completion_submissions')
                    ->onDelete('cascade');
            }

            $table->index('document_type');
        });
    }

    public function down(): void
    {
        // Keep table intact on rollback to avoid data loss in live environments.
    }
};
