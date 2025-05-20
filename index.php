<?php
declare(strict_types=1);
require 'vendor/autoload.php';
require_once './config/routes/Router.php';
require_once './config/routes/Routes.php';
require_once './utils/RequestParser.php';
require_once './.db/DBInfo.php';

session_start();

$templateRenderer = new TemplateRenderer();
$db = new DB($conn);
$routes = new Routes($templateRenderer, $db);
$router = new Router();
$rp = new RequestParser();

foreach ($routes->routes as $route) {
    $router->addRoute($route);
}

if (!isset($_SESSION['user_id'])
    && $_SERVER['REQUEST_URI'] !== "/register/"
    && !str_starts_with($_SERVER['REQUEST_URI'], "/login/")) {
    header("Location: /login/");
    exit;
}
else if (isset($_SESSION['user_id']) && !$db->getUsers()->readByID($_SESSION['user_id'])->getIsVerified() 
    && !str_starts_with($_SERVER['REQUEST_URI'], "/register/verify/")
    && !str_starts_with($_SERVER['REQUEST_URI'], "/register/api/verify"))
{
    header("Location: /register/verify/");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET')
    $rp->parseQueryString($_SERVER['QUERY_STRING']);
else
    $rp->parsePostBody();

// try {
    echo $router->dispatch(explode('?', $_SERVER['REQUEST_URI'])[0], $_SERVER['REQUEST_METHOD']);
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }