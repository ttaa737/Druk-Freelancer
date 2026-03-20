<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixConversationsTable2 extends Command
{
    protected $signature = 'fix:conversations2';
    protected $description = 'Fix conversations table - drop unique constraint then columns';

    public function handle()
    {
        $this->info('Attempting comprehensive fix...');

        try {
            $this->info('Step 1: Drop the unique key constraint');
            DB::statement("ALTER TABLE conversations DROP KEY conversations_participant_one_participant_two_job_id_unique");
            $this->info("✓ Dropped unique constraint");
        } catch (\Exception $e) {
            $this->warn("Could not drop unique constraint: " . $e->getMessage());
        }

        try {
            $this->info('Step 2: Drop foreign key on participant_one');
            DB::statement("ALTER TABLE conversations DROP FOREIGN KEY conversations_participant_one_foreign");
            $this->info("✓ Dropped FK on participant_one");
        } catch (\Exception $e) {
            $this->warn("Could not drop FK on participant_one: " . $e->getMessage());
        }

        try {
            $this->info('Step 3: Drop foreign key on participant_two');
            DB::statement("ALTER TABLE conversations DROP FOREIGN KEY conversations_participant_two_foreign");
            $this->info("✓ Dropped FK on participant_two");
        } catch (\Exception $e) {
            $this->warn("Could not drop FK on participant_two: " . $e->getMessage());
        }

        // NOW drop the columns
        try {
            $this->info('Step 4: Drop column participant_one');
            DB::statement("ALTER TABLE conversations DROP COLUMN participant_one");
            $this->info("✓ Dropped column participant_one");
        } catch (\Exception $e) {
            $this->error("Failed to drop column participant_one: " . $e->getMessage());
        }

        try {
            $this->info('Step 5: Drop column participant_two');
            DB::statement("ALTER TABLE conversations DROP COLUMN participant_two");
            $this->info("✓ Dropped column participant_two");
        } catch (\Exception $e) {
            $this->error("Failed to drop column participant_two: " . $e->getMessage());
        }

        // Verify
        $this->info("\nVerifying final structure...");
        $columns = DB::select('DESCRIBE conversations');
        $hasProblems = false;
        foreach ($columns as $col) {
            if (in_array($col->Field, ['participant_one', 'participant_two'])) {
                $this->error("❌ Column {$col->Field} still exists!");
                $hasProblems = true;
            }
        }

        if ($hasProblems) {
            $this->error("\n❌ Fix FAILED - old columns still present");
            return 1;
        } else {
            $this->info("\n✓ SUCCESS: All old columns removed!");
            $this->info("\nFinal columns:");
            foreach ($columns as $col) {
                $nullable = ($col->Null === 'YES') ? 'NULL' : 'NOT NULL';
                $key = ($col->Key) ? " [{$col->Key}]" : '';
                $this->line("  {$col->Field} ({$col->Type}) $nullable$key");
            }
            return 0;
        }
    }
}
