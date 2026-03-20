<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('wallets')) {
            return;
        }

        Schema::table('wallets', function (Blueprint $table) {
            if (! Schema::hasColumn('wallets', 'is_frozen')) {
                $table->boolean('is_frozen')->default(false)->after('total_spent');
            }
            if (! Schema::hasColumn('wallets', 'freeze_reason')) {
                $table->string('freeze_reason')->nullable()->after('is_frozen');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('wallets')) {
            return;
        }

        Schema::table('wallets', function (Blueprint $table) {
            if (Schema::hasColumn('wallets', 'freeze_reason')) {
                $table->dropColumn('freeze_reason');
            }
            if (Schema::hasColumn('wallets', 'is_frozen')) {
                $table->dropColumn('is_frozen');
            }
        });
    }
};
