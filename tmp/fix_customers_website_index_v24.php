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

// Move to the start of the callback parameter block after '('.
$openParenPos = strpos($contents, '(', $pos + strlen($needle));
if ($openParenPos === false) {
    echo "NO_OPEN_PAREN_AFTER_ADD_COLUMN\n";
    exit(3);
}

// Find the end of the callback closure: closing })
$scanStart = $openParenPos;
$braceCount = 0;
$i = $scanStart;
$len = strlen($contents);
$foundClose = false;
while ($i < $len) {
    if ($contents[$i] === '{') {
        $braceCount++;
    } elseif ($contents[$i] === '}') {
        $braceCount--;
        if ($braceCount === 0) {
            // Make sure this is followed by ';'
            $afterPos = $i + 1;
            $remaining = substr($contents, $afterPos, 2);
            if ($remaining === ');') {
                $foundClose = true;
                break;
            }
        }
    }
    $i++;
}

if (!$foundClose) {
    echo "NO_CLOSE_BRACE_SEMICOLON\n";
    exit(4);
}

$oldBlock = substr($contents, $pos, $i + 2 - $pos);

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

$updated = substr_replace($contents, $newBlock, $pos, strlen($oldBlock));
if ($updated === false) {
    echo "REPLACE_FAILED\n";
    exit(5);
}

file_put_contents($path, $updated);
echo "PATCHED\n";
exit(0);
