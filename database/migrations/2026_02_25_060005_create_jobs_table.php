<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poster_id')->comment('User ID of the job poster');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->enum('type', ['fixed', 'hourly'])->default('fixed');
            $table->decimal('budget_min', 12, 2)->nullable()->comment('Min budget in BTN');
            $table->decimal('budget_max', 12, 2)->nullable()->comment('Max budget in BTN');
            $table->integer('duration_days')->nullable()->comment('Expected project duration');
            $table->string('experience_level')->nullable()->comment('entry, intermediate, expert');
            $table->string('dzongkhag')->nullable()->comment('Preferred freelancer district');
            $table->boolean('remote_ok')->default(true);
            $table->enum('status', ['draft', 'open', 'in_progress', 'completed', 'cancelled', 'on_hold'])->default('open');
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('proposals_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('awarded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('poster_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->index('status');
            $table->index('type');
            $table->index('dzongkhag');
            $table->fullText(['title', 'description']);
        });

        Schema::create('job_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('skill_id');

            $table->unique(['job_id', 'skill_id']);
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
        });

        Schema::create('job_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_attachments');
        Schema::dropIfExists('job_skills');
        Schema::dropIfExists('jobs');
    }
};
