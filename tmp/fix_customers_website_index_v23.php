<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$needle = '->addColumn(\'domain_view\',';
$pos = strpos($contents, $needle);
if ($pos === false) {
    echo "NO_ADD_COLUMN_DOMAIN_VIEW\n";
    exit(2);
}

$endPos = strpos($contents, '});', $pos);
if ($endPos === false) {
    echo "NO_CLOSING_BRACE_SEMICOLON\n";
    exit(3);
}

$oldBlock = substr($contents, $pos, $endPos - $pos + 2);

$newBlock = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$previewUrl = route('customers-website.preview', \$row->id);\n"
    . "                return '<a href=\"' . e(\$previewUrl) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            });";

if (strpos($oldBlock, 'customers-website.preview') !== false) {
    echo "ALREADY_PATCHED\n";
    exit(0);
}

if (strpos($oldBlock, "\$previewUrl = route('customers-website.preview'") !== false) {
    echo "ALREADY_PATCHED_2\n";
    exit(0);
}

$updated = substr_replace($contents, $newBlock, $pos, strlen($oldBlock));
if ($updated === false) {
    echo "REPLACE_FAILED\n";
    exit(4);
}

file_put_contents($path, $updated);
echo "PATCHED\n";
exit(0);
