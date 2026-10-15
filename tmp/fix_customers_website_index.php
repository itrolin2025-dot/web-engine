<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$search = "
            ->addColumn('domain_view', function (\$row) {
                if (!\$row->domain) return '-';
                \$url = \\Illuminate\\Support\\Str::startsWith(\$row->domain, 'http') ? \$row->domain : 'https://' . \$row->domain;
                return '<a href=\"' . e(\$url) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'
                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';
            })";

$replace = "
            ->addColumn('preview_url', function (\$row) {
                if (!\$row->domain) return null;
                return route('customers-website.preview', \$row->id);
            })
            ->addColumn('domain_view', function (\$row) {
                if (!\$row->domain) return '-';
                \$url = \\Illuminate\\Support\\Str::startsWith(\$row->domain, 'http') ? \$row->domain : 'https://' . \$row->domain;
                return '<a href=\"' . e(\$url) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'
                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';
            })";

if (strpos($contents, $search) === false) {
    echo "PATTERN_NOT_FOUND\n";
    exit(2);
}

$updated = str_replace($search, $replace, $contents);
if ($updated === false) {
    echo "REPLACE_FAILED\n";
    exit(3);
}

file_put_contents($path, $updated);
echo "PATCHED\n";
exit(0);
