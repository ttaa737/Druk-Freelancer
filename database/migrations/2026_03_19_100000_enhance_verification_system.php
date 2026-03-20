<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure verification documents table has all necessary fields
        if (Schema::hasTable('verification_documents')) {
            Schema::table('verification_documents', function (Blueprint $table) {
                // Add is_required field to track document requirements
                if (!Schema::hasColumn('verification_documents', 'is_required')) {
                    $table->boolean('is_required')->default(true)->after('status');
                }
                // Add verification_valid_until for document expiry tracking
                if (!Schema::hasColumn('verification_documents', 'valid_until')) {
                    $table->date('valid_until')->nullable()->after('is_required');
                }
            });
        }

        // Update verification document types to include all required types
        if (Schema::hasTable('verification_documents')) {
            DB::statement("ALTER TABLE verification_documents MODIFY COLUMN document_type 
                ENUM('cid', 'brn', 'license', 'tax_certificate', 'education', 'other')");
        }

        // Ensure users table has all verification related fields
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'verification_status')) {
                    $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])
                        ->default('unverified')
                        ->after('email_verified_at');
                }
                if (!Schema::hasColumn('users', 'verification_rejected_reason')) {
                    $table->text('verification_rejected_reason')->nullable()->after('verification_status');
                }
                if (!Schema::hasColumn('users', 'last_verification_attempt')) {
                    $table->timestamp('last_verification_attempt')->nullable()->after('verification_rejected_reason');
                }
            });
        }
    }

    public function down(): void
    {
        // Revert changes if needed
        Schema::table('verification_documents', function (Blueprint $table) {
            if (Schema::hasColumn('verification_documents', 'is_required')) {
                $table->dropColumn('is_required');
            }
            if (Schema::hasColumn('verification_documents', 'valid_until')) {
                $table->dropColumn('valid_until');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'verification_rejected_reason')) {
                $table->dropColumn('verification_rejected_reason');
            }
            if (Schema::hasColumn('users', 'last_verification_attempt')) {
                $table->dropColumn('last_verification_attempt');
            }
        });
    }
};
