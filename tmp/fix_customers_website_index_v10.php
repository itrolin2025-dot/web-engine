<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

if (str_contains($contents, "\r\n")) {
    $contents = str_replace("\r\n", "\n", $contents);
}
if (str_contains($contents, "\r")) {
    $contents = str_replace("\r", "\n", $contents);
}

echo $contents;
exit(0);
