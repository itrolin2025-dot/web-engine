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

$lines = explode("\n", $contents);
$found = false;
foreach ($lines as $i => $line) {
    $trimmed = trim($line);
    if ($trimmed !== '' && strpos($trimmed, 'addColumn') !== false) {
        echo "L" . ($i + 1) . " [" . $trimmed . "]\n";
        $found = true;
    }
}

if (!$found) {
    echo "NO_ADD_COLUMN_LINE\n";
    exit(2);
}

exit(0);
