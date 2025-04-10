<?php
declare(strict_types=1);

require_once '../src/Controller/HomeController.php';
require_once '../src/Controller/CitiesController.php';
require_once '../config/routes/Router.php';

$templateRenderer = new TemplateRenderer();
$routes = new Routes($templateRenderer);
$router = new Router();
foreach ($routes as $route) {
    $router->addRoute($route);
}

try {
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}