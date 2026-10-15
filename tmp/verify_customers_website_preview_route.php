<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$target = null;
$matchedRoutes = [];
foreach (Route::getRoutes() as $route) {
    $name = $route->getName();
    $uri = $route->uri();
    if (str_contains($name, 'customers-website') && str_contains($uri, 'preview')) {
        $matchedRoutes[] = $name.' => '.$uri;
    }
    if ($name === 'customers-website.preview' || $name === 'admin.customers-website.preview') {
        $target = $route;
    }
}

if ($target) {
    echo "ROUTE_FOUND: ".$target->getName().' => '.$target->uri()."\n";
    exit(0);
}

if ($matchedRoutes) {
    echo "NO_TARGET_ROUTE: found similar routes:\n";
    foreach ($matchedRoutes as $m) {
        echo "  - ".$m."\n";
    }
    exit(1);
}

echo "NO_CUSTOMERS_WEBSITE_PREVIEW_ROUTE\n";
exit(127);
