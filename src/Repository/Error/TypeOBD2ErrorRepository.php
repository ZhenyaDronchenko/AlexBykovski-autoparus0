<?php

namespace App\Repository\Error;

use Doctrine\ORM\EntityRepository;

class TypeOBD2ErrorRepository extends EntityRepository
{
    public function findAllUrlsForSiteMap()
    {
        return $this->createQueryBuilder('tobd2')
            ->select('tobd2.url')
            ->getQuery()
            ->getResult();
    }
}