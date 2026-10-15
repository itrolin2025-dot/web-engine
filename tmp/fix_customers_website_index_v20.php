<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$search = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$previewUrl = route('customers-website.preview', \$row->id);\n"
    . "                return '<a href=\"' . e(\$previewUrl) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            })";

if (strpos($contents, $search) !== false) {
    echo "ALREADY_PATCHED\n";
    exit(0);
}

$oldRenderer = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$url = \\Illuminate\\Support\\Str::startsWith(\$row->domain, 'http') ? \$row->domain : 'https://' . \$row->domain;\n"
    . "                return '<a href=\"' . e(\$url) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            })";

if (strpos($contents, $oldRenderer) === false) {
    echo "NO_OLD_RENDERER\n";
    exit(2);
}

$newRenderer = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$previewUrl = route('customers-website.preview', \$row->id);\n"
    . "                return '<a href=\"' . e(\$previewUrl) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            })";

$updated = str_replace($oldRenderer, $newRenderer, $contents);
if ($updated === false) {
    echo "REPLACE_FAILED\n";
    exit(3);
}

file_put_contents($path, $updated);
echo "PATCHED\n";
exit(0);
