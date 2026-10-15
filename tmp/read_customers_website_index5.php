<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$pos = 0;
$found = [];
while (($pos = stripos($contents, 'addColumn', $pos)) !== false) {
    $snippet = substr($contents, $pos, 200);
    $found[] = "OFFSET $pos: $snippet\n---\n";
    $pos += 9;
}

if (!$found) {
    echo "NO_ADD_COLUMN\n";
    exit(2);
}

foreach ($found as $f) {
    echo $f;
}
exit(0);
