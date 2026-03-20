<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('conversations')) {
    echo "conversations table does not exist\n";
    exit;
}

echo "=== CONVERSATIONS TABLE STRUCTURE ===\n";
$columns = DB::select('DESCRIBE conversations');
foreach ($columns as $col) {
    $nullable = ($col->Null === 'YES') ? 'NULL' : 'NOT NULL';
    $key = ($col->Key) ? " [KEY: {$col->Key}]" : '';
    $default = ($col->Default !== null) ? " DEFAULT: {$col->Default}" : '';
    echo $col->Field . " (" . $col->Type . ") " . $nullable . $key . $default . "\n";
}

echo "\n=== CHECKING FOR PROBLEMATIC COLUMNS ===\n";
$problematicCols = ['participant_one', 'participant_two', 'is_archived_by_one', 'is_archived_by_two'];
foreach ($problematicCols as $col) {
    if (Schema::hasColumn('conversations', $col)) {
        echo "❌ PROBLEM: Column '$col' still exists!\n";
    } else {
        echo "✓ OK: Column '$col' has been removed\n";
    }
}

echo "\n=== CHECKING FOR REQUIRED COLUMNS ===\n";
$requiredCols = ['poster_id', 'freelancer_id', 'job_id'];
foreach ($requiredCols as $col) {
    if (Schema::hasColumn('conversations', $col)) {
        echo "✓ OK: Column '$col' exists\n";
    } else {
        echo "❌ ERROR: Required column '$col' is missing!\n";
    }
}

echo "\n=== TEST: CAN WE CREATE A CONVERSATION? ===\n";
try {
    // Test if we can insert a simple record
    DB::statement("INSERT INTO conversations (poster_id, freelancer_id, job_id, created_at, updated_at) 
                   VALUES (1, 2, 1, NOW(), NOW()) ON DUPLICATE KEY UPDATE updated_at = NOW()");
    echo "✓ SUCCESS: Can insert conversations with new schema\n";
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
