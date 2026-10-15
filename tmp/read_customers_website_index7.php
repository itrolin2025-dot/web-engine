<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

echo "LEN: " . strlen($contents) . "\n";

$positions = [];
$needle = 'addColumn';
while (($pos = stripos($contents, $needle, $pos)) !== false) {
    $positions[] = $pos;
    $pos += 9;
}

echo "ADD_COLUMN_POSITIONS: " . implode(',', $positions) . "\n";

$needle2 = 'domain';
$positions2 = [];
$pos2 = 0;
while (($pos2 = stripos($contents, $needle2, $pos2)) !== false) {
    $positions2[] = $pos2;
    $pos2 += 6;
}

echo "DOMAIN_POSITIONS_COUNT: " . count($positions2) . "\n";
echo "DOMAIN_FIRST_CONTEXT:\n";
if ($positions2) {
    echo substr($contents, $positions2[0] - 200, 600) . "\n";
}
exit(0);
