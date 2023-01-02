<?php
session_start();
require_once 'AppControler.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';
class SecurityController extends AppControler
{
    public function login()
    {

        if($this->isGet())
            return $this->render('login');

        $login = $_POST['login'];
        $password = $_POST['password'];

        $userRepository = new UserRepository();
        $user = $userRepository->getUser($login);

        if(!$user)
        {
            return $this->render("login", ['messages' => ['Invalid login or password.']]);
        }

        if($user->getLogin() !== $login)
        {
            return $this->render('login', ["messages" => ['Invalid login or password.']]);
        }

        if($user->getPassword() !== $password)
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
        if($this->isGet())
            return $this->render('registerPage');

        $loginCandidate = $_POST['login'];
        $passwordCandidate = $_POST['password'];
        $passwordCandidateCong = $_POST['confirm-password'];
        $email = $_POST['email'];
     }
}