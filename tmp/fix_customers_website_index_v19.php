<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$needle = "addColumn('domain_view'";
$pos = strpos($contents, $needle);
if ($pos === false) {
    $needle2 = 'addColumn("domain_view"';
    $pos = strpos($contents, $needle2);
}
if ($pos === false) {
    echo "NO_DOMAIN_VIEW_ADD_COLUMN\n";
    exit(2);
}

$openParenPos = strpos($contents, '(', $pos);
if ($openParenPos === false) {
    echo "NO_OPEN_PAREN\n";
    exit(3);
}

$closeCount = 0;
$i = $openParenPos;
$len = strlen($contents);
$closePos = false;
while ($i < $len) {
    if ($contents[$i] === '(') {
        $closeCount++;
    } elseif ($contents[$i] === ')') {
        $closeCount--;
        if ($closeCount === 0) {
            $closePos = $i;
            break;
        }
    }
    $i++;
}

if ($closePos === false) {
    echo "NO_CLOSE_PAREN\n";
    exit(4);
}

$oldRenderer = substr($contents, $pos, $closePos - $pos + 1);

$newRenderer = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$previewUrl = route('customers-website.preview', \$row->id);\n"
    . "                return '<a href=\"' . e(\$previewUrl) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            })";

if (strpos($oldRenderer, 'customers-website.preview') !== false) {
    echo "ALREADY_PATCHED\n";
    exit(0);
}

$updated = str_replace($oldRenderer, $newRenderer, $contents);
if ($updated === false) {
    echo "REPLACE_FAILED\n";
    exit(5);
}

file_put_contents($path, $updated);
echo "PATCHED\n";
exit(0);
