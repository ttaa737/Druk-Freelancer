<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('completion_submission_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('completion_submission_id');
            $table->string('file_name')->comment('Original file name');
            $table->string('file_path')->comment('Storage path');
            $table->string('file_type')->comment('MIME type');
            $table->unsignedBigInteger('file_size')->comment('File size in bytes');
            $table->text('description')->nullable()->comment('Description of the attachment');
            $table->enum('document_type', [
                'evidence', 'report', 'deliverable', 'screenshot', 'video', 'other'
            ])->default('evidence');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('completion_submission_id')->references('id')->on('completion_submissions')->onDelete('cascade');
            $table->index('document_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('completion_submission_attachments');
    }
};
