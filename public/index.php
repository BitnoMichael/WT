<?php

require '../src/Controller/HomeController.php';
require '../src/Controller/CitiesController.php';
require '../config/routes/Router.php';
$homeController = new HomeController();
$citiesController = new CitiesController();
$router = new Router();

$router->addRoute('/', 'GET', $homeController, 'showMainPage'); 
$router->addRoute('/', 'POST', $citiesController, 'handleRequest'); 

try {
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}