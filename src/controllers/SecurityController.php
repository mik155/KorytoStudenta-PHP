<?php
session_start();
require_once 'AppControler.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';
class SecurityController extends AppControler
{
    public function login()
    {

        if ($this->isGet())
            return $this->render('login');

        $login = $_POST['login'];
        $password = $_POST['password'];

        $userRepository = new UserRepository();
        $user = $userRepository->getUser($login);

        if (!$user) {
            return $this->render("login", ['messages' => ['Invalid login or password.']]);
        }

        if ($user->getLogin() !== $login) {
            return $this->render('login', ["messages" => ['Invalid login or password.']]);
        }

        if (!password_verify($password ,$user->getPassword()))
        {
            return $this->render('login', ["messages" => ['Invalid login or password.']]);
        }

        $_SESSION['user'] = $login;

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/mainPage");
    }

    public function logout()
    {
        session_destroy();
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/mainPage");
    }

    public function register()
    {
        if ($this->isGet())
            return $this->render('registerPage');

        $loginCandidate = $_POST['login'];
        $passwordCandidate = $_POST['password'];
        $passwordCandidateConf = $_POST['confirm-password'];
        $email = $_POST['email'];

        if(!$this->validateLogin($loginCandidate))
            return $this->render("register", ['messages' => ['Invalid login.']]);

        if($this->loginOccupied($loginCandidate))
            return $this->render("register", ['messages' => ['Account with entered login already exists.']]);

        $passwordCheck = $this->validatePassword($passwordCandidate, $passwordCandidateConf);
        if($passwordCheck === -2)
            return $this->render("register", ['messages' => ['Entered two diffrent passwords.']]);

        if($passwordCheck === -1)
            return $this->render("register", ['messages' => ['Password\'s length should be longer than 8 and shorter than 20.']]);

        if(!$this->validateEmail($email))
            return $this->render("register", ['messages' => ['Incorrect email.']]);

        $passwordCandidate = password_hash($passwordCandidate, PASSWORD_BCRYPT);

        $userRepository = new UserRepository();
        if(!$userRepository->addUser($loginCandidate, $passwordCandidate, $email))
            return $this->render("register", ['messages' => ['Something went wrong.']]);
        else
            return $this->render("login", ['messages' => ['Now you can log in.']]);



    }

    public function validateLogin($login)
    {
        if (!preg_match("/^[a-zA-Z0-9]{1,30}$/", $login))
            return false;
        return true;
    }

    public function loginOccupied($login)
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getUser($login);

        if ($user === null)
            return false;
        return true;
    }

    public function validatePassword($password, $passwordConfirm)
    {
        if(!$password === $passwordConfirm)
            return -2;

        if(strlen($password) < 8 || strlen($password) > 20)
            return -1;

        return 0;
    }

    public function validateEmail($email)
    {
        return preg_match("/^[a-zA-Z0-9]+@[a-z]+\.[a-z]+$/", $email);
    }
}