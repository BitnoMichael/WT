<?php

declare(strict_types=1);

class RouteDefinition
{
    public $routeDefinition;
    public $method;
    public $path;
    public $controller;

    public function __construct($path, $method, $controller, $action)
    {
        $this->path = $path;
        $this->action = $action;
        $this->controller = $controller;
        $this->method = $method;
    }
    public function execute(): void
    {
        if (method_exists($this->controller, $this->method)) {
            call_user_func([$this->controller, $this->method]);
        } else {
            throw new Exception("Method {$this->method} does not exist in controller " . get_class($this->controller));
        }
    }
}