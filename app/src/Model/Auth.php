<?php

namespace App\Model;

use App\Model\Repository\UsersRepository;

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

    public function isValid($username, $password)
    {
        $user = $this->repository->getByUsername($username);
        if (password_verify($password, $user->password)) {
            return true;
        }

        return false;
    }
}