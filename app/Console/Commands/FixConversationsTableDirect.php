<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixConversationsTableDirect extends Command
{
    protected $signature = 'fix:conversations-direct';
    protected $description = 'Directly fix conversations table by dropping old columns';

    public function handle()
    {
        $this->info('Attempting to fix conversations table...');

        // Check connections and constraints first
        $this->info('Getting current constraints...');
        $constraints = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME='conversations' AND COLUMN_NAME IN ('participant_one', 'participant_two')");
        
        foreach ($constraints as $constraint) {
            if ($constraint->CONSTRAINT_NAME && $constraint->CONSTRAINT_NAME !== 'PRIMARY') {
                try {
                    $this->line("Dropping constraint: {$constraint->CONSTRAINT_NAME}");
                    DB::statement("ALTER TABLE conversations DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
                } catch (\Exception $e) {
                    $this->warn("Could not drop constraint {$constraint->CONSTRAINT_NAME}: " . $e->getMessage());
                }
            }
        }

        // Now drop the columns
        $columnsToRemove = ['participant_one', 'participant_two'];
        foreach ($columnsToRemove as $column) {
            try {
                if (Schema::hasColumn('conversations', $column)) {
                    $this->line("Dropping column: $column");
                    DB::statement("ALTER TABLE conversations DROP COLUMN $column");
                    $this->info("✓ Successfully dropped column: $column");
                }
            } catch (\Exception $e) {
                $this->error("Failed to drop column $column: " . $e->getMessage());
            }
        }

        // Verify
        $this->info("\nVerifying structure...");
        $columns = DB::select('DESCRIBE conversations');
        $hasProblems = false;
        foreach ($columns as $col) {
            if (in_array($col->Field, ['participant_one', 'participant_two'])) {
                $this->error("❌ Column {$col->Field} still exists!");
                $hasProblems = true;
            }
        }

        if (!$hasProblems) {
            $this->info("✓ SUCCESS: All old columns removed!");
            $this->info("\nFinal table structure:");
            foreach ($columns as $col) {
                $this->line("{$col->Field} ({$col->Type})");
            }
        }

        return 0;
    }
}
