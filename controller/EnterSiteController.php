<?php
declare(strict_types=1);
require_once 'utils/TemplateRenderer.php';
require_once 'repository/DB.php';
require_once 'repository/AttractionsRepository.php';
require_once 'repository/ReviewsRepository.php';
require_once 'repository/UsersRepository.php';
require_once 'services/EnterSiteService.php';
class EnterSiteController
{
    private EnterSiteService $service;
    private TemplateRenderer $trDI;
    private DB $db;
    public function __construct(TemplateRenderer $tr, DB $dataBase)
    {
        $this->trDI = $tr;
        $this->db = $dataBase;
        $this->service = new EnterSiteService($dataBase->getUsers());
    }
    public function showSignInPage(): string
    {
        $answer= $this->trDI->render('public/templates/views/signIn.html');
        $this->trDI->clear();
        return $answer;
    }
    
    public function showRegisterPage(): string
    {
        $answer= $this->trDI->render('public/templates/views/registration.html');
        $this->trDI->clear();
        return $answer;
    }

    public function register(): string
    {
        if ($this->service->isNewUserCorrect())
        {
            $this->service->addNewUser();
            $this->service->enterToSite();
            header("Content-Type: text/html; charset=utf-8");
            header("Location: /");
        }
        else {
            echo 'user not correct';
        }
        return "";
    }
    public function signIn(): string
    {
        if ($this->service->isUserCorrect())
        {
            $this->service->enterToSite();
            header("Content-Type: text/html; charset=utf-8");
            header("Location: /");
        }
        else {
            echo 'user not correct';
        }
        return "";
    }
}