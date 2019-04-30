<?php

namespace App\Repository;

use App\Entity\City;
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

    public function findUrlsForSiteMap($types = [City::REGIONAL_CITY_TYPE, City::CAPITAL_TYPE, City::OTHERS_TYPE])
    {
        return $this->createQueryBuilder('c')
            ->select('c.url, c.name')
            ->where("c.active = :trueValue")
            ->andWhere("c.type IN(:types)")
            ->setParameter("trueValue", true)
            ->setParameter("types", $types)
            ->getQuery()
            ->getResult();
    }
}