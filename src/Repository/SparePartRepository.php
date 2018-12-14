<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class SparePartRepository extends EntityRepository
{
    /**
     * @param string $text
     * @return array
     */
    public function searchByText($text)
    {
        return $this->createQueryBuilder('spp')
            ->select('spp')
            ->where("spp.name LIKE :text")
            ->andWhere("spp.active = :trueValue")
            ->setParameter("trueValue", true)
            ->setParameter("text", '%' . $text . '%')
            ->orderBy("spp.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findAllForAdvert()
    {
        return $this->createQueryBuilder('spp')
            ->select('spp.id, spp.name')
            ->orderBy("spp.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findAllUrlsForSiteMap()
    {
        return $this->createQueryBuilder('spp')
            ->select('spp.url')
            ->where("spp.active = :trueValue")
            ->setParameter("trueValue", true)
            ->getQuery()
            ->getResult();
    }
}