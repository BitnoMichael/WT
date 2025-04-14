<?php

require_once 'controller/HomeController.php';
require_once 'controller/CitiesController.php';
require_once 'controller/AdminController.php';

class Routes
{
    public $routes = [];
    public function __construct($tr)
    {
        $this->routes = [
            new RouteDefinition('/', 'GET', new HomeController($tr), 'showMainPage'),
            new RouteDefinition('/', 'POST', new CitiesController($tr), 'handleRequest'),
            new RouteDefinition('/admin/', 'GET', new AdminController($tr), 'showAdminFileManager'),
            new RouteDefinition('/admin/api/files', 'GET', new AdminController($tr), 'getFilesInfo'),
            new RouteDefinition('/admin/api/fileContent', 'GET', new AdminController($tr), 'getFileContent'),
            new RouteDefinition('/admin/api/save', 'POST', new AdminController($tr), 'saveFile'),
            new RouteDefinition('/admin/api/download', 'GET', new AdminController($tr), 'getFile'),
            new RouteDefinition('/admin/api/delete', 'POST', new AdminController($tr), 'deleteFile'),
            new RouteDefinition('/admin/api/createFolder', 'POST', new AdminController($tr), 'mkDir'),
        ];
    }
}