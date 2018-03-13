<?php

namespace PHPMinds\Repository;

use Doctrine\ORM\EntityRepository;
use PHPMinds\Entity\User;

/**
 * Class UserRepository
 * @package PHPMinds\Repository
 */
class UserRepository extends EntityRepository
{

    public function findOneByEmail($email)
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->where('u.email = :email')
            ->setParameter('email', $email);

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (\Exception $e) {
            echo $e->getMessage();exit;
        }

    }
}