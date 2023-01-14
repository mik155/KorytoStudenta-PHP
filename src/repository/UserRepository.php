<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';
class UserRepository extends Repository
{
    public function getUser(string $login)
    {
        $stmt = $this->database->connect()->prepare(
            'SELECT id, login, hash, email FROM "User" WHERE login = :login'
        );
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user)
        {
            return null;
        }

        return new User(
            $user['id'],
            $user['login'],
            $user['hash'],
            $user['email']
        );
    }

    public function addUser($login, $hash, $email)
    {
        $stmt = $this->database->connect()->prepare(
            'SELECT createUser(:login, :hash, :email) result'
        );
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['result'];
    }
}