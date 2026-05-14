<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('completion_status', [
                'pending', 'submitted', 'verified', 'rejected', 'paid'
            ])->default('pending')->after('status')->comment('Completion submission status');
            $table->timestamp('completion_submitted_at')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('completion_status');
            $table->dropColumn('completion_submitted_at');
        });
    }
};
