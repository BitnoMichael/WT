<?php
declare(strict_types=1);

require_once 'RouteDefinitions.php';
class Router
{
    private array $routes = [];

    public function addRoute(RouteDefinition $routeDefinition): void
    {
        array_push($this->routes, $routeDefinition);
    }

    public function dispatch(string $path, string $method): string
    {
        foreach ($this->routes as $route) {
            if ($route->getPath() === $path && $route->getMethod() === $method)
            {            
                return $route->execute();
            }
        }
        return '';
    }
}