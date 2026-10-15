<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$search = '->addColumn(\'status_view\'';
$idx = strpos($contents, $search);
if ($idx === false) {
    echo "NO_STATUS_VIEW_ADD_COLUMN\n";
    exit(2);
}

$before = substr($contents, 0, $idx);
$after = substr($contents, $idx);

$insert = "->addColumn('preview_url', function (\$row) {\n"
    . "                if (!\$row->domain) return null;\n"
    . "                return route('customers-website.preview', \$row->id);\n"
    . "            })\n"
    . "            ";

$updated = $before . $insert . $after;

if ($updated === false) {
    echo "REPLACE_FAILED\n";
    exit(3);
}

file_put_contents($path, $updated);
echo "PATCHED\n";
exit(0);
