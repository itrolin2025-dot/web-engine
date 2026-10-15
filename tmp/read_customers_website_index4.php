<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$addColumnPos = strpos($contents, 'addColumn');
$domainPos = strpos($contents, 'domain');

echo "ADD_COLUMN_POS: $addColumnPos\n";
echo "DOMAIN_POS: $domainPos\n";

if ($addColumnPos === false || $domainPos === false) {
    echo "MISSING_ONE\n";
    exit(2);
}

$context = substr($contents, $addColumnPos, 200);
echo "CONTEXT:\n$context\n";
exit(0);
