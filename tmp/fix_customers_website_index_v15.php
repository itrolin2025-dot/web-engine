<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$needle = 'getData';
$pos = stripos($contents, $needle);
if ($pos === false) {
    echo "NO_GET_DATA\n";
    exit(2);
}

$snippet = substr($contents, $pos, 2400);
echo "POS: $pos\n";
echo "SNIPPET:\n$snippet\n";
exit(0);
