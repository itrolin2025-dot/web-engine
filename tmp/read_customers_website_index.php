<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$pos = strpos($contents, "->addColumn('domain_view'");
if ($pos === false) {
    $pos = strpos($contents, "addColumn('domain_view'");
}
if ($pos === false) {
    echo "NO_DOMAIN_VIEW_ADD_COLUMN\n";
    exit(2);
}

$context = substr($contents, $pos, 700);
echo "POS: $pos\nCONTEXT:\n$context\n";
exit(0);
