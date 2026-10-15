<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

echo $contents;
exit(0);
