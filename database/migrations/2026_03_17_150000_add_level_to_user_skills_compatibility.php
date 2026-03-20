<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_skills')) {
            return;
        }

        if (!Schema::hasColumn('user_skills', 'level')) {
            Schema::table('user_skills', function (Blueprint $table) {
                $table->enum('level', ['beginner', 'intermediate', 'expert'])
                    ->default('intermediate')
                    ->after('skill_id');
            });
        }

        if (Schema::hasColumn('user_skills', 'proficiency')) {
            DB::statement("UPDATE user_skills SET level = proficiency WHERE level IS NULL OR level = 'intermediate'");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('user_skills') || !Schema::hasColumn('user_skills', 'level')) {
            return;
        }

        Schema::table('user_skills', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
