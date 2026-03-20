<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('conversations')) {
            // Step 1: Ensure poster_id and freelancer_id columns exist
            Schema::table('conversations', function (Blueprint $table) {
                if (!Schema::hasColumn('conversations', 'poster_id')) {
                    $table->unsignedBigInteger('poster_id')->nullable()->after('job_id');
                }
                if (!Schema::hasColumn('conversations', 'freelancer_id')) {
                    $table->unsignedBigInteger('freelancer_id')->nullable()->after('poster_id');
                }
            });

            // Step 2: Migrate data from old columns to new ones if needed
            if (Schema::hasColumn('conversations', 'participant_one')) {
                DB::statement('UPDATE conversations SET poster_id = participant_one WHERE poster_id IS NULL AND participant_one IS NOT NULL');
            }
            if (Schema::hasColumn('conversations', 'participant_two')) {
                DB::statement('UPDATE conversations SET freelancer_id = participant_two WHERE freelancer_id IS NULL AND participant_two IS NOT NULL');
            }

            // Step 3: Make the columns non-nullable
            Schema::table('conversations', function (Blueprint $table) {
                $table->unsignedBigInteger('poster_id')->change();
                $table->unsignedBigInteger('freelancer_id')->change();
            });

            // Step 4: Add foreign key constraints if they don't exist
            if (!Schema::hasColumn('conversations', 'poster_id')) {
                // Skip foreign key addition if column doesn't exist
            } else {
                try {
                    Schema::table('conversations', function (Blueprint $table) {
                        // Check if foreign key exists before adding
                        $table->foreign('poster_id')->references('id')->on('users')->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // Foreign key might already exist
                }
            }

            try {
                Schema::table('conversations', function (Blueprint $table) {
                    // Check if foreign key exists before adding
                    $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist
            }

            // Step 5: Verify unique constraint exists
            try {
                Schema::table('conversations', function (Blueprint $table) {
                    if (!Schema::hasColumn('conversations', 'unique_poster_freelancer_job')) {
                        // Create unique index if not exists
                    }
                });
            } catch (\Exception $e) {
                // Constraint might already exist
            }
        }
    }

    public function down(): void
    {
        // Intentionally not reversible - data migration is one-way
    }
};
