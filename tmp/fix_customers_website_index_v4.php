<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$needle = "->addColumn('status_view'";
$offset = 0;
$found = false;
while (($idx = strpos($contents, $needle, $offset)) !== false) {
    $found = true;
    break;
}

if (!$found) {
    echo "NO_STATUS_VIEW_ADD_COLUMN\n";
    exit(2);
}

$insert = <<<'PHP'
            ->addColumn('preview_url', function ($row) {
                if (!$row->domain) return null;
                return route('customers-website.preview', $row->id);
            })

PHP;

$before = substr($contents, 0, $idx);
$after = substr($contents, $idx);
$updated = $before . $insert . $after;

if ($updated === false) {
    echo "REPLACE_FAILED\n";
    exit(3);
}

file_put_contents($path, $updated);
echo "PATCHED\n";
exit(0);
