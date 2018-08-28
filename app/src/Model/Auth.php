<?php

namespace PHPMinds\Model;

use PHPMinds\Repository\UsersRepository;

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
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new \stdClass();
        $user->email = $email;
        $user->password = $hash;
        $this->repository->save($user);

        return $user;
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

    /**
     * @param $email
     * @return bool
     */
    public function removeUser($email): bool
    {
        return (bool) $this->repository->delete($email);
    }

    public function store()
    {
        if (!is_null($this->user)) {
            $_SESSION['auth']['user_id'] = $this->user->id;
            $_SESSION['auth']['email'] = $this->user->email;
        }
    }

    public function getUserId()
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        return $_SESSION['auth']['user_id'] ;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['auth']);
    }

    public function clear()
    {
        if (isset($_SESSION['auth'])) {
            unset($_SESSION['auth']);
            session_regenerate_id();
        }
    }
}
