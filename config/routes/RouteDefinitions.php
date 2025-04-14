<?php

declare(strict_types=1);

class RouteDefinition
{
    public $action;
    public $method;
    public $path;
    public $controller;

    public function __construct($path, $method, $controller, string $action)
    {
        $this->path = $path;
        $this->action = $action;
        $this->controller = $controller;
        $this->method = $method;
    }
    public function execute(): void
    {
        if (method_exists($this->controller, $this->action)) {
            call_user_func([$this->controller, $this->action]);
        } else {
            throw new Exception("Method {$this->method} does not exist in controller " . get_class($this->controller));
        }
    }
}