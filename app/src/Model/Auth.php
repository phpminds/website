<?php

namespace PHPMinds\Model;

use PHPMinds\Exception\Model\UserNotLoggedInException;
use PHPMinds\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use PHPMinds\Entity\User;

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
     * @var User|null
     */
    private $user;

    /**
     * Auth constructor.
     * @param EntityManager  $em
     * @param UserRepository $repository
     */
    public function __construct(EntityManager $em, UserRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerUser(string $email, string $password)
    {
        /** @var string $hash */
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($hash);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function isValid(string $email, string $password)
    {
        try {
            /** @var User|null $user */
            $user = $this->repository->findOneByEmail($email);
        } catch (\Doctrine\ORM\NonUniqueResultException $e) {
            return false;
        }

        if (is_null($user)) {
            return false;
        }

        if (password_verify($password, $user->getPassword())) {
            $this->user = $user;
            return true;
        }

        return false;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function removeUser(string $email): bool
    {
    }

    public function store()
    {
        if (!is_null($this->user)) {
            $_SESSION['auth']['user_id'] = $this->user->getId();
            $_SESSION['auth']['email'] = $this->user->getEmail();
        }
    }

    /**
     * @return int
     * @throws UserNotLoggedInException
     */
    public function getUserId()
    {
        if (!$this->isLoggedIn()) {
            throw new UserNotLoggedInException();
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
