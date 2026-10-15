<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$problems = [];

// Route present
$previewRoute = null;
foreach (Route::getRoutes() as $route) {
    if ($route->getName() === 'customers-website.preview') {
        $previewRoute = $route;
        break;
    }
}
if (!$previewRoute) {
    $problems[] = 'ROUTE_MISSING';
}

// Preview route controller action = CustomersWebController::preview
if ($previewRoute) {
    $action = $previewRoute->getAction();
    if (!isset($action['controller'])) {
        $problems[] = 'PREVIEW_NO_CONTROLLER_ACTION';
    } else {
        $parts = explode('@', $action['controller']);
        if (count($parts) !== 2 || $parts[0] !== 'App\\Http\\Controllers\\admin\\CustomersWebController' || $parts[1] !== 'preview') {
            $problems[] = 'PREVIEW_ACTION_MISMATCH: ' . ($action['controller'] ?? 'n/a');
        }
    }
}

// Controller public methods exist
$controllerClass = App\Http\Controllers\admin\CustomersWebController::class;
if (!class_exists($controllerClass)) {
    $problems[] = 'CONTROLLER_CLASS_MISSING';
} else {
    foreach (['index', 'preview', 'getData'] as $method) {
        try {
            $rm = new ReflectionMethod($controllerClass, $method);
            if (!$rm->isPublic()) {
                $problems[] = "METHOD_NOT_PUBLIC: $method";
            }
        } catch (ReflectionException $e) {
            $problems[] = "METHOD_MISSING: $method";
        }
    }
}

if ($problems) {
    echo implode("\n", $problems) . "\n";
    exit(1);
}

echo "OK: route and controller present and wired\n";
exit(0);
