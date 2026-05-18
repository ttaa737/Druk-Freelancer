<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM(
            'deposit',
            'escrow_hold',
            'escrow_release',
            'withdrawal',
            'refund',
            'platform_fee',
            'penalty',
            'completion_settlement',
            'completion_payment',
            'platform_fee_earned',
            'job_payment'
        ) NOT NULL");
    }

    public function down(): void
    {
        // Intentionally left unchanged to avoid data loss if new enum values are in use.
    }
};
