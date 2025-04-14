<?php

require_once 'services/TemplateRenderer.php';
require_once 'services/AdminService.php';
class AdminController
{
    private $trDI;
    private $adminService;
    public function __construct($tr)
    {
        $this->trDI = $tr;
        $this->adminService = new AdminService();
    }

    public function showAdminFileManager()
    {
        echo $this->trDI->render("admin/templates/views/admin.html");
        return;
    }

    public function getFilesInfo()
    {
        header('Content-Type: application/json');
        echo json_encode($this->adminService->getFiles());
    }
    public function getFileContent()
    {
        header('Content-Type: application/json');
        echo $this->adminService->getFileContent();
    }
    
    public function saveFile()
    {
        $this->adminService->saveFile();
    }

    public function getFile()
    {
        $this->adminService->getFile();
    }
    
    public function deleteFile()
    {
        $this->adminService->deleteFile();
    }
    public function mkDir()
    {
        $this->adminService->mkDir();
    }
}