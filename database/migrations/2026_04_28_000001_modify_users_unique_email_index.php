<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop existing unique index on email (if exists)
            // The column-based dropUnique will resolve the index name.
            if (Schema::hasColumn('users', 'email')) {
                $table->dropUnique(['email']);
            }

            // Add composite unique index on email + role so same email can be used across different roles
            $table->unique(['email', 'role']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop composite unique and restore single unique on email
            $table->dropUnique(['email', 'role']);
            $table->unique('email');
        });
    }
};
