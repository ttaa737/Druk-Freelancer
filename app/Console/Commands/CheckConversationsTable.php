<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckConversationsTable extends Command
{
    protected $signature = 'check:conversations';
    protected $description = 'Check conversations table structure';

    public function handle()
    {
        if (!Schema::hasTable('conversations')) {
            $this->error('conversations table does not exist');
            return 1;
        }

        $this->info('=== CONVERSATIONS TABLE STRUCTURE ===');
        $columns = DB::select('DESCRIBE conversations');
        foreach ($columns as $col) {
            $nullable = ($col->Null === 'YES') ? 'NULLABLE' : 'NOT NULL';
            $key = ($col->Key) ? " [{$col->Key}]" : '';
            $default = ($col->Default !== null) ? " DEFAULT: {$col->Default}" : '';
            $this->line($col->Field . " (" . $col->Type . ") " . $nullable . $key . $default);
        }

        $this->info("\n=== CHECKING FOR PROBLEMATIC COLUMNS ===");
        $problematicCols = ['participant_one', 'participant_two', 'is_archived_by_one', 'is_archived_by_two'];
        foreach ($problematicCols as $col) {
            if (Schema::hasColumn('conversations', $col)) {
                $this->error("❌ PROBLEM: Column '$col' still exists!");
            } else {
                $this->info("✓ OK: Column '$col' has been removed");
            }
        }

        $this->info("\n=== CHECKING FOR REQUIRED COLUMNS ===");
        $requiredCols = ['poster_id', 'freelancer_id', 'job_id', 'contract_id'];
        foreach ($requiredCols as $col) {
            if (Schema::hasColumn('conversations', $col)) {
                $this->info("✓ OK: Column '$col' exists");
            } else {
                $this->error("❌ ERROR: Required column '$col' is missing!");
            }
        }

        return 0;
    }
}
