<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$pos = strpos($contents, '->get()');
if ($pos === false) {
    echo "NO_GET\n";
    exit(2);
}

$context = substr($contents, $pos - 500, 2000);
echo "CONTEXT:\n$context\n";
exit(0);
