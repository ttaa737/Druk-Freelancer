<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add legacy action column (duplicate of event for compatibility)
            if (!Schema::hasColumn('audit_logs', 'action')) {
                $table->string('action')->nullable()->after('event');
            }

            // Add entity columns (duplicates of auditable for compatibility)
            if (!Schema::hasColumn('audit_logs', 'entity_type')) {
                $table->string('entity_type')->nullable()->after('auditable_id');
            }

            if (!Schema::hasColumn('audit_logs', 'entity_id')) {
                $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            }

            // Add module column
            if (!Schema::hasColumn('audit_logs', 'module')) {
                $table->string('module')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            if (Schema::hasColumn('audit_logs', 'action')) {
                $table->dropColumn('action');
            }
            if (Schema::hasColumn('audit_logs', 'entity_type')) {
                $table->dropColumn('entity_type');
            }
            if (Schema::hasColumn('audit_logs', 'entity_id')) {
                $table->dropColumn('entity_id');
            }
            if (Schema::hasColumn('audit_logs', 'module')) {
                $table->dropColumn('module');
            }
        });
    }
};
