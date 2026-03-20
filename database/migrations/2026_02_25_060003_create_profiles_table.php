<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            // Common fields
            $table->string('bio', 1000)->nullable();
            $table->string('dzongkhag')->nullable()->comment('District in Bhutan');
            $table->string('gewog')->nullable()->comment('Block/Sub-district');
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            // Freelancer specific
            $table->string('headline', 200)->nullable()->comment('e.g. Professional Graphic Designer');
            $table->decimal('hourly_rate', 10, 2)->nullable()->comment('Rate in BTN');
            $table->enum('availability', ['available', 'busy', 'not_available'])->default('available');
            $table->integer('experience_years')->nullable();
            // Job Poster specific
            $table->string('company_name')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable();
            // Stats
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_jobs_completed')->default(0);
            $table->decimal('total_earned', 15, 2)->default(0.00)->comment('For freelancers in BTN');
            $table->decimal('total_spent', 15, 2)->default(0.00)->comment('For job posters in BTN');
            $table->integer('profile_views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('dzongkhag');
            $table->index('availability');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
