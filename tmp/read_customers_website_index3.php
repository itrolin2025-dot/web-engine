<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$pos = 0;
$found = [];
while (($pos = strpos($contents, '->addColumn(', $pos)) !== false) {
    $end = strpos($contents, ')', $pos);
    if ($end === false) {
        break;
    }
    $snippet = substr($contents, $pos, $end - $pos + 1);
    $found[] = "OFFSET $pos: $snippet\n";
    $pos = $end + 1;
}

if (!$found) {
    echo "NO_ADD_COLUMN\n";
    exit(2);
}

foreach ($found as $f) {
    echo $f;
}
exit(0);
