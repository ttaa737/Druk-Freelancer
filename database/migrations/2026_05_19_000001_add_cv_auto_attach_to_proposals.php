<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add auto-attach CV functionality for verified freelancers
     * Allows freelancers' verified CV to be automatically attached to proposals
     */
    public function up(): void
    {
        if (Schema::hasTable('proposals')) {
            Schema::table('proposals', function (Blueprint $table) {
                // Flag: indicates if CV is from verified documents (auto-attached)
                if (!Schema::hasColumn('proposals', 'cv_from_verification')) {
                    $table->boolean('cv_from_verification')->default(false)->after('cv_file_name')
                        ->comment('True if CV is auto-attached from verified documents');
                }

                // Link to verification document if using auto-attach
                if (!Schema::hasColumn('proposals', 'cv_document_id')) {
                    $table->foreignId('cv_document_id')->nullable()->after('cv_from_verification')
                        ->constrained('verification_documents')
                        ->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('proposals')) {
            Schema::table('proposals', function (Blueprint $table) {
                if (Schema::hasColumn('proposals', 'cv_document_id')) {
                    $table->dropForeignIdFor(\App\Models\VerificationDocument::class, 'cv_document_id');
                    $table->dropColumn('cv_document_id');
                }

                if (Schema::hasColumn('proposals', 'cv_from_verification')) {
                    $table->dropColumn('cv_from_verification');
                }
            });
        }
    }
};
