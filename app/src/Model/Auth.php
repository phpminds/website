<?php

namespace PHPMinds\Model;

use Doctrine\ORM\EntityManager;
use PHPMinds\Repository\UserRepository;

class Auth
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var
     */
    private $user;

    /**
     * Auth constructor.
     * @param UserRepository $repository
     */
    public function __construct(EntityManager $em, UserRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param $email
     * @param $password
     * @return \stdClass
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerUser($email, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new \stdClass();
        $user->email = $email;
        $user->password = $hash;
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public function isValid($email, $password)
    {
        $user = $this->repository->findOneByEmail($email);
        var_dump($user, $user->getPassword());exit;
        if (password_verify($password, $user->getPassword())) {
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
//        return (bool) $this->repository->delete($email);
    }

    /**
     *
     */
    public function store()
    {
        if (!is_null($this->user)) {
            $_SESSION['auth']['user_id'] = $this->user->id;
            $_SESSION['auth']['email'] = $this->user->email;
        }
    }

    /**
     * @return bool
     */
    public function getUserId()
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        return $_SESSION['auth']['user_id'] ;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['auth']);
    }

    /**
     *
     */
    public function clear()
    {
        if (isset($_SESSION['auth'])) {
            unset($_SESSION['auth']);
            session_regenerate_id();
        }
    }
}
