<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('verification_documents', function (Blueprint $table) {
            // Add admin_notes field for documenting verification decisions
            $table->text('admin_notes')->nullable()->after('rejection_reason');
        });

        // Update enum to include education - Using raw SQL for enum modification
        DB::statement("ALTER TABLE verification_documents MODIFY COLUMN document_type 
            ENUM('cid', 'brn', 'tax_certificate', 'license', 'education', 'other')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_documents', function (Blueprint $table) {
            $table->dropColumn('admin_notes');
        });

        // Revert enum to original
        DB::statement("ALTER TABLE verification_documents MODIFY COLUMN document_type 
            ENUM('cid', 'brn', 'tax_certificate', 'license', 'other')");
    }
};
