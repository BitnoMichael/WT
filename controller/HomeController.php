<?php
declare(strict_types=1);
require_once 'utils/TemplateRenderer.php';
require_once 'repository/DB.php';
require_once 'repository/AttractionsRepository.php';
require_once 'repository/ReviewsRepository.php';
require_once 'repository/UsersRepository.php';
require_once './.constants/Constants.php';

class HomeController
{
    private TemplateRenderer $trDI;
    private DB $db;
    public function __construct(TemplateRenderer $tr, DB $dataBase)
    {
        $this->trDI = $tr;
        $this->db = $dataBase;
    }
    public function showMainPage(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $attractions = [];
            for ($i = 1; $i <= $this->db->getAttractions()->getLength(); $i++)
            {
                $attraction = $this->db->getAttractions()->read($i);
                $attractions[] = $attraction;
            }
            $this->trDI->assign('attractions', $attractions);
            $answer= $this->trDI->render('public/views/index.html');
            $this->trDI->clear();
        }
        return $answer;
    }
}