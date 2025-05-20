<?php
declare(strict_types=1);
require_once 'utils/TemplateRenderer.php';
require_once 'services/AdminService.php';
require_once 'repository/AttractionsRepository.php';
require_once 'repository/ReviewsRepository.php';
require_once 'repository/UsersRepository.php';
require_once './.constants/Constants.php';

class AdminController
{
    private TemplateRenderer $trDI;
    private object $adminService;
    private DB $db;
    public function __construct(TemplateRenderer $tr, DB $dataBase) 
    {
        $this->trDI = $tr;
        $this->adminService = new AdminService();
        $this->db = $dataBase;
    }

    public function showAdminFileManager(): string
    {
        return $this->trDI->render("admin/views/admin.html");
    }

    public function getFilesInfo(): string
    {
        header('Content-Type: application/json');
        return json_encode($this->adminService->getFiles());
    }
    public function getFileContent(): string
    {
        header('Content-Type: application/json');
        return $this->adminService->getFileContent();
    }
    
    public function saveFile(): string
    {
        return $this->adminService->saveFile();
    }

    public function getFile(): string
    {
        return $this->adminService->getFile();
    }
    
    public function deleteFile(): string
    {
        return $this->adminService->deleteFile();
    }
    public function mkDir(): string
    {
        return $this->adminService->mkDir();
    }
}