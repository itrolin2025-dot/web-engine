<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$lines = explode("\n", $contents);
foreach ($lines as $i => $line) {
    if (str_contains($line, 'addColumn') || str_contains($line, '->addColumn')) {
        echo "L" . ($i + 1) . " [" . trim($line) . "]\n";
    }
}
exit(0);
