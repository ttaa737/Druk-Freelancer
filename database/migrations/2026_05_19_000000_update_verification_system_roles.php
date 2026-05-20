<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * New verification system:
     * - All users: CID is REQUIRED
     * - Freelancers: CID (required) + CV (required)
     * - Job Posters: CID (required) + Business License (optional)
     * - Professional License: REMOVED from all users (was previously required)
     * - Education Certificate: REMOVED (optional removed entirely)
     * - Tax Clearance: REMOVED (optional removed entirely)
     */
    public function up(): void
    {
        // Add CV document type to verification_documents table enum
        if (Schema::hasTable('verification_documents')) {
            Schema::table('verification_documents', function (Blueprint $table) {
                // Add role_required field to track which roles require specific documents
                if (!Schema::hasColumn('verification_documents', 'role_required')) {
                    $table->string('role_required')->nullable()->comment('comma-separated: freelancer,job_poster')->after('is_required');
                }
            });

            // Update enum to include cv document type
            try {
                \Illuminate\Support\Facades\DB::statement(
                    "ALTER TABLE verification_documents MODIFY COLUMN document_type ENUM('cid', 'cv', 'brn', 'other')"
                );
            } catch (\Exception $e) {
                // Silently fail if already modified
            }
        }

        // Add cv_stored_for_proposals field to track if user's CV should be attached to proposals
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'has_approved_cv')) {
                    $table->boolean('has_approved_cv')->default(false)->after('verification_status')
                        ->comment('Flag: User has approved CV document for auto-attach to proposals');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('verification_documents')) {
            Schema::table('verification_documents', function (Blueprint $table) {
                if (Schema::hasColumn('verification_documents', 'role_required')) {
                    $table->dropColumn('role_required');
                }
            });

            try {
                \Illuminate\Support\Facades\DB::statement(
                    "ALTER TABLE verification_documents MODIFY COLUMN document_type ENUM('cid', 'brn', 'license', 'tax_certificate', 'education', 'other')"
                );
            } catch (\Exception $e) {
                // Silently fail
            }
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'has_approved_cv')) {
                    $table->dropColumn('has_approved_cv');
                }
            });
        }
    }
};
