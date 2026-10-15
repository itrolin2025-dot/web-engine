<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

// Locate the DataTables chain start.
$dtStart = stripos($contents, '->addColumn');
if ($dtStart === false) {
    echo "NO_ADD_COLUMN\n";
    exit(2);
}

$blockStart = $dtStart;

// Locate end of the DataTables chain by searching for make(true)
$searchFrom = $blockStart;
$endPos = stripos($contents, 'make(true)', $searchFrom);
if ($endPos === false) {
    $endPos = stripos($contents, ')->make(true)', $searchFrom);
}
if ($endPos === false) {
    // Fallback: search for make(true) anywhere after block start.
    $endPos = stripos($contents, 'make(true)');
}
if ($endPos === false) {
    echo "NO_MAKE_TRUE\n";
    exit(3);
}

$beforeBlock = substr($contents, 0, $blockStart);
$block = substr($contents, $blockStart, $endPos - $blockStart);
$afterBlock = substr($contents, $endPos);

// 1) Inject preview_url column before first appearance of status_view addColumn in the block.
$statusPos = stripos($block, "addColumn('status_view'");
if ($statusPos === false) {
    $statusPos = stripos($block, "addColumn(\"status_view\"");
}
if ($statusPos === false) {
    echo "NO_STATUS_VIEW_IN_BLOCK\n";
    exit(4);
}

$colInsert = "\n            ->addColumn('preview_url', function (\$row) {\n"
    . "                if (!\$row->domain) return null;\n"
    . "                return route('customers-website.preview', \$row->id);\n"
    . "            })";

$block = substr($block, 0, $statusPos) . $colInsert . substr($block, $statusPos);

// 2) Update domain_view renderer in the block to use preview route.
$domainPos = stripos($block, "addColumn('domain_view'");
if ($domainPos === false) {
    $domainPos = stripos($block, "addColumn(\"domain_view\"");
}
if ($domainPos === false) {
    echo "NO_DOMAIN_VIEW_IN_BLOCK\n";
    exit(5);
}

// Find the full domain_view addColumn call by matching braces.
$openPos = $domainPos + strlen("addColumn('domain_view'") - 1;
if (substr($block, $openPos, 1) !== '(') {
    // Try with double quote style
    $openPos = $domainPos + strlen('addColumn("domain_view"') - 1;
}
$openCount = 0;
$i = $openPos;
$blockLen = strlen($block);
while ($i < $blockLen) {
    if ($block[$i] === '(') {
        $openCount++;
    } elseif ($block[$i] === ')') {
        $openCount--;
        if ($openCount === 0) {
            $closePos = $i;
            break;
        }
    }
    $i++;
}

if (!isset($closePos)) {
    echo "NO_DOMAIN_VIEW_CLOSE\n";
    exit(6);
}

$oldDomainRenderer = substr($block, $domainPos, $closePos - $domainPos + 1);

$newDomainRenderer = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$previewUrl = route('customers-website.preview', \$row->id);\n"
    . "                return '<a href=\"' . e(\$previewUrl) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            })";

if ($oldDomainRenderer === $newDomainRenderer) {
    echo "ALREADY_PATCHED\n";
    file_put_contents($path, $beforeBlock . $block . $afterBlock);
    exit(0);
}

$newBlock = str_replace($oldDomainRenderer, $newDomainRenderer, $block);
if ($newBlock === false) {
    echo "REPLACE_FAILED\n";
    exit(7);
}

$newContents = $beforeBlock . $newBlock . $afterBlock;

file_put_contents($path, $newContents);
echo "PATCHED\n";
exit(0);
