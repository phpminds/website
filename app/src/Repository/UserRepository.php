<?php

namespace PHPMinds\Repository;

use Doctrine\ORM\EntityRepository;
use PHPMinds\Entity\User;

/**
 * @package PHPMinds\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param string $email
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByEmail(string $email)
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->where('u.email = :email')
            ->setParameter('email', $email);

        return $qb->getQuery()->getOneOrNullResult();
    }
}