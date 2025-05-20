<?php
require_once 'repository/UsersRepository.php';
require_once 'entity/User.php';
require_once './.constants/Constants.php';
use PHPMailer\PHPMailer\PHPMailer;

class EnterSiteService {
    private UsersRepository $usersRepository;
    public function __construct(UsersRepository $usersRepo)
    {
        $this->usersRepository = $usersRepo;
    }
    private function getRandomSalt() :string {
        $length = 22;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        $charCount = strlen($chars);
        
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, $charCount - 1)];
        }
        
        return $result;
    }
    private function isCaptchaEntered(): bool {
        if (isset($_POST['g-recaptcha-response'])) {
            $secret = "6Lc70jUrAAAAAJnTy-ZHRa-zYlUu1m3i6aaJM7hb";
            $response = $_POST['g-recaptcha-response'];
            $remoteip = $_SERVER['REMOTE_ADDR'];
            
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
            $data = file_get_contents($url);
            $result = json_decode($data);
            return $result->success;
        }
        return false;
    }
    public function isNewUserCorrect(): bool
    {
        return $this->usersRepository->read(name: $_POST['username']) === null
                &&  $_POST['password'] === $_POST['confirmPassword']
                && $this->isCaptchaEntered();
    }

    public function isUserCorrect(): bool
    {
        $user = $this->usersRepository->read(name: $_POST['username']);
        return $user != null
                && $user->getPasswordHash() === hash("sha256", $_POST['password'] . $user->getSalt());
    }
    public function addNewUser(): void
    {
        $salt = $this->getRandomSalt();
        $this->usersRepository->create( $_POST['username'], hash("sha256", $_POST['password'] . $salt), $salt, isset($_POST['rememberMe']), $_POST['email']);
    }
    public function enterToSite(): void
    {
        $_SESSION['user_id'] = $this->usersRepository->read($_POST['username'])?->getID();
    }

    public function sendVerificationEmail(string $emailContent): void
    {
        $user = $this->usersRepository->readByID($_SESSION['user_id']);

        $receiver = $user->getEmail();

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.mail.ru'; 
        $mail->SMTPAuth = true; 
        $mail->Username = Constants::SERVER_EMAIL; 
        $mail->Password = Constants::EMAIL_PASSWORD; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; 
        // Получатели
        $mail->setFrom(Constants::SERVER_EMAIL, 'Site service'); // Отправитель
        $mail->addAddress($receiver, 'New user'); // Получатель

        $mail->isHTML(true); 
        $mail->Subject = 'Verification'; 
        $mail->Body    = $emailContent; 
        $mail->AltBody = '';

        $mail->send();
    }
}