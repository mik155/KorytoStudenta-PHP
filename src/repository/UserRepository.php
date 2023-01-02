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
}