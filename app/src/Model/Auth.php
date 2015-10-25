<?php

namespace App\Model;

use App\Repository\UsersRepository;

class Auth
{
    /**
     * @var UsersRepository
     */
    private $repository;

    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function registerUser($username, $password)
    {
        // username exists ??
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new \stdClass();
        $user->username = $username;
        $user->password = $hash;
        $this->repository->save($user);
    }

    public function isValid($username, $password)
    {
        $user = $this->repository->getByUsername($username);
        if (password_verify($password, $user->password)) {
            return true;
        }

        return false;
    }
}