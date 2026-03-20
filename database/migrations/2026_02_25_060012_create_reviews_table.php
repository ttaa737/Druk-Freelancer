<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->unsignedBigInteger('reviewee_id');
            $table->enum('reviewer_role', ['poster', 'freelancer']);
            $table->tinyInteger('rating_overall')->comment('1-5 stars');
            $table->tinyInteger('rating_communication')->nullable()->comment('1-5 stars');
            $table->tinyInteger('rating_quality')->nullable()->comment('1-5 stars');
            $table->tinyInteger('rating_timeliness')->nullable()->comment('1-5 stars');
            $table->tinyInteger('rating_professionalism')->nullable()->comment('1-5 stars');
            $table->text('comment')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_flagged')->default(false);
            $table->text('flag_reason')->nullable();
            $table->timestamps();

            $table->unique(['contract_id', 'reviewer_id']);
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewee_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('reviewee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
