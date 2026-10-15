<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$all = [];
$matched = [];
foreach (Route::getRoutes() as $route) {
    $name = $route->getName();
    $uri = $route->uri();
    if ($name !== null && str_contains($name, 'customers-website')) {
        $matched[] = $name.' => '.$uri;
    }
    $all[] = $name.' => '.$uri;
}

echo "MATCHED_CUSTOMERS_WEBSITE_ROUTES:\n";
if ($matched) {
    echo implode("\n", $matched)."\n";
} else {
    echo "NONE\n";
}

echo "\nFIRST_50_ROUTES:\n";
echo implode("\n", array_slice($all, 0, 50))."\n";

$previewFound = false;
foreach (Route::getRoutes() as $route) {
    if ($route->getName() === 'customers-website.preview') {
        $previewFound = true;
        break;
    }
}

echo "\nPREVIEW_ROUTE_FOUND: ".($previewFound ? 'yes' : 'no')."\n";
exit($previewFound ? 0 : 1);
