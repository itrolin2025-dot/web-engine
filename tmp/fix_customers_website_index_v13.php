<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$needle = 'addColumn(\'domain_view\'';
$pos = strpos($contents, $needle);
if ($pos === false) {
    $pos = stripos($contents, 'addcolumn');
}
if ($pos === false) {
    echo "NO_DOMAIN_ADD_COLUMN\n";
    exit(2);
}

$snippet = substr($contents, $pos, 900);
echo "POS: $pos\n";
echo "SNIPPET:\n$snippet\n";
exit(0);
