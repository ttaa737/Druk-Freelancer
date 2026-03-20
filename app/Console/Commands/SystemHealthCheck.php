<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemHealthCheck extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Comprehensive system health check - tables, columns, constraints';

    public function handle()
    {
        $this->info('========== SYSTEM HEALTH CHECK ==========');
        $passed = 0;
        $failed = 0;

        // 1. Check critical tables exist
        $this->info('\n1. CHECKING CRITICAL TABLES:');
        $criticalTables = ['users', 'conversations', 'messages', 'jobs', 'proposals', 'verification_documents', 'wallets'];
        foreach ($criticalTables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("   OK: Table '$table' exists");
                $passed++;
            } else {
                $this->error("   FAIL: Table '$table' missing!");
                $failed++;
            }
        }

        // 2. Check conversations table structure (most critical)
        $this->info('\n2. CHECKING CONVERSATIONS TABLE STRUCTURE:');
        if (Schema::hasTable('conversations')) {
            $requiredCols = ['id', 'poster_id', 'freelancer_id', 'job_id', 'contract_id'];
            foreach ($requiredCols as $col) {
                if (Schema::hasColumn('conversations', $col)) {
                    $this->info("   OK: Column '$col' exists");
                    $passed++;
                } else {
                    $this->error("   FAIL: Column '$col' missing!");
                    $failed++;
                }
            }

            // Check for problematic old columns
            $oldCols = ['participant_one', 'participant_two', 'is_archived_by_one', 'is_archived_by_two'];
            foreach ($oldCols as $col) {
                if (!Schema::hasColumn('conversations', $col)) {
                    $this->info("   OK: Old column '$col' removed");
                    $passed++;
                } else {
                    $this->error("   FAIL: Old column '$col' still exists!");
                    $failed++;
                }
            }
        }

        // 3. Check users table verification columns
        $this->info('\n3. CHECKING USERS VERIFICATION SYSTEM:');
        if (Schema::hasTable('users')) {
            $verificationCols = ['verification_status', 'status', 'cid_number'];
            foreach ($verificationCols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $this->info("   OK: Column '$col' exists");
                    $passed++;
                } else {
                    $this->error("   FAIL: Column '$col' missing!");
                    $failed++;
                }
            }
        }

        // 4. Check wallets table
        $this->info('\n4. CHECKING WALLETS TABLE:');
        if (Schema::hasTable('wallets')) {
            $walletCols = ['user_id', 'available_balance', 'escrow_balance', 'is_frozen', 'freeze_reason'];
            foreach ($walletCols as $col) {
                if (Schema::hasColumn('wallets', $col)) {
                    $this->info("   OK: Column '$col' exists");
                    $passed++;
                } else {
                    $this->error("   FAIL: Column '$col' missing!");
                    $failed++;
                }
            }
        }

        // 5. Check messages table
        $this->info('\n5. CHECKING MESSAGES TABLE:');
        if (Schema::hasTable('messages')) {
            $messageCols = ['id', 'conversation_id', 'sender_id', 'body', 'created_at'];
            foreach ($messageCols as $col) {
                if (Schema::hasColumn('messages', $col)) {
                    $this->info("   OK: Column '$col' exists");
                    $passed++;
                } else {
                    $this->error("   FAIL: Column '$col' missing!");
                    $failed++;
                }
            }
        }

        // 6. Test critical operations
        $this->info('\n6. TESTING CRITICAL OPERATIONS:');
        try {
            $count = DB::table('users')->count();
            $this->info("   OK: Can query users table (found $count users)");
            $passed++;
        } catch (\Exception $e) {
            $this->error("   FAIL: Cannot query users: " . $e->getMessage());
            $failed++;
        }

        try {
            $count = DB::table('conversations')->count();
            $this->info("   OK: Can query conversations table (found $count conversations)");
            $passed++;
        } catch (\Exception $e) {
            $this->error("   FAIL: Cannot query conversations: " . $e->getMessage());
            $failed++;
        }

        try {
            $count = DB::table('messages')->count();
            $this->info("   OK: Can query messages table (found $count messages)");
            $passed++;
        } catch (\Exception $e) {
            $this->error("   FAIL: Cannot query messages: " . $e->getMessage());
            $failed++;
        }

        // 7. Check key models load
        $this->info('\n7. CHECKING KEY MODELS:');
        $models = [
            '\App\Models\User' => 'User',
            '\App\Models\Conversation' => 'Conversation',
            '\App\Models\Message' => 'Message',
            '\App\Models\Job' => 'Job',
            '\App\Models\Proposal' => 'Proposal',
            '\App\Models\Wallet' => 'Wallet',
        ];
        foreach ($models as $class => $name) {
            try {
                $model = new $class();
                $this->info("   OK: Model '$name' loads");
                $passed++;
            } catch (\Exception $e) {
                $this->error("   FAIL: Model '$name' failed to load: " . $e->getMessage());
                $failed++;
            }
        }

        // Summary
        $this->info('\n========== SUMMARY ==========');
        $total = $passed + $failed;
        $this->info("Passed: $passed/$total");
        if ($failed > 0) {
            $this->error("Failed: $failed/$total");
            $this->error("\n✗ SYSTEM HAS ISSUES - Fix errors above");
            return 1;
        } else {
            $this->info("Failed: $failed/$total");
            $this->info("\n✓ SYSTEM HEALTHY - All checks passed!");
            return 0;
        }
    }
}
