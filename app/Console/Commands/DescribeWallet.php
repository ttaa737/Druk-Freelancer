<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DescribeWallet extends Command
{
    protected $signature = 'describe:wallet';
    protected $description = 'Show wallets table structure';

    public function handle()
    {
        $columns = DB::select('DESCRIBE wallets');
        $this->info('Wallets table structure:');
        foreach ($columns as $col) {
            $nullable = ($col->Null === 'YES') ? 'NULL' : 'NOT NULL';
            $key = ($col->Key) ? " [{$col->Key}]" : '';
            $default = ($col->Default !== null) ? " DEFAULT: {$col->Default}" : '';
            $this->line($col->Field . " (" . $col->Type . ") $nullable$key$default");
        }
    }
}
