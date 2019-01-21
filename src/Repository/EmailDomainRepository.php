<?php

namespace App\Repository;

use App\Entity\General\EmailDomain;
use Doctrine\ORM\EntityRepository;

class EmailDomainRepository extends EntityRepository
{
    public function deleteAbsentByEmailEnds($existEmailEnds)
    {
        return $this->createQueryBuilder('ed')
            ->delete(EmailDomain::class, 'ed')
            ->where('ed.emailEnd NOT IN (:existEmailEnds)')
            ->setParameter('existEmailEnds', $existEmailEnds)
            ->getQuery()
            ->getResult();
    }
}