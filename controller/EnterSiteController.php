<?php
declare(strict_types=1);
require_once 'utils/TemplateRenderer.php';
require_once 'repository/DB.php';
require_once 'repository/AttractionsRepository.php';
require_once 'repository/ReviewsRepository.php';
require_once 'repository/UsersRepository.php';
require_once 'services/EnterSiteService.php';
require_once 'entity/User.php';
require_once './.constants/Constants.php';
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
        $answer= $this->trDI->render('public/views/signIn.html');
        $this->trDI->clear();
        return $answer;
    }
    
    public function showRegisterPage(): string
    {
        $answer= $this->trDI->render('public/views/registration.html');
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
            header("Location: /register/verify/");
        }
        else {
            echo 'user not correct';
        }
        return "";
    }
    public function showVerifyPage(): string
    {

        $verificationToken = bin2hex(random_bytes(16));
        $verificationLink = "http://localhost/register/api/verify?token=$verificationToken";
        $this->db->getUsers()->updateToken($_SESSION['user_id'], $verificationToken);
        $this->trDI->assign("verificationLink", $verificationLink);

        $this->service->sendVerificationEmail($this->trDI->render("public/views/confirmRegister.mjml"));
        $answer = $this->trDI->render('public/views/verification.html');
        $this->trDI->clear();
        return $answer;
    }
    public function verifyUser(): string
    {
        $curUser = $this->db->getUsers()->readByID($_SESSION['user_id']);
        if ($curUser->getToken() === $_GET['token'])
            $this->db->getUsers()->updateIsVerified($_SESSION['user_id'], true);
        return $this->showVerifyPage();
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