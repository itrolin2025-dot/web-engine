<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

// 1) Add preview_url column into DataTables columns array before status_view.
$target = "                    { data: 'status_view', name: 'is_active', className: 'dt-hide-mobile text-center' }";
$colInsert = "                    { data: 'preview_url', name: 'preview_url', visible: false, searchable: false },\n";

$idx = strpos($contents, $target);
if ($idx === false) {
    echo "NO_STATUS_VIEW_COLUMN\n";
    exit(2);
}

$beforeCols = substr($contents, 0, $idx);
$afterCols = substr($contents, $idx);
$contents = $beforeCols . $colInsert . $afterCols;

// 2) Replace domain_view renderer with one that links to customers-website.preview.
$oldRenderer = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$url = \\Illuminate\\Support\\Str::startsWith(\$row->domain, 'http') ? \$row->domain : 'https://' . \$row->domain;\n"
    . "                return '<a href=\"' . e(\$url) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            })";

$newRenderer = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$previewUrl = route('customers-website.preview', \$row->id);\n"
    . "                return '<a href=\"' . e(\$previewUrl) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            })";

if (strpos($contents, $oldRenderer) === false) {
    echo "NO_DOMAIN_RENDERER_MATCH\n";
    exit(3);
}

$contents = str_replace($oldRenderer, $newRenderer, $contents);

file_put_contents($path, $contents);
echo "PATCHED\n";
exit(0);
