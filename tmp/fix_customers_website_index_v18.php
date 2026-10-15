<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$needle = 'domain_view';
$pos = stripos($contents, $needle);
if ($pos === false) {
    echo "NO_DOMAIN_VIEW_AT_ALL\n";
    exit(2);
}

$found = substr($contents, $pos, 800);
echo "FOUND_SURROUNDS:\n$found\n";
exit(0);
