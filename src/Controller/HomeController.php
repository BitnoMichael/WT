<?php

require_once '/home/mihail/WT/src/Services/TemplateRenderer.php';
class HomeController
{
    public function showMainPage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $fp = fopen('/home/mihail/WT/templates/HTML/index.html', 'r') or die('could not open file');
            $template = fread($fp, filesize('/home/mihail/WT/templates/HTML/index.html'));
            fclose($fp);
            $tr = new TemplateRenderer($template);
            $tr->assign('haveCities', false);
            echo $tr->render();
        }
    }
}