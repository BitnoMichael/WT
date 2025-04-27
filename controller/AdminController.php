<?php
declare(strict_types=1);
require_once 'utils/TemplateRenderer.php';
require_once 'services/AdminService.php';
class AdminController
{
    private TemplateRenderer $trDI;
    private object $adminService;
    public function __construct(TemplateRenderer $tr)
    {
        $this->trDI = $tr;
        $this->adminService = new AdminService();
    }

    public function showAdminFileManager(): void
    {
        echo $this->trDI->render("admin/templates/views/admin.html");
        return;
    }

    public function getFilesInfo():void 
    {
        header('Content-Type: application/json');
        echo json_encode($this->adminService->getFiles());
    }
    public function getFileContent():void
    {
        header('Content-Type: application/json');
        echo $this->adminService->getFileContent();
    }
    
    public function saveFile():void
    {
        $this->adminService->saveFile();
    }

    public function getFile():void
    {
        $this->adminService->getFile();
    }
    
    public function deleteFile():void
    {
        $this->adminService->deleteFile();
    }
    public function mkDir():void
    {
        $this->adminService->mkDir();
    }
}