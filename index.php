<?php
declare(strict_types=1);

require_once './config/routes/Router.php';
require_once './config/routes/Routes.php';
require_once './utils/RequestParser.php';

session_start();

$templateRenderer = new TemplateRenderer();
$routes = new Routes($templateRenderer);
$router = new Router();
$rp = new RequestParser();

foreach ($routes->routes as $route) {
    $router->addRoute($route);
}

if (!isset($_SESSION['user_id'])  && !str_starts_with($_SERVER['REQUEST_URI'], "/register/") && !str_starts_with($_SERVER['REQUEST_URI'], "/login/")) {
    header("Location: /login/");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
    $rp->parsePostBody();
else if ($_SERVER['REQUEST_METHOD'] == 'GET')
    $rp->parseQueryString($_SERVER['QUERY_STRING']);

try {
    echo $router->dispatch(explode('?', $_SERVER['REQUEST_URI'])[0], $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}