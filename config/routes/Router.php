<?php
declare(strict_types=1);

require_once 'RouteDefinitions.php';
class Router
{
    private array $routes = [];

    public function addRoute($routeDefinition): void
    {
        array_push($this->routes, $routeDefinition);
    }

    public function dispatch(string $path, string $method): string
    {
        foreach ($this->routes as $route) {
            if ($route->path == $path && $route->method == $method)
            {            
                return $route->execute(); // Выполняем маршрут
            }
        }
        return '';
    }
}