<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$needle = 'addColumn(';
$pos = strpos($contents, $needle);
if ($pos === false) {
    echo "NO_ADD_COLUMN\n";
    exit(2);
}

$snippet = substr($contents, $pos, 1800);
echo "POS: $pos\nSNIPPET_START:\n$snippet\n";
exit(0);
