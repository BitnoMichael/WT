<?php
class RouteDefinition
{
    private object $controller;
    private string $method;

    public function __construct(object $controller, string $method)
    {
        $this->controller = $controller;
        $this->method = $method;
    }

    public function getController(): object
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
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