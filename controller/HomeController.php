<?php

require_once '/home/mihail/WT/src/Services/TemplateRenderer.php';
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
            echo $this->trDI->render('/home/mihail/WT/templates/HTML/index.html');
            $this->trDI->clear();
        }
    }
}