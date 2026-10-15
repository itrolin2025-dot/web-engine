<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$pos = 0;
$found = [];
while (($pos = strpos($contents, 'domain_view', $pos)) !== false) {
    $snippet = substr($contents, max(0, $pos - 20), 260);
    $found[] = "OFFSET $pos:\n$snippet\n---\n";
    $pos += 11;
}

if (!$found) {
    echo "NO_DOMAIN_VIEW_AT_ALL\n";
    exit(2);
}

foreach ($found as $f) {
    echo $f;
}
exit(0);
