<?php
declare(strict_types=1);

class RouteDefinition
{
    private string $action;
    public function getAction(): string {
        return $this->action;
    }
    private string $method;
    public function getMethod(): string {
        return $this->method;
    }
    private string $path;
    public function getPath(): string {
        return $this->path;
    }
    private object $controller;
    public function getController(): object {
        return $this->controller;
    }

    public function __construct(string $path, string $method, object $controller, string $action)
    {
        $this->path = $path;
        $this->action = $action;
        $this->controller = $controller;
        $this->method = $method;
    }
    public function execute(): string
    {
        if (method_exists($this->controller, $this->action)) {
            return call_user_func([$this->controller, $this->action]);
        } else {
            throw new Exception("Method {$this->method} does not exist in controller " . get_class($this->controller));
        }
    }
}