<?php
declare(strict_types=1);

class RouteDefinition
{
    public string $action;
    public string $method;
    public string $path;
    public object $controller;

    public function __construct(string $path, string $method, object $controller, string $action)
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