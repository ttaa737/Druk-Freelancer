<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix the conversations table issues with maximum safety
        if (Schema::hasTable('conversations')) {
            // Drop old columns safely - wrap each in try/catch to avoid errors
            foreach (['participant_one', 'participant_two', 'is_archived_by_one', 'is_archived_by_two'] as $columnToRemove) {
                try {
                    if (Schema::hasColumn('conversations', $columnToRemove)) {
                        Schema::table('conversations', function (Blueprint $table) use ($columnToRemove) {
                            $table->dropColumn($columnToRemove);
                        });
                    }
                } catch (\Throwable $e) {
                    // Column might not exist or have foreign key - that's OK
                    // The app will work fine without trying to drop it
                }
            }

            // Now ensure all required columns exist
            Schema::table('conversations', function (Blueprint $table) {
                // Add poster_id if not exists
                if (!Schema::hasColumn('conversations', 'poster_id')) {
                    $table->unsignedBigInteger('poster_id')->after('contract_id');
                }

                // Add freelancer_id if not exists
                if (!Schema::hasColumn('conversations', 'freelancer_id')) {
                    $table->unsignedBigInteger('freelancer_id')->after('poster_id');
                }

                // Add archived flags if not exists
                if (!Schema::hasColumn('conversations', 'poster_archived')) {
                    $table->boolean('poster_archived')->default(false)->after('freelancer_id');
                }
                if (!Schema::hasColumn('conversations', 'freelancer_archived')) {
                    $table->boolean('freelancer_archived')->default(false)->after('poster_archived');
                }
            });
        }

        // Fix any other common schema issues
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Ensure verification_status exists
                if (!Schema::hasColumn('users', 'verification_status')) {
                    $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])
                        ->default('unverified')
                        ->after('email_verified_at');
                }
            });
        }

        if (Schema::hasTable('wallets')) {
            Schema::table('wallets', function (Blueprint $table) {
                // Ensure is_frozen exists
                if (!Schema::hasColumn('wallets', 'is_frozen')) {
                    $table->boolean('is_frozen')->default(false);
                }
                if (!Schema::hasColumn('wallets', 'freeze_reason')) {
                    $table->text('freeze_reason')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        // Not reversible - this is a cleanup migration
    }
};
