<?php

require 'RouteDefinitions.php';
class Router
{
    private array $routes = [];

    public function addRoute(string $path, string $method, object $controller, string $action): void
    {
        $this->routes[$path][$method] = new RouteDefinition($controller, $action);
    }

    public function dispatch(string $path, string $method): void
    {
        if (isset($this->routes[$path]) && isset($this->routes[$path][$method])) {
            $routeDefinition = $this->routes[$path][$method];
            $routeDefinition->execute(); // Выполняем маршрут
        } else {
            throw new Exception("Route for path {$path} not found.");
        }
    }
}