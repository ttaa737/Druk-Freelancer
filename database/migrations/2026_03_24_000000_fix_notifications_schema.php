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
        // Add missing columns to notifications table
        Schema::table('notifications', function (Blueprint $table) {
            // Add user_id if it doesn't exist
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
            }

            // Add title if it doesn't exist
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->nullable()->after('type');
            }

            // Add body if it doesn't exist
            if (!Schema::hasColumn('notifications', 'body')) {
                $table->text('body')->nullable()->after('title');
            }

            // Add icon if it doesn't exist
            if (!Schema::hasColumn('notifications', 'icon')) {
                $table->string('icon')->nullable()->after('body');
            }

            // Add action_url if it doesn't exist
            if (!Schema::hasColumn('notifications', 'action_url')) {
                $table->string('action_url')->nullable()->after('icon');
            }

            // Add is_read if it doesn't exist
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('action_url');
            }

            // Add channel if it doesn't exist
            if (!Schema::hasColumn('notifications', 'channel')) {
                $table->string('channel')->default('in-app')->after('is_read');
            }

            // Drop notifiable columns if they exist (no longer needed)
            if (Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->dropColumn(['notifiable_type', 'notifiable_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop added columns
            if (Schema::hasColumn('notifications', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('notifications', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('notifications', 'body')) {
                $table->dropColumn('body');
            }
            if (Schema::hasColumn('notifications', 'icon')) {
                $table->dropColumn('icon');
            }
            if (Schema::hasColumn('notifications', 'action_url')) {
                $table->dropColumn('action_url');
            }
            if (Schema::hasColumn('notifications', 'is_read')) {
                $table->dropColumn('is_read');
            }
            if (Schema::hasColumn('notifications', 'channel')) {
                $table->dropColumn('channel');
            }
        });
    }
};
