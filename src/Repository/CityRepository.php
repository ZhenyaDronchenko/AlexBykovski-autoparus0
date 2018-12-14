<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    public function findAllUrlsForSiteMap()
    {
        return $this->createQueryBuilder('c')
            ->select('c.url')
            ->where("c.active = :trueValue")
            ->setParameter("trueValue", true)
            ->getQuery()
            ->getResult();
    }
}