<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$lines = explode("\n", $contents);
$inserted = false;
foreach ($lines as $i => $line) {
    if (!$inserted && ltrim($line) === "->addColumn('status_view'") {
        array_splice($lines, $i, 0, [
            "            ->addColumn('preview_url', function (\\$row) {",
            "                if (\\$row->domain) return route('customers-website.preview', \\$row->id);",
            "                return null;",
            "            })",
        ]);
        $inserted = true;
        break;
    }
}

if (!$inserted) {
    echo "NO_STATUS_VIEW_LINE\n";
    exit(2);
}

file_put_contents($path, implode("\n", $lines));
echo "PATCHED\n";
exit(0);
