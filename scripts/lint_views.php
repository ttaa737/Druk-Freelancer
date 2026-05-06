<?php
$files = glob(__DIR__ . '/../storage/framework/views/*.php');
if (!$files) {
    echo "No compiled views found.\n";
    exit(0);
}
foreach ($files as $f) {
    echo "Checking: $f\n";
    $output = null;
    $return = null;
    exec("php -l " . escapeshellarg($f) . " 2>&1", $output, $return);
    foreach ($output as $line) echo $line . "\n";
    if ($return !== 0) {
        echo "--- ERROR in $f ---\n";
        exit(2);
    }
}
echo "All compiled views passed lint.\n";
