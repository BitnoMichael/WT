<?php

class Routes
{
    public $routes = [];
    public function __construct($tr)
    {
        $this->routes = [
            new RouteDefinition('/', 'GET', new HomeController($tr), 'showMainPage'),
            new RouteDefinition('/', 'POST', new CitiesController($tr), 'handleRequest')
        ];
    }
}