<?php
declare(strict_types=1);
require_once 'controller/HomeController.php';
require_once 'controller/AdminController.php';
require_once 'controller/EnterSiteController.php';
require_once 'repository/DB.php';
require_once './.constants/Constants.php';

class Routes
{
    public array $routes = [];
    public function __construct(TemplateRenderer $tr, DB $db)
    { 
        //скрипт автоматического добавления
        $homeController = new HomeController($tr, $db);
        $adminController = new AdminController($tr, $db);
        $enterSiteController = new EnterSiteController($tr, $db);
        $this->routes = [
            new RouteDefinition('/', 'GET', $homeController, 'showMainPage'),
            new RouteDefinition('/', 'POST', $homeController, 'validateEmail'),
            new RouteDefinition('/register/', 'GET', $enterSiteController, 'showRegisterPage'),
            new RouteDefinition('/register/verify/', 'GET', $enterSiteController, 'showVerifyPage'),
            new RouteDefinition('/register/api/verify', 'GET', $enterSiteController, 'verifyUser'),
            new RouteDefinition('/register/', 'POST', $enterSiteController, 'register'),
            new RouteDefinition('/login/', 'GET', $enterSiteController, 'showSignInPage'),
            new RouteDefinition('/login/', 'POST', $enterSiteController, 'signIn'),
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