<?php
declare(strict_types=1);
require_once 'controller/HomeController.php';
require_once 'controller/AdminController.php';
require_once 'src/repository/DB.php';

class Routes
{
    public array $routes = [];
    public function __construct(TemplateRenderer $tr)
    { 
        include './.db/DBInfo.php';
        $db = new DB($conn);
        $homeController = new HomeController($tr, $db);
        $adminController = new AdminController($tr, $db);

        $this->routes = [
            new RouteDefinition('/', 'GET', $homeController, 'showMainPage'),
            new RouteDefinition('/', 'POST', $homeController, 'validateEmail'),
            new RouteDefinition('/admin/', 'GET', $adminController, 'showAdminFileManager'),
            new RouteDefinition('/admin/api/files', 'GET', $adminController, 'getFilesInfo'),
            new RouteDefinition('/admin/api/fileContent', 'GET', $adminController, 'getFileContent'),
            new RouteDefinition('/admin/api/save', 'POST', $adminController, 'saveFile'),
            new RouteDefinition('/admin/api/download', 'GET', $adminController, 'getFile'),
            new RouteDefinition('/admin/api/delete', 'POST', $adminController, 'deleteFile'),
            new RouteDefinition('/admin/api/createFolder', 'POST', $adminController, 'mkDir'),
        ];
    }
}