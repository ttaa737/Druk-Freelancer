<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'is_anonymous')) {
                $table->boolean('is_anonymous')->default(false)->after('comment');
            }

            if (!Schema::hasColumn('reviews', 'rating_payment_behavior')) {
                $table->tinyInteger('rating_payment_behavior')->nullable()->after('rating_professionalism');
            }

            if (!Schema::hasColumn('reviews', 'rating_project_clarity')) {
                $table->tinyInteger('rating_project_clarity')->nullable()->after('rating_payment_behavior');
            }

            if (!Schema::hasColumn('reviews', 'reported_by')) {
                $table->unsignedBigInteger('reported_by')->nullable()->after('flag_reason');
                $table->foreign('reported_by')->references('id')->on('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('reviews', 'reported_at')) {
                $table->timestamp('reported_at')->nullable()->after('reported_by');
            }

            if (!Schema::hasColumn('reviews', 'moderated_by')) {
                $table->unsignedBigInteger('moderated_by')->nullable()->after('reported_at');
                $table->foreign('moderated_by')->references('id')->on('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('reviews', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }

            if (!Schema::hasColumn('reviews', 'moderation_notes')) {
                $table->text('moderation_notes')->nullable()->after('moderated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'moderation_notes')) {
                $table->dropColumn('moderation_notes');
            }

            if (Schema::hasColumn('reviews', 'moderated_at')) {
                $table->dropColumn('moderated_at');
            }

            if (Schema::hasColumn('reviews', 'moderated_by')) {
                $table->dropForeign(['moderated_by']);
                $table->dropColumn('moderated_by');
            }

            if (Schema::hasColumn('reviews', 'reported_at')) {
                $table->dropColumn('reported_at');
            }

            if (Schema::hasColumn('reviews', 'reported_by')) {
                $table->dropForeign(['reported_by']);
                $table->dropColumn('reported_by');
            }

            if (Schema::hasColumn('reviews', 'rating_project_clarity')) {
                $table->dropColumn('rating_project_clarity');
            }

            if (Schema::hasColumn('reviews', 'rating_payment_behavior')) {
                $table->dropColumn('rating_payment_behavior');
            }

            if (Schema::hasColumn('reviews', 'is_anonymous')) {
                $table->dropColumn('is_anonymous');
            }
        });
    }
};
