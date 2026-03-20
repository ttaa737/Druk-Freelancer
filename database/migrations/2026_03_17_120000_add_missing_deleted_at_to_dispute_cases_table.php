<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dispute_cases') || Schema::hasColumn('dispute_cases', 'deleted_at')) {
            return;
        }

        Schema::table('dispute_cases', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('dispute_cases') || !Schema::hasColumn('dispute_cases', 'deleted_at')) {
            return;
        }

        Schema::table('dispute_cases', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};
