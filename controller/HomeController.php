<?php
declare(strict_types=1);
require_once 'services/TemplateRenderer.php';
class HomeController
{
    private TemplateRenderer $trDI;
    public function __construct(TemplateRenderer $tr)
    {
        $this->trDI = $tr;
    }
    public function showMainPage(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $this->trDI->assign('haveCities', false);
            echo $this->trDI->render('public/templates/views/index.html');
            $this->trDI->clear();
        }
    }
}