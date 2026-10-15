<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$search = "->addColumn('status_view'";
$idx = strpos($contents, $search);
if ($idx === false) {
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
$newline = "\n";
$updated = $before . $insert . $newline . $after;

if ($updated === false) {
    echo "REPLACE_FAILED\n";
    exit(3);
}

file_put_contents($path, $updated);
echo "PATCHED\n";
exit(0);
