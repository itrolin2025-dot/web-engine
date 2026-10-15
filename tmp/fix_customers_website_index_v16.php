<?php
$path = __DIR__.'/../resources/views/admin/customers-website/index.blade.php';
$contents = file_get_contents($path);
if ($contents === false) {
    echo "NO_FILE\n";
    exit(1);
}

$pos = stripos($contents, 'getData');
if ($pos === false) {
    echo "NO_GET_DATA\n";
    exit(2);
}

$end = stripos($contents, 'return DataTables::of($data)', $pos);
if ($end === false) {
    echo "NO_RETURN_DT\n";
    exit(3);
}

$preambleEnd = $end + strlen('return DataTables::of($data)');
$preamble = substr($contents, 0, $preambleEnd);

$rest = substr($contents, $preambleEnd);

$insert = "\n            ->addColumn('preview_url', function (\$row) {\n"
    . "                if (!\$row->domain) return null;\n"
    . "                return route('customers-website.preview', \$row->id);\n"
    . "            })";

$preambleUpdated = $preamble . $insert;

if (strpos($preambleUpdated, "addColumn('domain_view'") === false) {
    echo "NO_DOMAIN_VIEW_ADD_COLUMN_AFTER_INSERT\n";
    exit(4);
}

$oldDomainRendererPos = strpos($preambleUpdated, "addColumn('domain_view', function (\$row) {");
if ($oldDomainRendererPos === false) {
    $oldDomainRendererPos = strpos($preambleUpdated, "addColumn('domain_view', function ($row) {");
}
if ($oldDomainRendererPos === false) {
    echo "NO_DOMAIN_VIEW_RENDERER_START\n";
    exit(5);
}

$rendererStart = $oldDomainRendererPos;
$rendererCloseSearchFrom = $rendererStart;
$closeBraceCount = 0;
$i = $rendererCloseSearchFrom;
$len = strlen($preambleUpdated);
while ($i < $len) {
    if ($preambleUpdated[$i] === '{') {
        $closeBraceCount++;
    } elseif ($preambleUpdated[$i] === '}') {
        $closeBraceCount--;
        if ($closeBraceCount === 0) {
            $rendererClosePos = $i;
            break;
        }
    }
    $i++;
}

if (!isset($rendererClosePos)) {
    echo "NO_DOMAIN_RENDERER_CLOSE\n";
    exit(6);
}

$rendererSnippet = substr($preambleUpdated, $rendererStart, $rendererClosePos - $rendererStart + 1);

$newRenderer = "->addColumn('domain_view', function (\$row) {\n"
    . "                if (!\$row->domain) return '-';\n"
    . "                \$previewUrl = route('customers-website.preview', \$row->id);\n"
    . "                return '<a href=\"' . e(\$previewUrl) . '\" target=\"_blank\" class=\"text-primary hover:underline dark:text-accent-light text-xs font-mono\">'\n"
    . "                    . e(\$row->domain) . ' <i class=\"fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5\"></i></a>';\n"
    . "            })";

if ($rendererSnippet === $newRenderer || strpos($rendererSnippet, 'customers-website.preview') !== false) {
    echo "ALREADY_PATCHED\n";
    file_put_contents($path, $preambleUpdated . $rest);
    exit(0);
}

$preambleUpdated = str_replace($rendererSnippet, $newRenderer, $preambleUpdated);
if ($preambleUpdated === false) {
    echo "REPLACE_FAILED\n";
    exit(7);
}

$newContents = $preambleUpdated . $rest;

file_put_contents($path, $newContents);
echo "PATCHED\n";
exit(0);
