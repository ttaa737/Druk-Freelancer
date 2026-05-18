<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            if (!Schema::hasColumn('proposals', 'cv_file_path')) {
                $table->string('cv_file_path')->nullable()->after('cover_letter');
            }

            if (!Schema::hasColumn('proposals', 'cv_file_name')) {
                $table->string('cv_file_name')->nullable()->after('cv_file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            if (Schema::hasColumn('proposals', 'cv_file_name')) {
                $table->dropColumn('cv_file_name');
            }

            if (Schema::hasColumn('proposals', 'cv_file_path')) {
                $table->dropColumn('cv_file_path');
            }
        });
    }
};