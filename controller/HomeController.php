<?php

require_once 'services/TemplateRenderer.php';
class HomeController
{
    private $trDI;
    public function __construct($tr)
    {
        $this->trDI = $tr;
    }
    public function showMainPage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $this->trDI->assign('haveCities', false);
            echo $this->trDI->render('public/templates/views/index.html');
            $this->trDI->clear();
        }
    }
}