<?php

namespace App\Model;

use App\Repository\UsersRepository;

class Auth
{
    /**
     * @var UsersRepository
     */
    private $repository;

    private $user;

    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function registerUser($email, $password)
    {
        // username exists ??
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new \stdClass();
        $user->email = $email;
        $user->password = $hash;
        $this->repository->save($user);
    }

    public function isValid($email, $password)
    {
        $user = $this->repository->getByEmail($email);
        if (password_verify($password, $user->password)) {
            $this->user = $user;
            return true;
        }

        return false;
    }

    public function store()
    {
        if (!is_null($this->user)) {
            $_SESSION['auth']['user_id'] = $this->user->id;
            $_SESSION['auth']['email'] = $this->user->email;
        }
    }

    public function clear()
    {
        if (isset($_SESSION['auth'])) {
            unset($_SESSION['auth']);
            session_regenerate_id();
        }
    }
}
