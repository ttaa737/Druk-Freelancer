php artisan tinker <<'EOF'
$columns = DB::select("DESCRIBE conversations");
echo "=== CONVERSATIONS TABLE STRUCTURE ===\n";
foreach ($columns as $col) {
    $nullable = ($col->Null === 'YES') ? 'NULLABLE' : 'NOT NULL';
    $key = ($col->Key) ? " [{$col->Key}]" : '';
    $default = ($col->Default !== null) ? " DEFAULT: {$col->Default}" : '';
    echo $col->Field . " (" . $col->Type . ") " . $nullable . $key . $default . "\n";
}
echo "\nChecking columns:\n";
echo "participant_one exists? " . (Schema::hasColumn('conversations', 'participant_one') ? 'YES - BAD!' : 'NO - GOOD!') . "\n";
echo "poster_id exists? " . (Schema::hasColumn('conversations', 'poster_id') ? 'YES - GOOD!' : 'NO - BAD!') . "\n";
echo "freelancer_id exists? " . (Schema::hasColumn('conversations', 'freelancer_id') ? 'YES - GOOD!' : 'NO - BAD!') . "\n";
EOF
